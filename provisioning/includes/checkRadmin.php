<?php

/**
 * Php script to check Radmin activation license directly on radmin web site
 */
require "share.php";

function parseDom($html) {
    $dom = new DomDocument();
    $dom->loadHTML($html, LIBXML_ERR_NONE);
    $idgreen = "green";
    $idred = "red";
    $xpath = new DOMXPath($dom);

    //$return = $xpath->query("//*[@id='" . $idgreen . "']");
    $filtered = $xpath->query('//h4[not(@class="f7note")]');
    $newDom = new DOMDocument;
    $newDom->formatOutput = true;
    if ($filtered->length > 0) {
        $i = 0;
    while( $myItem = $filtered->item($i++) ){
        $node = $newDom->importNode( $myItem, true );    // import node
        $newDom->appendChild($node);                    // append node
    }
    $html = $newDom->saveHTML();
    return $html;
    }
    // if here check if element error is present and return
    $return = $xpath->query("//*[@id='" . $idred . "']");

    if ($return->length > 0) {
        return "<h4 id='red'>".$return->item(0)->nodeValue."</h4>";
    }
    return false;
}


if (isset($_POST['lickey'])) {
    $lickey = $_POST['lickey'];

    // we need to do two request as the api supports only a request at a time
    $url = "https://www.radmin.com/support/activationscheck.php";
    $fields = array('lickey' => $lickey, 'keyactformsubmit' => 'Check');

//url-ify the data for the POST
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');



    $results = curlPost($url, $fields_string, false);
} else {
    return false;
}
echo parseDom($results);
?>
