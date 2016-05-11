<?php

header("Content-type: text/xml");
require_once("config.php");
require_once "class.xmlresponse.php";
require_once "share.php";

if (isset($_POST['ip']) && isset( $_POST['id'])) {
    $ip = $_POST['ip'];
    $id = $_POST['id'];
} else {
    #If not ip is posted exit this script
    exit;
}

$scriptID = 27; # ID of script to execute
$url = SITE_URL . "/SPOT/provisioning/api/remotecommands";
$salesorder = rand(1, 99999999);
$shelf = "Z";
$rack = rand(25, 99);
$exesequence = 1;
$executionFlag = 0;
$command = array("salesorder" => $salesorder,
    "rack" => $rack,
    "shelf" => $shelf,
    "clientaddress" => $ip,
    "exesequence" => $exesequence,
    "executionflag" => $executionFlag,
    "scriptid" => $scriptID
);



$postfields = json_encode($command);



# Set the script in cron to be execute
$set = apiPOST($url, $postfields, $method = 'POST');



# Start the listen script to send the answer

$url = SITE_URL . "/SPOT/provisioning/api/remotecommandses?salesorder_Equals=$salesorder";
sleep(10);

    $dbcall = apiWrapper($url);
    
    $check = json_decode($dbcall, true);
    $stdout = trim($check['rows'][0]['returnstdout']);
    if ($stdout === "") {
        $stdout = "Time out reached";
       
    } else
    
$xml = new xmlResponse();
$xml->start();

$xml->command('setvalue', array('target' => "$id", 'value' => "$stdout"));
$xml->end();
