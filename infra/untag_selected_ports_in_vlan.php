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
$dest_vlan =$_POST["vlan_id"];
$dest_ports = $_POST["dest_ports_untagged"];
	
if(!isset($dest_ports) && isset($switch_id) && isset($dest_vlan)) {
	die(MSG_AT_LEAST_ONE_PORT_MUST_BE_SELECTED);
}

if(!isset($dest_ports) || !isset($switch_id) || !isset($dest_vlan)) {
	die(MSG_WRONG_PARAMETERS);
}

$mySwitch = MySwitch::retrieveById($switch_id);

$port_ids_rm_tagged_flag=array();
$vlans_rm_tagged_flag=array();
$port_ids_add_untagged_flag = array();
$vlans_add_untagged_flag = array();
$ports_to_be_untagged_after = array();

foreach($dest_ports as $dst){
	$port = new GeneralPort($dst,$switch_id);
	if($port->isUntaggedSomewhere()){
		if($port->getVlanUntagged()->getId() != $dest_vlan){
			//Let's untag the port
			try {
				$mySwitch->untagPort($port->getId(),$dest_vlan);
			} catch (Exception $e){
					$errors[] = $e;
			
			} // next lines because snmpset set the port in the original VLAN as tagged... (and we don't want that)
			$port_ids_rm_tagged_flag[] = $port->getId();
			$vlans_rm_tagged_flag[] = $port->getVlanUntagged()->getId();
		}
		
	} else { // the port is tagged in a vlan and never untagged
		if(!$port->isTaggedInVlan($dest_vlan)){
			try {
				$mySwitch->tagPort($port->getId(),$dest_vlan);
			} catch (Exception $e){
					$errors[] = $e;
			}
		}
		$port_ids_add_untagged_flag[] = $port->getId();
		$ports_to_be_untagged_after[] = $port;
	}
}

if(isset($port_ids_rm_tagged_flag) && count($port_ids_rm_tagged_flag)>0){
	foreach($vlans_rm_tagged_flag as $vlan_id){
		try {
			$mySwitch->removeTaggedFlagOfPortsInVlan($vlan_id,$port_ids_rm_tagged_flag,false);
		} catch (Exception $e){
			$errors[] = $e;
		}
	}
}

if(isset($port_ids_add_untagged_flag) && count($port_ids_add_untagged_flag)>0){
	try {
		$mySwitch->addUntaggedFlagOfPortsInVlan($dest_vlan,$port_ids_add_untagged_flag,false);
	} catch (Exception $e){
		$errors[] = $e;
	}
}

foreach($ports_to_be_untagged_after as $port){
	try {
		$mySwitch->untagPort($port->getId(),$dest_vlan);
	} catch (Exception $e){
		$errors[] = $e;
	}
}

$smarty->assign("dest_ports_untagged",$dest_ports);
$smarty->assign("vlan_id",$dest_vlan);
$smarty->assign("mySwitch",$mySwitch);

$smarty->assign("errors",$errors);

$smarty->display('untag_selected_ports_in_vlan.tpl');

	
?>
	
