{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}
<ul>
{section name=i loop=$log_files_links}
    <li><a href="{$log_files_links[i]}">{$names[i]}</a></li>
{/section}
</ul>
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}