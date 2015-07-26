
//Widget that holds the color sliders, RGB field, and r/g/b/ and Hue/Sat/Lum fields


var ColorPickerTool = {

    //This is some padding in the image, to align slightly better
	paddingAboveSlider: 4,

    //Size of the box showing the current colour.
	boxSize: 256,


    //On slider focus, set the appropriate event handlers
	sliderFocus: function (event) {
		this.options.clickRGBColor = this.options.currentRGBColor;
		$(document).bind('mouseup', {sliderType: event.data.sliderType,  }, jQuery.proxy(this, 'sliderBlur' ) );	//mouseup anywhere 
		$(document).bind('mousemove', {sliderType: event.data.sliderType, clickedTarget: event.currentTarget }, jQuery.proxy(this, 'sliderMove' ) );
		return false;//prevent text selection
	},

    //On sliderMove, calculate the new position and update colour. The color is always
    //calculated from 'clickRGBColor' the color that the ColorWas set to last. This is
    //to avoid degeneration in the colour calcuation i.e. losing hue and saturation values
    //when the luminance is set to 255.
	sliderMove: function (event) {
		try{
			var offset = $(event.data.clickedTarget).offset();
			var startY = offset.top;
			var offsetY = event.pageY;

			var distance = (offsetY - startY - ColorPickerTool.paddingAboveSlider) / 256.0;
			newColor = calculateNewColor(this.options.clickRGBColor, 'hsl', event.data.sliderType, distance, 'assign');
			this.setRGBColor(newColor);
			return false;
		}
		catch(error){
			alert("Error in sliderMover " + error);
		}
		return false;//prevent text selection
	},

    //Remove the slider movement events when mouse is released.
	sliderBlur: function (event) {
		$(document).unbind('mouseup', jQuery.proxy(this, 'sliderBlur'));
		$(document).unbind('mousemove', jQuery.proxy(this, 'sliderMove'));
		return false;
	},

    //Attache the initial events to the sliders
	attachSliderEvents: function(){
		jQuery(this.element).find(".colorPicker_hueSlider").bind('mousedown', {sliderType: 'hue', }, jQuery.proxy(this, 'sliderFocus'));
		jQuery(this.element).find(".colorPicker_saturationSlider").bind('mousedown', {sliderType: 'saturation', }, jQuery.proxy(this, 'sliderFocus'));
		jQuery(this.element).find(".colorPicker_luminanceSlider").bind('mousedown', {sliderType: 'luminance', }, jQuery.proxy(this, 'sliderFocus'));
	},


    //Attach events to the little dragger boxes (next to the value fields)
	draggerFocus: function (event) {
		this.options.positionYAtClick = event.pageY;
		this.options.clickRGBColor = this.options.currentRGBColor;

		jQuery(event.currentTarget).addClass('colorPicker_draggerActive');

		//These events are attached to the document as they need to work when the mouse is outside 
		// of the drag button.
		$(document).bind('mouseup', {colorSpace: event.data.colorSpace, color: event.data.color, }, jQuery.proxy(this, 'draggerBlur'));
		$(document).bind('mousemove', {colorSpace: event.data.colorSpace, color: event.data.color, }, jQuery.proxy(this, 'draggerMove'));
		return false;
	},

    //On move update the appropriate r/g/b or H/S/L and calculate new color
	draggerMove: function (event) {
		var distance = event.pageY - this.options.positionYAtClick;

		if(event.data.colorSpace == 'hsl'){
			distance = distance / 256.0;
		}

		newColor = calculateNewColor(this.options.clickRGBColor, event.data.colorSpace, event.data.color, distance, 'addition');
		this.setRGBColor(newColor);
		return false; //prevent text selection
	},



	draggerBlur: function (event) {
		//Remove the active class from all dragger buttons in case a mouseup event has been lost 
		jQuery(this.element).find('.colorPicker_dragger').removeClass('colorPicker_draggerActive');

		$(document).unbind('mouseup', jQuery.proxy(this, 'draggerBlur'));
		$(document).unbind('mousemove', jQuery.proxy(this, 'draggerMove'));

		this.options.clickRGBColor = this.options.currentRGBColor;
		return false;
	},

    //Attach the initial events to the dragger elements.
	attachDraggerEvents: function(){
		jQuery(this.element).find(".colorPicker_redDragger").bind('mousedown', {colorSpace: 'rgb', color:  'red', }, jQuery.proxy(this, 'draggerFocus'));
		jQuery(this.element).find(".colorPicker_greenDragger").bind('mousedown', {colorSpace: 'rgb', color: 'green', }, jQuery.proxy(this, 'draggerFocus'));
		jQuery(this.element).find(".colorPicker_blueDragger").bind('mousedown', {colorSpace: 'rgb', color: 'blue', }, jQuery.proxy(this, 'draggerFocus'));

		jQuery(this.element).find(".colorPicker_hueDragger").bind('mousedown', {colorSpace: 'hsl', color:  'hue', }, jQuery.proxy(this, 'draggerFocus'));
		jQuery(this.element).find(".colorPicker_satDragger").bind('mousedown', {colorSpace: 'hsl', color: 'saturation', }, jQuery.proxy(this, 'draggerFocus'));
		jQuery(this.element).find(".colorPicker_luminanceDragger").bind('mousedown', {colorSpace: 'hsl', color: 'luminance', }, jQuery.proxy(this, 'draggerFocus'));
	},


    //Event for when one of the input fields is modified by the user.
	inputChange: function(event){
		try{
			newColor = calculateNewColor(this.options.currentRGBColor, event.data.colorSpace, event.data.color, event.currentTarget.value, 'assign');
			this.setRGBColor(newColor);
		}
		catch(error){
			alert("error caught in inputChange " + error);
		}
	},

    //Attach all initial events.
	attachInputEditEvents: function(){
		jQuery(this.element).find(".colorPicker_redInput").bind('change', {colorSpace: 'rgb', color:  'red', }, jQuery.proxy(this, 'inputChange'));
		jQuery(this.element).find(".colorPicker_redInput").bind('keydown', changeEnterKeyToBlur);

		jQuery(this.element).find(".colorPicker_greenInput").bind('change', {colorSpace: 'rgb', color:  'green', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);
		jQuery(this.element).find(".colorPicker_blueInput").bind('change', {colorSpace: 'rgb', color:  'blue', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);

		jQuery(this.element).find(".colorPicker_hueInput").bind('change', {colorSpace: 'hsl', color:  'hue', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);
		jQuery(this.element).find(".colorPicker_satInput").bind('change', {colorSpace: 'hsl', color:  'saturation', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);
		jQuery(this.element).find(".colorPicker_luminanceInput").bind('change', {colorSpace: 'hsl', color:  'luminance', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);

		jQuery(this.element).find(".colorPicker_rgbInput").bind('change', {colorSpace: 'rgb', color:  'rgbhex', }, jQuery.proxy(this, 'inputChange')).bind('keydown', changeEnterKeyToBlur);
	},

	setRGBColor: function(newRGBColor){
		if (this.options.currentRGBColor.r == newRGBColor.r &&
			this.options.currentRGBColor.g == newRGBColor.g &&
			this.options.currentRGBColor.b == newRGBColor.b){
			//Nothing to do.
			return;
		}

		this.options.currentRGBColor = newRGBColor;

		var hslColor = newRGBColor.toHSL();
		jQuery(this.element).find(".colorPicker_currentColor").css('background-color', newRGBColor.toHex());

		//Update the hsl input boxes
		jQuery(this.element).find(".colorPicker_hueInput").val(hslColor.hue);
		jQuery(this.element).find(".colorPicker_satInput").val(hslColor.saturation);
		jQuery(this.element).find(".colorPicker_luminanceInput").val(hslColor.luminance);

		//Update the hsl sliders
		jQuery(this.element).find(".colorPicker_hueSliderSelector").css('top', intval(hslColor.hue * 256));
		jQuery(this.element).find(".colorPicker_saturationSliderSelector").css('top', intval(hslColor.saturation * 256));
		jQuery(this.element).find(".colorPicker_luminanceSliderSelector").css('top', intval(hslColor.luminance * 256));

		//update the RGB inputs
		jQuery(this.element).find(".colorPicker_redInput").val(newRGBColor.r);
		jQuery(this.element).find(".colorPicker_greenInput").val(newRGBColor.g);
		jQuery(this.element).find(".colorPicker_blueInput").val(newRGBColor.b);

		//update the main box
		jQuery(this.element).find(".colorPicker_rgbInput").val(newRGBColor.toHex());

        //TODO - this is a linking between two different objects that could be improved.
		if(this.options.focusCSSVariable != null){
			this.options.focusCSSVariable.setValue(newRGBColor.toHexNoHash());
			$('.baserealityEvent').trigger(BaseRealityEvent.cssColorChanged, this.options.focusCSSVariable);
		}
	},

	updateFromHSLParams: function(event, hslParams){
//		var debugString = "hslParams ";
//
//		for(var key in hslParams){
//			debugString = debugString + " key " + key + " value " + hslParams[key];
//		}

		if(!this.options.clickRGBColor){
			alert("clickRGBColor is not set - cannot use updateFromHSLParams until it is set.");
			return;
		}

		var currentHSLParams = this.options.clickRGBColor.toHSL();
		var newHSLParams = $.extend(currentHSLParams, hslParams);
		var newRGBColor = new RGBColor(newHSLParams.hue, newHSLParams.saturation, newHSLParams.luminance, 'hsl');

		this.setRGBColor(newRGBColor);
	},



	setColor: function(rgbColor){
		this.options.clickRGBColor = rgbColor;
		this.setRGBColor(rgbColor);
	},

    //If the user clicked on a color, then
	cssColorFocus: function(event, cssVariable){
		if(cssVariable.type == BaseRealityConstant.CSS_VARIABLE_COLOR){
			this.options.focusCSSVariable = cssVariable;
			var rgbColor = new RGBColor(cssVariable.value);
			this.setColor(rgbColor);
		}
	},

	_create: function() {
		//Could do one off stuff here.
	},

	_init: function() {
		try{
			this.attachSliderEvents();
			this.attachDraggerEvents();
			this.attachInputEditEvents();
			this.setRGBColor(new RGBColor("556b2f"));

			$(this.element).addClass('baserealityEvent');

			$(this.element).bind(BaseRealityEvent.cssColorFocus, $.proxy(this, 'cssColorFocus'));
			$(this.element).bind(BaseRealityEvent.cssColorChangedHSL, $.proxy(this, 'updateFromHSLParams'));
			$(this.element).bind(BaseRealityEvent.cssColorChanged, $.proxy(this, 'cssColorFocus'));
			$(this.element).bind(BaseRealityEvent.updateCSS);
		}
		catch(error){
			alert("error in init " + error);
		}
	},

	destroy: function() {
       $.Widget.prototype.destroy.apply(this, arguments); // default destroy
        // now do other stuff particular to this widget
   },

	options: {
		currentRGBColor: new RGBColor('000000'),	//This is always the current colour -
		focusCSSVariable: null, //The CSS variable currently being editted.
		clickRGBColor: false,		//We remember the colour when the sliders are clicked to avoid degeneration when we convert hsl -> rgb -> hsl
									//to prevent hue information being lost when the luminance is changed to zero - i.e. rgb(0, 0, 0).
		positionYAtClick: 0,		//Need to remember where the draggers were clicked

	}
};

ColorPickerTool = Logger.extend(ColorPickerTool, 'ColorPickerTool');

$.widget("basereality.colorPickerTool", ColorPickerTool); // create the widget

function	initDeveloperColorPickerTool(params){

	var colorPickerSelector = params.colorPickerSelector;

	try{
		$(colorPickerSelector).colorPickerTool({});
	}
	catch(error){
		alert("Error initialising color picker " + error);
	}
}