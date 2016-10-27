<?php

$switch_id = $_GET["switch_id"];
if(!isset($switch_id)){
  die(ACCESS_DENIED);
}

$switch = MySwitch::retrieveById($switch_id);

if($protocol=="telnet"){
  $port=23;
  $plugins = "Socket,Telnet,ButtonBar(1),ButtonBar(2),Terminal,Status";
} else {
  $port=22;
  $plugins = "Socket,SSH,ButtonBar(1),ButtonBar(2),Terminal,Status";
}


$jta_applet =  
      "<applet CODEBASE=\".\"
            ARCHIVE=\"jta/jta26.jar\"
            CODE=\"de.mud.jta.Applet\" 
            WIDTH=590 HEIGHT=360>
            <param name=\"Socket.host\" value=\"".$switch->getIp()."\">
            <param name=\"Socket.port\" value=\"".$port."\">
            <param name=\"plugins\" value=\"".$plugins."\">
      </applet>";

$smarty->assign("jta_applet",$jta_applet);

?>