{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}

<p>{$LBL_4_vlan_id} <b>{$vlan_id} ({$vlan_name})</b>  {$LBL_4_vlan_creation_message} :</p>
<ul> 
	{if (!$error_on_first_switch)}
		<li>{$mySwitch->getName()}</li>
	{/if}
	{section name=i loop=$switches_ok}
		<li>{$switches_ok[i]}</li>
	{/section}
</ul>


{if $errors != null}
	<p>{$LBL_4_vlan_creation_error_message}</p>
	<ul> 
		{if $error_on_first_switch}
			<li>{$mySwitch->getName()}</li>
		{/if}
		{section name=i loop=$switches_errors}
			<li>{$switches_errors[i]}</li>
		{/section}
	</ul>
{/if}

<p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back} </a>
</p>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}