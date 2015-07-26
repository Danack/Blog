
var baserealityBookmarkletJ;

var basereality_bookmarkletID = 'baseBookmarkLet';

function basereality_addCSS(url){
    var headID = document.getElementsByTagName("head")[0];
    var cssNode = document.createElement('link');
    cssNode.type = 'text/css';
    cssNode.rel = 'stylesheet';
    cssNode.href = url;
    cssNode.media = 'screen';
    headID.appendChild(cssNode);
}


function basereality_getSelectedText(){
    var t = "";
    try {
        //Copied and pasted - I suggest not touching.
        t = ((window.getSelection && window.getSelection())||(document.getSelection && document.getSelection())||(document.selection && document.selection.createRange && document.selection.createRange().text));
    }
    catch(e){ // access denied on https sites
    }

    return t;
}

/**
 * Gets the domain that the bookmarklet script was loaded from. This is used
 * to load other items like CSS.
 * @return {String}
 */
function	basereality_getCurrentDomain(){
    var jsscript = document.getElementsByTagName("script");

    //Abandon hope all ye that enter here.
    //http://www.w3schools.com/jsref/jsref_obj_regexp.asp
    var pattern = new RegExp("bookmarklet.js", "i");
    var domain = "basereality.com";

    for (var i = 0; i < jsscript.length; i++) {
        var jsSrcURL = jsscript[i].getAttribute("src");

        if (pattern.test(jsSrcURL)) {
            var arr = jsSrcURL.split("/");
            domain = arr[2];
        }
    }

    return domain;
}


function basereality_imageDrop(event, ui){
    try{
        var params = {};
        params.message = 'basereality.imageDrop';
        params.imageSRC = ui.draggable.context.src;
        document.getElementById('bookmarklet_iframe').contentWindow.postMessage(params, "*");
    }
    catch(error){
        alert("error triggering event:" + error);
    }
}



function basereality_pureMagic(){

    try{
        baserealityBookmarkletJ = jQuery.noConflict();

        var j = baserealityBookmarkletJ;


        j('img').draggable({
            //helper
            revert: true,
            scroll: false,
            iframeFix: true,
            zIndex: 1005,
            helper: "clone",
            //		start:	dragStart,
            //		stop:	dragStop,
        });


        j('.dragTarget').droppable({
            drop: basereality_imageDrop,
            hoverClass: "dropHover",
        });

        baserealityBookmarkletJ('#' + basereality_bookmarkletID).bind('basereality.removeFrame', basereality_removeFrame);
    }
    catch(error){
        alert("Error initting javascript " + error);
    }
}


function basereality_loadjQueryUI(domain){

    var filerefUI = document.createElement('script');
    filerefUI.setAttribute("type", "text/javascript");
    filerefUI.setAttribute("src", "http://" + domain + "/js/jquery-ui-1.10.0.custom.min.js");
    filerefUI.onload = basereality_pureMagic;
    document.getElementsByTagName('body')[0].appendChild(filerefUI);
}

function basereality_addjQueryToDocument(domain){
    var fileref = document.createElement('script');
    fileref.setAttribute("type", "text/javascript");
    fileref.setAttribute("src", "http://" + domain + "/js/jquery-1.9.1.js");
    
    fileref.onload = function (){ basereality_loadjQueryUI(domain) };
    
    document.getElementsByTagName('body')[0].appendChild(fileref);
}

function	basereality_addiFrame(domain){
    var iframe_url = "http://" + domain + "/bookmarklet";

    var div = document.createElement("div");
    div.id = basereality_bookmarkletID;

    var text = basereality_getSelectedText();
    

    var str = "";

    iframe_url += "?description=" + encodeURIComponent(document.title);
    iframe_url += "&URL=" + encodeURIComponent(document.URL);
    iframe_url += "&text=" + encodeURIComponent(text);
    
    str += "<div>";
    str += "<iframe frameborder='0' class='toolPanelPopup dragTarget' style='z-index: 1000'  name='bookmarklet_iframe' id='bookmarklet_iframe' src='" + iframe_url + "' width='900px' height='400px' style='textalign:right; backgroundColor: white;' />";

    str += "</div>";

    div.innerHTML = str;

    document.body.insertBefore(div, document.body.firstChild);
}


function basereality_removeFrame(){
    $("#" + basereality_bookmarkletID).remove();
}

function basereality_setupFunction(){
    var domain = basereality_getCurrentDomain();
    basereality_addjQueryToDocument(domain);

    var existing_iframe = document.getElementById('bookmarklet_iframe');
    if (existing_iframe) {
        return;
    }

    basereality_addCSS("http://" + domain + "/css/bookmarklet.mini.css?rand=" + Math.random());
    basereality_addiFrame(domain);
    basereality_registerWindowHandler();
    //basereality_pureMagic();
}


function basereality_crossDomainMessageHandler(event){
    if (event.data.message === 'basereality.removeFrame') {
        basereality_removeFrame();
    }
}

function basereality_registerWindowHandler() {
    if (typeof window.addEventListener !== 'undefined') {
        window.addEventListener('message', basereality_crossDomainMessageHandler, false);
    } else {
        // Support for ie8
        window.attachEvent('onmessage', basereality_crossDomainMessageHandler);
    }
}


basereality_setupFunction();


