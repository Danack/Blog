
var EndlessContent = {

	options: {
		offset:	75,
		delayLoading: false,
		pauseLoading: false,
		contentDelay: 500,
		page: 1,

		loadMoreContentSelector: null,
		pauseEndlessSelector: null,
		resumeEndlessSelector: null,

		//contentHolder: null,
	},

	scroll: function() {
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - this.options.offset) {
			//Add something at the end of the page
			this.loadMoreContent();
		}
	},

	pauseContentLoading: function (){
		this.options.pauseLoading = true;
		$(this.options.pauseEndlessSelector).hide();
		$(this.options.resumeEndlessSelector).show();
	},

	resumeContentLoading: function (){
		this.options.pauseLoading = false;
		$(this.options.pauseEndlessSelector).show();
		$(this.options.resumeEndlessSelector).hide();
	},

	resetContentLoader: function	(){
		this.options.delayLoading = false;
	},


//	contentLoaded: function	(responseData, textStatus, jqXHR){
//		$("#scrollContent").append(responseData);
//	},

	loadMoreContent: function	(){

		if(this.options.delayLoading == true || this.options.pauseLoading == true){
			return;
		}

		setInterval($.proxy(this, 'resetContentLoader'),	this.options.contentDelay);

		this.options.delayLoading = true;

		//$(this.element).trigger('basereality.loadNextPage');
		$(".baserealityEvent").trigger('basereality.loadNextPage');

//		alert("trigger basereality.loadNextPage");
	},

	bindFunctions: function(){

		try{
			$(window).bind('scroll', $.proxy(this, 'scroll'));

			if(this.options.loadMoreContentSelector != null){
				$(this.element).find(this.options.loadMoreContentSelector).bind('click', $.proxy(this, 'loadMoreContent'));
			}

			if(this.options.pauseEndlessSelector != null){
				$(this.element).find(this.options.pauseEndlessSelector).bind('click', $.proxy(this, 'pauseContentLoading'));
			}

			if(this.options.resumeEndlessSelector != null){
				$(this.element).find(this.options.resumeEndlessSelector).bind('click', $.proxy(this, 'resumeContentLoading'));
			}
		}
		catch(error){
			alert("error setting up endless content: " + error);
		}
	},


	_create: function() {
		//alert("create called");
	},

	_init: function() {
		//bind the functions to the pause/resume button
		this.bindFunctions();

//		if(this.options.contentHolder == null){
//			alert("No content holder defined, that is not acceptable.");
//		}

	}, // grab the default value and use it


};

$.widget("basereality.endlessContent", EndlessContent); // create the widget


function initEndlessContent(contentSelector, selector2){

	$(contentSelector).endlessContent({
		loadMoreContentSelector: ".loadMoreContentButton",
		pauseEndlessSelector: ".pauseEndlessButton",
		resumeEndlessSelector: ".resumeEndlessButton",
		//contentHolder: "#content"
	});
}


//		var params = {};
//
//		params.fragment = true;
//		params.page = this.options.page;
//
//		var url =  '/images.php';
//
//		$.ajax({
//			dataType : 'html',
//			data: params,
//			type: 'GET',
//			success: $.proxy(this, contentLoaded),
//			url: url,
//		});
//
//		this.options.page = this.options.page + 1;