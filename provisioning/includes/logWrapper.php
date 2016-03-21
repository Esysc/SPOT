<?php

/*
 *
 * This script is a yet another log wrapper
 * 
 */

require_once "share.php";
require_once("config.php");




$ID = $_POST['commandid'];
$line = $_POST['content'];

//url to get actual value and update as well 
$url = SITE_URL . "/SPOT/provisioning/api/remotecommands/$ID";
$json_table_content = apiWrapper($url);

$filecontentobj = json_decode($json_table_content);
$filecontentout = $filecontentobj->returnstdout;
//first line is the update line

$newline = "$line"."\n$filecontentout";
$jsonPut = Array("returnstdout" => $newline);
$jsonPutobj = json_encode($jsonPut);

//PUT the updates appending the line (update = false)

apiPost($url, $jsonPutobj, "PUT");
?>

