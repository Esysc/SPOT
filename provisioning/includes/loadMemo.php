<?php

/*
 * This script connect to ist and get all memos id related on a release
 */
require_once("config.php");
require_once("share.php");

$release = $_POST['release'];

$xml = getMemoIds($release);


echo $xml;
?>
