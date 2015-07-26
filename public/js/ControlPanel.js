
function 	smartyShowMagic(){
	$('div.example').css('width', function(index) {
		return index * 50;
	});
}

function 	smartyHideMagic(){

}

var ControlPanel = function(options, element){
	this.options = options;
	this.element = element;

	this._init();
};

ControlPanel.prototype = {

	instance: null,

	options: {
//		//contentObject: null,
	},

	option: function( key, value ){
		// get/change options AFTER initialization:
		// you don't have to support all these cases,
		// but here's how:

		// signature: $('#foo').bar({ cool:false });
		if( $.isPlainObject( key ) ){
			this.options = $.extend(true, this.options, key);

			// signature: $('#foo').option('cool');  - getter
		} else if ( key && typeof value === "undefined" ){
			return this.options[ key ];

			// signature: $('#foo').bar('option', 'baz', false);
		} else {
			this.options[ key ] = value;
		}

		return this; // make sure to return the instance!
	},

	smartyDebugTemplate: function(event, value){
		if(value){
			smartyShowMagic();
		}
		else{
			smartyHideMagic();
		}
	},

	addControlByEvent: function(event, controlPanelParams){
		this.addControl(controlPanelParams);
	},

	createControl: function(controlDefine){
//		diplayName: 'Template debug',
//		type:	'button',
//		state:	0,
//		eventType:	BaseRealityEvent.SmartyTemplateDebug
		var element = $("<span class='debugControl'/>");
		$(element).controlPanelButton(controlDefine);
		return element;
	},

	buttonClicked: function(event){
		if($(event.target).is(':checked') == true){
			$(this.element).find("." + event.data.name).show();
		}
		else{
			$(this.element).find("." + event.data.name).hide();
		}
	},

	logLevelChanged: function(event){

		var logLevelValue = $(event.target).val();
		var logLevelName = $(event.target).attr('name');

		$(".baserealityEvent").trigger(BaseRealityEvent.logLevelChange, [logLevelName, logLevelValue]);

	},

	createLogOptionForScope: function(scopeName, scopeLevel){
		var scopeLabel =	$("<span style='border: 3px solid #000000; padding: 2px; margin: 3px'>" + scopeName + "</span>");
		var selectHolder = $("<select name='" + scopeName +  "'></select>");

		$.each(Logger.levels, function(val, text) {

			var option = $('<option></option>').val(val).html(text);

			if (scopeLevel == text){
				option.attr('selected', 'selected');
			}

			selectHolder.append(option);
		});

		$(selectHolder).bind('change', $.proxy(this, 'logLevelChanged'));

		scopeLabel.append(selectHolder);

		return scopeLabel;
	},

	addLoggerPanelToContainer: function(container){
		for( var knownScope in Logger.knownScopes){
			//Add filters
			logOption = this.createLogOptionForScope(knownScope, Logger.knownScopes[knownScope]);
			$(container).append(logOption);
		}

		var logConsole = $("<div id='logConsole' class='logConsole'></div>");

		$(container).append(logConsole);
	},

	addControlsToContainer: function(container, controlParams){
		for ( var i in controlParams.controls) {
			var controlDefine = controlParams.controls[i];

			var control = this.createControl(controlDefine);
			$(container).append(control);
			$(container).append($("<br/>"));
		}
	},

	createContainer: function(name){

		var checkBox = $("<br/><input type='checkbox' style='margin-right: 15px' value='" + name + "' />");

		checkBox.bind(
			'change',
			{name: name},
			$.proxy(this, 'buttonClicked')
		);

		var container = $("<div style='display: none;' class='debugBox  " + name + "'></div>");

		var newElement = $("<span/>").append(checkBox).append($("<span>" + name + "</span>"));
		$(newElement).append(container);

		$(this.element).find(".controlSelector").append(newElement);

		return container;
	},

	addControl: function(controlParams){

		var name = controlParams.displayName;

		container = this.createContainer(name);

		switch(controlParams.type){
			case('standard'):{
				this.addControlsToContainer(container, controlParams)
				break;
			}
			case('logger'):{
				this.addLoggerPanelToContainer(container);
				break;
			}
			default:{
				throw new Error("Unknown ControlPanel type [" + controlParams.type + "]");
			}
		}
	},

	_create: function() {
		return this;
	},

	_init: function() {
		$(this.element).addClass("baserealityEvent");
		//$(this.element).bind(BaseRealityEvent.addControl, $.proxy(this, 'addControl'));
		$(this.element).on(BaseRealityEvent.smartyTemplateDebug, wrap(this, 'smartyDebugTemplate'));
		$(this.element).on(BaseRealityEvent.toggleControlPanelVisibility, wrap(this, 'toggleVisibility'));
		return this;
	},

	toggleVisibility: function(event){
		if(event.target != event.currentTarget){
			event.stopPropagation();
			event.preventDefault();
			return;
		}

		$(this.element).toggle(500);
	}
}

//$.widget("basereality.controlPanel", ControlPanel); // create the widget

//$.widget.bridge("basereality.controlPanel", ControlPanel);

$.widget.bridge('controlPanel', ControlPanel);


function initControlPanel(){

	var debugPanel = $('<div class="baseDebugPanel" id="baseDebugPanel" style="display: none;"></div>');

	var debugPanelControls = $("<div class='controlSelector'></div>");

	debugPanel.append(debugPanelControls);

	debugPanel.addClass('mainPanel shadow rounderCorners');

	$("body").append(debugPanel);

	var contentParams = {
//		//contentImageSelector: params.contentImageSelector,
//		//type: params.type,
	}

	var newControlPanel = $('#baseDebugPanel').controlPanel(contentParams);

	var smartyControlPanel = {
		displayName: "Smarty",
		type: 'standard',
		controls:	{
			0:	{
				diplayName: 'Template debug',
				type:	'button',
				state:	0,
				eventType:	BaseRealityEvent.smartyTemplateDebug
			}
		}
	};

	//$(newControlPanel).addControl(smartyControlPanel);
	newControlPanel.controlPanel("addControl", smartyControlPanel); // => "public method"

	var loggerControlPanel = {
		displayName: "Logger",
		type: 'logger',
		controls:	{
		}
	};

	//newControlPanel.addControl(loggerControlPanel);
	newControlPanel.controlPanel("addControl", loggerControlPanel); // => "public method"
}

initControlPanel();





