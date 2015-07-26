{extends file='component/framework'}

{block name='mainContent'}

    {inject name='editForm' type='BaseReality\Form\BlogEditForm'}

    {$editForm->render() | nofilter}
{/block}


