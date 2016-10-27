{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

<p>{$LBL_12_vlan_renamed_success_vlan_id} <b>{$vlan_id}</b> {$LBL_12_vlan_renamed_success_switch} {$mySwitch->getName()} {$LBL_12_vlan_renamed_success} <b>{$vlan_name}</b>.</p>

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
</p></div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}