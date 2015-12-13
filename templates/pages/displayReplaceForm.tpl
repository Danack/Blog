{extends file='component/framework'}

{inject name='replaceForm' type='BaseReality\Form\BlogReplaceForm'}
{inject name='formRender' type='FCForms\Render'}

{block name='mainContent'}
    {$formRender->render($replaceForm) | nofilter}
{/block}
