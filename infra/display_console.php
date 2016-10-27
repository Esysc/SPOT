<?php


/* 
*
* This program comes with ABSOLUTELY NO WARRANTY.
*
* This is free software, placed under the terms of the GNU
* General Public License, as published by the Free Software
* Foundation.  Please see the file COPYING for details.  */
      

include_once("includes.php");

$protocol = $_GET["protocol"];

    if (isset($protocol) && ($protocol == "ssh" or $protocol == "telnet")){
        if ($protocol == "ssh" and !ENABLE_SSH_CONSOLE){
            die(ACCESS_DENIED);
        }
        if ($protocol == "telnet" and !ENABLE_TELNET_CONSOLE){
            die(ACCESS_DENIED);            
        }
    } else {
        die(WRONG_PARAMETERS);
    }
    
    if((USE_JTA_CONSOLE && !USE_MINDTERM_CONSOLE) or (!USE_JTA_CONSOLE && !USE_MINDTERM_CONSOLE)){
         include_once("views/partial_commons/jta/_console.php");
         $smarty->display("display_console.tpl");            
    } elseif (USE_MINDTERM_CONSOLE && !USE_JTA_CONSOLE) {
         include_once("views/partial_commons/mindterm/_console.php");
         $smarty->display("display_console.tpl");
    } else {
        die(MINDTERM_AND_JTA_BOTH_SELECTED);
    }

?>