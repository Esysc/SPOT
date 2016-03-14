<?php

/**
 * Php script to set attributes on components of a sales order
 * It makes call to the syspropddb api
 */
require "share.php";
require "config.php";
$specification = "";
$specification_value = "";
$order_item_ids = "";
$ERROR = 0;
if (isset($_POST['specification']))
    $specification = $_POST['specification'];
if (isset($_POST['specification_value']))
    $specification_value = $_POST['specification_value'];
//To Do : create one app instead of split in two and get sdame 'serial' value for the two different params
if (isset($_POST['serial']))
    $order_item_ids = $_POST['serial'];
if ($specification !== "" && $specification_value !== "" && $order_item_ids !== "") {
    $item_arr = explode(',', $order_item_ids);
    $specification_value_arr = explode(',', $specification_value);
    $results = "";
    foreach ($item_arr as $key => $item) {
        $order_item_id = trim($item);
        // we need to do three request as the api supports only a request at a time
        // 1 add specification
        $url = URL_SYSPRODDB . '/AddSpecificationForOrderItem';
        $postfields = 'order_item_id=' . $order_item_id . '&specification=' . $specification . '&specification_value=' . $specification_value_arr[$key];
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase1: Try to add $specification</h5>";
        $results .= $message . jsonToHtml(json_decode($result, true));

        // 2 ack it 

        $url = URL_SYSPRODDB . '/UpdateSpecificationForOrderItem';
        $postfields = 'order_item_id=' . $order_item_id . '&specification=' . $specification . '&specification_value=' . $specification_value_arr[$key] . '&acknowledge=1';
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase2: Try to acknowledge</h5>";
        $results .= $message . jsonToHtml(json_decode($result, true));

        // 3 Assemble it
        $url = URL_SYSPRODDB . '/UpdateItemStatus';
        $postfields = 'order_item_id=' . $order_item_id . '&item_is_assembled=1';
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase3: Try to assemble</h5>";
        $results .= $message.jsonToHtml(json_decode($result, true));
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
