<?php


/* 
*
* This program comes with ABSOLUTELY NO WARRANTY.
*
* This is free software, placed under the terms of the GNU
* General Public License, as published by the Free Software
* Foundation.  Please see the file COPYING for details.  */
      

include_once("includes.php");

if(ENABLE_SWITH_CONFIGURATION_EDITION){//  edition of switches configurations is authorized
   
    $switch_id = $_POST["switch_id"];
    $conf = $_POST["conf"];
    $error = false;
    $message = "";
    if(!isset($switch_id) or !isset($conf)){
        die(WRONG_PARAMETERS);
    }
   
    $mySwitch = MySwitch::retrieveById($switch_id);
    $smarty->assign("mySwitch",$mySwitch);
    try {
            $mySwitch->setConf($conf);
    } catch (Exception $e) {
            $errors[] = "ERROR : ".$e->getMessage();
            $error = true;
    }
    if(!$error){
        $message=CONFIGURATION_SAVED_OK;
    }
        
    $smarty->assign("conf",$conf);
    $smarty->assign("message",$message);
    $smarty->assign("mySwitch",$mySwitch);
    
    $smarty->display('edit_config_form.tpl');
} else {
    die(ACCESS_DENIED);
}

?>