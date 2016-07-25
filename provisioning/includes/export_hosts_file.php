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
        //Header
        $res[] = "##### Generated hosts file for $salesorder . #####";
        $res[] = "\n\n";
        $res[] = "127.0.0.1   localhost";
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