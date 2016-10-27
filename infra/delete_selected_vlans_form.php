<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	

	$switch_id = $_POST["switch_id"];
	$selected_vlans =$_POST["selected_vlans"];
	
	if(!isset($selected_vlans) && isset($switch_id)) {
		die(MSG_AT_LEAST_ONE_VLAN_MUST_BE_SELECTED);
	}
	
	if(!isset($selected_vlans) || !isset($switch_id) || !ALLOW_VLAN_DELETION) {
		die(MSG_WRONG_PARAMETERS);
	}
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	
	$smarty->assign("mySwitch",$mySwitch);
	$smarty->assign("selected_vlans",$selected_vlans);

	
	$smarty->assign("errors",$errors);
	
	$smarty->display('delete_selected_vlans_form.tpl');
	
?>