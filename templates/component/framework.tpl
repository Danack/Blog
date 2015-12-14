<html>

{plugin type='Blog\TemplatePlugin\BlogPlugin'}
{inject name='scriptInclude' type='Intahwebz\Utils\ScriptInclude'}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

{block name='page_title'}
    <title>Bloggity blog</title>
{/block}

    {$scriptInclude->addCSS("jQuery/jquery-ui-1.10.0.custom")}
    {$scriptInclude->addCSS("bootstrap")}
    {* $scriptInclude->addCSS("bootstrap-theme") *}
    {$scriptInclude->addCSS("bootswatch")}
    {$scriptInclude->addCSS("blogcss")}
    {$scriptInclude->addCSS("blogPrint", 'print')}
    {$scriptInclude->addCSS("SyntaxHighlighter/shCoreRDark")}
    {$scriptInclude->addCSS("SyntaxHighlighter/shThemeRDark")}
    {$scriptInclude->includeCSS() | nofilter}

    <link rel="alternate" type="application/atom+xml" href="/rss" />
</head>

<body class="main">


{$scriptInclude->addJSRequired('jquery-1.9.1')}
{$scriptInclude->addJSRequired('jquery-ui-1.10.0.custom.min')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/XRegExp')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shCore')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushJScript')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushPhp')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushJava')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushBash')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushPlain')}
{$scriptInclude->addJSRequired('SyntaxHighlighter/shBrushConf')}
{$scriptInclude->addJSRequired('blog')}
{$scriptInclude->addJSRequired('Form/Form')}

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

{$scriptInclude->addBodyLoadFunction("SyntaxHighlighter.all();")}
{$scriptInclude->emitJSRequired() | nofilter}
{$scriptInclude->emitOnBodyLoadJavascript() | nofilter}

<div>
    {inject name='debug' type='Blog\Debug'}
    {$debug->render() | nofilter}
</div>

</body>


</html>