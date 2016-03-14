<?php
header('Access-Control-Allow-Origin: *');  
/*
 * This script retreive sales order item and set child and father
 * 
 * I
 */
require_once("config.php");
require_once("share.php");

function innerHTML($el) {
    $doc = new DOMDocument();
    $doc->appendChild($doc->importNode($el, TRUE));
    $html = trim($doc->saveHTML());
    $tag = $el->nodeName;
    return preg_replace('@^<' . $tag . '[^>]*>|</' . $tag . '>$@', '', $html);
}

function getElByclass($classname, $html) {
    $doc = new DOMDocument();
    $doc->loadHTML($html);
    
    $xpath = new DOMXpath($doc);
  $message = $xpath->query('//div[@class="'.$classname.'"]');
  foreach ($message as $item) {
      $text = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
      
  }
     
    
    return $text;
}

// generic function to get the contents of an HTML block
function get_inner_html($node) {
    $innerHTML = '';
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

function getHW($table, $order_item_id) {

    $html = "<table>" . get_inner_html($table) . "</table>";
    $html_arr = getdata($html);
// performing the search.....

    foreach ($html_arr as $key => $arr) {

        $product = "";

        if (isset($arr['order item id'])) {
            // This is the same function as for another app but == is for number here
            if ($arr['order item id'] == $order_item_id) {

                // echo $arr['item sn'];
                $product = $key;
                break;
            }
        }
    }
    $HW = array("name" => "Not Found", "product_ref" => "Not Found");
    if ($product !== "") {
        if ($html_arr[$product]['model name'] === '') $html_arr[$product]['model name'] = "BOM";
        $HW["name"] = $html_arr[$product]['product ref'] . " - " . $html_arr[$product]['model name'];
        $HW["product_ref"] = $html_arr[$product]['product ref'];
    }
    return $HW;
}

/*
 * START THE LOGIC
 */
/*
 * Analyze the request if any
 */

//$_SERVER['REQUEST_METHOD'] = 'GET';
//$_GET['salesorder'] = "11018528";

if (isset($_SERVER['REQUEST_METHOD'])) {

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            isset($_POST['salesorder']) ? $SO = $_POST['salesorder'] : exit;
            isset($_POST['product_ref']) ? $product_ref = $_POST['product_ref'] : exit; /* This is the product ref corresponding to the order item id , the child item not the father* */
            isset($_POST['linked_to_order_item_id']) ? $linked_to_order_item_id = $_POST['linked_to_order_item_id'] : exit;
            isset($_POST['order_item_id']) ? $order_item_id = $_POST['order_item_id'] : exit; /* this is the child */
            $postfields = "page=salesOrderDetails&action2=Update&order_item_id=$order_item_id&old_product_ref=$product_ref&linked_to_order_item_id=$linked_to_order_item_id";
            $url = URL_WEBSYSPRODDB . "/sales_order.php?page=salesOrderDetails&sales_order_ref=$SO";
            $result = curlPost($url, $postfields);
            //$message = jsonToHtml(json_decode($result, true));
            $classname="msgbox-error";
            
         $message = getElByclass($classname, $result);
         $classname = "msgbox-info";
         $message .= getElByclass($classname, $result);
            // echo $postfields;
         echo $message;
            break;
        case 'GET':
            isset($_GET['salesorder']) ? $SO = $_GET['salesorder'] : exit;
            //    $SO = $_GET['salesorder'];

            /*
             * request all the items
             */
            $url = URL_SYSPRODDB . "/GetOrderItems?sales_order_ref=$SO";
            $items = curlGet($url);

            $decoded = json_decode($items, true);

            if (!isset($decoded['error'])) {
                $SYSPRODDBItems = getTable($SO);
                foreach ($decoded as $key => $item) {
                    $order_item_id = $item['order_item_id'];
                    $HW = getHW($SYSPRODDBItems, $order_item_id);
                    $decoded[$key]['model_name'] = $HW["name"];
                    $decoded[$key]['product_ref'] = $HW["product_ref"];
                  //  $decoded[$key]['item_sn'] = $HW["item_sn"];
                }
                $encoded = json_encode($decoded);
                echo $encoded;
            } else {
                echo $decoded ['error']['details'];
            }




            break;
    }
}
?>
