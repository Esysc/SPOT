{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

<form action="create_vlan.php" method="post" id="create_vlan_form">

	<script type="text/javascript" src="web/js/create_vlan_form.js"></script>
	
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
	</div>
	
	<fieldset class="form-group">
						<label for="vlan_name">{$LBL_0_name}</label>
						<input type="text" class="form-control" id="vlan_name" name="vlan_name" value="{$vlan_name}" />
	</fieldset>
	
	<fieldset class="form-group">
						<label for="vlan_id">Id</label>
						<input type="text" class="form-control" id="vlan_id" name="vlan_id" value="{$vlan_id}" />
	</fieldset>
	
	<fieldset class="form-group">
		<input type="submit" class="btn btn-sm btn-info" value="OK"/>
	</fieldset>
	
	<fieldset class="form-group">
		<div>
			<h4>{$LBL_5_deploy_vlan} :</h4>
		</div>
		<ul>
			{section name=i loop=$mySwitchs}
				{if $mySwitchs[i]->getId() != $mySwitch->getId()}
					<li><input type="checkbox" name="dest_switches[]" id="dest_switches" value="{$mySwitchs[i]->getId()}"/> {$mySwitchs[i]->getName()}</li>
				{/if}
			{/section}
			
			<li>---</li>
			
			<li>
				<div class="checkbox">
					<input type="checkbox" id="check_all"/>{$LBL_0_select_all}
				</div>
			</li>
		</ul>
	</fieldset>

</form>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}