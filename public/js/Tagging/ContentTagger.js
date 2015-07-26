/*jslint evil: false, vars: true, eqeq: true, white: true */

//What is this in Javascript
//http://howtonode.org/what-is-this

//Hacking jQuery
//http://bililite.com/blog/understanding-jquery-ui-widgets-a-tutorial/
var ContentTagger = {

    setPrivateButton: '.setPrivate',

    options: {
        content: null,
        suggestedTags: ".suggestedTags",
        relatedTagsWidget: null,
    },


    //Remove all current tags
    clearExistingTags: function () {
        $(this.element).find('.currentTags').empty();
    },


    /**
     * removeTagFromContent
     * @param event
     * @param contentTag - the ContentTag object
     */
    removeTagFromContent: function (event, contentTag) {
        var url = "/tagDelete/" + contentTag.tagID + "/content/" + this.options.content.contentID + "/";

        $.ajax({
            url: url,
            dataType: 'json',
            type: 'DELETE',
            success: $.proxy(this, 'showCurrentTagsForContent'),
        });
    },


    /**
     *
     * @param contentTag
     */
    createAndAddTag: function (contentTag) {
        var newElement = createTag(contentTag, " x", BaseRealityEvent.removeTagFromContent);
        $(this.element).find('.currentTags').append(newElement);
    },

    /**
     * Get and then create all the tags for some content.
     */
    showCurrentTagsForContent: function () {
        getTagsForContent(this, 'tagsLoadedForContent', this.options.content);
    },

    /**
     * Success result of
     * @param data
     * @param textStatus
     * @param jqXHR
     */
    tagsLoadedForContent: function (data, textStatus, jqXHR) {
        try {
            this.clearExistingTags();

            for (index in data) {
                var tagText = data[index];
                this.createAndAddTag(tagText);
            }

            $(this.element).find('.currentTags').show();
            $(this.options.relatedTagsWidget).tagRelated("getRelatedTagsForTags", data);
        }
        catch (error) {
            alert("hmm " + error);
        }
    },

    /**
     * Success result of addTag
     * @param data
     * @param textStatus
     * @param jqXHR
     */
    tagSubmitOK: function (data, textStatus, jqXHR) {
        $(this.element).find('.tagInput').val("");
        this.showCurrentTagsForContent();
        //TODO trigger a reload of tags in the TagFilter.
    },

    //We're boned
    tagSubmitError: function (jqXHR, textStatus, errorThrown) {
        //alert("well now what? " + textStatus);
    },

    keyPress: function () {
        $(this.element).find('.tagInput').autocomplete("close");
    },

    inputSubmit: function () {
        var newTag = $(this.element).find('.tagInput').val();
        this.addTag(newTag);
        $(this.element).find('.tagInput').autocomplete("close");
        return false;
    },

    //Add a special private tag
    setPrivate: function () {
        this.addTag(BaseRealityConstant.TAG_PRIVATE);
    },

    //
    addTagToContent: function (event, contentTag) {
        this.addTag(contentTag.text);
    },



    addTag: function (tag) {

        if (this.options.content == null) {
            throw "Trying to add tag but contentID is null.";
            //return;
        }

        var params = {};
        params.tag = tag;

        if (params.tag.length == 0) {
            return;
        }

        var url = "/tagAdd/content/" + this.options.content.contentID + "/" + encodeURIComponent(tag);

        $.ajax({
            url: url,
            //data: params,
            type: 'POST',
            success: $.proxy(this, 'tagSubmitOK'),
            error: $.proxy(this, 'tagSubmitError'),
        });
    },

    //Content has changed - clear the current tags, and get the tags for the content
    previewContent: function (event, contentObject) {
        this.clearExistingTags();
        $(this.element).find('.tagInput').val("");
        this.options.content = contentObject;
        $(this.element).show(200);
        //this.showCurrentTagsForContent();
    },

    //No content selected, hide the panel
    closePreview: function () {
        $(this.element).hide(200);
    },

    _create: function () {
    },

    focus: function (e) {
        var val = $(this.element).find('.tagInput').val();
        if (val !== undefined &&
            val.length > 0) {
            $(this.element).find('.tagInput').autocomplete("search");
        }
    },


    //Create the content Tagger
    _init: function () {
        try {
            $(this.element).find('form').bind('submit', wrap(this, 'inputSubmit'));
            $(this.element).find(ContentTagger.setPrivateButton).on('click', wrap(this, 'setPrivate'));

            bindOnce(this.element, BaseRealityEvent.previewContent, this, 'previewContent');
            bindOnce(this.element, BaseRealityEvent.closePreview, this, 'closePreview');

            bindOnce(this.element, BaseRealityEvent.addTagToContent, this, 'addTagToContent');
            bindOnce(this.element, BaseRealityEvent.removeTagFromContent, this, 'removeTagFromContent');

            $(this.element).find('.tagInput').on('keypress', $.proxy(this, 'keyPress'));
            $(this.element).find('.tagInput').bind('focus', $.proxy(this, 'focus'));

            $(this.element).find('.tagInput').bind('change', $.proxy(this, 'inputSubmit'));

            $(this.element).find('.tagInput').autocomplete({
                minLength: 3,
                disabled: false,
                source: "/tags/suggest/",
                autoFocus: false,
            });

            $(this.element).find(ContentTagger.setPrivateButton).show();

            var params = { event: BaseRealityEvent.addTagToContent };

            this.options.relatedTagsWidget = $(this.element).find(this.options.suggestedTags).tagRelated(params);
        }
        catch (error) {
            alert("adding _init failed " + error);
        }
    },
};

$.widget("basereality.contentTagger", ContentTagger); // create the widget

function initContentTagger(selector) {
    $(selector).contentTagger({});
}
