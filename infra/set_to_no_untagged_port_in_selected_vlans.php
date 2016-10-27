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
	$dest_vlans =$_POST["dest_vlans"];
	
	if(!isset($dest_vlans) && isset($port_id) && isset($switch_id) && isset($source_vlan)) {
		die(MSG_AT_LEAST_ONE_VLAN_MUST_BE_SELECTED);
	}
	
	if(!isset($dest_vlans) || !isset($port_id) || !isset($switch_id) || !isset($source_vlan)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	$vlans_list = $mySwitch->getVlans();
	
	foreach($vlans_list as $vlan){
		$tagged_ports = $mySwitch->getTaggedPorts($vlan->getId()); 
		foreach($tagged_ports as $tagged_port){
			if ($tagged_port->getId() == $port_id){
				$vlans_where_the_port_is_tagged[] = $vlan;
			}
		}
	}
	
	$vlans_where_port_has_been_set_to_no_untagged = array();
	$vlans_where_port_has_been_untagged = array();
	
	foreach($dest_vlans as $dest_vlan){
		if(($source_vlan == $dest_vlan) and (count($dest_vlans) == count($vlans_where_the_port_is_tagged)) and ($mySwitch->portIsUntagged($port_id,true) ==  false)){
			// A port cannot be "no-untagged" in all vlans, it must be untagged in at least one vlan
			try {
				$mySwitch->addUntaggedFlagOfPortInVlan($source_vlan,$port_id);
				$mySwitch->untagPort($port_id,$dest_vlan);
				$vlans_where_port_has_been_untagged[] = $dest_vlan;
			} catch (Exception $e){
				$errors[] = $e;
			}
		} else {

			try {
				$mySwitch->removeTaggedFlagOfPortInVlan($dest_vlan,$port_id);
				$vlans_where_port_has_been_set_to_no_untagged[] = $dest_vlan;
			} catch (Exception $e){
			 	$errors[] = $e;
			}
		}
		$mySwitch = MySwitch::retrieveById($switch_id);
	}
	
	
	$smarty->assign("vlans_where_port_has_been_set_to_no_untagged",$vlans_where_port_has_been_set_to_no_untagged);
	$smarty->assign("vlans_where_port_has_been_untagged",$vlans_where_port_has_been_untagged);
	$smarty->assign("dest_vlans",$dest_vlans);
	$smarty->assign("port_id",$port_id);
	$smarty->assign("mySwitch",$mySwitch);
	$smarty->assign("vlan_id",$vlan_id);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('set_to_no_untagged_port_in_selected_vlans.tpl');
	
?>