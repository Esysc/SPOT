{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

<script type="text/javascript" src="web/js/edit_vlan_form.js"></script>

<p>Vlan id <b>{$vlan_id}</b> -- {$mySwitch->getName()} ({$LBL_11_actual_name} : {$vlan_name})</p>

<p><u>{$LBL_11_rename_vlan}</u> : </p>

<form action="edit_vlan_name.php" method="post" id="edit_vlan_name">
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
		<input type="hidden" id="vlan_id" name="vlan_id" value="{$vlan_id}"/>
	</div>

	<input type="text" value="{$vlan_name}" id="vlan_name" name="vlan_name"/>

	<input type="submit" class="btn btn-sm btn-info" value="OK"/>
</form>

{if $ALLOW_PORT_TAGGING}
	<div class="tag_port">
	
		<p><u><b>{$LBL_0_tag_in_vlan} {$vlan_id}</b></u> : </p>
		
		
		<form action="tag_selected_ports_in_vlan.php" method="post" id="tag_selected_ports_in_vlan">
			<div>
				<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
				<input type="hidden" id="vlan_id" name="vlan_id" value="{$vlan_id}"/>

				<ul>
					{section name=i loop=$taggable_ports}
						<li><input type="checkbox" name="dest_ports_tagged[]" id="dest_ports_tagged" value="{$taggable_ports[i]->getId()}"/>{$taggable_ports[i]->getName()}</li>
					{/section}
					
					<li>---</li>
					<li><input type="checkbox" id="check_all_tagged"/>{$LBL_0_select_all}</li>
				</ul>
				
				<br />
				
				<input type="submit" class="btn btn-sm btn-info" value="{$LBL_0_tag_in_vlan} {$vlan_id}"/>
			</div>
		</form>
		
	</div>
	
	<div class="no_untag_port">

			<p><u><b>{$LBL_0_no_untag_in_vlan} {$vlan_id}</b></u> : </p>
			
			<form action="set_to_no_untagged_selected_ports_in_vlan.php" method="post" id="set_to_no_untagged_selected_ports_in_vlan">
				<div>
					<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
					<input type="hidden" id="vlan_id" name="vlan_id" value="{$vlan_id}"/>
				
					<ul>
						{section name=i loop=$no_untaggable_ports}
								<li><input type="checkbox" id="dest_ports_no_untagged" name="dest_ports_no_untagged[]" value="{$no_untaggable_ports[i]->getId()}"/>{$no_untaggable_ports[i]->getName()}</li>
						{/section}
						
						<li>---</li>
						<li><input type="checkbox" id="check_all_no_untagged"/>{$LBL_0_select_all}</li>
					</ul>
					
					<br />
					
				<input type="submit" class="btn btn-sm btn-info" value="{$LBL_0_no_untag_in_vlan} {$vlan_id}"/>
				</div>
			</form>
		</div>
{/if}

<div class="untag_port">

	<p><u><b>{$LBL_0_untag_ports_in_vlan} {$vlan_id}</b></u> : </p>
	
	<form action="untag_selected_ports_in_vlan.php" method="post" id="untag_selected_ports_in_vlan">
		<div>
			<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
			<input type="hidden" id="vlan_id" name="vlan_id" value="{$vlan_id}"/>
		
			<ul>
				{section name=i loop=$untaggable_ports}
						<li><input type="checkbox" id="dest_ports_untagged" name="dest_ports_untagged[]" value="{$untaggable_ports[i]->getId()}"/>{$untaggable_ports[i]->getName()}</li>
				{/section}
				
				<li>---</li>
				<li><input type="checkbox" id="check_all_untagged"/>{$LBL_0_select_all}</li>
			</ul>
			
			<br />
			
		<input type="submit" value="{$LBL_0_untag_selected_ports_in_vlan} {$vlan_id}"/>
		</div>
	</form>
</div>

{if $ALLOW_VLAN_DELETION}
<hr />
	<form action="delete_vlan.php" method="post" id="edit_vlan_name">
		<div>
			<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
			<input type="hidden" id="vlan_id" name="vlan_id" value="{$vlan_id}"/>
			<input type="hidden" id="vlan_name" name="vlan_name" value="{$mySwitch->getName()}"/>
		</div>
	
		<input type="submit" class="btn btn-sm btn-info" value="{$LBL_11_delete_vlan}"/>
	</form>
{/if}

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
</p></div>


{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}