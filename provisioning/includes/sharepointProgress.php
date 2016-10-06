<?php

/*
 * This script connect to sharepoint and filter orders "In Progress"
 * It can also connect to IST to download releases information if the ID is entered in sharepoint
 * It then echos a json object containing all the informarions
 */
require_once("config.php");
require_once("share.php");

function release_find($rel_id) {

    $url_rel = URL_REL . trim($rel_id);
    $results = curlGet($url_rel);
    if ($results) {
        $doc = new DomDocument;
        $doc->strictErrorChecking = FALSE;
        libxml_use_internal_errors(true);
        $doc->loadHTML($results);

        $div = $doc->getElementById('Delivery_inspect_deliverableRelease_container');
        if (!$div) {
            $url_rel = URL_SOL . trim($rel_id);
            $results = curlGet($url_rel);
            if ($results) {
                $doc = new DomDocument;
                $doc->strictErrorChecking = FALSE;
                libxml_use_internal_errors(true);
                $doc->loadHTML($results);

                $div = $doc->getElementById('SolutionRelease_inspect_pmsName_container');
                if (!$div) {
                    return false;
                }
            }
        }
        if ($div) {
            $ret = strip_tags($doc->saveXML($div));
            $ret_arr = explode(' ', $ret);
            $count_arr = count($ret_arr);
            $elem = $count_arr - 1;
            $release = preg_replace("/[^A-Za-z0-9_.]/", "", $ret_arr[$elem]);
            if ($release !== '') {
                return $release;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

function HErelease($rel_id) {
    // $cookie_file_path = "/tmp/share_cookie.txt";
    $url_rel = URL_REL . trim($rel_id);
    /* $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url_rel);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_USERPWD, 'sysprod:***REMOVED***');
      curl_setopt($ch, CURLOPT_AUTOREFERER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


      $chresult = curl_exec($ch);
      $chapierr = curl_errno($ch);
      $cherrmsg = curl_error($ch);

      curl_close($ch); */

    $results = curlGet($url_rel);



    $xml = simplexml_load_string($results) or die("Error: Cannot create object");
    $release = $xml->HECustomerProductRelease->composedName;

    return $release;
}

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

$sharepoint = array();
foreach ($share_value_arr as $key => $value) {
//	echo "STATUS: ".$share_value_arr[$key]['Status'];
//    if ($share_value_arr[$key]['Status'] === 'In Progress' || $share_value_arr[$key]['Status'] === 'In Progress*') {
   
	if (stristr($share_value_arr[$key]['Status'], "progress") !== false ) { 
      $temp['SharepointLink'] = $value['SharepointLink'];
        $temp['Status'] = $value['Status'];
        $temp['SalesOrder'] = $value['SalesOrder'];
        $temp['Customer'] = $value['Customer'];
        $temp['Machines'] = $value['#Machines'] . " machines and " . $value['#Racks'] . " racks.";
        $temp['ProgramManager'] = $value['ProgramManager'];
        $temp['PlannedStart'] = $value['PlannedStart'];
        $temp['PlannedEnd'] = $value['PlannedEnd'];
        $temp['User'] = $value['SysProdActor'];
        $temp['OrderDescription'] = $value['Orderdescription'];
        $HErel = '';
        $MSrel = '';
         if(isset($value['HEReleaseID'])) {
        $HEid = $value['HEReleaseID'];

        $HErel = release_find($HEid);
         } 

        if(isset($value['MSReleaseID'])) {
        $MSid = $value['MSReleaseID'];
        $MSrel = release_find($MSid);
        }
        if (!empty($HErel)) {
            $value['RELEASE'] = "$HErel";
        } else {
            $value['RELEASE'] = "";
        }
        if ($value['RELEASE'] !== '' && (!empty($MSrel)))
            $value['RELEASE'] = $value['RELEASE'] . "," . $MSrel;
        if ($value['RELEASE'] === '' && (!empty($MSrel)))
            $value['RELEASE'] = $MSrel;
        $temp['Release'] = $value['RELEASE'];
        $sharepoint[$key] = json_encode($temp);
    }
}





$jsonenc = json_encode($sharepoint);
echo $jsonenc;
?>
