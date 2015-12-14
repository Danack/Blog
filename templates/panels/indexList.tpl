{inject name='blogListFront' type='BaseReality\Service\BlogList'}
{plugin type='Blog\TemplatePlugin\BlogPostPlugin'}



{foreach $blogListFront->getBlogs() as $blogPost}
<div class="row">
    <div class="col-md-12">

    <h3>
        <span class='blogPostTitle'>
            {$blogPost->renderTitle() | nofilter}
        </span>

        <span class='blogPostDate'>
            {$blogPost->renderDate() | nofilter}
        </span>
    </h3>
    </div>

    <div class="col-md-12">
        {$blogPost->showPreview()}
    </div>
</div>
{/foreach}
