<?php

/**
 * 	Script that resolved hostname from IP address
 */
# include required scripts
require( dirname(__FILE__) . '/../../../functions/functions.php' );
$Snmp = new phpipamSNMP ();
sleep(1);
# initialize required objects
$Database = new Database_PDO;
$User = new User($Database);
$Subnets = new Subnets($Database);
$DNS = new DNS($Database);


# verify that user is logged in
$User->check_user_session();

# fetch subnet
$subnet = $Subnets->fetch_subnet("id", $_POST['subnetId']);
$nsid = $subnet === false ? false : $subnet->nameserverId;

# resolve
$hostname = $DNS->resolve_address($_POST['ipaddress'], false, true, $nsid);
if (trim($hostname['name']) === "") {
    
    
    $Snmp->query_snmp_device($_POST['ipaddress']);
    

    $hostname['name']['hostname'] = snmpget($_POST['ipaddress'], "public", "system.sysName.0", $timeout = 1000000, $retries = 1);

    $hostname['name']['description'] = snmpget($_POST['ipaddress'], "public", "system.sysDescr.0", $timeout = 1000000, $retries = 1);
    $result = $hostname;
} else {
    $result['name']['hostname'] = $hostname['name'];
}

$result['name'] = json_encode($result['name']);
# print result
print str_replace("STRING: ", "", $result['name']);
str
?>