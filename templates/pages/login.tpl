{extends file='component/framework'}


{block name='mainContent'}
    
    {inject name='formRender' type='FCForms\Render'}
    {inject name='loginForm' type='Blog\Form\LoginForm'}

    {$formRender->render($loginForm) | nofilter}
{/block}