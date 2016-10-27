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

	$port_id = $_POST["port_id"];
	$switch_id = $_POST["switch_id"];
	$source_vlan =$_POST["source_vlan"];
	$dest_vlans =$_POST["dest_vlans"];
        
	if(!isset($dest_vlans) && isset($port_id) && isset($switch_id) && isset($source_vlan)) {
		die(MSG_AT_LEAST_ONE_VLAN_MUST_BE_SELECTED);
	}
	
	if(!isset($dest_vlans) || !isset($port_id) || !isset($switch_id) || !isset($source_vlan)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	$source_vlan_not_selected = true;
	foreach($dest_vlans as $dest_vlan){
		try {
			if(!$mySwitch->getHp3ComCompat()){
				$mySwitch->tagPort($port_id,$dest_vlan);
			} else {
				$errors[] = "port tagging not yet working of HP switches originally designed by 3com..";
			}
		} catch (Exception $e){
		 	$errors[] = $e;
		}
		try {
			if(!$mySwitch->getHp3ComCompat()){
				if($dest_vlan == $source_vlan){
					$mySwitch->removeUntaggedFlagOfPortInVlan($source_vlan,$port_id);
				}
			} 
		} catch (Exception $e){
		 	$errors[] = $e;
		}
		if ($dest_vlan == $source_vlan){
			$source_vlan_not_selected = false;
		}
	}
	
	$smarty->assign("dest_vlans",$dest_vlans);
	$smarty->assign("port_id",$port_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('tag_port_in_selected_vlans.tpl');
}

?>