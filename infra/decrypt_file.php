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

$key = hash("SHA256", CONFIGURATION_FILES_ENCRYPT_KEY, true);
$crypttext = file_get_contents($file);
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);

$smarty->assign("decrypt",nl2br(htmlspecialchars($decrypt)));
$smarty->display("decrypted_conf.tpl");

?>