
//Allows you to pipe log functions and see the Object in the console.
//$(this.element).log("Hello, I am the happy ContentPanel").bind(BaseRealityEvent.firstPage, $.proxy(this, 'firstPage'));
//jQuery.fn.log = function (msg) {
//	console.log("%s: %o", msg, this);
//	return this;
//};



function	triggerScopedEvent(scope, eventName, contentObject){

	try{
		Logger.log("Firing event " + eventName);

		var event = jQuery.Event(eventName);

		$(scope).trigger(event, contentObject);
	}
	catch(error ){
		alert("Error caught: " + error);
	}
}

function	eventWrapper(event){

	try{
		var logString = 'Event called: ' + event.type + ":" + event.namespace;

		if (jQuery.isFunction(this.log) == true) {
			this.log(logString);
		}
		else if (jQuery.isFunction(Logger.log) == true) {
			Logger.log(logString);
		}
		else{
			console.log(logString);
		}

		event.stopPropagation();
	}
	catch(error){
		debugger;
	}
}



var Logger = {

	eventsBound: false,

	debug:		'debug',
	warning:	'warning',
	error:		'error',
	fatal:		'fatal',

	levels: {
		debug:		'Debug',
		warning:	'Warning',
		error:		'Error',
		fatal:		'Fatal',
		none:		'None',
	},

	scope: 'all',

	knownScopes: {
		//all: 'debug',
		all: 'error',
	},

	logLevelChange: function(event, scopeName, newLogLevel){
		var visible = false;
		Logger.knownScopes[scopeName] = newLogLevel;

		for(var level in Logger.levels){

			if(level == newLogLevel){
				visible = true;
			}

			var className = "log" + scopeName  + level;

			if(visible == true){
				$("." + className).css("display", "block");
			}
			else{
				$("." + className).css("display", "none");
			}
		}
	},

	isLevelVisible: function(scopeName, logLevel){
		var scopeLevel = 'debug';

		if(Logger.knownScopes.hasOwnProperty(scopeName)){
			scopeLevel = Logger.knownScopes[scopeName];
		}

		//Find out which is lower - the requested level or the current
		//level for that scope.
		for(var level in Logger.levels){
			if(level == scopeLevel){
				return true;
			}
			if(level == logLevel){
				return false;
			}
		}
	},

	getScope: function(logLevel){
		return "log" + this.scope  + logLevel;
	},

	log: function(debugString, logLevel){

		if (logLevel === undefined){
			logLevel = Logger.debug;
		}

		//debugger;
		return;

		var logConsole = this.getLogConsole();

		var scopePrefix = '';

		if(this.scope == 'all'){
		}
		else{
			scopePrefix = this.scope + ": "
		}

		var styleString = "";

		if(this.isLevelVisible(this.scope, logLevel) == false){
			styleString = "style='display: none;'";
		}

		var className = this.getScope(logLevel);
		var debugHTML = "<div class='" + className  + "' " + styleString + " >" + scopePrefix;

		debugHTML += debugString;
		debugHTML += "</div>";

		logConsole.append(debugHTML);
	},

	note: function (noteID, string, logLevel){

		if (logLevel === undefined){
			logLevel = Logger.debug;
		}

		debugger;
		return;

		var logConsole = this.getLogConsole();
		var className = this.getScope(logLevel);
		var existingNote = $(logConsole).find("." + className).find("." + noteID);

		if(existingNote.length == 0){
			var newNote = $("<div class=" + className + "><span class='" + noteID + "'>" + string + "</span></div>")
			logConsole.append(newNote);
		}
		else{
			existingNote.text(string);
		}
	},

	getLogConsole: function(){
		var logConsole = $("#logConsole");

		if(logConsole.length == 0){
			this.createLogConsole();
			logConsole = $("#logConsole");
		}

		if(Logger.eventsBound == false){
			logConsole.addClass("baserealityEvent");
			logConsole.bind(BaseRealityEvent.logLevelChange, Logger.logLevelChange);
			Logger.eventsBound = true;
		}

		return logConsole;
	},

	createLogConsole: function(){
		var logConsole = $("<div id='logConsole'></div>");
		$("body").append(logConsole);
	},

	show: function(){
		var logConsole = this.getLogConsole();
		logConsole.css('display', 'block');
	},

	extend: function(currentObject, scope){

		var scopeParams = {
			scope: scope,
		};

		var knownScopesParams = {};

		knownScopesParams[scope] = Logger.debug;

		var newLogger = jQuery.extend(Logger, scopeParams);

		var newObject = jQuery.extend(currentObject, newLogger);

		Logger.knownScopes = jQuery.extend(Logger.knownScopes, knownScopesParams);

		return newObject;
	},
};




