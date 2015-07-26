

var ImageMagickEdit = {

    imageMagickFunctionsVar: null,

    options: {
        offset:	75,
        functionName: null,
    },

    getFunctionDetails: function() {

        if(ImageMagickEdit.imageMagickFunctionsVar == null){
            alert("imageMagickFunctionsVar == null, so no functions are available.");
            return;
        }

        for(var name in ImageMagickEdit.imageMagickFunctionsVar){
            if(name == this.options.functionName){
                return ImageMagickEdit.imageMagickFunctionsVar[name];
            }
        }

        var errorString = "Unknown function name [" + this.options.functionName +  "] so things are about to go spectacularly wrong."

        alert(errorString);

        throw errorString;
    },

    getFunctionAndParams: function(){
        var functionDetails = this.getFunctionDetails();
        var params = {};
        
        params.functionName = this.options.functionName;
        params.params = {};

        for(param in functionDetails.params){
            var paramClass = "param_" + param;
            var value = $(this.element).find("." + paramClass).val();
            params.params[param] = value;
        }

        return params;
    },


    addDataEntry: function (element, param, paramDetails){

        var paramClass = "param_" + param;

        switch(paramDetails[0]){

            case('int'):
            case('float'):{
                $(element).append("<input type='text' class='" + paramClass + "' length='6' />");
                break;
            }

            case('option'):{
                var options = paramDetails.options;
                var inputSelect = "<select class='" + paramClass + "'>";

                for(option in options){
                    inputSelect += "<option value='" + option + "'>" + options[option] + "</option>"
                }

                inputSelect += "</select>";

                $(element).append(inputSelect);

                break;
            }


            default:{
                alert("Unknown data type [" + paramDetails[0] + " for function " + param);
            }
        }

    },

    remove: function(){
        //alert("Remove the function [" + this.options.functionName + "]");
        this.destroy();
    },


    bindFunctions: function(){

        $(this.element).find(".imRemoveFunction").bind('click', $.proxy(this, 'remove'));

    },

    _create: function() {
        //alert("create called");
    },

    _init: function() {

        $(this.element).addClass();
        
        var functionDetails = this.getFunctionDetails();

        $(this.element).append('<span class="clickyButton imRemoveFunction">Remove</span>');

        $(this.element).append('<br/>');

        for(param in functionDetails.params){
            var element = $('<span/>',{
                text: "" + param,
            }).appendTo(this.element);

            var paramDetails = functionDetails.params[param];

            this.addDataEntry(element, param, paramDetails);

            $(this.element).append('<br/>');
        }

        this.bindFunctions();

        return this;
    },



    imageMagickFunctions: function(imageMagickFunctions){

        if(imageMagickFunctions == undefined){
        }
        else{
            ImageMagickEdit.imageMagickFunctionsVar = imageMagickFunctions;
        }

        return  ImageMagickEdit.imageMagickFunctionsVar;
    },


    destroy: function() {

        // Unbind any events that may still exist
        $(this.element).find(".imRemoveFunction").unbind('click');

        // Remove any new elements that you created
        $(this.element).remove();

        // Remove any classes, including CSS framework classes, that you applied
        this._trigger("destroy",{type:"destroy"},{options:this.options});

        // After you're done, you still need to invoke the "base" destroy method
        // Does nice things like unbind all namespaced events on the original element
        $.Widget.prototype.destroy.call(this);
    }
};




$.widget("basereality.imageMagickEdit", ImageMagickEdit);