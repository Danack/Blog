
//http://www.erichynds.com/jquery/using-jquery-ui-widget-factory-bridge/

var DropUploadItem = function(options, element){
	this.options = options;
	this.element = element;

	this._init();
};

DropUploadItem.prototype = {

	instance: null,

	options: {
		index: null,
		file: null,
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

	setUploadPercent: function (percent){
		var uploadString = "Uploaded " + percent + " %";
		$(this.element).text(uploadString);
	},

	setContent: function(contentObject){
		var contentDOM = $(contentObject.displayThumbnail());
		$(this.element).append(contentDOM);
	},

	remove: function(){
		$(this.element).remove();
	},

	setContentData: function(dataObject){
		$(this.element).empty();
		var contentObject = json_decode_object(dataObject);
		var contentDOM = $(contentObject.displayThumbnail());
		contentDOM.data('serialized', dataObject);
		$(contentDOM).contentImageHolder({isPreview: true,});
		$(this.element).append(contentDOM);

		var removeButton = $("<span>Done</span>");
		$(removeButton).on('click', $.proxy(this, 'remove'));
		$(this.element).append(removeButton);
	},


	_init: function() {
		this.setUploadPercent(0);

		return this;
	},
};


DropUploadItem.createElement = function(){

	return $("<span></span>");
}

$.widget.bridge('dropUploadItem', DropUploadItem);