
var TrashCan = function(options, element){
	this.options = options;
	this.element = element;
	this._init();
};


TrashCan.prototype = {

	instance: null,

	options: {
		contentTag: null,
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

	dragStart: function	(event, contentObject){
		$(this.element).find('.trashTarget').css('display', 'inline-block').css('border', '2px dashed #000000').css('height', '80px').css('width', '80px').text("Drop files here");
	},

	dragStop: function 	(event, contentObject){
		$(this.element).find('.trashTarget').css('display', 'none').css('border', '0px none #000000').text("");
	},

	processDeleteResponse: function (data, textStatus, jqXHR){
		//Should do thingy deleted...
		//$(".baserealityEvent").trigger('contentRefresh', 'all');

		baseTrigger(BaseRealityEvent.contentFilterChanged);
	},

	trashContent: function (event, ui){

		try{
			var contentObject = $(ui.draggable).contentImageHolder("getContentObject");
			this.deleteContent(contentObject);
		}
		catch(error){
			alert("Exception caught: " + error);
		}

		event.stopPropagation();
		event.stopImmediatePropagation();
	},

	deleteContent: function (contentObject){

		var result = confirm("Really delete " + contentObject.typeName +  " " + contentObject.contentID + "?");

		var rootURL = false;
		switch(contentObject.typeName){

			case('File'):{
				rootURL = "/file/";
				break;
			}

			case('Image'):{
				rootURL = "/image/";
				break;
			}
		}

		if(rootURL == false){
			alert("Unknown content type " + contentObject.typeName + " cannot delete.");
			return;
		}

		var url = rootURL + contentObject.contentID + "/delete";

		if(result){
			var params = {};
			params['contentID'] = contentObject.contentID;

			$.ajax({
				url: url,
				data: params,
				dataType: 'json',
				//type: 'DELETE',
				success:$.proxy(this, 'processDeleteResponse'),
				error: genericAjaxError,
				traditional: true,
			});
		}
	},

	_init: function() {
		$(this.element).droppable({
			drop: $.proxy(this, 'trashContent'),
		});

		bindOnce(this.element, BaseRealityEvent.dragStart, this, 'dragStart');
		bindOnce(this.element, BaseRealityEvent.dragStop, this, 'dragStop');
		return this;
	},
};

$.widget.bridge('trashCan', TrashCan);



function initTrashDrop(selector){
	$(selector).trashCan({});
}

/*

$('.droppable').droppable({ drop: function(ev, ui) {
	//Get Details of dragged and dropped
	var draggedclass = ui.draggable.attr('class'),
		droppedclass = 'class' + $(this).attr('name').toLowerCase();

	//update the classes so that it looks od.
	ui.draggable.removeClass(draggedclass).addClass(droppedclass);
	ui.draggable.removeAttr('style');
});

*/