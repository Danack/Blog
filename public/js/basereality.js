
var CSS_VARIABLE_COLOR = "CSS_VARIABLE_COLOR";
var CSS_VARIABLE_SIZE = "CSS_VARIABLE_SIZE";


function initThings(){
    $('#resizable').resizable({
        //start,
        //resize
        //stop
        animate: true,
        ghost: true,
    });
}

var resizeTimer;

function setWindowResizeCallback(){
    $(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(doSomething, 100);
    });
}

function testSize(objectToResize){

    try{
        var parentElement = $(objectToResize).parent();

        var innerHeight = $(parentElement).innerHeight();
        var innerWidth = $(parentElement).innerWidth();

        var originalSource = jQuery.data(objectToResize, 'originalSource');

        if(!originalSource){
            originalSource = $(objectToResize).attr("src");
            //originalSource = objectToResize.src;
            //alert("Really? " + originalSource);
            jQuery.data(objectToResize, 'originalSource', originalSource);
        }

        var newSrc = originalSource + "&size=" + innerWidth;

        alert("newSRc = " + newSrc);

        $(objectToResize).attr('src', newSrc);
    }
    catch(error){
        alert("Exception testin size " + error);
    }
}

function genericAjaxError(data, textStatus, jqXHR){
    try{
        debugger;
        alert("Some sort of error " + data + " status " + textStatus + " when doing ajax to " + jqXHR.url);
    }
    catch(error){
        alert("oops: " + error);
    }
}



function	imageDropCrossFrame(event, params){
    uploadImage(params.src);
}

function changeEnterKeyToBlur(e){
    var key = false;

    if(window.event){
        key = window.event.keyCode;     //IE
    }
    else{
        key = e.which;     //firefox
    }

    if(key === 13){
        $(event.currentTarget).blur();
        return false;
    }

    return true;
}



function	genericError(){
    alert("A generic error occurred");
}

function crossDomainMessageHandler(event){
    if (event.data.message === 'basereality.imageDrop') {
        // event.origin contains the host of the sending window.
        //alert("Why, hello to you too, " + event.origin);
        uploadImage(event.data.imageSRC);
    }
//	else{
//		alert("message is " + event.data.message);
//	}
}


function setLocationFromOptionValue(object, withEvents){

    var newPage = $(object).val();

    if (withEvents) {
        baseTrigger(BaseRealityEvent.goToPage, newPage);
    }
    else{
        window.location = newPage;
    }
}


window.addEventListener("message", crossDomainMessageHandler, false );


function keyLogger(event){

    try{
        var codes = {
            'debug': BaseRealityEvent.toggleControlPanelVisibility,
            'login': BaseRealityEvent.loginRedirect,
            'logout': BaseRealityEvent.logoutRedirect,
        };

        if (keyLogger.positions === undefined ) {
            // It has not... perform the initilization
            keyLogger.positions = {};

            for(var code in codes){
                keyLogger.positions[code] = 0;
            }
        }

        var letter = String.fromCharCode(event.keyCode);

        for(var code in codes){
            var currentPosition = keyLogger.positions[code];

            if (letter == code.charAt(currentPosition)){
                keyLogger.positions[code]++;
                if(keyLogger.positions[code] >= code.length){
                    keyLogger.positions[code] = 0;
                    $(".baserealityEvent").trigger(codes[code]);
                }
            }
            else{
                keyLogger.positions[code] = 0;
            }
        }
    }
    catch(error){
        var err = getErrorObject();
        var caller_line = err.stack.split("\n")[4];
        var index = caller_line.indexOf("at ");
        var clean = caller_line.slice(index+2, caller_line.length);
    }
}

function	loginRedirect(){
    window.location = "/login"
}

function	logoutRedirect(){
    window.location = "/logout"
}

function bindKeys() {
    $('body').bind('keypress', keyLogger);
    //$('body').bind(BaseRealityEvent.loginRedirect, loginRedirect);
    bindOnce('body', BaseRealityEvent.loginRedirect, null, loginRedirect)
    $('body').bind(BaseRealityEvent.logoutRedirect, logoutRedirect);
    $('body').addClass("baserealityEvent");
}




function json_decode_object (json){
    var data = json_decode(json);

    
    return json_decode_object_internal(data);
}

function assignVars(object, vars){
    for(index in vars) {

        if (index != 'x-objectType') {
            object[index] = vars[index];
        }
    }
}

var utils = {};

/**
 * http://chalmershouse.co.uk/2011/08/13/javascript-is_array-isarray-typeof-array-check/
 * utils.isArray
 *
 * Best guess if object is an array.
 */
