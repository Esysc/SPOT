<?php

/*
 * This script retreive all sales order from sdysproddb and can filter in a table
 * 
 * It then echos a json object containing all the informarions
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

// generic function to get the contents of an HTML block
function get_inner_html($node) {
    $innerHTML = '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
    }
    return $innerHTML;
}

$url = URL_SHAREPOINT;

$salesorder = $_GET['salesorder'];
//$salesorder = "11019955";


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


//echo json_encode($share_value_arr);
$CRMSystemUID=$share_value_arr[$salesorder]['CRMSystemUID'];
$result = array("crm_system_id" => $CRMSystemUID);
$json = json_encode($result);
echo $json;

//echo $code_output;
?>
