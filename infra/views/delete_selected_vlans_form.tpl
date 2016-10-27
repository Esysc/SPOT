{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}


{if $ALLOW_VLAN_DELETION}
	<form action="delete_selected_vlans.php" method="post" id="delete_selected_vlans_form">
	
		<script type="text/javascript" src="web/js/delete_selected_vlans_form.js"></script>
	
		<div>
				<h3>{$LBL_7_selected_vlans} :</h3>
		</div>
	
		<div>
			
			<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>

			<ul>
			{section name=i loop=$selected_vlans}
				<li><input type="checkbox" checked="checked" name="selected_vlans[]" id="selected_vlans" value="{$selected_vlans[i]}" />VLAN {$selected_vlans[i]}</li>
			{/section}
			</ul>
			
		</div>
		
		<div>
				<h3>{$LBL_7_delete_vlan_on_other_switches} : </h3>
		</div>
		
		<ul>
				{section name=i loop=$mySwitchs}
				 	{if $mySwitchs[i]->getId() != $mySwitch->getId()}
						<li><input type="checkbox" name="dest_switches[]" id="dest_switches" value="{$mySwitchs[i]->getId()}"/> {$mySwitchs[i]->getName()}</li>
					{/if}
				{/section}
				
				<li>---</li>
				<li><input type="checkbox" id="check_all"/>{$LBL_0_select_all}</li>
		</ul>
		<br />
		<input type="submit" class="btn btn-sm btn-info" value="OK"/>
		
	</form>
{/if}
<div class="back-link">
	<p>
		<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
	</p>
</div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}