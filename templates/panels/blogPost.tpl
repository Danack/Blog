
{inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}
{plugin type='Blog\TemplatePlugin\BlogPostPlugin'}
{plugin type='Blog\TemplatePlugin\BlogPlugin'}

{$templateBlogPost = makeRenderableBlogPost($activeBlogPost->blogPost)}

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default blogPostContent" >
            <h3>{$templateBlogPost->blogPost->getTitle()}
            <small>
                {$templateBlogPost->renderDate(true)}
            </small>
            </h3>
            <div>
                {inject name='postEditBox' type='Blog\Site\EditBlogPostBox'}
                {$postEditBox->render() | nofilter}
            </div>
        
            <div class="blogPostBody">
                {blogPostBody($templateBlogPost->blogPost) | nofilter}
            </div>
        </div>
        <div>
            <a href='https://twitter.com/share'
               class='twitter-share-button'
               data-via='MrDanack' data-dnt='true'>
                Tweet
            </a>

            <script>
                addTwitterDelayed();
            </script>
        </div>
        
        <div>
            <a href="{routeIndex()}">Back to index</a>
        </div>
    </div>
</div>




