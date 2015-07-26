{extends file='component/framework'}


{block name='mainContent'}
    {inject name='loginForm' type='BaseReality\Form\LoginForm'}
    {$loginForm->render() | nofilter}
{/block}