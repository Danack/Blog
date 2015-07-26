
//http://www.erichynds.com/jquery/using-jquery-ui-widget-factory-bridge/

var ContentTag = function(options, element){
	this.options = options;
	this.element = element;

	this._init();
};

ContentTag.prototype = {

	instance: null,

	options: {
		contentTag: null,
		text: null,
		type: null,
		appendString: '',
		eventName: null,
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

	/*
	triggerRemoveTag: function(){
		baseTrigger(BaseRealityEvent.removeTag, this.options.contentTag);
	},

	triggerRemoveTagFilter: function(){
		baseTrigger(BaseRealityEvent.removeTagFromFilter, this.options.contentTag);
		$(this.element).remove();
	},

	triggerAddTagToFilter: function(){
		baseTrigger(BaseRealityEvent.addTagToFilter, this.options.contentTag);
		baseTrigger(BaseRealityEvent.closePreview);
	}, */

	triggerEvent:function(){

		if($.isArray(this.options.eventName) == true){
			for(var i in this.options.eventName){
				var eventName = this.options.eventName[i];
				baseTrigger(eventName, this.options.contentTag);
			}
		}
		else{
			baseTrigger(this.options.eventName, this.options.contentTag);
		}
	},

	_init: function() {

		if(this.options.appendString === undefined){
			this.options.appendString = "";
		}

		var text = this.options.contentTag.text;

		if(text == null){
			text = 'hmm';
		}

		$(this.element).addClass('tag').addClass('clickyButton removeTag');
		$(this.element).html(text + this.options.appendString);
		$(this.element).on('click', wrap(this, 'triggerEvent'));
		return this;
	},
};

$.widget.bridge('contentTagJQ', ContentTag);