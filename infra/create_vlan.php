<?php
/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	if(isset($_POST["switch_id"]) && isset($_POST["vlan_name"]) && isset($_POST["vlan_id"])){
		$switch_id = $_POST["switch_id"];
		$vlan_name = $_POST["vlan_name"];
		$vlan_id = (int)$_POST["vlan_id"];
		$dest_switches = $_POST["dest_switches"];
	} else {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	$error_on_first_switch = false;
	try {
		$mySwitch->addVlan($vlan_id,$vlan_name);
	} catch (Exception $e) {
		$error_on_first_switch = true;
		$errors[] = $e;
	}
	
	// Adding vlan in selected swithes (if any)
	if (!empty($dest_switches)){
		foreach($dest_switches as $one_selected_switch_id){
			$switch = MySwitch::retrieveById($one_selected_switch_id);
			try{
				$switch->addVlan($vlan_id,$vlan_name);
				$switches_ok[] = $switch->getName();
			} catch (Exception $e){
				$switches_errors[] = $switch->getName();
				$errors[] = $e;
			}
		}
	}
	
	$smarty->assign("mySwitch",$mySwitch);
	$smarty->assign("vlan_id",$vlan_id);
	$smarty->assign("vlan_name",$vlan_name);
	$smarty->assign("switches_errors",$switches_errors);
	$smarty->assign("switches_ok",$switches_ok);
	$smarty->assign("error_on_first_switch",$error_on_first_switch);
	$smarty->assign("dest_switches",$dest_switches);
	$smarty->assign("errors",$errors);
	
	$smarty->display('create_vlan.tpl');
?>