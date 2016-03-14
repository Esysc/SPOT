<?php

/*
 * This script connect to ist and get the memos xml details
 * ref. : https://atlassian.my.compnay.com/confluence/display/QTP/IST+REST+API+for+Memo
 */
require_once("config.php");
require_once("share.php");

$memoID = '91';

$xml = getMemoXmlDetail($memoID);


echo $xml;
?>
