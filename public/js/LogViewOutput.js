var LogViewOutput = function(options, element){
	this.options = options;
	this.element = element;

	this._init();
};

LogViewOutput.prototype = {


	refreshTimeout: null,
	refreshTime: 1000,


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


	clickPauseButton: function(){
		this.options.running = false;
		$(this.element).find(".pauseButton").hide();
		$(this.element).find(".updateButton").show();
	},

	clickUpdateButton: function(){
		this.options.running = true;
		$(this.element).find(".pauseButton").show();
		$(this.element).find(".updateButton").hide();
	},

	logViewUpdateResult: function(data, textStatus, jqXHR){

		this.options.refreshLogViewTimeout = null;

		//alert("do something with the data");
		//data.logEntries
		if(this.options.running == true){
		}
	},

	startRefreshTimeout: function(){
		if(this.options.refreshLogViewTimeout != null){
			clearTimeout(this.options.refreshLogViewTimeout);
			this.options.refreshLogViewTimeout = null;
		}

		this.options.refreshLogViewTimeout = setTimeout($.proxy(this, 'logViewUpdate'), 1000);
	},


	clearLogEntries: function(){
		$(this.element).find(".logEntries").empty();
	},

	logViewUpdate: function(event, logViewFilter){
		$.ajax({
			url: '/logViewRefresh',
			dataType: 'json',
			//data: params,
			type: 'GET',
			success: $.proxy(this, 'logViewUpdateResult'),
		});
	},

	_create: function() {
		return this;
	},

	_init: function() {

		this.options.running = true;

		$(this.element).addClass("baserealityEvent");
		$(this.element).on(BaseRealityEvent.logViewUpdate, wrap(this, 'logViewUpdate'));

		$(this.element).find(".refreshButton").bind('click', $.proxy(this, 'logViewUpdate'));
		$(this.element).find(".clearButton").bind('click', $.proxy(this, 'clearLogEntries'));

		$(this.element).find(".pauseButton").bind('click', $.proxy(this, 'clickPauseButton'));
		$(this.element).find(".updateButton").bind('click', $.proxy(this, 'clickUpdateButton'));

		return this;
	},
}





$.widget.bridge('logViewOutput', LogViewOutput);


function initLogViewOutput(){
	var logViewOutputParams = {};

	var logViewOutputPanel = $('#logViewOutputPanel').logViewOutput(logViewOutputParams);
	//newControlPanel.controlPanel("addControl", loggerControlPanel); // => "public method"
}

initLogViewOutput();

