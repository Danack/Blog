/*jslint evil: false, vars: true, eqeq: true, white: true */




var TagFilter = {

    options: {
        content: null,

        filterTags: '.searchTags',
        contentTags: '.contentTags',
        suggestedTags: '.suggestedTags',
        suggestWidget: null,
    },

    /**
     * Remove all current tags in the container
     * @param container The container to clear the tags from
     */
    clearExistingTags: function(container){
        $(this.element).find(container).empty();
    },

    //Create a new tag, display it and add it to the content via Ajax.
    addTagToFilter: function(event, contentTag){
        var newTag = createTag(contentTag, " x", BaseRealityEvent.removeTagFromFilter);
        $(this.element).find(this.options.filterTags).append(newTag);

        ContentFilterWidget.addFilterTag(contentTag.text);
        var tags = ContentFilterWidget.getTags();
        $(this.options.relatedTagsWidget).tagRelated("getRelatedTagsForText", tags);
    },

    //Success result of getTagsForContent
    tagsLoadedForContent: function(data, textStatus, jqXHR){
        try{
            this.clearExistingTags(this.options.contentTags);

            for(index in data){
                var contentTag = data[index];
                var newTag = createTag(contentTag, " +", BaseRealityEvent.addTagToFilter);
                $(this.element).find(this.options.contentTags).append(newTag);
            }
        }
        catch(error){
            alert(error);
        }
    },


    keyPress: function(){
        $(this.element).find('.tagInput').autocomplete("close");
    },

    //Get the current Text, add it as a tag to the filter, refresh the content and related tags
    addFilterTagFromText: function(){
        var tagText = $(this.element).find('.tagInput').val();
        $(this.element).find('.tagInput').val("");
        ContentFilterWidget.addFilterTag(tagText);

        var tags = ContentFilterWidget.getTags();
        $(this.options.relatedTagsWidget).tagRelated("getRelatedTagsForText", tags);
    },

    //Remove a contentTag from the filer, refresh the content and related tags
    removeTagFromFilter: function(event, contentTag){
        ContentFilterWidget.removeFilterTag(contentTag.text);
        var tags = ContentFilterWidget.getTags();
        $(this.options.relatedTagsWidget).tagRelated("getRelatedTagsForText", tags);
    },

    //Hack to get content without tags.
    showContentWithoutTags: function(){
        ContentFilterWidget.addWithoutTag(null);
    },


    showAllContent: function(){
        ContentFilterWidget.clearAllTags();
    },

    //TODO - replace with showAll content?
    clearExistingContentTags: function(){
        ContentFilterWidget.clearAllTags();
    },


    //Content has changed -
    previewContent: function(event, contentObject){
        //TODO - replace with showAll content?
        this.clearExistingContentTags();
        this.options.content = contentObject;
        //$(this.element).show(200);
        //this.showCurrentTagsForContent();
        getTagsForContent(this, 'tagsLoadedForContent', this.options.content);
    },


    /**
     * Create the tags as approprirate from the current filter
     */
    setFilterTagsFromContentFilter: function(){
        this.clearExistingTags(this.options.filterTags);
        var tags = ContentFilterWidget.getTags();

        for(var index in tags){
            var tagText = tags[index];
            var contentTag = new Tag();
            contentTag.text = tagText;
            var tag = createTag(contentTag, " x", BaseRealityEvent.removeTagFromFilter);
            $(this.element).find(this.options.filterTags).append(tag);
        }
    },

    closePreview: function(){
    },

    _create: function() {
        //alert("create called");
    },

    //One of the autocomplete options is chosen.
    autocompleteSelect: function(event, suggestItems){
        var suggestItem = suggestItems.item;
        $(this.element).find('.tagInput').val(suggestItem.value);
        $(this.element).find('.tagInput').blur();
        $(this.element).find('.tagInput').val("");
        event.stopPropagation();
        return false;
    },

    _init: function() {
        $(this.element).addClass("baserealityEvent");

        $(this.element).find('.tagInput').on('keypress', $.proxy(this, 'keyPress'));
        $(this.element).find('.tagInput').on('keydown', changeEnterKeyToBlur);
        $(this.element).find('.tagInput').on('change', $.proxy(this, 'addFilterTagFromText'));

        //$(this.element).find('form').on('submit', $.proxy(this, 'inputSubmit'));
        $(this.element).find('.contentClear').on('click', $.proxy(this, 'clearContent'));
        $(this.element).find('.showContentWithoutTags').on('click', $.proxy(this, 'showContentWithoutTags'));
        $(this.element).find('.showAllContent').on('click', $.proxy(this, 'showAllContent'));

        bindOnce(this.element, BaseRealityEvent.previewContent, this, 'previewContent');
        bindOnce(this.element, BaseRealityEvent.closePreview, this, 'closePreview');
        bindOnce(this.element, BaseRealityEvent.addTagToFilter, this, 'addTagToFilter');

        bindOnce(this.element, BaseRealityEvent.removeTagFromFilter, this, 'removeTagFromFilter');
        bindOnce(this.element, BaseRealityEvent.contentFilterChanged, this, 'setFilterTagsFromContentFilter');

        this.setFilterTagsFromContentFilter();


        //Create the autocomplete widget
        $(this.element).find('.tagInput').autocomplete({
            minLength: 3,
            disabled: false,
            source: "/tags/suggest/",
            //autoFocus: true,
            change: $.proxy(this, 'addFilterTagFromText'),
            select: $.proxy(this, 'autocompleteSelect'),
        });

        var params = { event: BaseRealityEvent.addTagToFilter }

        this.options.relatedTagsWidget = $(this.element).find(this.options.suggestedTags).tagRelated(params);
    }, // grab the default value and use it
};

$.widget("basereality.tagFilter", TagFilter); // create the widget

function initTagFilter(selector){
    $(selector).tagFilter({	});
}
