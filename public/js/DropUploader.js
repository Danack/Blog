
var DropUploader = {

	options: {
		uploads: [],
	},

	instance: null,

	uploadStarted: function (index, file, files_count){
		this.docLeave();

		var newElement = DropUploadItem.createElement();
		$(this.element).find('.dropUploads').append(newElement);

		var params = {};
		params.index = index;
		params.file = file;

		var dropUploadItem = $(newElement).dropUploadItem(params);
		this.options.uploads[index] = dropUploadItem;
	},

	uploadUpdate: function  (index, file, currentProgress){
		var upload = this.options.uploads[index];
		upload.dropUploadItem('setUploadPercent', currentProgress);
	},

	uploadFinished:  function (index, file, dataObject, timeDiff){
		var uploadItem = this.options.uploads[index];
		uploadItem.dropUploadItem('setUploadPercent', 100);

		try{
			uploadItem.dropUploadItem('setContentData', dataObject);
		}
		catch(error){
			alert("Exception in uploadFinished " + error.toString());
		}
	},

	docOver: function(event){
		$(this.element).find('.fileDropTarget').addClass('dropTargetActive');
	},

	docLeave: function	(event){
		$(this.element).find('.fileDropTarget').removeClass('dropTargetActive');
	},

	_create: function() {
		return this;
	},

	initFileDrop : function(){

		//$(this.element).find(".imageDropTarget").filedrop({
		$(this.element).filedrop({
			fallback_id: 'fallbackFileDrop',
			url: '/pictures/upload',

			//    refresh: 1000,
			paramname: 'fileUpload',
			//    maxfiles: 25,           // Ignored if queuefiles is set > 0
			maxfilesize: 4,         // MB file size limit
			//    queuefiles: 0,          // Max files before queueing (for large volume uploads)
			//    queuewait: 200,         // Queue wait time if full
			//    data: {},
			//    headers: {},
			//    drop: empty,
			//    dragEnter: empty,
			//    dragOver: empty,
			//    dragLeave: empty,
			//    docEnter: empty,
			docOver: $.proxy(this, 'docOver'),
			docLeave:$.proxy(this, 'docLeave'),
			//	beforeEach: empty,
			//   afterAll: empty,
			//  rename: empty,
			//  error: function(err, file, i) {
			//    alert(err);
			//  },
			uploadStarted: $.proxy(this, 'uploadStarted'),
			uploadFinished: $.proxy(this, 'uploadFinished'),
			progressUpdated: $.proxy(this, 'uploadUpdate'),
			//     speedUpdated
		});
	},

	_init: function() {
		//$(this.element).addClass("baserealityEvent");
		this.initFileDrop();
		return this;
	},
};

$.widget("basereality.dropUploader", DropUploader); // create the widget

function initDropUploader(params){
	var contentHolder = $(params.holderSelector).dropUploader(params);
}
