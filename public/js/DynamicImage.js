var DynamicImage = {

	updateImage: function(forceUpdate){

		var params = [
			'horizontalFOV',
			'verticalFOV',
			'sunRadiusArcSeconds',
			'sunX',
			'sunY',
			'innerDiameter',
			'outerDiameter'
		];

		var imageParams = "";
		var d = new Date();
		imageParams += "ticks=" + d.getTime();

		for(param in params){
			var paramName = params[param];

			var paramValue = $('#coronaParameters').find('.' + paramName).val();

			if(paramValue !== undefined){
				paramValue = $.trim(paramValue);
				if(paramValue.length > 0){
					imageParams += "&" + paramName + "=" + encodeURIComponent(paramValue) ;
				}
			}
		}

		var	showDegreesChecked = $('#coronaParameters').find('.showDegrees').is(':checked');

		if(showDegreesChecked !== undefined && showDegreesChecked == true){
			imageParams += "&" + 'showDegrees' + "=" + encodeURIComponent('true') ;
		}

		var imageUpdate = false;
		var	autoUpdateChecked = $('#coronaParameters').find('.autoUpdate').is(':checked');

		if(forceUpdate !== undefined && forceUpdate == true){
			imageUpdate = true;
		}

		if(autoUpdateChecked !== undefined && autoUpdateChecked == true){
			imageUpdate = true;
		}

		if(imageUpdate == true){
			$("#coronaImage").attr('src', '/imageOverlay?link=true' + imageParams);
		}
		$("#coronaPermLink").attr('href', '/articles/SunCorona/?' + imageParams);
	},


	modifyValue: function(parameter, scale){
		var currentValue = $('#coronaParameters').find('.' + parameter).val();

		var modifierValue = $('#coronaParameters').find('.modifier').val();

		if(modifierValue === undefined){
			modifierValue = 0.1;
		}
		else{
			try{
				modifierValue = parseFloat(modifierValue);
			}
			catch(error){
				modifierValue = 0.1;
			}
		}

		if(currentValue !== undefined){
			try{
				currentValue = parseFloat(currentValue);
				currentValue = (modifierValue * scale) + currentValue;
				currentValue = currentValue.toFixed(2);
			}
			catch(error){
				currentValue = 1;
			}

			$('#coronaParameters').find('.' + parameter).val(currentValue).change();
		}
	}
};


DynamicImage.updateImage();