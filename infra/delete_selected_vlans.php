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
	$selected_vlans =$_POST["selected_vlans"];
	$dest_switches = $_POST["dest_switches"];
	
	if(!isset($selected_vlans) && isset($switch_id)) {
		die(MSG_AT_LEAST_ONE_VLAN_MUST_BE_SELECTED);
	}
	
	if(!isset($selected_vlans) || !isset($switch_id) || !ALLOW_VLAN_DELETION) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	if(ALLOW_VLAN_DELETION){
	
		$mySwitch = MySwitch::retrieveById($switch_id);
		
		if(!empty($dest_switches)){
			
			foreach($dest_switches as $dest_switch){
				$dest_switches_objects[] = MySwitch::retrieveById($dest_switch);
			}
			
			$switches[] = $mySwitch;
			$switches = array_merge($switches,$dest_switches_objects);
		} else {
			$switches[] = $mySwitch;
		}
		
		$i=0;
		foreach($switches as $switch){
			$j=0;
			foreach($selected_vlans as $selected_vlan){
				
				$error = false;
				
				if(!$switch->vlanExists($selected_vlan)){
					$errors[] = ("<b>Switch : ".$switch->getName()." ---></b> VLAN ID ".$selected_vlan." : ".MSG_NON_EXISTENT_VLAN);
					$error = true;
				} 
				if(!$error){
					try {
						$egressPorts = $switch->getEgressPorts($selected_vlan);
					} catch (Exception $e){
						$errors[] = $e;
						$vlans_deleted_error[] =$selected_vlan;	
					}
					if(isset($egressPorts[0])){ // At least one tagged or untagged port.
						$errors[] = ("<b>Switch : ".$switch->getName()." ---></b> VLAN ID ".$selected_vlan." : ".MSG_VLAN_HAS_PORTS_TAGGED_OR_UNTAGGED);
						$error = true;
					}
				}
				
				if(!$error){
					if($selected_vlan == 1){
						$errors[] = ("<b>Switch : ".$switch->getName()." ---></b> VLAN ID ".$selected_vlan." : ".MSG_DEFAULT_VLAN_CANNOT_BE_DELETED);
						$error = true;
					}
				}
				
				if ($error) {
					$vlans_deleted_error[$i][] = "VLAN <b>$selected_vlan</b> Switch ".$switch->getName();
				} else {
					try {
						$switch->deleteVlan($selected_vlan);
						$vlans_deleted_ok[$i][] = "VLAN <b>$selected_vlan</b> of switch ".$switch->getName();
					} catch (Exception $e){
						$vlans_deleted_error[$i][] = "VLAN <b>$selected_vlan</b> of switch ".$switch->getName();
				 		$errors[] = $e;
					}
				}			
				$j++;
			}
			$i++;
		}
		
		$smarty->assign("switches",$switches);
		$smarty->assign("mySwitch",$mySwitch);
		$smarty->assign("selected_vlans",$selected_vlans);
		$smarty->assign("vlans_deleted_ok",$vlans_deleted_ok);
		$smarty->assign("vlans_deleted_error",$vlans_deleted_error);
		
		$smarty->assign("errors",$errors);
		
		$smarty->display('delete_selected_vlans.tpl');
	}
?>