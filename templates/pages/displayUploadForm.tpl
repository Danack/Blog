{extends file='component/framework'}



{inject name='uploadForm' type='BaseReality\Form\BlogUploadForm'}

{block name='mainContent'}
    {$uploadForm->render() | nofilter}
{/block}



