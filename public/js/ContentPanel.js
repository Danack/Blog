//What is this in Javascript
//http://howtonode.org/what-is-this

//Hacking jQuery
//http://bililite.com/blog/understanding-jquery-ui-widgets-a-tutorial/
var ContentPanel = {

    options: {
        url: null,
    },

    nextPage: function(event){
        ContentFilterWidget.nextPage();
    },

    previousPage: function(){
        ContentFilterWidget.previousPage();
    },

    goToPage: function(event, newPage){
        ContentFilterWidget.goToPage(newPage);
    },

    firstPage: function(){
        ContentFilterWidget.firstPage();
    },

    lastPage: function(){
        ContentFilterWidget.lastPage();
    },

    previewContent: function(){
        $(this.element).hide(200);
    },

    closePreview: function(){
        $(this.element).show(200);
    },

    contentRefresh: function(event, mode, searchTerms){
        //TODO - delete this, just call load current page
        this.refreshContent(mode, searchTerms);
    },

    refreshContentWithoutTags: function(event){
        if(event.currentTarget == event.target){
            this.refreshContent('withoutTag');
        }
    },

	contentLoaded: function	(responseData, textStatus, jqXHR){
		try{
			/* $(this.element).find(".content").each(function(index, Element){
					draggable("destroy");
				}
			); */
		}
		catch(error){
			alert("Exception in refreshContentResult: " + error);
		}

        $(this.element).html(responseData);
    },

    addHistory: function(url, contentFilterData, replace){
        if(replace === undefined){
            replace = false;
        }
        //TODO move this to contentFilterData once the URL is solely coming from contentFilterData
        var contentFilterDataState = json_encode_object(window.contentFilterData, "BaseReality\\Model\\ContentFilterData");

        var pageName = "" + this.options.url;// + contentFilterData.page;//Does nothing on current browsers

        if(replace == true){
            history.replaceState(contentFilterDataState, pageName, url);
        }
        else{
            history.pushState(contentFilterDataState, pageName, url);
        }

        Logger.log("Pushed state for url " + url);
    },

    contentFilterChanged: function(event, pushHistory){
        if(pushHistory === undefined){
            pushHistory = true;
        }

        this.loadCurrentPage(pushHistory);
    },


    loadCurrentPage: function(pushHistory){
        if (pushHistory === undefined){
            pushHistory = true;
        }

        var params = {};
        params.view = 'panel';

        var url = this.options.url + "/" + contentFilterData.getPath();

        //TODO - WRAP THIS BEGIN
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: $.proxy(this, 'contentLoaded'),
        });

        if (pushHistory == true){
            this.addHistory(url, contentFilterData);
        }

        //TODO - WRAP THIS END
    },

    refreshContent: function(mode, searchTerms){
        var params = {};
        //TODO - delete this, just call load current page

        params.mode = mode;

        if(searchTerms !== undefined &&
            searchTerms.length != 0){
            params.searchTerms = searchTerms;
        }

        var url = this.options.url;

        params.view = 'panel';

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: $.proxy(this, 'contentLoaded'),
        });
    },

    popState: function(event) {

        var popStateData = event.originalEvent.state;

        if(popStateData === undefined ||
            popStateData === null){
            Logger.log("No state to restore.");
        }
        else{
//			if(popStateData.ObjectType === undefined){
//				//no data to restore (because page was not pushed into history?).
//				Logger.log("no data to restore (because page was not pushed into history?).");
//			}
//			else{
                //this.options.page = popStateData.page;
            try{
                //var popStateObject = json_decode(popStateData);
                var newContentFilterData = json_decode_object(popStateData);
                if(newContentFilterData instanceof ContentFilterData){
                    window.contentFilterData = newContentFilterData;
                }
                else{
                    alert("Something went horribly wrong - contentFilterData isn't");
                }
            }
            catch(error){
                alert("Exception caught restoring state.");
            }

            //Logger.log("Restoring page " + this.options.page);
            this.loadCurrentPage(false);

        }
    },

    filterContentByTags: function(event, tags){
        //TODO - delete this, just call load current page
        if (event.target == event.currentTarget) {
            this.refreshContent('searchByTags', tags);
        }
    },

    _create: function() {
        return this;
    },

    bindEvents: function(){
        bindOnce(this.element, BaseRealityEvent.nextPage, this, 'nextPage');
        bindOnce(this.element, BaseRealityEvent.previousPage, this, 'previousPage');
        bindOnce(this.element, BaseRealityEvent.goToPage, this, 'goToPage');
        bindOnce(this.element, BaseRealityEvent.firstPage, this, 'firstPage');
        bindOnce(this.element, BaseRealityEvent.lastPage, this, 'lastPage');
    },


    _init: function() {
        $(this.element).addClass("baserealityEvent");
        this.log("Hello, I am the happy fun-time ContentPanel");
        this.bindEvents();

        bindOnce(this.element, BaseRealityEvent.previewContent, this, 'previewContent');
        bindOnce(this.element, BaseRealityEvent.closePreview, this, 'closePreview');

        //TODO - this shouldn't go through here.
        //The triggering function should clear the tags in contentFilterData, and then just call
        //page reload on this.
        bindOnce(this.element, BaseRealityEvent.filterContentByTags, this, 'filterContentByTags');
        bindOnce(this.element, BaseRealityEvent.contentFilterChanged, this, 'contentFilterChanged');

        $(window).on('popstate', $.proxy(this, 'popState'));
        this.addHistory(location.path, contentFilterData, true);

        return this;
    },

    // destroy: function(){
    // $.ui.green5.prototype.destroy.call(this); // call the original function
    // },
};

ContentPanel = Logger.extend(ContentPanel, 'Content');

$.widget("basereality.ContentPanel", ContentPanel); // create the widget


function initContentPanel(params){
    var contentHolder = $(params.holderSelector).ContentPanel(params);
}