var LogViewControl = function(options, element){
	this.options = options;
	this.element = element;

	this._init(); //init gets called by bridge
};

LogViewControl.prototype = {

	instance: null,

	/** LogViewFilter */
	logViewFilter: null,

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

	logFilterUpdate: function(event){
		var name = $(event.target).attr("name");
		var value = $(event.target).val();

		this.logViewFilter.setFilter(name, value);

		$(".baserealityEvent").trigger(BaseRealityEvent.logViewUpdate, this.logFilter);
	},

	setMySession: function(event){
		var sessionID = $(event.target).attr('name');

		$(this.element).find(".logFilter[name=session]").val(sessionID);
	},

	_create: function() {
		return this;
	},

	_init: function() {
		this.logViewFilter = new LogViewFilter();
		$(this.element).addClass("baserealityEvent");

		$(this.element).find(".logFilter").bind('change', $.proxy(this, 'logFilterUpdate'));
		$(this.element).find(".setMySessionButton").bind('click', $.proxy(this, 'setMySession'));
		return this;
	},
}


$.widget.bridge('logViewControl', LogViewControl);


function initLogViewControl(){

	var logViewControlParams = {};

	var logViewControlPanel = $('#logViewControlPanel').logViewControl(logViewControlParams);
	//newControlPanel.controlPanel("addControl", loggerControlPanel); // => "public method"
}

initLogViewControl();

