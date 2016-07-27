<?php

/**
 * 	Generate hostfile dump for /etc/hosts
 * ******************************* */
require_once("config.php");
if (isset($_POST['salesorder'])) {

    $salesorder = $_POST['salesorder'];
//set filename
    $filename = $salesorder."_" . date("Y-m-d"). ".txt";

    $filePath = SITE_DIR . '/log/' . $filename;
    $handle = fopen($filePath, "w");
//fetch all addresses with hostname set
    $hosts = $_POST['ipaddress'];
    $hostnames = $_POST['hostname'];
//loop
    if (sizeof($hosts) > 0 && sizeof($hostnames) > 0) {
        $ip = explode('.', $hosts[1]);  // Get the second ip in array because the first is always empty
        $bc = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".255";
        $time = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".242";
        $fw = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".252";
        $vpn = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".250";
        $esw1 = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".231";
        $esw2 = $ip[0] . ".". $ip[1]. ".". $ip[2]. ".232";
        //Header
        $res[] = "##### Generated hosts file for $salesorder . #####";
        $res[] = "\n\n# Common networking part";
        $res[] = "127.0.0.1   localhost";
        $res[] = $bc ."   broadcast";
        $res[] = $fw ."   fw01";
        $res[] = $vpn ."   vpn01";
        $res[] = $esw1 ."   esw01";
        $res[] = $esw2 ."   esw02";
        $res[] = $time ."   time ntp01\n\n";
        $res[] = "##### Servers and services . #####";
        
        foreach ($hosts as $key => $host) {
            //build the host file
            $hostname = $hostnames[$key];
            $res[] = "$host\t" . $hostnames[$key] ;
        }
    }

# join content
    $content = implode("\n", $res);
    fwrite($handle, $content);
    fclose($handle);
# print the url
    
   // $url = SITE_URL . "/SPOT/log/".$filename;
    $url = $filePath;
    $result = array("filename" => "$filename","url" => "$url");
    
    print(json_encode($result));
}
?>