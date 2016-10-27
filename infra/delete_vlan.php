<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	$vlan_id = $_POST["vlan_id"];
	$vlan_name = $_POST["vlan_name"];
	$switch_id = $_POST["switch_id"];
	
	if(!isset($vlan_id) || !isset($switch_id) || !isset($vlan_name)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	try {
		$mySwitch->deleteVlan($vlan_id);
	} catch (Exception $e){
	 	$errors[] = $e;
	}
	
	$smarty->assign("vlan_name",$vlan_name);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('delete_vlan.tpl');
	
?>