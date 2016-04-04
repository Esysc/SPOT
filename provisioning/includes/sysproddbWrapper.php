<?php

/*
 * This script retreive all sales order from sdysproddb and can filter in a table
 * 
 * It then echos a json object containing all the informarions
 */
require_once("config.php");
require_once("share.php");


$bgcolor['Packed'] = 66015;
$bgcolor['In Progress'] = 16711744;
$bgcolor['Validated'] = 16776960;
$bgcolor['Finished'] = 3858176;
$bgcolor['On Hold'] = 16776960;
$bgcolor['R28 - Delivered to SysProd'] = 16098560;
$bgcolor['P27 - Picked up'] = 66015;

function innerHTML($el) {
    $doc = new DOMDocument();
    $doc->appendChild($doc->importNode($el, TRUE));
    $html = trim($doc->saveHTML());
    $tag = $el->nodeName;
    return preg_replace('@^<' . $tag . '[^>]*>|</' . $tag . '>$@', '', $html);
}

// generic function to get the contents of an HTML block
function get_inner_html($node) {
    $innerHTML = '';
    if (!is_object($node))
        return false;
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
    }
    return $innerHTML;
}

function tdrowsHead($elements) {
    $row = array();
    foreach ($elements as $element) {

        $row[] = $element->nodeValue;
    }

    return $row;
}

function tdrows($elements, $header) {
    $row = array();
    $counter = 0;
    foreach ($elements as $key => $element) {
        $newk = $header[$key];
        $row[$newk] = $element->nodeValue;
    }

    return $row;
}

function getdata($html) {
    $contents = $html;
    $DOM = new DOMDocument;
    $DOM->loadHTML($contents);

    $items = $DOM->getElementsByTagName('tr');
    $result = array();
    $counter = 0;
    foreach ($items as $node) {

        //check if this is the header
        if ($counter == 0) {

            $header = tdrowsHead($node->childNodes);
        } else {
            $result[$counter] = tdrows($node->childNodes, $header);
        }
        $counter++;
    }
    return $result;
}

function getTable($SO) {
    $url = URL_WEBSYSPRODDB . "$SO";
    $data = curlGet($url);
    $doc = new DomDocument();
    @$doc->loadHTML($data);
    $doc->preserveWhiteSpace = false;
    $table = $doc->getElementById('orderItems');
//echo $result->nodeValue; //just for testing
    return $table;
}

function getHW($table, $SN) {

    $html = "<table>" . get_inner_html($table) . "</table>";
    $html_arr = getdata($html);
// performing the search.....
    $product = "";
    foreach ($html_arr as $key => $arr) {


        if (isset($arr['item sn'])) {
            if ($arr['item sn'] === $SN) {
                // echo $arr['item sn'];
                $product = $key;
                break;
            }
        }
    }
    $HW = "Not Found";
    if ($product !== "") {
        if (array_key_exists('product ref', $html_arr))
            $HW = $html_arr[$product]['product ref'];
    }
    return $HW;
}

/*
 * Get all orders from sharepoint
 */

$url = URL_SHAREPOINT;




$result = curlGet($url, true);




$xml = simplexml_load_string($result);

$share_value_arr = array();
foreach ($xml->channel->item as $Item) {
    // A part of title became the key for our array
    $tmp_key = explode('-', $Item->title);
    $arr_key = space($tmp_key[0]);
    $doc = new DOMDocument();
    $doc->loadHTML($Item->description);
    $ellies = $doc->getElementsByTagName('div');
    $tmp_link = $Item->link;
    $link = space($tmp_link[0]);
    // $link = $linkobj[0];
    // var_dump($link);
    $share_value_arr[$arr_key]['SharepointLink'] = $link;
    foreach ($ellies as $one_el) {
        if ($ih = get_inner_html($one_el)) {
            $arr_values = explode(':', strip_tags($ih));
            $subkey = space($arr_values[0]);
            $subitem = trim($arr_values[1]);
            $share_value_arr[$arr_key][$subkey] = $subitem;
        }
    }
}




