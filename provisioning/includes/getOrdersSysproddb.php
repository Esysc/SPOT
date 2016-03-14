<?php
header('Access-Control-Allow-Origin: *');  
/*
 * This script dosnload the sales orders page from the sysproddb  and filter orders based on status name
 
 * It then echos a json object containing all the informarions
 */
require_once("config.php");
require_once("share.php");

function tdrows($elements)
{
    $row = array();
    //$str = "";
    foreach ($elements as $element) {
        $row[] = $element->nodeValue;
    }

    return $row;
}

function getdata($url)
{
    $contents = curlGet($url, false);
    $DOM = new DOMDocument;
    @$DOM->loadHTML($contents);

    $items = $DOM->getElementsByTagName('tr');
    $results = array();
    foreach ($items as $node) {
        $results[] =  tdrows($node->childNodes);
        
    }
    return $results;
   
}






$url = URL_WEBSYSPRODDB_ORDERS;
$results = getdata($url);
$returns = array();
foreach ($results as $key => $value) {
    //test the status
    $status = trim($value[10]);
    if ($status !== "Packed" && $status !== "Shipped" && $status !== "Assembled" && $status !== "Cancelled" && $value[0] !== "sales order ref") {
        $SO = $value[0];
        $url = URL_WEBSYSPRODDB . $SO;
        $details = getdata($url);
        
        $returns[] = $value[0] . " | ". $details[2][2] . " | " . $details[4][2] ;
    }
}






//echo $result;

$jsonenc = json_encode($returns);
echo $jsonenc;
?>
