
{inject name='adminLinks' type='Blog\Site\AdminLinks'}
{$adminLinks->render() | nofilter}

{block name='homelink'}
{/block}


<div class="row panel panel-default pastLinks">
    <div class="col-md-12">
    {inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}
    <ul class="nav nav-list smallPadding">
        {inject name='blogList' type='Blog\Service\BlogList'}
        {foreach $blogList->getBlogs() as $blogPost}
            {if $blogPost->blogPost->blogPostID == $activeBlogPost->blogPost->blogPostID}
                <li class="active">
            {else}
                <li>
            {/if}

            {$blogPost->renderTitle() | nofilter}
            </li>
        {/foreach}

        <li><a href='/rss'>RSS feed</a></li>
    </ul>

    </div>
</div>