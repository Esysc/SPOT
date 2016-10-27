<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	$port_id = $_POST["port_id"];
	$switch_id = $_POST["switch_id"];
	$vlan_id =$_POST["vlan_id"];
	
	if(!isset($port_id) || !isset($switch_id) || !isset($vlan_id)) {
		die(MSG_WRONG_PARAMETERS);
	}
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	try {
		$mySwitch->removeTaggedFlagOfPortInVlan($vlan_id,$port_id);
	} catch (Exception $e){
	 	$errors[] = $e;
	}

	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("port_id",$port_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('set_to_no_untagged_port.tpl');
	
?>