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
	$switch_id = $_POST["switch_id"];
	$vlan_name =$_POST["vlan_name"];
	
	if(!isset($vlan_id) || !isset($switch_id) || !isset($vlan_name)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	$vlan = new Vlan($switch_id,$vlan_id,""); // We don't need the name for now (this parameter only used for the php object)
	$vlan->setName($vlan_name); // Set done via snmp (not only the object is modified)
	
	$smarty->assign("vlan_name",$vlan_name);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->display('edit_vlan_name.tpl');
	
?>