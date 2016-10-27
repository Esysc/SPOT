<?php
/* ACS 2016.
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
	include_once("includes.php");
	
	if(isset($_POST["host"]) ){
		$host = $_POST["host"];
	
	} else {
		die("Error : Param(s) not correct(s)");
	}
	
	$results = shell_exec("sudo su - rancid /var/lib/rancid/rancidPush $host");
        
        
	
?>