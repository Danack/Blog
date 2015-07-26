

ContentFilterWidget = function(options, element){
    this.options = options;
    this.element = element;

    this._init();
};

ContentFilterWidget.prototype = {

    options: {
        index: null,
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

    _init: function() {
        return this;
    },
};


ContentFilterWidget.addFilterTag = function(tagText){
    window.contentFilterData.addFilterTag(tagText);
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}

ContentFilterWidget.addWithoutTag = function(tagText){
    window.contentFilterData.addWithoutTag(tagText);
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}


ContentFilterWidget.getTags = function(){
    return window.contentFilterData.tags;
}

ContentFilterWidget.nextPage = function(){
    window.contentFilterData.nextPage();
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}

ContentFilterWidget.previousPage = function(){
    window.contentFilterData.previousPage();
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}


ContentFilterWidget.goToPage = function(newPage){
    window.contentFilterData.goToPage(newPage);
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}


ContentFilterWidget.firstPage = function(){
    window.contentFilterData.firstPage();
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}

ContentFilterWidget.lastPage = function(){
    window.contentFilterData.lastPage();
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}


ContentFilterWidget.addFilterTag = function(tagText){
    window.contentFilterData.addFilterTag(tagText);
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}

ContentFilterWidget.removeFilterTag = function(tagText){
    window.contentFilterData.removeFilterTag(tagText);
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}

ContentFilterWidget.clearAllTags = function(){
    window.contentFilterData.clearAllTags();
    baseTrigger(BaseRealityEvent.contentFilterChanged);
}