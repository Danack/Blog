{extends file='component/framework'}

{block name='mainContent'}
    
    {inject name='sourceFileList' type='Blog\Model\SourceFileList'}
    
    {foreach $sourceFileList as $sourceFile}
        <a href="{$sourceFile->getRoute() | attr}">
        {$sourceFile->getFilename()}
        </a>
        <br/>
    {/foreach}
{/block}

