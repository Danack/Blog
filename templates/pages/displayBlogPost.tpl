{extends file='component/framework'}

{inject name='blogPost' type='Blog\Content\BlogPost'}

{block name='title'}
    <title>{$blogPost->getTitle()}</title>
{/block}


{block name='mainContent'}
    {include file='panels/blogPost'}
{/block}


{block name='drafts'}
   
{/block}