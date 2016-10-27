<?php


/* 
*
* This program comes with ABSOLUTELY NO WARRANTY.
*
* This is free software, placed under the terms of the GNU
* General Public License, as published by the Free Software
* Foundation.  Please see the file COPYING for details.  */
      

include_once("includes.php");

if(ENABLE_SWITH_CONFIGURATION_VIEW){//  displaying switch configuration is authorized
    
    $switch_id = $_GET["switch_id"];
    if(!isset($switch_id)){
        die(WRONG_PARAMETERS);
    }
    $mySwitch = MySwitch::retrieveById($switch_id);
    $smarty->assign("mySwitch",$mySwitch);
	try {
		//$conf = $mySwitch->getConf();
            $conf = $mySwitch->getConfRancid();
	} catch (Exception $e) {
		$conf = "<span class='label label-danger'>ERROR : </span>".$e->getMessage();
	}
	$smarty->assign("conf",$conf);
    $smarty->display('display_config.tpl');
} else {
    die(ACCESS_DENIED);
}

?>