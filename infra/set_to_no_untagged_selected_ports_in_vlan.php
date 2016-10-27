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
	$dest_ports_no_untagged =$_POST["dest_ports_no_untagged"];
		
	if(!isset($dest_ports_no_untagged) && isset($port_id) && isset($switch_id) && isset($vlan_id)) {
		die(MSG_AT_LEAST_ONE_PORT_MUST_BE_SELECTED);
	}
	
	if(!isset($dest_ports_no_untagged) || !isset($switch_id) || !isset($vlan_id)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	// A port cannot be "no-untagged" in all vlans, it must be untagged in at least one vlan we decide to untag ports if they're only tagged in this vlan.
	$ports_that_have_to_be_untagged = array();
	$ports_ids_that_can_be_set_to_untagged = array();
	for($i=0;$i<count($dest_ports_no_untagged);$i++){
		$port = new GeneralPort($dest_ports_no_untagged[$i],$switch_id);
		if($port->isTaggedInVlan($vlan_id)){ // tagged so "no_untaggable"
			if(!$port->isUntaggedSomewhere() && $port->isTaggedInOnlyOneVlan()){
				// if the port is tagged in only one vlan and the port is not untagged anywhere, we decide to untag it and warn the user.
				$ports_that_have_to_be_untagged[] = $port->getId();
			} else {
				$ports_ids_that_can_be_set_to_no_untagged[] = $port->getId();
			}
		}
	}
	
	try {
		if (count($ports_that_have_to_be_untagged) >0){
			$mySwitch->addUntaggedFlagOfPortsInVlan($vlan_id,$ports_that_have_to_be_untagged,false);
			$mySwitch->untagPorts($ports_that_have_to_be_untagged,$vlan_id);
		} 
		if (count($ports_ids_that_can_be_set_to_no_untagged)>0){
			$mySwitch->removeTaggedFlagOfPortsInVlan($vlan_id,$ports_ids_that_can_be_set_to_no_untagged,false);
		}
	} catch (Exception $e) {
		$errors[] = $e;
	}

	$smarty->assign("ports_that_has_been_untagged",$ports_that_have_to_be_untagged);
	$smarty->assign("ports_that_has_been_set_to_no_untagged",$ports_ids_that_can_be_set_to_no_untagged);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->assign("errors",$errors);
	
	$smarty->display('set_to_no_untagged_selected_ports_in_vlan.tpl');
 }
	
?>