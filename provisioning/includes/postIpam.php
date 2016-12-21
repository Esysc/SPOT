<?php

/**
 * Php script to add hosts to ipam DB
 */
function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

if (!isset($_POST['url']))
    return false;
$url = myUrlEncode($_POST['url']);
session_start();
require "share.php";
require "config.php";
$header = array("token: " . $_SESSION['token'], 'Content-Type: application/json', 'Accept: application/json');
$apiCall = CallAPI('POST', $url, false, $header);
var_dump($apiCall);
//echo $_SESSION['token'];
//echo $apiCall;
?>
