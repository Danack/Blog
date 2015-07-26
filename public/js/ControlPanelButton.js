var ControlPanelButton = {

	instance: null,

	options: {
		//contentObject: null,
		diplayName: "Unknown button",
		state: 0,
		eventType: null,
	},

//	smartyDebugTemplate: function(event, value){
//		alert("Hello. Value is " + value);
//	},
//
//	addControlByEvent: function(event, controlPanelParams){
//		this.addControl(controlPanelParams);
//	},
//
//	buttonClicked: function(event){
//		var newValue = $(event.target).val();
//
//		if(newValue){
//			$(this.element).find("." + event.data.name).show();
//		}
//		else{
//			$(this.element).find("." + event.data.name).hide();
//		}
//	},

//	addControl: function(controlPanelParams){

//		var name = controlPanelParams.displayName;
//
//		var checkBox = $("<input type='checkbox' style='margin-right: 15px' value='" + name + "' />");
//
//		checkBox.bind(
//			'change',
//			{name: name},
//			$.proxy(this, 'buttonClicked')
//		);
//
//		var newElement = $("<span/>").append(checkBox).append($("<span>" + name + "</span>"));
//
//		//var controlsSpan = $("<span > Controls go in here</span>");
//
//		$(newElement).append("<div style='display: none;' class='debugBox  " + name + "'>Smarty stuff goes here.</div>")
//
//
//		$(this.element).find(".controlSelector").append(newElement);
//	},

	change: function(event){
		var value = 0;

		if($(event.target).is(':checked') == true){
			value = $(event.target).val();
		}

		$(".baserealityEvent").trigger(this.options.eventType, value);
	},

	_create: function() {
		return this;
	},

	_init: function() {

		//diplayName: 'Template debug',
//		type:	'button',
//		state:	0,
//		eventType:	BaseRealityEvent.SmartyTemplateDebug
//		$(this.element).addClass("baserealityEvent");

		var 	label = $("<label for='anonymousCheckBox'>" + this.options.diplayName + "</label>");

		var stateString = "";
		if(this.options.state != 0){
			stateString = "checked='checked'";
		}

		var 	checkBox = $("<input type='checkbox' class='debugControl' name='anonymousCheckBox' value='1' " + stateString + "/>");

		$(checkBox).on("change", wrap(this, 'change'));

		$(this.element).append(label);
		$(this.element).append(checkBox);

		return this;
	}
}

$.widget("basereality.controlPanelButton", ControlPanelButton); // create the widget

