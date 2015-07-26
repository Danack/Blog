var ContentImageHolder = {

    constructedClass: 'jsContentImageHolder',

    instance: null,

    options: {
        contentObject: null,
        isPreview: false,
        isDragging: false,
        draggable: false
    },

    getContentObject: function(){
        return this.options.contentObject;
    },

    click: function(event){
        if(this.options.isDragging == true){
            //draggable dropEvent also triggers a click
            event.stopPropagation();
            return false;
        }
        baseTrigger(BaseRealityEvent.previewContent, this.options.contentObject);
        return false;
    },

    previewContentByID: function(event, idToPreview){
        if(this.options.contentObject.getDOMID() == idToPreview){
            baseTrigger(BaseRealityEvent.previewContent, this.options.contentObject);
        }
    },

    _create: function() {
        return this;
    },

    dragStart: function(event){
        baseTrigger(BaseRealityEvent.dragStart, this.options.contentObject);
        this.options.isDragging = true;
    },

    dragStop: function(event){
        baseTrigger(BaseRealityEvent.dragStop, this.options.contentObject);
        this.options.isDragging = false;
    },

    makeContentDraggable: function(){
        try{
            $(this.element).draggable({
                revert: true,
                scroll: false,

                start:	$.proxy(this, 'dragStart'),
                stop:	$.proxy(this, 'dragStop'),
            });
        }
        catch(error){
            alert("Exception in makeContentDraggable: " + error);
        }
    },

    _init: function() {

        if($(this.element).hasClass(ContentImageHolder.constructedClass) == true){
            //Already constructed -
            return;
        }

        var serialized = $(this.element).data('serialized');

        if ( undefined === serialized) {
            //throw Error("Failed to get data from serialized for contentImage");
            return;
        }

        this.options.contentObject = json_decode_object(serialized);
        $(this.element).addClass("baserealityEvent").addClass(ContentImageHolder.constructedClass);

        if(this.options.isPreview == true){
            $(this.element).on('click', wrap(this.click, this));
        }

        $(this.element).removeClass('content');

        if (this.options.draggable == true) {
            this.makeContentDraggable();
        }

        bindOnce(this.element, BaseRealityEvent.previewContentByID, this, 'previewContentByID');
    }
};

$.widget("basereality.contentImageHolder", ContentImageHolder); // create the widget

function initContent(params){

    var contentParams = {};

    for(var i in params){
        if(params.hasOwnProperty(i)){
            if(i != 'contentSelector'){
                contentParams[i] = params[i];
            }
        }
    }

    var contentHolder = $(params.contentSelector).contentImageHolder(contentParams);
}