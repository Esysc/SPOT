<ul style="margin-top:50px;">
{section name=i loop=$messages}
    <li>{$messages[i]}</li>
{/section}
</ul>

<p><b>{$nbok}</b> switch(s) sauvegard&eacute;s.</p>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}
