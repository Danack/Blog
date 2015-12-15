{inject name='scriptInclude' type='Intahwebz\Utils\ScriptInclude'}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

{block name='page_title'}
    <title>Bloggity blog</title>
{/block}

    <link rel="alternate" type="application/atom+xml" href="/rss" />
    
    {include file='component/favicon'}
    
</head>

<body class="main">




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
        
        </div>

        <div class="col-md-9 columnAdjust">
            This is a perf test.
        </div>

         <div class="col-md-1">
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {* peakMemory() | nofilter *}
        </div>
    </div>

</div>


</body>
</html>