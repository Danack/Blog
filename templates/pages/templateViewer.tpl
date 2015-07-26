{extends file='component/framework'}

{block name='mainContent'}    
    {inject name='templates' type='Blog\Data\TemplateList'}
    <form action="/templateViewer" method="POST">
    
        {htmlOptions('template', $templates->getList())}

        <br/>
        
        <label for="displayAsPre">Display as &lt;pre&gt;</label>
        <input type="checkbox" name="displayAsPre" id="displayAsPre" value="true" /><br>

        <br/>

        <input type="submit" value='Display template'>
    </form>
    
    
    <pre>
        {inject name='templateText' type='Blog\Model\TemplateHTML'} 
        {$templateText->render()}
    </pre>
    
    
{/block}

