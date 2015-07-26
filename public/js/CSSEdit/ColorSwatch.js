/*jslint evil: false, vars: true, eqeq: true, white: true */


//These are the color swatches that can be dragged around inside the
//box at the bottom.

var ColorSwatch = {

    colorSwatchCounter: 0,
    sortMode: 'saturationLuminance',

    //Set the color of a swatch
    setColor: function(cssVariable) {
        try{
            this.element.css('backgroundColor', '#' + cssVariable.getValue());
        }
        catch(error){
            alert("error in this.setColor " + error);
        }
    },

    //Sets the sort mode (and editing mode) for the swatch box.
    setSortMode: function(newSortMode){
        switch(newSortMode){
            case('saturationHue'):
            case('saturationLuminance'):
            case('hueLuminance'):
            case('nosort'):
                ColorSwatch.sortMode = newSortMode;
            break;

            default:
                alert("New sort mode [" + newSortMode + "] not recognised.");
            break;
        }
    },

    //Whenever a swatch has it's colour changed, it's position should also change.
    updateElementPositionFromCurrentColor: function(){
        var backgroundColor = this.element.css('background-color');
        var rgbColor = new RGBColor(backgroundColor);

        var hsl = rgbColor.toHSL();

        var positionX = 0;
        var positionY = 0;

        switch(ColorSwatch.sortMode){

            case('saturationHue'):
                positionX = intval(hsl.hue * 256 * 2);
                positionY = intval(hsl.saturation * 256 * 2);
            break;

            case('saturationLuminance'):
                positionX = intval(hsl.luminance * 256 * 2);
                positionY = intval(hsl.saturation * 256 * 2);
            break;

            case('nosort'):
                var xCount = (this.options.colorSwatchID % 16);
                var yCount = (this.options.colorSwatchID - xCount) / 16;
                positionX = intval(xCount * 16 * 2);
                positionY = intval(yCount * 16 * 2);
            break;

            default:
            case('hueLuminance'):
                positionX = intval(hsl.luminance * 256 * 2);
                positionY = intval(hsl.hue * 256 * 2);
            break;
        }

        var posXString = '' + positionX + 'px';
        var posYString = '' + positionY + 'px';

        this.element.css('left', posXString);
        this.element.css('top', posYString);
    },


    //Whenever a swatch is dragged, it's color should change.
    updateElementHSLFromPosition: function (event, ui){
        var positionX = ui.position.left;
        var positionY = ui.position.top;

        var hslParams = {};

        switch(ColorSwatch.sortMode){

            case('saturationHue'):
                hslParams['hue'] = positionX / (256.0 * 2);
                hslParams['saturation'] = positionY / (256.0 * 2);
            break;

            case('saturationLuminance'):
                hslParams['luminance'] = positionX / (256.0 * 2);
                hslParams['saturation'] = positionY / (256.0 * 2);
            break;

            case('nosort'):
                return;	//can't update color in this mode

            default:
            case('hueLuminance'):
                hslParams['luminance'] = positionX / (256.0 * 2);
                hslParams['hue'] = positionY / (256.0 * 2);
            break;
        }

        $('.baserealityEvent').trigger(BaseRealityEvent.cssColorChangedHSL, hslParams);
    },


    //Whenever a CSSVariable is editted, go through them all and update the
    //appropriate swatched color and position.
    colorChanged: function(event, cssVariable){
        if(this.options.cssVariable.cssVariableID == cssVariable.cssVariableID){
            this.setColor(cssVariable);
            this.updateElementPositionFromCurrentColor();
        }
    },

    //When the user starts dragging a swatch
    onFocus: function (event) {
        try{
            var backgroundColor = this.element.css('background-color');
            var rgbColor = new RGBColor(backgroundColor);
            Logger.log("Triggering cssColor focus");

            $('.baserealityEvent').trigger(BaseRealityEvent.cssColorFocus, this.options.cssVariable);
        }
        catch(error){
            alert("Exception in onFocus " + error);
        }
    },

    //Fired when we start editing a CSS variable.
    //Appropriate element is highlighted, others are deselected.
    cssColorFocus: function(event, cssVariable){
        if(this.options.cssVariable.cssVariableID == cssVariable.cssVariableID){
            $(this.element).css('border', '5px solid #000000');
            $(this.element).css('margin', '-5px');
        }
        else{
            $(this.element).css('border', '0px solid #cfcfcf');
            $(this.element).css('margin', '0px');
        }
    },

    _create: function() {
    },

    _init: function() {

        this.element.bind('mousedown', jQuery.proxy(this, 'onFocus'));

        this.options.colorSwatchID = ColorSwatch.colorSwatchCounter;

        ColorSwatch.colorSwatchCounter++;

        //Set the initial position from the color
        this.updateElementPositionFromCurrentColor();

        $(this.element).bind(BaseRealityEvent.cssColorFocus, $.proxy(this, 'cssColorFocus'));
        $(this.element).bind(BaseRealityEvent.cssColorChanged, $.proxy(this, 'colorChanged'));
        $(this.element).bind('drag', $.proxy(this, 'updateElementHSLFromPosition'));
        $(this.element).addClass('baserealityEvent');

        try{
            jQuery(this.element).draggable({
                containment: 'parent',
            });
        }
        catch(error){
            alert("adding draggable failed " + error);
        }
    },

    options: {
        colorSwatchID: 0,
        cssVariable: null,
    }
};

ColorSwatch = Logger.extend(ColorSwatch, 'ColorSwatch');

$.widget("basereality.colorSwatch", ColorSwatch); // create the widget


function createSwatch(cssVariable) {

    var colorHolder = '#colorHolder';
    var holder = $(colorHolder);
    var color = cssVariable.getValue();

    var colorDiv = $("<div class='tooltip swatchColor' style='position: absolute; border: 1px solid #000000; height:32px; width: 32px;' title='" + color + "' alt='" + color +  "'></div>");

    colorDiv.css('background-color', '#' + cssVariable.getValue());
    holder.append(colorDiv);

    var params = {};

    params.cssVariable = cssVariable;

    $(colorDiv).colorSwatch(params);
}