{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

<ul>
	{foreach $conf_files_links as $file_props}
			 {if $file_props.group}<li><a href="{$file_props.link}"><img src="web/images/group.png" alt="group"/>&nbsp;{$file_props.name}</a></li>{/if}
	{/foreach}

	{foreach $conf_files_links as $file_props}
			  {if !$file_props.group}<li><a href="{$file_props.link}">{$file_props.name}</a></li>{/if}
	{/foreach}
</ul>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}