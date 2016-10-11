<?php

/**
 * Php script to set attributes like hostnames and IP in new sysprod DB
 */
require "share.php";
require "config.php";

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
    $page = $_POST['page'];
    $action3 = "Add";
    $action = "Update";
    $network_name = $_POST['network_name'];
    $network_mask = $_POST['network_mask'];
    $vlan = $_POST['vlan'];
    $release_installed = $_POST['release_installed'];
    $url = SYSLOG_ROOT . '/sales_order.php?page=' . $page . '&sales_order_ref=' . $sales_order_ref;
    $postfields = 'page=' . $page . '&sales_order_ref=' . $sales_order_ref . '&action=' . $action . '&release_installed=' . $release_installed;
    $message = "<h5>Trying to add release name</h5>";
    $result = curlPost($url, $postfields);
    $results .= $message . jsonToHtml(json_decode($result, true));
    $postfields = 'page=' . $page . '&sales_order_ref=' . $sales_order_ref . '&action3=' . $action3 . '&vlan=' . $valn .
            'network_name=' . $network_name . '&network_mask=' . $network_mask . '&ip=' . $ip . '&';
    $message = "<h5>Trying to add network</h5>";
    $result = curlPost($url, $postfields);
    $results .= $message . jsonToHtml(json_decode($result, true));
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
