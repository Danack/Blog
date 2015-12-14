{plugin type='Blog\TemplatePlugin\BlogPlugin'}

{inject name='templates' type='Blog\Data\TemplateList'}

<form action="/templateViewer" method="get">
    {htmlOptions('template', $templates->getList())}
    <br/>
    <label for="displayAsPre">Display as &lt;pre&gt;</label>
    <input type="checkbox" name="displayAsPre" id="displayAsPre" value="true" checked="checked" /><br>
    <br/>
    <input type="submit" value='Display template'>
</form>