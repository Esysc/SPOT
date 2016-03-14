<?php

/**
 * Php script to set attributes on components of a sales order
 * It makes call to the syspropddb api
 */
require "share.php";
require "config.php";
$specification = "";
$specification_value = "";
$serial = "";
$ERROR = 0;
if (isset($_POST['specification']))
    $specification = $_POST['specification'];
if (isset($_POST['specification_value']))
    $specification_value = $_POST['specification_value'];

if (isset($_POST['serial']))
    $serial = $_POST['serial'];
if ($specification !== "" && $specification_value !== "" && $serial !== "") {
    $ser_arr = explode(',', $serials);
    $specification_value_arr = explode(',', $specification_value);
    $results = "";
    foreach ($ser_arr as $key => $serial) {
        $serial = trim($serial);
        // we need to do three request as the api supports only a request at a time
        // 1 add specification
        $url = URL_SYSPRODDB . '/AddSpecificationForOrderItem';
        $postfields = 'serial_number=' . $serial . '&specification=' . $specification . '&specification_value=' . $specification_value_arr[$key];
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase1: Try to add $specification</h5>";
        $results .= $message.jsonToHtml(json_decode($result, true));

        // 2 ack it 

        $url = URL_SYSPRODDB . '/UpdateSpecificationForOrderItem';
        $postfields = 'serial_number=' . $serial . '&specification=' . $specification . '&specification_value=' . $specification_value_arr[$key] . '&acknowledge=1';
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase2: Try to acknowledge</h5>";
        $results .= $message.jsonToHtml(json_decode($result, true));

        // 3 Assemble it
        $url = URL_SYSPRODDB . '/UpdateItemStatus';
        $postfields = 'serial_number=' . $serial . '&item_is_assembled=1';
        $result = curlPost($url, $postfields);
        $message = "<h5>Phase3: Try to assemble</h5>";
        $results .= $message.jsonToHtml(json_decode($result, true));
    }
} else {
    $ERROR = 1;
    $results .= "
            <table class='table'>
            <tr>
            <th>Message from php script</th><th>Specification name</th><th>Serial number</th><th>specification Value</th>
            </tr>
            <tr>
            <td>I can't proceed as not all mandatory values has been posted.</td>
            <td>$specification</td>
                <td>$serial</td>
                    <td>$specification_value</td>
             </tr>
            </table> ";
}
echo $results;
?>
