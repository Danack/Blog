
var flickrSelector = null;

function flickrContentLoaded(photoList, textStatus, jqXHR){
	if(flickrSelector != null){

		var content = $("<div></div>");

		var table = $("<table style='margin-left: auto; margin-right: auto;	width: 90%;'></table>");
		var tableRow = $("<tr></tr>");

		$(table).append(tableRow);

		var count = 0;

		var photos = photoList.photos;

		for(var index in photos){
			if(photos.hasOwnProperty(index)){
				var serialized = photos[index];
				var flickrPhoto = json_decode_object_internal(serialized);
				var imgURL = flickrPhoto.getImageURL();
				var image = $("<img src='" + imgURL + "' />");
				var tableCell = $("<td></td>");
				var link = $("<a href='" + flickrPhoto.getPhotoURL() + "' target='_blank' />");

				$(link).append(image);
				$(tableCell).append(link);
				$(tableRow).append(tableCell);

				count++;

				if ((count%4) == 0){
					tableRow = $("<tr></tr>");
					$(table).append(tableRow);
				}
			}
		}

		$(content).append(table);

		$(flickrSelector).replaceWith(content);
	}
}


function loadFlickrView(selector){

	flickrSelector = selector;

	var params = {};
	params.numberImages = 8;

	$.ajax({
		url: '/flickrView',
		data: params,
		dataType: 'json',
		type: 'GET',
		success: flickrContentLoaded,
	});


}