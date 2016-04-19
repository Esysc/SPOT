<?php
header('Access-Control-Allow-Origin: *');  
/*
 * This script Get all information relatively to a given sales order
 
 * It then echos a json object containing all the informarions
 */
require_once("config.php");
require_once("share.php");


if (isset($_GET['sales_order_ref'])&& $_GET['sales_order_ref'] !== '') {
$salesorder = $_GET['sales_order_ref'];
$url = URL_SYSPRODDB . '/GetSalesOrder?sales_order_ref='.$salesorder;
$results = curlGet($url, false);

echo $results;
}
?>
