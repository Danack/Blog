
{inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}
{plugin type='Blog\TemplatePlugin\BlogPostPlugin'}
{plugin type='Blog\TemplatePlugin\BlogPlugin'}


{$templateBlogPost = makeRenderableBlogPost($activeBlogPost->blogPost)}


<div class="row">
    <div class="col-md-12">
        <h2>{$templateBlogPost->blogPost->getTitle()}
            <small>
                {$templateBlogPost->renderDate(true)}
            </small>
        </h2>
    </div>
</div>

{inject name='loginStatus' type='Blog\Service\LoginStatus'}
{if $loginStatus->isLoggedIn()}
<div class="row">
    <div class="col-md-12">
    
        You are logged in:<br/>
        <a href="{routeBlogEdit($templateBlogPost->blogPost->blogPostID)}">
             Edit blog post
        </a><br/>
        <a href="{routeBlogReplace($templateBlogPost->blogPost->blogPostID)}">
             Replace text
        </a><br/>
        <hr/>
    </div>
</div>
{/if}


<div class="row">
    <div class="col-md-12">
        {blogPostBody($templateBlogPost->blogPost) | nofilter}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        {showTweetButton() | nofilter}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <a href="{routeIndex()}">Back to index</a>
    </div>
</div>

