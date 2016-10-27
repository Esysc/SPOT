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
	if(isset($_GET["vlan_id"]) && isset($_GET["vlan_name"]) && isset($_GET["switch_id"])){
		$vlan_id = $_GET["vlan_id"];
		$vlan_name = $_GET["vlan_name"];
		$switch_id = $_GET["switch_id"];
	} else {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	$no_untaggable_ports = array();
	$taggable_ports = array();
	$untaggable_ports = array();

	$ports_number = $mySwitch->getNbPorts();
	$ports = $mySwitch->getAllPorts();
	foreach($ports as $port_id){
		$port = new GeneralPort($port_id,$switch_id);
		if($port->isTaggedSomewhere()){
			if($port->isTaggedInVlan($vlan_id)){
				$no_untaggable_ports[] = $port;
				
			} else {
				$taggable_ports[] = $port;
			}
			if($port->isUntaggedSomewhere()){
				if($port->getVlanUntagged()->getId() != $vlan_id){
					$untaggable_ports[] = $port;
				}
			} else {
				$untaggable_ports[] = $port;
			}
		} else {
			if($port->getVlanUntagged()->getId() != $vlan_id){
				$untaggable_ports[] = $port;
			}
			$taggable_ports[] = $port;
		}
	}
	
	$smarty->assign("taggable_ports",$taggable_ports);
	$smarty->assign("no_untaggable_ports",$no_untaggable_ports);
	$smarty->assign("untaggable_ports",$untaggable_ports);
	$smarty->assign("mySwitch",$mySwitch);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("vlan_name",$vlan_name);
	$smarty->display('edit_vlan_form.tpl');
}
	
?>