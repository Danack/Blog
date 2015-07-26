{inject name='blogList' type='BaseReality\Service\BlogList'}

{inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}



<div class="row">
    <div class="col-md-12">

  

    <ul class="nav nav-list">
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