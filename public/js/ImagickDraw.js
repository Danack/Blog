
function emitText(string, placeHolders) {

    try{
        var outputText = string;

        for (search in placeHolders) { 
            var searchType = typeof(search);

            if("string" == searchType && 
                ("string" == typeof(placeHolders[search]) ||
                  "number" == typeof(placeHolders[search]))){

                var searchUpperCase = search.toUpperCase();
                var replace = placeHolders[search];

                outputText = outputText.replace( "%" + searchUpperCase + "%", replace);

            }
        };

        return outputText;

    }
    catch(error){
        alert(error);
    }

    return 'Unknown String - ' + string + '[' + arg1 + ']';
}





function Point(x, y) {
    this.x = x;
    this.y = y;
}

function Outline(width, height) {

    this.render = function (context) {
        context.strokeStyle = "red";
        context.beginPath();
        context.moveTo(this.points[0].x, this.points[0].y);
        context.lineTo(this.points[1].x, this.points[1].y);
        context.lineTo(this.points[2].x, this.points[2].y);
        context.lineTo(this.points[3].x, this.points[3].y);
        context.closePath();
        context.stroke();
    };

    this.setPoint = function (n, x, y) {
        this.points[n].x = x;
        this.points[n].y = y;
    };

    this.points = [];
    this.points[0] = new Point(0, 0);
    this.points[1] = new Point(width, 0);
    this.points[2] = new Point(width, height);
    this.points[3] = new Point(0, height);
}



var ImagickDraw = {

    stateInitial: "stateInitial",
    stateInactive: "stateInactive",
    stateDragging: "stateDragging",
    stateFinished: "stateFinished",
    
    justFinished: false,
    
    cornerNames: {
        0: "top-left",
        1: "top-right",
        2: "bottom-right",
        3: "bottom-left"
    },
    
    instructionsText: {
        "stateInitial": "Click the picture where the %CORNERNAME% corner should be.",
        "stateInactive": "Click the picture where the %CORNERNAME% corner should be.",
        "stateDragging": "Drag the cursor to the exact position",
        "stateFinished": "Either download the picture, or adjust the corners, starting with the top-left."
    },
    
    options: {
        activeCorner: -1,
        backgroundImage: null,
        context: null,
        canvas: null,
        debug: false,
        canvasObject: null,
        instructionBox: null,
        outline: null,
        state: null,
    },
    
    incrementCorner: function() {
        this.activeCorner = (this.activeCorner + 1) % 4;
        
        if(this.activeCorner == 0) {
            this.justFinished = true;
            this.refreshImage();
            this.options.state = this.stateFinished;
        }
    },

    mouseDown: function(event) {
        if( (event.which == 3) ) {
            return true;
        }
        else if( (event.which == 2) ) {
            return true;
        }
        
        this.justFinished = false;

        this.setState(this.stateDragging);
        this.updatePosition(event);
        this.redraw();

        event.preventDefault();
        return false;
    },

    setState: function(newState){

        this.options.state = newState;

        try {
            if(typeof this.instructionsText[this.options.state] == 'undefined') {
                // does not exist
            }
            else {
            
                var cornerNumber = this.activeCorner;
                
                if (cornerNumber < 0) {
                    cornerNumber = 0;
                }
            
                var params = {
                    cornerName: this.cornerNames[cornerNumber]
                };
            
                var text = this.instructionsText[this.options.state];

                if (text) {
                    text = emitText(this.instructionsText[this.options.state], params);
                    this.options.instructionBox.text(text);
                }
            }
        }
        catch(error) {
            alert("Error caught " + error);
        }
    },

    mouseUp: function() {
        this.incrementCorner();
        this.setInactiveState();
    },
    
    setInactiveState: function() {
        if (this.justFinished == true) {
            this.setState(this.stateFinished);
        }
        else {
            this.setState(this.stateInactive);
        }
    },

    mouseMove: function(event) {

        switch(this.options.state) {
            case(this.stateInactive) : {
                break;
            }

            case(this.stateDragging) : {
                this.updatePosition(event);
                break;
            }

            default: {
                break;
            }
        }

        this.redraw();

        event.preventDefault();
        return false;
    },
    
    mouseLeave: function() {
        this.setInactiveState();
    },
    
    calcImageURL: function() {
        var imageURL = "/imagickTestImage";
        imageURL += "?points=" + json_encode_object(this.outline.points, Outline);
        
        return imageURL;
    },
    
    refreshImage: function() {
        var imageURL = this.calcImageURL();
        this.backgroundImage.attr('src', imageURL);
    },

    downloadImage: function() {
        var imageURL = this.calcImageURL();
        imageURL += "&download=true";

        window.location.href = imageURL;
    },
    
    updatePosition: function(event) {
        var x = event.pageX - this.options.canvasObject.offset().left;
        var y = event.pageY - this.options.canvasObject.offset().top;

        this.outline.setPoint(this.activeCorner, x, y);
    },
    
    redraw: function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
            
        if (this.options.debug) {
            this.context.font = "bold 12px sans-serif";
            this.context.fillText("Mode: " + this.options.state, 10, 20);
        }

        if (this.options.state != this.stateFinished) {
            this.outline.render(this.context);
        }
    },

    bindFunctions: function(){
    
        var editWindow = $(this.element).find('.jsEditWindow');
        
        if (!editWindow) {
           throw "Could not find element '.jsEditWindow' to bind to edit window.";
        }

        editWindow.bind('mousedown', $.proxy(this, 'mouseDown'));
        editWindow.bind('mouseup', $.proxy(this, 'mouseUp'));
        editWindow.bind('mousemove', $.proxy(this, 'mouseMove'));
        editWindow.bind('mouseleave', $.proxy(this, 'mouseLeave'));

        $(this.element).find(".refreshButton").bind('click', $.proxy(this, 'refreshImage'));
        $(this.element).find(".downloadButton").bind('click', $.proxy(this, 'downloadImage'));

        this.options.instructionBox = $(this.element).find(".jsInstructionBox");
        this.backgroundImage = $(this.element).find(".backgroundImage");

    },

    createDrawingContext:  function() {
        var elem = $(this.element).find(".imagickCanvas");

        if (!elem) {
            alert("Failed to find canvas");
            return;
        }
        
        this.options.canvasObject = elem;
        
        elem = elem[0];
        
        if (elem.getContext) {
            this.context = elem.getContext('2d');
            this.canvas = elem;
        }
    },
    
    _create: function() {
        //alert("create called");
    },

    _init: function() {
        this.bindFunctions();
        this.createDrawingContext();
        //$(this.element).css("border", "1px solid #000000");
        this.outline = new Outline(this.canvas.width, this.canvas.height);
        this.activeCorner = 0;
        
        this.setState(this.stateInitial);
        this.redraw();
        
        return this;
    },


    destroy: function() {

//        // Unbind any events that may still exist
//        $(this.element).find(".imRemoveFunction").unbind('click');
//
//        // Remove any new elements that you created
//        $(this.element).remove();
//
//        // Remove any classes, including CSS framework classes, that you applied
//        this._trigger("destroy",{type:"destroy"},{options:this.options});
//
//        // After you're done, you still need to invoke the "base" destroy method
//        // Does nice things like unbind all namespaced events on the original element
//        $.Widget.prototype.destroy.call(this);
    }
};




$.widget("basereality.imagickDraw", ImagickDraw);


function initImagickDraw(selector){
    $(selector).imagickDraw({
        //readOnly: readOnly
    });
}