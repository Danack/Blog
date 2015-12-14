{extends file='component/framework'}


{block name='mainContent'}
    {inject name='templates' type='Blog\Data\TemplateList'}

    {include file='pages/test/templateSelectForm'}
{/block}

