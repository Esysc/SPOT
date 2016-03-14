<?php

/*
 *
 * This script accept a ajax post as filename and filecontent and set
 * the header for download dialog
 */

require_once "share.php";
require_once("config.php");



$fileid = urldecode($_GET['fileid']);

$url = SITE_URL . "/SPOT/provisioning/api/configtemplate/$fileid";

$json_table_content = apiWrapper($url);
$filecontentobj = json_decode($json_table_content);
$filecontent = $filecontentobj->configTemplate;
$filename = $filecontentobj->versionId;
header('Content-Description: File Transfer');
header("Content-type: text/plain");
header('Content-Disposition: attachment; filename="' . $filename . '"');

header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
echo $filecontent;


?>