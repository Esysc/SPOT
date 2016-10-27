<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	$start_time = microtime(true);
	include_once("includes.php");
	
	
	$switch_id = $_GET["switch_id"];
	if(!isset($switch_id)) {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$switch = MySwitch::retrieveById($switch_id);
	if(isset($switch)){
	 	try {
			$vlans = $switch->getVlans();
	 	} catch (Exception $e){
	 		$errors[] = $e;
	 	}
	} else {
		$errors[] = "Wrong switch id !";
	}

	foreach($vlans as $vlan){
		try {
			$ports[$vlan->getId()] = $switch->getAllPorts($vlan->getId());
		} catch (Exception $e){
	 		$errors[] = $e;
	 	}
        }
	$smarty->assign("mySwitch",$switch);
	$smarty->assign("vlans",$vlans);
	$smarty->assign("ports",$ports);
	
	$smarty->assign("errors",$errors);
	
	$end_time = microtime(true);
	$smarty->assign("exec_time",round($end_time - $start_time, 4));

	$smarty->display("list_vlans.tpl");
?>
