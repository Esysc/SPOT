<?php

header("Content-type: text/xml");
require_once("config.php");
require_once "class.xmlresponse.php";
require_once "share.php";
# Run a script on remote client and set the content to stdout


if (isset($_POST['ip']) && isset($_POST['id'])) {
    $ip = $_POST['ip'];
    $id = $_POST['id'];
    $scriptID = $_POST['scriptID'];
} else {
    #If not ip is posted exit this script
    exit;
}


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

function findKey($array, $keySearch)
{
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
           
            return true;
        }
        else {
            if (is_array($item) && findKey($item, $keySearch)) {
               return true;
            }
        }
    }

    return false;
}


$postfields = json_encode($command);



# Set the script in cron to be execute
$set = apiPOST($url, $postfields, $method = 'POST');



# Start the listen script to send the answer

$url = SITE_URL . "/SPOT/provisioning/api/remotecommandses?salesorder_Equals=$salesorder";
//sleep(10);
$stdout = "";
for ($i = 0; $i <= 25; $i++) {

    $dbcall = apiWrapper($url);

    $check = json_decode($dbcall, true);

    if (findKey($check, 'returnstdout')) {
        $stdout = trim($check['rows'][0]['returnstdout']);
        if ($stdout !== "") {
            break;
        }
        
    }
    sleep(1); // this should halt for 1 seconds for every loop
    flush();
}

if ($stdout === "") {
    exit;
}
$xml = new xmlResponse();
$xml->start();

$xml->command('setvalue', array('target' => "$id", 'value' => "$stdout"));
$xml->end();
