<form action="comparative_view.php" method="post" id="comparative_view">
	<fieldset class="form-group">
		<select class="form-control chosen" id="switch_id_1" name="switch_id_1">
			{section name=i loop=$mySwitchs}
				<option label="{$mySwitchs[i]->getName()}" value="{$mySwitchs[i]->getId()}">{$mySwitchs[i]->getName()}</option>
			{/section}
		</select>
	</fieldset>
	<fieldset class="form-group">
		<select class="form-control chosen" id="switch_id_2" name="switch_id_2">
			{section name=i loop=$mySwitchs}
				<option label="{$mySwitchs[i]->getName()}" value="{$mySwitchs[i]->getId()}">{$mySwitchs[i]->getName()}</option>
			{/section}
		</select>
	</fieldset>
	<input type="submit" class="btn btn-sm btn-info" value="OK"/>
</form>
