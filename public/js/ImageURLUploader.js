//What is this in Javascript
//http://howtonode.org/what-is-this


//Hacking jQuery
//http://bililite.com/blog/understanding-jquery-ui-widgets-a-tutorial/
var ImageURLUploader = {

	options: {
	},

	instance: null,

	imageURLUploadSuccess: function(dataObject, textStatus, jqXHR){
		try{
            debugger;


            contentObject = json_decode_object(dataObject);
			var contentDOM = $(contentObject.displayThumbnail());
			$(this.element).find(".imageHolder").html(contentDOM);

			//$(".baserealityEvent").trigger(BaseRealityEvent.previewContent, this.options.contentObject);
		}
		catch(error){
			alert("Exception in imageURLUploadSuccess: " + error);
		}
	},

    imageURLUploadError: function(dataObject, textStatus, jqXHR) {
        
    },

	uploadImageLink: 	function(event){

		var url = $(event.target).val();
		$(event.target).val("");
		this.uploadImageLinkReal(url);
	},

	uploadImageLinkReal: 	function(url){

		var params = {};

		params.url = url;
		var contentDOM = $("<span>Starting to fetch image from URL " + params.url + "</span>");

		$(this.element).find(".imageHolder").html(contentDOM);

		$.ajax({
			url: '/pictures/fetch',
			data: params,
			dataType: 'json',
			type: 'POST',
			success: $.proxy(this, 'imageURLUploadSuccess'),
			error: $.proxy(this, 'imageURLUploadError'),
		});
	},

	previewContent: function(event, contentObject){
		$(this.element).hide(200);
	},

	closePreview: function(){
		$(this.element).show(200);
	},

    _create: function() {
		return this;
    },

    _init: function() {

		$(this.element).addClass("baserealityEvent");
		$(this.element).bind(BaseRealityEvent.previewContent, $.proxy(this, 'previewContent'));
		$(this.element).bind(BaseRealityEvent.closePreview, $.proxy(this, 'closePreview'));

		$(this.element).find('.imageURLUploader').bind('change', $.proxy(this, 'uploadImageLink'));
		$(this.element).find(".imageURLUploader").bind('keydown', changeEnterKeyToBlur);

		ImageURLUploader.instance = this;

		return this;
    },
};

$.widget("basereality.ImageURLUploader", ImageURLUploader); // create the widget

var existingImageURLUploaderSelector = null;

function initImageURLUploader(params){
	var contentHolder = $(params.holderSelector).ImageURLUploader(params);
	existingImageURLUploader = params.holderSelector;
}


function	uploadImage(imageURL){
	if(existingImageURLUploader == null){
		alert("No uploader has been created, cannot upload image.");
		return;
	}

	ImageURLUploader.instance.uploadImageLinkReal(imageURL);
}