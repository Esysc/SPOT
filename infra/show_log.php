<?php

/* 
*
* This program comes with ABSOLUTELY NO WARRANTY.
*
* This is free software, placed under the terms of the GNU
* General Public License, as published by the Free Software
* Foundation.  Please see the file COPYING for details.  */
      

include_once("includes.php");

if(isset($_GET["location"])){
	$location = $_GET["location"];
} else {
    $location = "logs";
}

if(ENABLE_CONFIGURATION_BACKUP_MANAGEMENT){//  backing up/restoration of switches configuration is authorized
    $log_files_links=array();
    $names=array();
    if ($handle = opendir($location)) {
        while (false !== ($entry = readdir($handle))) {
            if($entry != "." && $entry != '..' && $entry != '.htaccess' && strpos($entry,".log")!=false){
                $log_files_links[]="read_log.php?location=".$location."/".$entry;
                $names[]=$entry;   
            } 
        }
    }
    $smarty->assign("names",$names);
    $smarty->assign("log_files_links",$log_files_links);
    $smarty->display('show_log.tpl');
} else {
    die(ACCESS_DENIED);
}

?>