{extends file='component/framework'}

{block name='page_title'}
    {inject name='activeBlogPost' type='Blog\Model\ActiveBlogPost'}
    <title>{$activeBlogPost->blogPost->getTitle()}</title>
{/block}

{block name='mainContent'}    
    {include file='panels/blogPost'}
{/block}

{block name='drafts'}
{/block}

{block name='gohome'}
    This is a blog page.
{/block}
