<?php
/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	if(isset($_GET["switch_id"])){
		$switch_id = $_GET["switch_id"];
	} else {
		die(MSG_WRONG_PARAMETERS);
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
	
	$vlans_list = $mySwitch->getVlans();
	
	$smarty->assign("mySwitch",$mySwitch);
	
	$smarty->display('create_vlan_form.tpl');
?>