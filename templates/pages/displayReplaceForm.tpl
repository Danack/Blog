{extends file='component/framework'}


{inject name='replaceForm' type='BaseReality\Form\BlogReplaceForm'}

{block name='mainContent'}
    {$replaceForm->render() | nofilter}
{/block}



