/*jslint evil: false, vars: true, eqeq: true, white: true */

//What is this in Javascript
//http://howtonode.org/what-is-this

//Hacking jQuery
//http://bililite.com/blog/understanding-jquery-ui-widgets-a-tutorial/
var Form = {

	options: {
		content: null,
		url: null,
		sendTimeout: null,
	},

	sendFormDataSuccess: function() {
		//This could do something with the data.
	},


	getFormData: function() {
		var inputElements = $(this.element).find(':input');
		var formData = {};

		inputElements.each(
			function(indexInArray, element) {
				var value = $(element).val();
				var name = $(element).attr('name');

				if ($(element).is(':checkbox') == true) {
					if($(element).prop('checked') == false) {
						value = false;
					}
				}

				if (name !== undefined) {
					formData[name] = value;
				}
			}
		);

		formData['background'] = true;

		return formData;
	},

	sendFormData: function() {
		clearTimeout(this.options.sendTimeout);
		this.options.sendTimeout = null;

		var formData = this.getFormData();

		if (this.options.url != null) {
			$.ajax({
				url: this.options.url,
				data: formData,
				type: 'POST',
				success: $.proxy(this, 'sendFormDataSuccess'),
			});
		}
	},

	fieldChange: function(event) {
		clearTimeout(this.options.sendTimeout);
		this.options.sendTimeout = setTimeout($.proxy(this, 'sendFormData'), 500);
	},


	// Add appropriate events to all fields inside the form
	bindEvents: function() {
		var inputElements = $(this.element).find(':input');
		var changeFunction = $.proxy(this, 'fieldChange');

		inputElements.each(
			function(indexInArray, valueOfElement) {
				$(valueOfElement).on("change", changeFunction);
			}
		)
	},


	_init: function() {
		$(this.element).addClass("baserealityEvent");
		this.bindEvents();
	}, // grab the default value and use it
};

$.widget("basereality.form", Form); // create the widget

//function initTagFilter(selector){
//	$(selector).tagFilter({	});
//}

