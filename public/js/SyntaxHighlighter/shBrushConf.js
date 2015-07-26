;(function()
{
	// CommonJS
	SyntaxHighlighter = SyntaxHighlighter || (typeof require !== 'undefined'? require('shCore').SyntaxHighlighter : null);

	function Brush()
	{


        this.regexList = [
            { regex: SyntaxHighlighter.regexLib.singleLinePerlComments,		css: 'comments' },			// variables
            { regex: /;.*$/gm,		css: 'comments' },			// variables
        ];
	};

	Brush.prototype	= new SyntaxHighlighter.Highlighter();
	Brush.aliases	= ['conf', 'ini'];

	SyntaxHighlighter.brushes.Conf = Brush;

	// CommonJS
	typeof(exports) != 'undefined' ? exports.Brush = Brush : null;
})();
