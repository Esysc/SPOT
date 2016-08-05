<?php

/**
 * 	Script that resolved hostname from IP address for windows machines
 */
# include required scripts
require( dirname(__FILE__) . '/../../../functions/functions.php' );


# initialize required objects
$Database = new Database_PDO;
$User = new User($Database);
$Subnets = new Subnets($Database);
$DNS = new DNS($Database);


# verify that user is logged in
$User->check_user_session();


$cmd = "timeout 1 nmblookup -A " . $_POST['ipaddress'] . " | head -2 | tail -1 | awk '{print $1}'";
# resolve
$hostname['name']['hostname'] = exec($cmd);
if (trim($hostname['name']['hostname']) === "") {
    die();
} else {
    $cmd = "timeout 1 nmblookup -A " . $_POST['ipaddress'] . " | head -3 | tail -1 | awk '{print $1}'";
    $hostname['name']['description'] = exec($cmd);
    $result = $hostname;
}

# print result

print json_encode($result['name']);
str
?>