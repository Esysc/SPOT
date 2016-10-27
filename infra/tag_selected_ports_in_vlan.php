<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
include_once("includes.php");

 if(ALLOW_PORT_TAGGING){
 	
	$switch_id = $_POST["switch_id"];
	$vlan_id =$_POST["vlan_id"];
	$dest_ports =$_POST["dest_ports_tagged"];
	
	if(!isset($dest_ports) && isset($switch_id) && isset($vlan_id)) {
		die(MSG_AT_LEAST_ONE_PORT_MUST_BE_SELECTED);
	}
	
	if(!isset($dest_ports) || !isset($switch_id) || !isset($vlan_id)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	if($mySwitch->getHp3ComCompat()){
		die("port tagging not yet working of HP switches originally designed by 3com..");
	}


	try {
		$mySwitch->tagPorts($dest_ports,$vlan_id);
	} catch (Exception $e){
	 	$errors[] = $e;
	}
	
	try {
		$mySwitch->removeUntaggedFlagOfPortsInVlan($vlan_id,$dest_ports);
	} catch (Exception $e){
		$errors[] = $e;
	}
	
	$smarty->assign("dest_ports_tagged",$dest_ports);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('tag_selected_ports_in_vlan.tpl');
 }
	
?>