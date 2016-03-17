<?php
header('Access-Control-Allow-Origin: *');  
/*
 * This script download the sales orders page from the sysproddb  and filter orders based on status name
 
 * It then echos a json object containing all the informarions
 */
require_once("config.php");
require_once("share.php");

/* function tdrows($elements)
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
*/


$url = URL_SYSPRODDB . '/GetSalesOrders';
$results = json_decode(curlGet($url, false), true);
$returns = array();
foreach ($results as $key => $value) {
    /*
     * Example of json return
     * "sales_order_ref": 99000010,
        "customer_system_name": "customer AAA system 1",
        "head_end_acronym": "AAA1",
        "program_manager_name": "toto",
        "project_name": "JSE test order_item_in_pallet",
        "planned_start_date": "2015-01-26",
        "planned_end_date": "2015-01-26",
        "real_start_date": null,
        "real_end_date": null,
        "crm_system_id": null,
        "release_installed": null,
        "has_snapshot": 0,
        "sysprod_comments": null,
        "status_name": "Validated",
        "status_date": "2015-01-26 11:44:03",
        "user_profile_name": "srobyr"
     */
    //test the status
    $status = $value['status_name'];
    switch ($status) {
        case 'Validated':
        case 'Being assembled':
            $SO = $value['sales_order_ref'];
           
            $returns[] = $value['sales_order_ref'] . " | ". $value['head_end_acronym'] . " | " . $value['project_name'] ;
            break;

        default:
            continue;
    }
    
}
/*$url = URL_WEBSYSPRODDB_ORDERS;
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
*/





//echo $result;

$jsonenc = json_encode($returns);
echo $jsonenc;
?>
