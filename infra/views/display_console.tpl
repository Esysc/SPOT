
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{if $USE_MINDTERM_CONSOLE == 1}
	{$mindterm_applet}
{else}
	{$jta_applet}
{/if}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}