$end = array();
//var_dump($temp);
foreach ($share_value_arr as $key => $subtemp) {
    $status = (array_key_exists('Status', $subtemp) ? $subtemp['Status'] : '');
    $packstatus = (array_key_exists('PackingStatus', $subtemp) ? $subtemp['PackingStatus'] : '' );
    $startshelf = '';
    $tmpRack = (array_key_exists('HWPlacement', $subtemp) ? $subtemp['HWPlacement'] : '');

    //echo "$packstatus";
    if (strpos($status, 'Packed') !== false || strpos($status, 'Progress') !== false || strpos($status, 'Hold') !== false || strpos($status, 'Finished') !== false) {

        // Get the values stocked in SPOT db and the values stocked in syslog DB
        $SO = $key;
        $SYSPRODDBItems = getTable($SO);
        $urlSPOT = SITE_URL . "/SPOT/provisioning/api/tblprogresses?Salesorder_Equals=$SO";

        $user = "admin";
        $pass = "***REMOVED***";
        $json = apiWrapper($urlSPOT, $user, $pass);

        $parsing = json_decode($json);

        $end[$key]['bgcolor'] = $bgcolor[$status];
        if ((strpos($status, 'Progress') !== false && strpos($packstatus, 'Delivered') !== false ) || strpos($packstatus, 'Picked') !== false )
            $end[$key]['bgcolor'] = $bgcolor[$packstatus];
        if ($parsing->totalResults != 0) {
            $data = $parsing->rows[0]->data;
            $data_decoded = json_decode($data);
            // $CustomerACR = " | $data_decoded->CustomerACR</br />";
            // $orderdescription = $data_decoded->orderdescription;
            
            $network = (isset($data_decoded->network) ? $data_decoded->network : '');

            $clients = (isset($data_decoded->clients) ? $data_decoded->clients : '');
            $client_table = "";
            if ((strpos($status, 'Progress') !== false || strpos($status, 'Finished') !== false || strpos($status, 'On Hold') !== false ) && strpos($packstatus, 'Picked') === false) {
                // echo $packstatus;

                $client_table = '<ul  class="rotation rotation-list">';
                $counter = 1;
                $total = count((array) $clients);
                foreach ($clients as $client) {
                    $rack = $client->rackname;
                    // get the serial number of the machine from dashboard
                    $NOTIFID = "[$SO][$rack]";
                    $urlDASH = SITE_URL . "/SPOT/provisioning/api/provisioningnotificationses?Notifid_Equals=$NOTIFID";
                    $jsonDASH = apiWrapper($urlDASH, $user, $pass);
                    $parsingDASH = json_decode($jsonDASH);
                    if ($parsingDASH->totalResults != 0) {
                        $serial = $parsingDASH->rows[0]->serial;
                        $HW = getHW($SYSPRODDBItems, $serial);

                        if (!isset($HW)) {
                            $HW = "Product code N/D";
                        }
                    } else {
                        $serial = "SN Not found.";
                    }

                    $client_table .= "<li><blockquote>"
                            . "<span class='badge badge-inverse'><center>Srv N.:  $counter of $total</center></span>"
                            . "<p class='badge'>" . $rack . "</p>"
                            . "<p class='badge'> " . $client->hostname . "</p>"
                            . "<p class='badge'> " . $client->ip . "</p>"
                            . "<p class='badge'> " . $serial . " </p>"
                            . "<p class='badge'>" . " " . $HW . " "
                            . "</p></blockquote></li>";
                    $counter++;
                }
                $client_table .= "</ul>";
            }
            if (strpos($status, 'Packed') !== false || strpos($packstatus, 'Picked') !== false) {
                $startshelf = "Transfered In R27";
                $stopshelf = "Transfered In R27";
            } else {

                $startshelf = $data_decoded->startshelf;
                $stopshelf = $data_decoded->stopshelf;
                if ($subtemp["#Racks"] != 0) {
                    $startshelf .= "<br />Rack assembling room";
                }
            }

            $releasename = "Release: $data_decoded->releasename<br />";
        } else {
            // $end[$key]['bgcolor'] = $bgcolor["Validated"];
            // $CustomerACR = "";
            // $orderdescription = "";
            $client_table = "";
            trim($tmpRack) !== '' && $startshelf === '' ? $startshelf = "Rack(s): $tmpRack" : $startshelf = "Not in SPOT DB";
            if (strpos($status, 'Packed') !== false) {

                $startshelf = "Transfered In R27";
                $stopshelf = "Transfered In R27";
            }

            $stopshelf = $startshelf;
            $network = "";
            $releasename = "";
        }
        // Build the value I want!
        $end[$key]["Sales Order Details"] = "Sales Order: $SO<br />";
        $end[$key]["Sales Order Details"] .= "Customer: " . $subtemp["Customer"] . "<br />";
        $end[$key]["Sales Order Details"] .= "$releasename";
        $end[$key]["Sales Order Details"] .= $subtemp["Orderdescription"] . "<br />";
        if (isset($network) && $network !== '')
            $end[$key]["Sales Order Details"] .= "Network: $network";


        isset($subtemp["SysProdActor"]) ? $end[$key]["User"] = $subtemp["SysProdActor"] : $end[$key]["User"] = "Logistics";
        if ( ! array_key_exists("PackingStatus", $subtemp)) $subtemp["PackingStatus"] = '';
        $end[$key]["Status"] = $subtemp["Status"] . "<br />" . $subtemp["PackingStatus"];
        isset($subtemp["RealStart"]) ? $START = $subtemp["RealStart"] : $START = $subtemp["PlannedStart"];
        isset($subtemp["RealEnd"]) ? $END = $subtemp["RealEnd"] : $END = $subtemp["PlannedEnd"];
        isset($subtemp["ExpShipment"]) ? $SHIP = "Exp. ship: " . $subtemp["ExpShipment"] : $SHIP = "";
        $end[$key]["Schedule"] = "Start: $START<br />End: $END<br />$SHIP";

        //$tmpRack = $subtemp['HWPlacement'];
        if (trim($startshelf) === '')
            $startshelf = "Rack(s): " . $tmpRack;
        if (trim($stopshelf) === '')
            $stopshelf = "Rack(s): " . $tmpRack;
        $end[$key]['Quantity'] = "Machines : " . $subtemp["#Machines"];
        $end[$key]['Quantity'] .= "<br />Racks : " . $subtemp["#Racks"];
        $end[$key]['From Rack'] = $startshelf;
        $end[$key]['To Rack'] = $stopshelf;
        $end[$key]['Hosts'] = $client_table;
        // $end[$key]['Network'] = $network;
    }
}
echo json_encode($end);
//echo $jsonSYSLOG;
?>
