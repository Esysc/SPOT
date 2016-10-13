<?php

/**
 * Php script to set attributes like hostnames and IP in new sysprod DB
 */
require "share.php";
require "config.php";

function parseDom($html) {
    $dom = new DomDocument();
    $dom->loadHTML($html);

    $classinfo = "msgbox-info";
    $classerr = "msgbox-error";
    $xpath = new DOMXPath($dom);
    $return = $xpath->query("//*[@class='" . $classinfo . "']");

    if ($return->length > 0) {
        $results .= $return->item(0)->nodeValue;
    }
    $return = $xpath->query("//*[@class='" . $classerr . "']");
    if ($return->length > 0) {
        $results .= $return->item(0)->nodeValue;
    }
    return $results;
}

if (isset($_POST['serial'])) {
    $serial = $_POST['serial'];



    $specifications = array('IP' => 'ipaddress', 'Hostname' => 'hostname', 'CPU' => 'cpu', 'RAM' => 'ram', 'OS' => 'os');
    $results = "";
    foreach ($specifications as $key => $specification) {
        if (isset($_POST[$specification])) {
            $current = $_POST[$specification];
            if ($key === "RAM") {
                $current = intval($current);

                $current = ceil($current * (1 / 1024));
                echo "$key $current";
            }
            // we need to do two request as the api supports only a request at a time
            $url = URL_SYSPRODDB . '/AddSpecificationForOrderItem';
            $postfields = 'serial_number=' . $serial . '&specification=' . $key . '&specification_value=' . $current;
            $message = "<h5>Phase1 Try to add specification $key</h5>";
            $result = curlPost($url, $postfields);
            $results .= $message . jsonToHtml(json_decode($result, true));

            //Let's ack those values........

            $url = URL_SYSPRODDB . '/UpdateSpecificationForOrderItem';
            $postfields = 'serial_number=' . $serial . '&specification=' . $key . '&specification_value=' . $current . '&acknowledge=1';
            $message = "<h5>Phase2: Try to ack $key</h5>";
            $result = curlPost($url, $postfields);
            $results .= $message . jsonToHtml(json_decode($result, true));
        }
    }

    if ($results === "") {
        $ERROR = 1;
        $results = "
            <table class='table'>
            <tr>
            <th>Message from Server</th>
            </tr>
            <tr>
            <td>No value has been posted, so no specifications have been set!</td>
            
             </tr>
            </table> ";
    }

    //If a serial number has not been posted, thrown an error
} elseif (isset($_POST['action3'])) {

    $ip = $_POST['ip'];
    $sales_order_ref = $_POST['sales_order_ref'];
    $action3 = "Add";
    $network_name = $_POST['network_name'];
    $network_mask = $_POST['network_mask'];
    $vlan = $_POST['vlan'];
    $url = URL_WEBSYSPRODDB . $sales_order_ref;

    /*
     * Preparing postfields to add networks... (third tab)
     */
    $postfields = '&action3=' . $action3 . '&vlan=' . $vlan .
            '&network_name=' . $network_name . '&network_mask=' . $network_mask . '&ip=' . $ip . '&';
    $message = "<h5>Trying to add network $ip......</h5>";
    /*
     * Send the network...
     */
    $result = curlPost($url, $postfields);

    $results .= $message . parseDom($result);
} elseif (isset($_POST['action'])) {
    

    $sales_order_ref = $_POST['sales_order_ref'];
    $url = URL_SYSPRODDB . '/GetSalesOrder?sales_order_ref=' . $sales_order_ref;
    $return = json_decode(curlGet($url, false), true);
    
    $action = "Update";
    $release_installed = $_POST['release_installed'];
    $comment = $_POST['comment'];
    $url = URL_WEBSYSPRODDB . $sales_order_ref;
    /*
     * Preparing string to update sales order information (first tab
     */

    foreach ($return as $key => $value) {
        /*
         * Rewriting the values
         */

        $return[$key] = str_replace('+', ' ', $value);
    }


    $postfields = "page=salesOrderDetails&action=Update&sales_order_ref=$sales_order_ref&headend_acronym=" . $return['head_end_acronym'] . "&program_manager=" . $return['program_manager_name'] . "&project_name=" . $return['project_name'] . "&planned_start_date=" . $return['planned_start_date'] . "&planned_end_date=" . $return['planned_end_date'] . "&crm_system_id=" . $return['crm_system_id'] . "&release_installed=$release_installed&has_snapshot=1&comment=$comment";
    $message = "<h5>Trying to add release $release_installed....</h5>";

    /*
     * Send the update
     */
    $result = curlPost($url, $postfields);
    $results .= $message . parseDom($result);
} else {
    $ERROR = 1;
    $results = "
            <table class='table'>
            <tr>
            <th>Message from Server</th>
            </tr>
            <tr>
            <td>No serial number has been posted, I cannot proceed further!!</td>
            
             </tr>
            </table> ";
}
echo $results;
?>
