<?php

/*
 *
 * This script accept a ajax post as filename and filecontent and set
 * the header for download dialog
 */

require_once "share.php";
require_once("config.php");



$fileid = urldecode($_GET['fileid']);

$url = SITE_URL . "/SPOT/provisioning/api/customconfig/$fileid";

$json_table_content = apiWrapper($url);


$filecontentobj = json_decode($json_table_content);
$configTarget = $filecontentobj->configTarget;
$url = SITE_URL . "/SPOT/provisioning/api/networkequipment/$configTarget";
$other_json = apiWrapper($url);
$equipmentobj = json_decode($other_json);
$target_name = $equipmentobj->equipModel;
$filecontent = $filecontentobj->configContent;
$filename = $filecontentobj->salesorder.'_'.$filecontentobj->configId.'_'.$target_name;
header('Content-Description: File Transfer');
header("Content-type: text/plain");
header('Content-Disposition: attachment; filename="' . $filename . '"');

header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
echo $filecontent;


?>