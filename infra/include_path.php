<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

if(OS_TYPE == "UNIX"){
	define("SYSTEM_PATH_SEPARATOR","/");
	$appli_folder = APPLI_FOLDER_NAME;
	
} elseif (OS_TYPE == "WINDOWS"){
	define("SYSTEM_PATH_SEPARATOR","\\");
	$appli_folder = APPLI_FOLDER_NAME_WINDOWS;
} else {
	die(MSG_BAD_OS_TYPE);
}

$root=$_SERVER["DOCUMENT_ROOT"];	
if ($root{strlen($root)-1} != "/" && $root{strlen($root)-1} != "\\"){
	if(OS_TYPE=="UNIX"){
		$root .= "/";
	} elseif (OS_TYPE=="WINDOWS"){
		$root .= "\\";
	} else {
		die(MSG_BAD_OS_TYPE);
	} 
} 
define("ROOT",$root);

$path = ROOT.$appli_folder."/lib";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

if (OS_TYPE=="WINDOWS" && ENABLE_CONFIGURATION_BACKUP_MANAGEMENT){
	$path = ROOT.$appli_folder."/lib/phpseclib0.3.5";
	ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);
}

$path = ROOT.$appli_folder."/lib/classes";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

$path = ROOT.$appli_folder."/lib/adLDAP";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

$path = ROOT.$appli_folder."/includes";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

$path = ROOT.$appli_folder."/params";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

$path = ROOT.$appli_folder."/actions";
ini_set('include_path',get_include_path() . PATH_SEPARATOR . $path);

?>
