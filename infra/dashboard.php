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
	
	
	$switchs = array();
	$vlans = array();
	$ports = array();
	$i =0;
	foreach($mySwitchs as $comm){
		if($comm->hasToBeDiplayedInDashboard()){
			try {
				$vlans[$i] = $comm->getVlans();
		 	} catch (Exception $e){
		 		$errors[] = $e;
		 	}
		 	foreach($vlans[$i] as $vlan){
				try {
					$ports[$i][$vlan->getId()] = $comm->getAllPorts($vlan->getId());
				} catch (Exception $e){
			 		$errors[] = $e;
			 	}
				/*$ports[$vlan->getId()]=array(); // replace foreach code with this to see execution time difference*/ 
			}
		}
		$i++;
	}
	
	$smarty->assign("mySwitchs",$mySwitchs);
	$smarty->assign("vlans",$vlans);
	$smarty->assign("ports",$ports);
	
	$smarty->assign("errors",$errors);
	
	$end_time = microtime(true);
	$smarty->assign("exec_time",round($end_time - $start_time, 4));
	
	$smarty->display("dashboard.tpl");
	
?>