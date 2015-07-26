function Shape(x, y, w, h, fill) {
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.fill = fill;
}


function drawingTest() {
    
    // get canvas element.
    var elem = document.getElementById('myCanvas');
    
    // check if context exist
    if (elem.getContext) {
    
        var myRect = [];
    
        myRect.push(new Shape(10, 0, 25, 25, "#333"));
        myRect.push(new Shape(0, 40, 39, 25, "#333"));
        myRect.push(new Shape(0, 80, 100, 25, "#333"));
    
        context = elem.getContext('2d');
        for (var i in myRect) {
            oRec = myRect[i];
            context.fillStyle = oRec.fill;
            context.fillRect(oRec.x, oRec.y, oRec.w, oRec.h);
        }
    }
}


function customShape() {

    var canvas = document.getElementById('myCanvas');
    var context = canvas.getContext('2d');

    // begin custom shape
    context.beginPath();
    context.moveTo(170, 80);
    context.bezierCurveTo(130, 100, 130, 150, 230, 150);
    context.bezierCurveTo(250, 180, 320, 180, 340, 150);
    context.bezierCurveTo(420, 150, 420, 120, 390, 100);
    context.bezierCurveTo(430, 40, 370, 30, 340, 50);
    context.bezierCurveTo(320, 5, 250, 20, 250, 50);
    context.bezierCurveTo(200, 5, 150, 20, 170, 80);

    // complete custom shape
    context.closePath();
    context.lineWidth = 5;
    context.strokeStyle = 'blue';
    context.stroke();
    
}