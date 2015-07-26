var Related = {

    options: {
        event: null,
    },

    //Remove all current tags.
    clearExistingSuggestedTags: function(){
        $(this.element).empty();
    },

    //Success function for getRelatedTagsForText
    //This is used for the autocomplete.
    getRelatedTagsResult: function(tags){
        this.clearExistingSuggestedTags();

        for(var tagText in tags){
            var contentTag = new Tag();
            contentTag.text = tagText;

            if (this.options.event !== null) {
                var newElement = createTag(contentTag, " +", this.options.event);
                $(this.element).append(newElement);
            }
        }
    },

    //Get all the ContentTag objects text, as an array
    //And start getting related tags.
    getRelatedTagsForTags: function(contentTagArray, var2, var3){
        var textArray = [];
        for(index in contentTagArray){
            var contentTag = contentTagArray[index];
            textArray.push(contentTag.text);
        }
        this.getRelatedTagsForText(textArray);
    },

    //Build and send the query to get related tags.
    getRelatedTagsForText: function(textArray, var2, var3){
        var url = "/tags/relatedTags";
        var separator = "?";

        for(index in textArray){
            var text = textArray[index];
            url += separator + "tags[]=" + encodeURIComponent(text);
            separator = "&";
        }

        $.ajax({
            url: url,
            dataType: 'json',
            type: 'GET',
            success: $.proxy(this, 'getRelatedTagsResult'),
            error: genericAjaxError,
        });
    },

    _init: function() {
    },
};

$.widget("basereality.tagRelated", Related);

