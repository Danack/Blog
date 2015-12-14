
{inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}
{plugin type='Blog\TemplatePlugin\BlogPostPlugin'}
{plugin type='Blog\TemplatePlugin\BlogPlugin'}


{$templateBlogPost = makeRenderableBlogPost($activeBlogPost->blogPost) | nofilter}

<div class="row">
    <div class="col-md-12  panel panel-default">

<div class="row">
    <div class="col-md-12">
        <h2>{$templateBlogPost->blogPost->getTitle()}
            <small>
                {$templateBlogPost->renderDate(true)}
            </small>
        </h2>
    </div>
</div>        
<div class="row">
    <div class="col-md-12">
        {inject name='postEditBox' type='Blog\Site\EditBlogPostBox'}
        {$postEditBox->render() | nofilter}
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        {blogPostBody($templateBlogPost->blogPost) | nofilter}
    </div>
</div>
        
        
        
        
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        {showTweetButton() | nofilter}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <br/>
        <a href="{routeIndex()}">Back to index</a>
    </div>
</div>

