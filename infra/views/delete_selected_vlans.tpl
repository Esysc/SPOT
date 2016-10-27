{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}

<br />
{section name=i loop=$switches}
	<ul>
		{section name=j loop=$vlans_deleted_ok[i]}
			<li>{$vlans_deleted_ok[i][j]} {$LBL_6_vlan_deleted_success}</li>
		{/section}
	<ul>
	
	<ul>
		{section name=j loop=$vlans_deleted_error[i]}
			<li>{$vlans_deleted_error[i][j]} {$LBL_6_vlan_deleted_error}</li>
		{/section}
	</ul>
{/section}

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
</p></div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}