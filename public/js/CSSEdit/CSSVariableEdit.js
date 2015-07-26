

//Widget that holds one of the CSS variable editting forms
// either sizes/or colors

var CSSVariableEdit = {

    cssVariableList: [],

    options: {
        name: null,
        cssVariable: null
    },

    // Modify a cssString to use the new value.
    calculateNewCSSString: function(cssString) {
        for(var index in CSSVariableEdit.cssVariableList){
            var cssVariable = CSSVariableEdit.cssVariableList[index];
            var search = "{$" + cssVariable.name + "}";
            cssString = cssString.replace(search, cssVariable.getValue());
        }

        cssString = cssString.replace(/^\s+|\s+$/g,''); //Trims white space for safety
        return cssString;
    },

    //For all the listed editable CSS, update the CSS, and apply it.
    // each cssAttrVarMap looks like:
    //['{$buttonNormalBackgroundColor}', 'ul.contentNav li select', 'background-color',]

    updateAllCSSEntries: function(){
        if(cssAttrVarMap){
            for(var i=0 ; i<cssAttrVarMap.length ; i+=3){
                var css 	 = cssAttrVarMap[i + 0];
                var selector = cssAttrVarMap[i + 1];
                var attr 	 = cssAttrVarMap[i + 2];

                css = this.calculateNewCSSString(css);

                changecss(selector, attr, css);
            }
        }
    },

    //When clicked on a swatch, let other widgets know
    colorClickerClicked: function(event){
        $('.baserealityEvent').trigger(BaseRealityEvent.cssColorFocus, this.options.cssVariable);
    },

    //Current CSS Variable has changed, update the field
    //and also regenerate the CSS.
    updateFieldFromCSSValue: function(){
        $(this.element).find(".cssVariableField").val(this.options.cssVariable.getValue());
        CSSVariableEdit.updateAllCSSEntries();
    },

    /**
     * Called when the increment/decrement buttons are clicked.
     * @param event
     */
    deltaChange: function(event){
        var  delta = event.data.delta;
        this.options.cssVariable.adjustValue(delta);
        this.updateFieldFromCSSValue();
    },

    /**
     * Called on BaseRealityEvent.cssColorChanged
     * @param event
     * @param focusCSSVariable - Which CSSVariable has just changed
     */
    cssColorChanged: function(event, focusCSSVariable) {
        if(focusCSSVariable.cssVariableID == this.options.cssVariable.cssVariableID){
            this.updateFieldFromCSSValue();
            //this.updateClickerButton();
            $(this.element).find(".colorClicker").css('backgroundColor', this.options.cssVariable.getValue());
        }
    },


    //Input field has changed value. Get the new value,
    //set the current CSSVariable to the new value, and
    //trigger a color changed event.
    fieldChanged: function(event) {
        var newValue = $(event.target).val();
        this.options.cssVariable.setValue(newValue);
        event.stopPropagation();
        CSSVariableEdit.updateAllCSSEntries();

        $('.baserealityEvent').trigger(BaseRealityEvent.cssColorChanged, this.options.cssVariable);
    },

    _create: function() {
    },

    _init: function() {
        var data = $(this.element).data('serialized');
        this.options.cssVariable = json_decode_object(data);

        CSSVariableEdit.cssVariableList.push(this.options.cssVariable);

        $(this.element).find(".decrement").bind('click', {delta: -1}, $.proxy(this, 'deltaChange'));
        $(this.element).find(".increment").bind('click', {delta:  1}, $.proxy(this, 'deltaChange'));
        $(this.element).find(".cssVariableField").bind('change', $.proxy(this, 'fieldChanged'));
        $(this.element).find(".cssVariableField").bind('focus', $.proxy(this, 'colorClickerClicked'));
        $(this.element).find(".colorClicker").bind('click', $.proxy(this, 'colorClickerClicked'));
        $(this.element).find(".colorClicker").addClass('hoverBorder');
        $(this.element).bind(BaseRealityEvent.cssColorChanged, $.proxy(this, 'cssColorChanged'));
        $(this.element).addClass('baserealityEvent');

        createSwatch(this.options.cssVariable);
    },
};

CSSVariableEdit = Logger.extend(CSSVariableEdit, 'CSSVariableEdit');

$.widget("basereality.cssVariableEdit", CSSVariableEdit); // create the widget

function initSizeFields(params) {
    $(params.selector).cssVariableEdit({});
}

function initColorFields(params) {
    $(params.selector).cssVariableEdit({});
}



//Cchangecss Function by Shawn Olson
//Copyright 2006-2011
//http://www.shawnolson.net
//If you copy any functions from this page into your scripts, you must provide credit to Shawn Olson & http://www.shawnolson.net
//*******************************************
function changecss(theClass, element, value) {
    //Last Updated on July 4, 2011
    //documentation for this script at
    //http://www.shawnolson.net/a/503/altering-css-class-attributes-with-javascript.html
    var cssRules;

    for (var S = 0; S < document.styleSheets.length; S++){

        try{
            //Gecko
            document.styleSheets[S].insertRule(
                theClass + ' { ' + element + ': ' + value + '; }',
                document.styleSheets[S][cssRules].length
            );
        }
        catch(err){
            try{
                //I.E.
                document.styleSheets[S].addRule(theClass,element + ': ' + value + ';');
            }
            catch(err){
                try{
                    if (document.styleSheets[S]['rules']) {
                        cssRules = 'rules';
                    }
                    else if (document.styleSheets[S]['cssRules']) {
                        cssRules = 'cssRules';
                    }
                    else {
                        //no rules found... browser unknown
                    }

                    for (var R = 0; R < document.styleSheets[S][cssRules].length; R++) {
                        if (document.styleSheets[S][cssRules][R].selectorText == theClass) {
                            if(document.styleSheets[S][cssRules][R].style[element]){
                                document.styleSheets[S][cssRules][R].style[element] = value;
                                break;
                            }
                        }
                    }
                }
                catch (err){
                }
            }
        }
    }
}

