<html>

{plugin type='Blog\TemplatePlugin\BlogPlugin'}
{inject name='scriptInclude' type='ScriptHelper\ScriptInclude'}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

{block name='page_title'}
    <title>Bloggity blog</title>
{/block}

    {$scriptInclude->addCSSFile("jQuery/jquery-ui-1.10.0.custom")}
    {$scriptInclude->addCSSFile("bootstrap")}
    {* $scriptInclude->addCSS("bootstrap-theme") *}
    {$scriptInclude->addCSSFile("bootswatch")}
    {$scriptInclude->addCSSFile("blogcss")}
    {* $scriptInclude->addCSS("blogPrint", 'print') *}
    {$scriptInclude->addCSSFile("SyntaxHighlighter/shCoreRDark")}
    {$scriptInclude->addCSSFile("SyntaxHighlighter/shThemeRDark")}
    {$scriptInclude->addCSSFile("code_highlight_danack")}

    {$scriptInclude->renderCSSLinks() | nofilter}

    <link rel="alternate" type="application/atom+xml" href="/rss" />
    
    {include file='component/favicon'}
    
</head>

<body class="main">


{$scriptInclude->addJSFile('jquery-1.9.1')}
{$scriptInclude->addJSFile('jquery-ui-1.10.0.custom.min')}
{$scriptInclude->addJSFile('SyntaxHighlighter/XRegExp')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shCore')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushJScript')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushPhp')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushJava')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushBash')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushPlain')}
{$scriptInclude->addJSFile('SyntaxHighlighter/shBrushConf')}
{$scriptInclude->addJSFile('blog')}
{$scriptInclude->addJSFile('Form/Form')}

<div class="container">
    <div class="row page-header">
        <div class="col-md-10">
                <a href="/" class="siteTitle">Blog@basereality</a>
        </div>
    </div>
{block name='title'}
{/block}
    <div class="row">
        <div class="col-md-2 navPanel">
            {include file='panels/pastPosts'}
        </div>

        <div class="col-md-9 columnAdjust">
            {block name='mainContent'}
                Main content goes here.
            {/block}        
        </div>

         <div class="col-md-1">
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {peakMemory() | nofilter}
        </div>
    </div>

</div>

{$scriptInclude->addBodyLoadJS("SyntaxHighlighter.all();")}
{$scriptInclude->renderJSLinks() | nofilter}
{$scriptInclude->renderOnBodyLoadJavascript() | nofilter}

<div>
    {inject name='debug' type='Blog\Debug'}
    {$debug->render() | nofilter}
</div>

</body>
</html>