<!DOCTYPE html>
<html>

{plugin type='Blog\TemplatePlugin\BlogPlugin'}
{inject name='scriptInclude' type='ScriptHelper\ScriptInclude'}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

{block name='page_title'}
    <title>Bloggity blog</title>
{/block}

    {$scriptInclude->addCSSFile("jQuery/jquery-ui-1.10.0.custom")}
    
    {inject name='themeCSS' type='Blog\Site\ThemeCSS'}
    {$themeCSS->addCSS()}
    {$scriptInclude->addCSSFile("blogcss")}
    {$scriptInclude->renderCSSLinks() | nofilter}

    <link rel="alternate" type="application/atom+xml" href="/rss" />

    {include file='component/favicon'}
    {* <link rel="canonical" href="http://example.com/" /> *}
</head>

<body class="main">


{$scriptInclude->addJSFile('jquery-1.9.1')}
{$scriptInclude->addJSFile('jquery-ui-1.10.0.custom.min')}
{$scriptInclude->addJSFile('blog')}
{$scriptInclude->addJSFile('Form/Form')}

<header class="navbar navbar-static-top bs-docs-nav visible-xs visible-sm" id="top">
  <div class="container">
    <div class="nav navbar-nav menuBackground btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
         Past posts <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="/">Home</a></li>
      {inject name='blogList' type='Blog\Service\BlogList'}
      {foreach $blogList->getBlogs() as $blogPost}
        <li>
          {$blogPost->renderTitle() | nofilter}
        </li>
      {/foreach}
      </ul>
    </div>
  </div>
</header>


<div class="container">
    <div class="row page-header visible-md visible-lg">
        <div class="col-md-10">
                <a href="/" class="siteTitle">Blog@basereality</a>
        </div>
        <div>
            {$themeCSS->renderThemeButton() | nofilter}
        </div>
        
    </div>

{block name='title'}
{/block}
    <div class="row">
        <div class="col-md-2 navPanel visible-md visible-lg">
            {include file='panels/pastPosts'}
        </div>
        
        <div class="col-sm-10 col-lg-7 columnAdjust">
            {block name='mainContent'}
                Main content goes here.
            {/block}        
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {peakMemory() | nofilter}
        </div>
    </div>

</div>

{$scriptInclude->addBodyLoadJS("SyntaxHighlighter.all({  gutter: false  });")}
{$scriptInclude->renderJSLinks() | nofilter}
{$scriptInclude->renderOnBodyLoadJavascript() | nofilter}

<div>
    {inject name='debug' type='Blog\Debug'}
    {$debug->render() | nofilter}
</div>

{* Yui compressor is currently breaking this, and it's already minified.*}
<script src="/js/bootstrap.min.js"></script>
</body>
</html>