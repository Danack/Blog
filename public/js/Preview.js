


var Preview = {

    instance: null,

    options: {
        contentSelector: null,
    },

    closePreview: function(){
        $(this.element).hide();
    },

    previewContent: function(event, contentObject, extra1, extra2){

        this.log("Previewing contentID " + contentObject.getContentID());

        try{
            $(this.element).empty();
        }
        catch(error){
            alert("wut " + error);
        }

        var functionString = "baseTrigger(\"" + BaseRealityEvent.closePreview + "\");";
        var closePreview = $("<br/><span class='clickyButton' onclick='" + functionString + "'>Close preview</span><br/>");

        var newdiv1 = $("<div style='text-align: center'/>");
        $(newdiv1).append(closePreview);

        var previewObject = contentObject.displayPreview();

        newdiv1.append(previewObject);

        closePreview = $("<br/><span class='clickyButton' onclick='" + functionString + "'>Close preview</span><br/>");
        $(newdiv1).append(closePreview);

        $(this.element).append(newdiv1);
        $(this.element).show();
    },

    _create: function() {
        return this;
    },

    keyup: function (e) {
        baseTrigger(BaseRealityEvent.closePreview);
    },



    _init: function() {
        $(this.element).addClass("baserealityEvent");
        bindOnce(this.element, BaseRealityEvent.previewContent, this, 'previewContent');
        bindOnce(this.element, BaseRealityEvent.closePreview, this, 'closePreview');
        Preview.instance = this;
    }
}

Preview = Logger.extend(Preview, 'Preview');

$.widget("basereality.preview", Preview); // create the widget

function initPreview(params){
    var previewParams = {
        contentSelector: params.contentSelector,
    }

    var contentHolder = $(params.holderSelector).preview(previewParams);
}
