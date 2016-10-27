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
	$source_vlan =$_POST["source_vlan"];
	$dest_vlan =$_POST["dest_vlan"];
	
	if(!isset($port_id) || !isset($switch_id) || !isset($source_vlan)|| !isset($dest_vlan)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	if($mySwitch->getHp3ComCompat()){
		die("port tagging not yet working of HP switches originally designed by 3com..");
	}
	try {
		$mySwitch->tagPort($port_id,$dest_vlan);
	} catch (Exception $e){
	 	$errors[] = $e;
	}
	try {
		$mySwitch->removeUntaggedFlagOfPortInVlan($source_vlan,$port_id);
	} catch (Exception $e){
	 	$errors[] = $e;
	}
	if($mySwitch->portIsUntagged($port_id) && $source_vlan != $dest_vlan){
		try {
			$mySwitch->removeTaggedFlagOfPortInVlan($source_vlan,$port_id);
		} catch (Exception $e){
			$errors[] = $e;
		}
	}	
	$smarty->assign("dest_vlan",$dest_vlan);
	$smarty->assign("port_id",$port_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('tag_port.tpl');
	
?>