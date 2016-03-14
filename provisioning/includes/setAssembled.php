<?php

/**
 * Php script to assembling globally  all items sales order
 * first get all items, then sort descending
 * It makes call to the syspropddb api
 */
require "share.php";
require "config.php";

function sortIt($a, $b) {
    return $b['order_item_id'] - $a['order_item_id'];
}

$sales_order_ref = "";
$results = "";
$order_item_ids = "";
$ERROR = 0;
if (isset($_POST['sales_order_ref']))
    $sales_order_ref = $_POST['sales_order_ref'];


if ($sales_order_ref !== "") {
    $postfields = "sales_order_ref=$sales_order_ref";
    $url = 'http://sysproddb.my.compnay.com/api/1.0/GetOrderItems';
    $result = CurlPost($url, $postfields);
    $array = json_decode($result,true);
    //sort the array desc order
    usort($array, "sortIt");
    $message = "<h5>Get Items: Try to Get items from sales order $sales_order_ref and reorder descending following the order_item_id </h5>";
   $results .= $message . jsonToHtml($array);
    
    $url = 'http://sysproddb.my.compnay.com/api/1.0/UpdateItemStatus';
    foreach ($array as $item) {
       //  usort($item, "sortIt");
        
        $item_id = $item['item_id'];
        if (trim($item['status_name']) === 'Being assembled') {
            
            $postfields = "item_id=$item_id&item_is_assembled=1";
            $result = CurlPost($url, $postfields);
            $message = "<h5>Assembling: Try to assemble $item_id</h5>";
            $results .= $message . jsonToHtml(json_decode($result, true));
        }
        else
        {
            $message = "<h5>Assembling:  The item id '$item_id' is not in 'Being assembled' status. The status name is ".$item['status_name'].".</h5>";
            $results .= $message;
        }
    }

} else {
    $ERROR = 1;
    $results .= "
            <table class='table'>
            <tr>
            <th>Message from php script</th><th>Specification name</th><th>order_item_id</th><th>specification Value</th>
            </tr>
            <tr>
            <td>I can't proceed as not all mandatory values has been posted.</td>
            <td>$specification</td>
                <td>$order_item_ids</td>
                    <td>$specification_value</td>
             </tr>
            </table> ";
}
echo $results;
?>
