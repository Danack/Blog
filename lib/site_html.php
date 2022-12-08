<?php

use Blog\MarkdownRenderer\MarkdownRenderer;
use Blog\Route;
use Blog\Content\BlogPost;
use Blog\Service\BlogList;

function renderFavIcon(): string
{
    $html = <<< HTML

    <link rel="shortcut icon" type="image/x-icon" href="/favicon/favicon.ico"/>
<link rel="shortcut icon" type="image/png" href="http://eg.com/favicon.png"/>
<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<link rel="manifest" href="/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

HTML;

    return $html;
}

function renderSocialData(BlogPost $activeBlogPost)
{
    $builder = new Utlime\SeoMetaTags\BuilderDelegate(
        new Utlime\SeoMetaTags\CommonBuilder(),
        new Utlime\SeoMetaTags\TwitterBuilder(),
        new Utlime\SeoMetaTags\OpenGraphBuilder()
    );

//        $preview = renderBlogPostPreview(
//            $blogPostTwig,
//            $activeBlogPost->blogPost
//        );
    $preview = "Preview of blog post goes here.";

    $url = Route::blogPost($activeBlogPost);

    $header_chunk = $builder
        ->add('title', $activeBlogPost->getTitle())
        ->add('description', $preview)
        ->add('language', 'en')
        ->add('canonical', $url)
        ->add('image', 'http://blog.basereality.com/images/Portrait.jpg')
        ->add('twitter:card', 'summary')
        ->build();

    return str_replace("/>", "/> \n", $header_chunk);
}



function renderPageStart(BlogPost|null $blogPost)
{
    $social_data_string = "";
    if ($blogPost !== null) {
        $social_data_string = renderSocialData($blogPost);
    }
    $fav_icon_string = renderFavIcon();
    $blog_title_string = "Bloggity blog";

    $html = <<< HTML
    <!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{$blog_title_string}</title>


    <link rel='stylesheet' type='text/css'  media='screen' href='/css/bootstrap.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/bootswatch.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/blogcss.css' />
    <link rel='stylesheet' type='text/css'  media='screen' href='/css/code_highlight_dark.css' />
    <link rel="alternate" type="application/atom+xml" href="/rss" />
    {$fav_icon_string}

    {$social_data_string}
</head>
    
HTML;

    return $html;
}


function renderPageEnd(
    Blog\Service\BlogList $blogList,
//    MarkdownRenderer $markdownRenderer,
    string $main_content
) {
    $pageTitle = "Base Reality";
    $memory_debug_string = memory_debug();

    $blogPostList = renderBlogPostListWithLink($blogList, null);



    $html = <<< HTML
<body class="main">

<header class="navbar navbar-static-top bs-docs-nav visible-xs visible-sm" id="top">
  <div class="container">
    <div class="nav navbar-nav menuBackground btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        Past posts <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="/">Home</a></li>
        {$blogPostList}
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
            <!--  \$themeCSS->renderThemeButton() | nofilter #} -->
                <!-- DJA - theme button goes here. -->
        </div>
    </div>

        <div class="col-md-2 navPanel visible-md visible-lg">
            <div class="row panel panel-default pastLinks">
  <div class="col-md-12">
    <ul class="nav nav-list smallPadding">
        {$blogPostList}
        <li><a href='/rss'>RSS feed</a></li>
        <li><a href='http://docs.basereality.com'>RFCs + slides</a></li>
    </ul>
  </div>
</div>
        </div>
        
        <div class="col-sm-10 col-lg-7 columnAdjust widthAdjust">
          {$main_content}
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {$memory_debug_string}
        </div>
    </div>
</div>

<script type='text/javascript' src='/js/blog?version=2018_06_29'></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>

HTML;

    return $html;
}






function renderBlogPostListWithLink(
    Blog\Service\BlogList $blogList,
    Blog\Content\BlogPost|null $activeBlogPost
) {

    $output = [];

    $active_blog_post_id = null;
    if ($activeBlogPost !== null) {
        $active_blog_post_id = $activeBlogPost->blogPostID;
    }

    foreach ($blogList->getBlogs() as $blogPost) {
        $class = '';
        if ($blogPost->blogPostID === $active_blog_post_id) {
            $class = 'active';
        }

        $html = <<< HTML
<li class='%s'>
  <a href='%s'>
    %s
  </a>
</li>
HTML;
        $output[] = sprintf(
            $html,
            $class,
            Route::blogPost($blogPost),
            $blogPost->getTitle()
        );
    }

    return implode("\n", $output);
}



/**
 * @param \Blog\Service\BlogList $blogList
 * @param \Blog\BlogPostRenderer $blogPostRenderer
  */
