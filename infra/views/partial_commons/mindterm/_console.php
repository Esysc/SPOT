<?php

$switch_id = $_GET["switch_id"];
if(!isset($switch_id)){
  die(ACCESS_DENIED);
}

$switch = MySwitch::retrieveById($switch_id);
if($protocol == "ssh"){
  $class="com.mindbright.application.MindTerm.class";
} else {
  $class="com.mindbright.application.MindTermTelnet.class";
}
  $mindterm_applet = '  
    <APPLET CODE="'.$class.'"
          ARCHIVE="mindterm/mindterm.jar" WIDTH=0 HEIGHT=0>
              <PARAM NAME="sepframe" value="true">
              <PARAM NAME="debug" value="true">
              <PARAM NAME="server" value="'.$switch->getIp().'">
    </APPLET>';

$smarty->assign("mindterm_applet",$mindterm_applet);
?>