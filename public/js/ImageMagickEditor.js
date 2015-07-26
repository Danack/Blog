

var ImageMagickEditor = {

    editID: 0,

    options: {
        imAddFunctionSelector: '.imAddFunctionSelector',
        imAddFunctionButton: '.imAddFunctionButton',
        imUpdateImageButton: '.imUpdateImageButton',
    },

    getNextID: function(){
        return "editID_" + this.editID;
    },

    createAndAddFunction: function(functionName){

        var divID = this.getNextID();

        var div = $("<div/>", {
            id: divID,
            text: "" + functionName
        }).appendTo("#editHolder");

        $(div).imageMagickEdit({
            functionName: functionName
        });

        $(div).addClass('imageMagickFunction');
    },

    addFunction: function (){
        var functionName = $(this.element).find(this.options.imAddFunctionSelector).val();
        this.createAndAddFunction(functionName);
    },

    updateImage: function(){
        var functions = [];

        $(this.element).find(":basereality-imageMagickEdit").each(function (index, domEle) {
            var imFuncObject = $(domEle).data("basereality-imageMagickEdit");
            var imFuncDef = imFuncObject.getFunctionAndParams();
            functions.push(imFuncDef);
        });

        var params = {};

        params.api = 'true';
        params.functionList = functions;

        var jsonFunction = JSON.stringify(functions);

        var now = new Date();
        var ticks = now.getTime();
        
//        $.ajax({
//            url: '/ImageMagick.php',
//            data: params,
//            //dataType: 'json',
//            type: 'POST',
//            success: $.proxy(this, 'updateImageResult'),
//            error: $.proxy(this, 'updateImageError'),
//        });

        var newSrc = "/ImageMagickImage?api=true&ticks=" + ticks + "&funcList=" + encodeURIComponent(jsonFunction);

        $("#imMagickImage").attr('src', newSrc);
    },

    updateImageResult: function(data, textStatus, jqXHR){
        var now = new Date();
        var ticks = now.getTime();
        var newSrc = "/ImageMagick.php?api=true&ticks=" + ticks;
        $("#imMagickImage").attr('src', newSrc);
    },

    updateImageError: function(data, textStatus, jqXHR){
        alert("Error updating image: " + data);
    },

    bindFunctions: function(){

        try{
            if(this.options.imAddFunctionButton != null){
                $(this.element).find(this.options.imAddFunctionButton).bind('click', $.proxy(this, 'addFunction'));
            }

            $(this.element).find(this.options.imUpdateImageButton).bind('click', $.proxy(this, 'updateImage'));
        }
        catch(error){
            alert("error setting up endless content: " + error);
        }
    },

    _create: function() {
        //alert("create called");
    },

    _init: function() {
        this.bindFunctions();
    },

};

$.widget("basereality.imageMagickEditor", ImageMagickEditor);


function initImageMagickEditor(selector){
    $(selector).imageMagickEditor({
        //readOnly: readOnly
    });
}