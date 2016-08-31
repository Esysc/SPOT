<?php

header('Access-Control-Allow-Origin: *');
/*
 * This script Get installation report pdf from a given sales order

 * The name is already prepared to check in in Sharepoint
 */
require_once("config.php");
require_once("share.php");

//$_GET['sales_order_ref'] = "11020472";
if (isset($_GET['sales_order_ref']) && $_GET['sales_order_ref'] !== '') {

    $salesorder = $_GET['sales_order_ref'];
    $filename = "SO_".$salesorder."_sysprod-installation_report.pdf";
    $url = SYSLOG_ROOT . '/document.php?doc=InstallationReport&sales_order_ref=' . $salesorder;
    $results = curlGet($url, false);
    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment;filename=$filename");

    echo $results;
} else {
    echo "<div class='alert alert-danger'>I didn't receive any sales order...</div>";
}
?>
