<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

	include_once("includes.php");
	if(!isset($_POST["switch_id_1"]) || !isset($_POST["switch_id_2"])) {
		die(MSG_WRONG_PARAMETERS);
	}
	$switch_id_1 = $_POST["switch_id_1"];
	$switch_id_2 = $_POST["switch_id_2"];
	
	$selected_switchs[] = MySwitch::retrieveById($switch_id_1);
	$selected_switchs[] = MySwitch::retrieveById($switch_id_2);
	
	$i=0;
	foreach($selected_switchs as $commutateur){
		try {
			$vlans[$i] = $commutateur->getVlans();
	 	} catch (Exception $e){
	 		$errors[] = $e;
	 	}
		$i++;
	}
	
	$i=0;
	foreach($selected_switchs as $commutateur){
		foreach($vlans[$i] as $vlan){
			$ports[$i][$vlan->getId()] = $commutateur->getAllPorts($vlan->getId());
		}
		$i++;
		
		
	}

	$smarty->assign("selected_switchs",$selected_switchs);
	$smarty->assign("vlans",$vlans);
	$smarty->assign("ports",$ports);
	
	$smarty->assign("errors",$errors);
	$smarty->display("comparative_view.tpl");
?>
