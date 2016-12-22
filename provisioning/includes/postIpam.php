<?php

/**
 * Php script to add hosts to ipam DB
 */
function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

$error = "<strong>An error occured, may be the token is not valid. Try to logout and login again, then run this action again.</strong>";
if (!isset($_POST['url']))
    return false;
$url = myUrlEncode($_POST['url']);
session_start();
require "share.php";
require "config.php";
$header = array("token: " . $_SESSION['token'], 'Content-Type: application/json', 'Accept: application/json');
$apiCall = CallAPI('POST', $url, false, $header);
if ($apiCall != false) {
    $results = json_decode($apiCall);
    echo array2table((array) $results);
    if ($results->code == 403)
        echo $error;
} else {
    
    echo $error;
}

//print_r($results);
?>