utils.isArray = function(obj) {
    // do an instanceof check first
    if (obj instanceof Array) {
        return true;
    }

    // then check for obvious falses
    if (typeof obj !== 'object') {
        return false;
    }
    if (utils.type(obj) === 'array') {
        return true;
    }
    return false;
};

/**
 * utils.type
 *
 * Attempt to ascertain actual object type.
 */
utils.type = function(obj) {
    if (obj === null || typeof obj === 'undefined') {
        return String (obj);
    }
    return Object.prototype.toString.call(obj)
        .replace(/\[object ([a-zA-Z]+)\]/, '$1').toLowerCase();
};


function json_decode_object_internal (jsonData){
    
    if (jsonData == null) {
        return null;
    }
    
    if (utils.isArray(jsonData) == true ||
        typeof jsonData === 'object' ) {
        var data = [];

        if (array_key_exists('x-objectType', jsonData) == true) {
            var objectType = jsonData['x-objectType'];
            var objectTypeInfo = parse_classname(objectType);
            data = new window[objectTypeInfo.classname];
        }

        for (var key in jsonData) {
            if (key === 'x-objectType') {
                continue;
            }

            data[key] = json_decode_object_internal(jsonData[key]);
        }

        return data;
    }

    return jsonData; //was a value
}


function	json_encode_object(objectToEncode, objectType){

    if(objectType === undefined){
        objectType = "UnknownObjectType";
    }

    var params = {};

    for(var name in objectToEncode){
        if(objectToEncode.hasOwnProperty(name)){

            var propertyValue = objectToEncode[name];

            if (propertyValue instanceof Function) {
                //skip it.
            }
            else{
                params[name] = propertyValue;
            }
        }
    }

    params['x-objectType'] = objectType;

    return JSON.stringify(params);
}

function json_decode(jsonString){

    try{
        return JSON.parse(jsonString);
        //return jQuery.parseJSON(jsonString);
    }
    catch(error) {
        alert("Error " + error.toString() + "  decoding json [" + jsonString + "]");
    }

    return {};
}

function isFunction(functionToCheck) {
    var getType = {};
    return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}


function DomainManagement(){
}

DomainManagement.getContentDomain = function(contentID){
    var value = parseInt(contentID);
    var domain = "http://cdn" + ((value % 5) + 1) + "." + location.hostname;

    if (location.port) {
        if (location.port != 80) {
            domain = domain + ":" + location.port;
        }
    }

    return domain;
};


function	initImageDrop(){
    try{
        $('.imageDropTarget').bind('basereality.imageDrop', imageDropCrossFrame);
    }
    catch(error){
        alert("Exception caught " + error);
    }
}

function bindOnce(selector, eventName, scope, callbackFunction) {
    if (scope) {
        $(selector).on(eventName, wrap(scope, callbackFunction));
    }
    else{
        //TODO - does this need wrapping so it's only fired once.
        $(selector).on(eventName, callbackFunction);
    }
    $(selector).addClass(eventName);
}

function baseTrigger(eventName, value){
    try{
    if(value === undefined){
        $("." + eventName).trigger(eventName);
    }
    else{
        $("." + eventName).trigger(eventName, value);
    }
    }
    catch(error){
        alert(error);
    }
}



function wrap(context, callback){
    return $.proxyWrapper(eventWrapper, context, callback);
}


function getTagsForContent(context, callback, content){
    //todo - remove this somewhere more sensible.
    try {
        $.ajax({
            url: '/tag/content/' + content.getContentID(),
            dataType: 'json',
            type: 'GET',
            success: $.proxy(context, callback),
            error: genericAjaxError,
        });
    }
    catch(error) {
        console.log("blah:" + error);
    }
}

function	createTag(contentTag, appendString, eventName){
    var newElement = $('<span></span>');
    var params = {
        appendString: appendString,
        eventName: eventName,
        contentTag: contentTag,
    };

    var contentTagJQ = $(newElement).contentTagJQ(params);

    return newElement;
}


function safeText(string){
    return htmlentities(string, 'ENT_QUOTES');
}


function parse_classname(name){
    var nameParts = name.split("\\");
    var classnameParts = nameParts.slice(-1);

    var info = {
        namespace: nameParts.slice(0, name.length - 1),
        classname: classnameParts[0],
    };

    return info;
}

function removeFrame(){

    var params = {};
    params.message = 'basereality.removeFrame';

    parent.postMessage(params, "*");
}


function DataMapper() {
}


function showHide(object, className){
    $(object).parent().find('.' + className).toggle();
}


function initTableSorter() {
    
    var table = $("#myTable");

    if (table) {
        table.tablesorter({
            headers: {
                0: {
                    sorter: 'metric'
                }/*,
                1: {
                    sorter: 'text'
                } */
            },
            widgets: ['zebra']
        });
    }
    else{
        alert("table not found");
    }
}