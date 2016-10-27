<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
include_once("includes.php");

$file = $_GET["location"];

if(!isset($file)) {
    die(MSG_WRONG_PARAMETERS);
}

if(!ENABLE_CONFIGURATION_BACKUP_MANAGEMENT){
    die(ACCESS_DENIED);
}

$text = file_get_contents($file);

$smarty->assign("text",nl2br(htmlspecialchars($text)));
$smarty->display("log_view.tpl");

?>