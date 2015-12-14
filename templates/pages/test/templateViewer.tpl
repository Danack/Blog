{extends file='component/framework'}

{block name='mainContent'}    
    {include file='pages/test/templateSelectForm'}
    <pre>{trim}
      {inject name='templateText' type='Blog\Model\TemplateHTML'}
      
        {$templateText->render()}
      {/trim}</pre>
{/block}

