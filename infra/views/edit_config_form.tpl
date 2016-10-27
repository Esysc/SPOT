{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{if $ENABLE_SWITH_CONFIGURATION_EDITION == 1}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}

<h2>{$LBL_9_switch_configuration_file_modification} {$mySwitch->getName()}</h2>

{if $message != ""}
	<p style="color: green">{$message}</p>
{else}
	<p style="color: red">{$LBL_9_switch_configuration_modification_warning}</p>
{/if}

<form action="edit_config.php" method="post" id="edit_config">
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
		
	</div>

	<textarea cols='100%' rows='50' id="conf" name="conf">{$conf}</textarea>
	<br /><br />
	<input type="submit" class="btn btn-sm btn-info" value="OK"/>
</form>

{/if}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}
