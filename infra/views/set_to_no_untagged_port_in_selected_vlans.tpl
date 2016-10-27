{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}

{section name=i loop=$vlans_where_port_has_been_set_to_no_untagged}
	<p>{$LBL_0_no_untag_success_port_id} <b>{$port_id}</b> {$LBL_0_no_untag_success_switch} {$mySwitch->getName()} {$LBL_0_no_untag_success} <b>{$vlans_where_port_has_been_set_to_no_untagged[i]}</b>.</p>
{/section}

{section name=i loop=$vlans_where_port_has_been_untagged}
	<p><u>{$LBL_0_no_untag_warning_message}</u></p>
	<p>{$LBL_0_untag_success_port_id} <b>{$port_id}</b> {$LBL_0_untag_success_switch} {$mySwitch->getName()} {$LBL_0_untag_success} <b>{$vlans_where_port_has_been_untagged[i]}</b>.</p>
{/section}
<div class="back-link"><p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
</p></div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}