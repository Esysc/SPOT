<?php
/* ACS 2016.
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	if(isset($_POST["port_id"]) && isset($_POST["switch_id"])){
		$port_id = $_POST["port_id"];
		$switch_id = $_POST["switch_id"];
               
	} else {
		die("ERREUR : Parametre(s) incorrect(s)");
	}
	
	$mySwitch = MySwitch::retrieveById($switch_id);
        
        $switchIP = $mySwitch->getIp();
        
	$macs = getMacs($switchIP,$port_id);
	echo $macs
?>