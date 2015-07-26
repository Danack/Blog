

{* inject name='navItems' type='Tier\Model\NavItems' *}


<nav class="bs-docs-sidebar hidden-print">

    <ul class="nav">
        {* foreach $navItems as $navItem *}
        <li>
            Nav item goes here.
            {* <a href="{$navItem->url}">{$navItem->description}</a> *}
        </li>
        {* /foreach *}
    </ul>
</nav>