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
