
{extends file='component/framework'}


{block name='mainContent'}


{inject name='blogDraftMap' type='BaseReality\Service\BlogDraftList'}


<div style='height: 20px'></div>
<h3>Drafts</h3>

{foreach $blogDraftMap->getMap() as $draftFilename => $draftTitle}
    <a href="{routeDraft($draftFilename)}">
        {$draftTitle}
    </a>
    <br/>
{/foreach}

{/block}

