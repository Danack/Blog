/*jslint evil: false, vars: true, eqeq: true, white: true, plusplus: true, sub: true */

/**
 * A class to parse color values
 * @author Stoyan Stefanov <sstoo@gmail.com>
 * @link   http://www.phpied.com/rgb-color-parser-in-javascript/
 * @license Use it if you like it
 */


function intval( mixed_var, base ) {
	// Get the integer value of a variable using the optional base for the conversion    
	//   
	// version: 812.3015  
	// discuss at: http://phpjs.org/functions/intval  
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)  
	// +   improved by: stensi  
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)  
	// *     example 1: intval('Kevin van Zonneveld');  
	// *     returns 1: 0  
	// *     example 2: intval(4.2);  
	// *     returns 2: 4  
	// *     example 3: intval(42, 8);  
	// *     returns 3: 42  
	// *     example 4: intval('09');  
	// *     returns 4: 9  

	//DJA original version of this function was buggered
	//tmp.toString(10) returns 10 times the value.

	var type = typeof( mixed_var );

	if(type === 'boolean'){
		if (mixed_var === true) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else if(type ==='string'){

		//tmp = parseInt(mixed_var * 1);  original but jslint objects
		var tmp = parseInt(mixed_var, 10);
		if(isNaN(tmp) || !isFinite(tmp)){
			return 0;
		}
		else{
			if(base){
				return tmp.toString(base);
			}
			else{
				return tmp;//tmp.toString(10);  
			}
		}
	}
	else if(type === 'number' && isFinite(mixed_var) ){

		var floorNumber = Math.floor(mixed_var);

		if(base){
			return floorNumber.toString(base);
		}

		return floorNumber;
	}
	else{
		return 0;
	}
}



function hue2rgb(p, q, t){
	if(t < 0){ t += 1; }
	if(t > 1){ t -= 1; }
	if(t < 1/6){ return p + (q - p) * 6 * t; }
	if(t < 1/2){ return q;}
	if(t < 2/3){ return p + (q - p) * (2/3 - t) * 6;}
	return p;
}

