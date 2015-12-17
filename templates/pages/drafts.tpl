
{extends file='component/framework'}


{block name='mainContent'}


{inject name='blogDraftMap' type='Blog\Service\BlogDraftList'}

<div class="row">
  <div class="col-md-12 panel panel-default">    
    <h3>Drafts</h3>    

{foreach $blogDraftMap->getMap() as $draftFilename => $draftTitle}
    <a href="{routeShowDraft($draftFilename)}">
        {$draftTitle}
    </a>
    <br/>
{/foreach}
  </div>      
</div>

{/block}

