<?php

header('Access-Control-Allow-Origin: *');
/*
 * This script Get installation report pdf from a given sales order

 * The name is already prepared to check in in Sharepoint
 */
require_once("config.php");
require_once("share.php");

function error($salesorder, $message) {
    header("HTTP/1.1 400 $message");
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => "<div class='alert alert-danger'>$message</div>", 'code' => $code)));
}

//$_GET['sales_order_ref'] = "11020472";
if (isset($_GET['sales_order_ref']) && $_GET['sales_order_ref'] !== '') {

    $salesorder = $_GET['sales_order_ref'];
    $filename = "SO_" . $salesorder . "_sysprod-installation_report.pdf";
    // Check if order exists
    $url = URL_SYSPRODDB . '/GetSalesOrder?sales_order_ref=' . $salesorder;
    $results = curlGet($url, false);
    $parse = json_decode($results);
    if (isset($parse->error)) {
        $message = $parse->error->message . "<br />" . $parse->error->details;
        $code = $parse->error->code;
        error($salesorder, $message, $code);
    }

    if (curl_getinfo($url, CURLINFO_HTTP_CODE) == 404)
        error($salesorder);
    $url = SYSLOG_ROOT . '/document.php?doc=InstallationReport&sales_order_ref=' . $salesorder;
    $results = curlGet($url, false);
    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment;filename=$filename");

    echo $results;
} else {
    error("Sales order Empty","Sales order Empty", 1300 );
}
?>