function RGBColor(c1, c2, c3, mode){
	var color_string = false;
	var self = this;
	this.ok = false;

	//http://en.wikipedia.org/wiki/HSL_and_HSV
	//http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL
	//h, s, l all in the range 0 - 1 inclusive
	this.HSLToHex = function(h, s, l){
		var r, g, b;

		if(s == 0){
			r = g = b = l; // achromatic
		}
		else{
			var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
			var p = 2 * l - q;
			r = hue2rgb(p, q, h + 1/3);
			g = hue2rgb(p, q, h);
			b = hue2rgb(p, q, h - 1/3);
		}

		r = intval(r * 255, 16);
		g = intval(g * 255, 16);
		b = intval(b * 255, 16);

		// var r = r.toString(16);
		// var g = g.toString(16);
		// var b = b.toString(16);

		if (r.length == 1){ r = '0' + r;}
		if (g.length == 1){ g = '0' + g;}
		if (b.length == 1){ b = '0' + b;}
		var newColorString = '#' + r + g + b;

		return	newColorString;
	};

	if (mode !== undefined){
		if(mode == 'hsl'){
			color_string = self.HSLToHex(c1, c2, c3);
		}
		else if(mode == 'rgb'){

			c1 = intval(c1);
			c2 = intval(c2);
			c3 = intval(c3);

			var r = c1.toString(16);
			var g = c2.toString(16);
			var b = c3.toString(16);

			if (r.length == 1){ r = '0' + r;}
			if (g.length == 1){ g = '0' + g;}
			if (b.length == 1){ b = '0' + b;}
			color_string = '#' + r + g + b;
		}
		else{
			alert("Unknown color mode [" + mode + "]");
		}
	}
	else{
		color_string = c1;
	}

	try{

		// strip any leading #
		if(typeof color_string.charAt === 'function'){
			if (color_string.charAt(0) == '#') { // remove # if any
				color_string = color_string.substr(1,6);
			}
		}
		else{
			debugger;
		}

		color_string = color_string.replace(/ /g,'');
		color_string = color_string.toLowerCase();
	}
	catch(error){
		debugger;
		alert("error caught ooo: " + error);
	}

    // before getting into regexps, try simple matches
    // and overwrite the input
    var simple_colors = {
        aliceblue: 'f0f8ff',
        antiquewhite: 'faebd7',
        aqua: '00ffff',
        aquamarine: '7fffd4',
        azure: 'f0ffff',
        beige: 'f5f5dc',
        bisque: 'ffe4c4',
        black: '000000',
        blanchedalmond: 'ffebcd',
        blue: '0000ff',
        blueviolet: '8a2be2',
        brown: 'a52a2a',
        burlywood: 'deb887',
        cadetblue: '5f9ea0',
        chartreuse: '7fff00',
        chocolate: 'd2691e',
        coral: 'ff7f50',
        cornflowerblue: '6495ed',
        cornsilk: 'fff8dc',
        crimson: 'dc143c',
        cyan: '00ffff',
        darkblue: '00008b',
        darkcyan: '008b8b',
        darkgoldenrod: 'b8860b',
        darkgray: 'a9a9a9',
        darkgreen: '006400',
        darkkhaki: 'bdb76b',
        darkmagenta: '8b008b',
        darkolivegreen: '556b2f',
        darkorange: 'ff8c00',
        darkorchid: '9932cc',
        darkred: '8b0000',
        darksalmon: 'e9967a',
        darkseagreen: '8fbc8f',
        darkslateblue: '483d8b',
        darkslategray: '2f4f4f',
        darkturquoise: '00ced1',
        darkviolet: '9400d3',
        deeppink: 'ff1493',
        deepskyblue: '00bfff',
        dimgray: '696969',
        dodgerblue: '1e90ff',
        feldspar: 'd19275',
        firebrick: 'b22222',
        floralwhite: 'fffaf0',
        forestgreen: '228b22',
        fuchsia: 'ff00ff',
        gainsboro: 'dcdcdc',
        ghostwhite: 'f8f8ff',
        gold: 'ffd700',
        goldenrod: 'daa520',
        gray: '808080',
        green: '008000',
        greenyellow: 'adff2f',
        honeydew: 'f0fff0',
        hotpink: 'ff69b4',
        indianred : 'cd5c5c',
        indigo : '4b0082',
        ivory: 'fffff0',
        khaki: 'f0e68c',
        lavender: 'e6e6fa',
        lavenderblush: 'fff0f5',
        lawngreen: '7cfc00',
        lemonchiffon: 'fffacd',
        lightblue: 'add8e6',
        lightcoral: 'f08080',
        lightcyan: 'e0ffff',
        lightgoldenrodyellow: 'fafad2',
        lightgrey: 'd3d3d3',
        lightgreen: '90ee90',
        lightpink: 'ffb6c1',
        lightsalmon: 'ffa07a',
        lightseagreen: '20b2aa',
        lightskyblue: '87cefa',
        lightslateblue: '8470ff',
        lightslategray: '778899',
        lightsteelblue: 'b0c4de',
        lightyellow: 'ffffe0',
        lime: '00ff00',
        limegreen: '32cd32',
        linen: 'faf0e6',
        magenta: 'ff00ff',
        maroon: '800000',
        mediumaquamarine: '66cdaa',
        mediumblue: '0000cd',
        mediumorchid: 'ba55d3',
        mediumpurple: '9370d8',
        mediumseagreen: '3cb371',
        mediumslateblue: '7b68ee',
        mediumspringgreen: '00fa9a',
        mediumturquoise: '48d1cc',
        mediumvioletred: 'c71585',
        midnightblue: '191970',
        mintcream: 'f5fffa',
        mistyrose: 'ffe4e1',
        moccasin: 'ffe4b5',
        navajowhite: 'ffdead',
        navy: '000080',
        oldlace: 'fdf5e6',
        olive: '808000',
        olivedrab: '6b8e23',
        orange: 'ffa500',
        orangered: 'ff4500',
        orchid: 'da70d6',
        palegoldenrod: 'eee8aa',
        palegreen: '98fb98',
        paleturquoise: 'afeeee',
        palevioletred: 'd87093',
        papayawhip: 'ffefd5',
        peachpuff: 'ffdab9',
        peru: 'cd853f',
        pink: 'ffc0cb',
        plum: 'dda0dd',
        powderblue: 'b0e0e6',
        purple: '800080',
        red: 'ff0000',
        rosybrown: 'bc8f8f',
        royalblue: '4169e1',
        saddlebrown: '8b4513',
        salmon: 'fa8072',
        sandybrown: 'f4a460',
        seagreen: '2e8b57',
        seashell: 'fff5ee',
        sienna: 'a0522d',
        silver: 'c0c0c0',
        skyblue: '87ceeb',
        slateblue: '6a5acd',
        slategray: '708090',
        snow: 'fffafa',
        springgreen: '00ff7f',
        steelblue: '4682b4',
        tan: 'd2b48c',
        teal: '008080',
        thistle: 'd8bfd8',
        tomato: 'ff6347',
        turquoise: '40e0d0',
        violet: 'ee82ee',
        violetred: 'd02090',
        wheat: 'f5deb3',
        white: 'ffffff',
        whitesmoke: 'f5f5f5',
        yellow: 'ffff00',
        yellowgreen: '9acd32'
    };

	var key;
	var i;

    for (key in simple_colors) {
		if (simple_colors.hasOwnProperty(key)) {
			if (color_string == key) {
				color_string = simple_colors[key];
			}
		}
    }
    // emd of simple type-in colors

    // array of color definition objects
    var color_defs = [
        {
            re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
            example: ['rgb(123, 234, 45)', 'rgb(255,234,245)'],
            process: function (bits){
                return [
                    parseInt(bits[1], 10),
                    parseInt(bits[2], 10),
                    parseInt(bits[3], 10)
                ];
            }
        },
        {
            re: /^(\w{2})(\w{2})(\w{2})$/,
            example: ['#00ff00', '336699'],
            process: function (bits){
                return [
                    parseInt(bits[1], 16),
                    parseInt(bits[2], 16),
                    parseInt(bits[3], 16)
                ];
            }
        },
        {
            re: /^(\w{1})(\w{1})(\w{1})$/,
            example: ['#fb0', 'f0f'],
            process: function (bits){
                return [
                    parseInt(bits[1] + bits[1], 16),
                    parseInt(bits[2] + bits[2], 16),
                    parseInt(bits[3] + bits[3], 16)
                ];
            }
        }
    ];

    // search through the definitions to find a match
    for (i = 0; i < color_defs.length; i++) {
        var re = color_defs[i].re;
        var processor = color_defs[i].process;
        var bits = re.exec(color_string);
        if (bits) {
            var channels = processor(bits);
            this.r = channels[0];
            this.g = channels[1];
            this.b = channels[2];
            this.ok = true;
        }
    }

    // validate/cleanup values
    this.r = (this.r < 0 || isNaN(this.r)) ? 0 : ((this.r > 255) ? 255 : this.r);
    this.g = (this.g < 0 || isNaN(this.g)) ? 0 : ((this.g > 255) ? 255 : this.g);
    this.b = (this.b < 0 || isNaN(this.b)) ? 0 : ((this.b > 255) ? 255 : this.b);

    // some getters
    this.toRGB = function () {
        return 'rgb(' + this.r + ', ' + this.g + ', ' + this.b + ')';
    };

	this.toHexNoHash = function(){
		var r = this.r.toString(16);
		var g = this.g.toString(16);
		var b = this.b.toString(16);
		if (r.length == 1){ r = '0' + r;}
		if (g.length == 1){ g = '0' + g;}
		if (b.length == 1){ b = '0' + b;}
		return '' + r + g + b;
	};

    this.toHex = function () {
        return '#' + this.toHexNoHash();
    };

	this.toHSL =

	function (){
		try{
			// $var_r, $var_g and $var_b are the three decimal fractions to be input to our RGB-to-HSL conversion routine
			var var_r = (this.r) / 255;
			var var_g = (this.g) / 255;
			var var_b = (this.b) / 255;
			// Input is var_r, var_g and var_b from above
			// Output is HSL equivalent as $h, $s and $l — these are again expressed as fractions of 1, like the input values

			var var_min = Math.min(var_r, var_g, var_b);
			var var_max = Math.max(var_r, var_g, var_b);
			var del_max = var_max - var_min;

			//alert("var_min " + var_min + " var_max " + var_max + "del_max " + del_max );
			//return;

			var l = (var_max + var_min) / 2;
			var h = 0;
			var s = 0;

			if (del_max == 0)
			{
					h = 0;
					s = 0;
			}
			else
			{
					if (l < 0.5)
					{
							s = del_max / (var_max + var_min);
					}
					else
					{
							s = del_max / (2 - var_max - var_min);
					}

					var del_r = (((var_max - var_r) / 6) + (del_max / 2)) / del_max;
					var del_g = (((var_max - var_g) / 6) + (del_max / 2)) / del_max;
					var del_b = (((var_max - var_b) / 6) + (del_max / 2)) / del_max;

					if (var_r == var_max)
					{
							h = del_b - del_g;
					}
					else if (var_g == var_max)
					{
							h = (1 / 3) + del_r - del_b;
					}
					else if (var_b == var_max)
					{
							h = (2 / 3) + del_g - del_r;
					}

					if (h < 0)
					{
							h += 1;
					}

					if (h > 1)
					{
						h -= 1;
					}
			}

			var color = [];
			color['luminance']	= l;
			color['hue']		= h;
			color['saturation'] = s;

			return color;
		}
		catch(error){
			alert("error converting color to HSL " + error);
		}
	};
}



