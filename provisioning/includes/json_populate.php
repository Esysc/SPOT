<?php

$LIB_PATH = "1.0STD1";


require_once "$LIB_PATH/dbwrapper.php";
#require_once "$LIB_PATH/shlib/commonFunctions.php";
#require_once "$LIB_PATH/debug.php";



session_start();
$db = new dbwrapper('', '');
//      $db = unserialize($_SESSION["pdb10S1_dbw"]);

if (isset($_GET['term'])) {
    $serial = $_GET["term"];

    if ($serial != "") {
        $out = $db->get_serials("$serial%");
        if ($out == NULL) {
            echo -2;
        } else {
            header("Content-Type : application/json");
            echo json_encode($out);
        }
    } else
        echo -1;
}
else {
    echo "no terms received";
}
?>


