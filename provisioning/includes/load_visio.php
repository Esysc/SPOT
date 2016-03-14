<?php

session_start();
set_include_path("1.0STD1/");
require_once 'dbwrapper.php';
require_once 'order.php';
$db = new dbwrapper('', '');

//$db = unserialize($_SESSION["pdb10S1_dbw"]);
if (isset($_GET['Salesorder'])) {
    if ($db->orderExist(intval($_GET["Salesorder"]))) {
        if (isset($_SESSION["pdb10S1_order"]))
            unset($_SESSION["pdb10S1_order"]);

        $_SESSION["pdb10S1_order"] = serialize($db->getOrder(intval($_GET["Salesorder"])));
        unset($_SESSION["change_so"]);
    }

//$db = unserialize($_SESSION["pdb10S1_dbw"]);

    $visio = array();
    $orderExist = isset($_SESSION["pdb10S1_order"]);
    if ($orderExist)
        $order = unserialize($_SESSION["pdb10S1_order"]);
    if ($orderExist) {

        $visio["<strong>Sales order</strong>"] = $order->getSalesOrder();
        $visio["<strong>Customer</strong>"] = $order->getCustomer();
        $visio["<strong>Customer Acr</strong>"] = $order->getcustomerAcronym();
        $visio["<strong>Program Manager</strong>"] = $order->getProgramManager();
        $visio["<strong>Site Engineer</strong>"] = $order->getSiteEngineer();
        $psdate = $order->getProdStartDate();
        $visio["<strong>Start Prod date</strong>"] = $psdate;
        $pedate = $order->getProdEndDate();
        $visio["<strong>End Prod date</strong>"] = $pedate;
        // $visio["<strong>Comments</strong>"] = $order->getComments();
        $visio["<strong>Sysprod Actor</strong>"] = $sysprodActors = $order->getSysprodActor();
        $visio["<strong>Release</strong>"] = $order->getRelease();
        $visio["<strong>CCT Snapshot</strong>"] = $order->getCCTSnaptshot();
        foreach ($order->getNetworks() as $name => $value) {
            $visio["<strong>Network</strong>"]["<strong>Name</strong>"] = $name;
            $visio["<strong>Network</strong>"]["<strong>IP address</strong>"] = $value[0];
            $visio["<strong>Network</strong>"]["<strong>Netmask</strong>"] = $value[1];
        }
        echo arr_to_html($visio);
        $visio = array();
        foreach ($order->getItems() as $itemTab => $val) {

            if ($val != NULL) {

                foreach ($val as $key => $value) {
                    foreach ($value as $value_key => $items) {
                        if (isset($items) || !empty($items)) {
                            switch ($value_key) {
                                case 'str_hostname':
                                    $name = 'Hostname';
                                    $item = $items;
                                    break;
                                case 'str_ip':
                                    $name = 'IP Address';
                                    $item = $items;
                                    break;
                                case 'str_comment':
                                case 'str_Comments':
                                    $name = 'Comment';
                                    $item = $items;
                                    break;
                                case 'str_servicesList':
                                case 'bool_defaultConfig':
                                    break;
                                case 'str_osversion':
                                case 'str_osVersion':
                                    $name = 'OS Version';
                                    $item = $items;
                                    break;
                                case 'int_modelID':
                                    if ($get_item = $db->getModelsFromDB($items)) {
                                        $name = "HW information";
                                        foreach ($get_item as $detail_item) {
                                            $arr_item = $detail_item;
                                            foreach ($arr_item as $key_item => $key_model) {
                                                switch ($key_item) {
                                                    case 'Description':
                                                        $item['<strong>Product Code</strong>'] = $key_model;
                                                        break;
                                                    case 'BrandName':
                                                        $item['<strong>Brand Name</strong>'] = $key_model;
                                                        break;
                                                    case 'Model':
                                                        $item['<strong>Model</strong>'] = $key_model;
                                                        break;
                                                }
                                            }
                                        }
                                    }
                                    break;
                                case 'int_apcGID':
                                case 'str_apccode':
                                    break;
                                default:
                                    $name = $value_key;
                                    $item = $items;
                                    break;
                            }
                            $visio["<strong>Order Components</strong>"]["<strong>Serial number: " . $key . "</strong>"]["<strong>" . $name . "</strong>"] = $item;
                        }
                    }
                }
            }
        }

        //	html_show_array($visio);
        echo arr_to_html($visio);
    }
} elseif (isset($_GET['serial'])) {
    $serial = $_GET['serial'];
   
    $results = $db->search_serial($serial);
    echo json_encode($results);
    
} else {
    echo "<p class='label label-alert'>No results</p>";
}

function arr_to_html($array, $recursive = true, $null = '&nbsp;') {
    // Sanity check
    if (empty($array) || !is_array($array)) {
        return false;
    }

    if (!isset($array[0]) || !is_array($array[0])) {
        $array = array($array);
    }

    // Start the table
    $table = "<table class='collection table table-bordered table-hover'>";

    // The header
    $table .= "<tr>";
    // Take the keys from the first row as the headings
    foreach (array_keys($array[0]) as $heading) {
        $table .= '<th>' . $heading . '</th>';
    }
    $table .= "</tr>";

    // The body
    foreach ($array as $row) {
        $table .= "<tr>";
        foreach ($row as $cell) {
            $table .= '<td>';

            // Cast objects
            if (is_object($cell)) {
                $cell = (array) $cell;
            }

            if ($recursive === true && is_array($cell) && !empty($cell)) {
                // Recursive mode
                ob_start();
                html_show_array($cell);
                $table .= "\n" . ob_get_clean() . "\n";
                ob_end_flush();
            } else {
                $table .= (strlen($cell) > 0) ?
                        htmlspecialchars((string) $cell) :
                        $null;
            }

            $table .= '</td>';
        }

        $table .= "</tr>\n";
    }

    $table .= '</table>';
    return $table;
}
?>
 