function calculateNewColor(currentRGBColor, colorSpace, colorType, newValue, operation){

	var colors = {};
	var minLimit = 0;
	var maxLimit = 1;
	var c1, c2, c3;

	switch(colorSpace){

		case('hsl'):
			var hsl = currentRGBColor.toHSL();

			c1 = 'hue';
			c2 = 'saturation';
			c3 = 'luminance';

			colors['hue'] = hsl['hue'];
			colors['saturation'] = hsl['saturation'];
			colors['luminance'] = hsl['luminance'];

			minLimit = 0;
			maxLimit = 1;
		break;


		case('rgb'):
			if(colorType == 'rgbhex'){
				return new RGBColor(newValue);
			}

			c1 = 'red';
			c2 = 'green';
			c3 = 'blue';

			colors['red'] = currentRGBColor.r;
			colors['green'] = currentRGBColor.g;
			colors['blue'] = currentRGBColor.b;

			minLimit = 0;
			maxLimit = 255;
		break;
	}

	switch(operation){
		case('addition'):
			colors[colorType] = colors[colorType] + newValue;
		break;

		case('assign'):
			colors[colorType] = newValue;
		break;
	}

	if(colors[colorType] < minLimit){
		colors[colorType] = minLimit;
	}

	if(colors[colorType] > maxLimit){
		colors[colorType] = maxLimit;
	}

	return new RGBColor(colors[c1], colors[c2], colors[c3], colorSpace);
}