function renderBlogPostListFrontPage(
    Blog\Service\BlogList $blogList

) {
    $result = [];

    $html = <<< HTML
      <div class="row">
          <div class="col-md-12">
          <h3>
              <span class='blogPostTitle'>
                <a href="%s">
                  %s
                </a>
              </span>     
              
              <span class='blogPostDate'>
                  %s
              </span>
          </h3>
          </div>
      
          <div class="col-md-12">
              %s
          </div>
      </div>
HTML;

    foreach ($blogList->getBlogs() as $blogPost) {
        $preview = renderBlogPostPreview($blogPost);

        $result[] = sprintf(
            $html,
            Route::blogPost($blogPost),
            $blogPost->getTitle(),
            $blogPost->getDatestamp(),
            $preview
        );
    }

    return implode("\n", $result);
}


function showFrontPage(
    Blog\Service\BlogList $blogList,
    MarkdownRenderer $markdownRenderer
): string {

    $main_content = renderBlogPostListFrontPage(
        $blogList
    );

    $output  = renderPageStart(null);
    // $output .= renderBlogPostListFrontPage();
    $output .= renderPageEnd($blogList, $main_content);

    return $output;
}

function showBlogPostPage(
    BlogList $blogList,
    MarkdownRenderer $markdownRenderer,
    \Blog\Content\BlogPost $activeBlogPost
) {

    $output  = renderPageStart(null);
    $main_content = renderActiveBlogPostBody($activeBlogPost, $markdownRenderer);
    $output .= renderPageEnd($blogList, $main_content);

    return $output;
}


function renderActiveBlogPostBody(
    \Blog\Content\BlogPost $activeBlogPost,
    MarkdownRenderer $markdownRenderer
): string {

    $html = <<< HTML
    <div class="col-md-12">
        <div class="panel panel-default blogPostContent" >
            <h2>%s
            <small>
                %s
            </small>
            </h2>
        
            <div class="blogPostBody">
                %s
            </div>
        </div>
        <!--<div>-->
           <!--<a href='https://twitter.com/share'-->
               <!--class='twitter-share-button'-->
               <!--data-via='MrDanack' data-dnt='true'>-->
                <!--Tweet-->
            <!--</a>-->

            <!--<script>-->
                <!--addTwitterDelayed();-->
            <!--</script>-->
        <!--</div>-->
        <!---->
        <div>
            <a href="%s">Back to index</a>
        </div>
    </div>
HTML;



    $bodyHtml = $markdownRenderer->render($activeBlogPost->getText());





    $output = sprintf(
        $html,
        $activeBlogPost->getTitle(),
        $activeBlogPost->getDatestamp(),
        $bodyHtml,
        Route::index()
    );

    return $output;
}


/*

{{syntaxHighlighterFile('example_nginx.conf', 'js')}}

{syntaxHighlighterFile lang='js' file='example_php-fpm.conf'}



{{syntaxHighlighterFile('example_nginx.conf', 'js')}}


### Base PHP-FPM config file that is used by all sites.

{syntaxHighlighterFile lang='js' file='example_php-fpm.conf'}


### Site Nginx config that routes requests to either static files, or the front controller.

{syntaxHighlighterFile lang='js' file='example_site.nginx.conf'}


### PHP-FPM config for a site, which creates pools and workers.

{syntaxHighlighterFile lang='js' file='example_site.php-fpm.conf'}




### FastCGI config, to avoid repetition in the above fle.

{syntaxHighlighter lang='js'}

fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;

    #QUERY_STRING is not set in here - set it in Nginx to prevent extra redirect
    #fastcgi_param  QUERY_STRING       $query_string;

    fastcgi_param  REQUEST_METHOD     $request_method;
    fastcgi_param  CONTENT_TYPE       $content_type;
    fastcgi_param  CONTENT_LENGTH     $content_length;

    fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
    fastcgi_param  REQUEST_URI        $request_uri;
    fastcgi_param  DOCUMENT_URI       $document_uri;
    fastcgi_param  DOCUMENT_ROOT      $document_root;
    fastcgi_param  SERVER_PROTOCOL    $server_protocol;
    fastcgi_param  HTTPS              $https if_not_empty;

    fastcgi_param  GATEWAY_INTERFACE  CGI/1.1;
    fastcgi_param  SERVER_SOFTWARE    nginx/$nginx_version;

    fastcgi_param  REMOTE_ADDR        $remote_addr;
    fastcgi_param  REMOTE_PORT        $remote_port;
    fastcgi_param  SERVER_ADDR        $server_addr;
    fastcgi_param  SERVER_PORT        $server_port;
    fastcgi_param  SERVER_NAME        $server_name;

    # PHP only, required if PHP was built with --enable-force-cgi-redirect
    fastcgi_param  REDIRECT_STATUS    200;

{/syntaxHighlighter}



*/