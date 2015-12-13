{extends file='component/framework'}

{block name='mainContent'}
    {inject name='editForm' type='BaseReality\Form\BlogEditForm'}
    {inject name='formRender' type='FCForms\Render'}
    {$formRender->render($editForm) | nofilter}
{/block}


