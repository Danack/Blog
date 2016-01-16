{extends file='component/framework'}

{inject name='uploadForm' type='Blog\Form\SourceFileUploadForm'}
{inject name='formRender' type='FCForms\Render'}

{block name='mainContent'}
    {$formRender->render($uploadForm) | nofilter}
{/block}
