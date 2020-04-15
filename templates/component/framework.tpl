<!DOCTYPE html>
<html>

{# plugin type='Blog\TemplatePlugin\BlogPlugin' #}
{# inject name='scriptInclude' type='ScriptHelper\ScriptInclude' #}

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

{% block page_title %}
    <title>Bloggity blog</title>
{% endblock %}

    <link rel='stylesheet' type='text/css'  media='screen' href='/css/bootstrap.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/bootswatch.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/blogcss.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/code_highlight_dark.css' />
    <link rel="alternate" type="application/atom+xml" href="/rss" />
    {% include 'component/favicon.tpl' %}

    {{ renderSocialData() }}

</head>

<body class="main">


<header class="navbar navbar-static-top bs-docs-nav visible-xs visible-sm" id="top">
  <div class="container">
    <div class="nav navbar-nav menuBackground btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
         Past posts <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="/">Home</a></li>
          {{ renderBlogList() }}
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
            {# $themeCSS->renderThemeButton() | nofilter #}
            <!-- DJA - theme button goes here. -->
        </div>
    </div>

{% block title %}
{% endblock %}

    <div class="row">
        <div class="col-md-2 navPanel visible-md visible-lg">
            {% include 'panels/pastPosts.tpl' %}
        </div>
        
        <div class="col-sm-10 col-lg-7 columnAdjust widthAdjust">
            {% block mainContent %}
                Main content goes here.
            {% endblock %}
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {{ memory_debug() }}
        </div>
    </div>
</div>

<script type='text/javascript' src='/js/blog?version=2018_06_29'></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>