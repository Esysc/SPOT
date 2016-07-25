<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * This script is responsible to load session from jquery
 * ARGS: the sales order to load
 */
session_start();





if (isset($_POST['salesorder'])) {
    $salesorder = $_POST['salesorder'];
    $_SESSION['salesorder'] = $salesorder;
}
if (isset($_POST['datatoPdf'])) {
    $htmltable = $_POST['datatoPdf'];
    $_SESSION['datatoPdf'] = $htmltable;
}
if (isset($_POST['label'])) {
    $_SESSION['hostname'] = $_POST['hostname'];
    $_SESSION['ipaddress'] = $_POST['ipaddress'];
    $_SESSION['vlan'] = $_POST['vlan'];
    
}
if (isset($_POST['image64enc'])) {
    $base64img = $_POST['image64enc'];
    //clean dir before
    $mask = "*.png";
    array_map("unlink", glob("/var/www/SPOT/log/" . $mask));

    $filename = md5(time() . uniqid()) . ".png";
    $filename_path = "/var/www/SPOT/log/" . $filename;
    $exploded = explode(',', $base64img, 2);
    $encoded = $exploded[1]; // pick up the 2nd part
    $decoded = base64_decode($encoded);
    $im = imagecreatefromstring($decoded);
    imagepng($im, $filename_path);




    $_SESSION['imageOS'] = $filename;
} else {
    unset($_SESSION['imageOS']);
}
if (isset($_POST['data'])) {

    $data = $_POST['data'];

    $Jdata = json_decode($data, true);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
            break;
    }



    foreach ($Jdata as $key => $value) {

        $_SESSION[$key] = $value;
    }
    if (isset($_SESSION['csvclients'])) {
        $_SESSION['newclients'] = $_SESSION['csvclients'];
    }

//    var_dump($_SESSION);

    /*  $Jdata = trim($Jdata, "{");
      $Jdata = trim($Jdata, "}");


      $dataArr = explode(",", $Jdata);
      foreach ($dataArr as $value) {
      $setData = explode(":", $value);
      $key = trim($setData[0], "\"");
      $set = trim($setData[1], "\"");
      $_SESSION[$key] = $set;
      } */
}





