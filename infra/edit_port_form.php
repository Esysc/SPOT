<?php
/* ACS 2016.
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	if(isset($_GET["port_id"]) && isset($_GET["switch_id"]) && isset($_GET["source_vlan"])){
		$port_id = $_GET["port_id"];
		$switch_id = $_GET["switch_id"];
		$source_vlan =$_GET["source_vlan"];
	} else {
		die("ERREUR : Parametre(s) incorrect(s)");
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	$vlans_list = $mySwitch->getVlans();
	
	$no_untaggable_vlans = array();
	$vlans_where_the_port_is_not_tagged = array();
	foreach($vlans_list as $vlan){
		$tagged_ports = $mySwitch->getTaggedPorts($vlan->getId());
		foreach($tagged_ports as $tagged_port){
			if ($tagged_port->getId() == $port_id){
				$no_untaggable_vlans[] = $vlan;
			} 
		}
	}
	
	foreach($vlans_list as $vlan){
		$found = false;
		$i=0;
		while($i<count($no_untaggable_vlans) && !$found){
			if($vlan->getID() == $no_untaggable_vlans[$i]->getId()){
				$found = true;
			}
			$i++;
		}
		if(!$found){
			$vlans_where_the_port_is_not_tagged[]=$vlan;
		} 
	}
	
	if(ALLOW_PORT_TAGGING){
		$smarty->assign("tagged_in_only_one_vlan",$mySwitch->portIsTaggedInOnlyOneVlan($port_id));
		$smarty->assign("tagged_in_more_than_one_vlan",$mySwitch->portIsTaggedInMoreThanOneVlan($port_id));
	}
	
	$port = Port::retreiveById($switch_id,$port_id,$source_vlan);
	$smarty->assign("port_name",$port->getName());
	$smarty->assign("port_description",$port->getDescription());
	$smarty->assign("port_alias",$port->getAlias());
	$smarty->assign("port_speed",(int) $port->getSpeed()/1000/1000);
	
	$smarty->assign("port_is_untagged",$mySwitch->portIsUntagged($port_id));
	$smarty->assign("no_untaggable_vlans",$no_untaggable_vlans);
	$smarty->assign("vlans_where_the_port_is_not_tagged",$vlans_where_the_port_is_not_tagged);
	
	$smarty->assign("vlans_list",$vlans_list);
	
	$smarty->assign("source_vlan",$source_vlan);
	$smarty->assign("port_id",$port_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	//$smarty->display('edit_port_form.tpl');
	$smarty->display('edit_port_form_popup.tpl');
?>