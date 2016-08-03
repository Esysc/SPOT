<?php

/*
 * Suggest new subnet
 * ******************* */


/* functions */
require( dirname(__FILE__) . '/../../../functions/functions.php');

# initialize user object
$Database = new Database_PDO;
$User = new User($Database);
$Admin = new Admin($Database, false);
$Sections = new Sections($Database);
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Result = new Result ();

function loopSubnet($start_arr) {
    $start_arr[2] ++;
    if ($start_arr[2] == 256) {
        $start_arr[2] = 0;
        $start_arr[1] ++;
        if ($start_arr[1] == 256) {
            $start_arr[1] = 0;
            $start_arr[0] ++;
            if ($start_arr[0] == 256) {
                $start_arr[1] = 0;
                $start_arr[0] ++;
            }
        }
    }
    return $start_arr;
}

$sectionId = $_POST['sectionId'];
if ($_POST['subnet'] !== '') {
    $subnet = $_POST['subnet'];
    $post_subnet = explode("/", $_POST['subnet']);
    $start = $post_subnet[0];
} else {
    $start = "10.0.0.0";
    
}
$mask = 24;

$start_arr = explode(".", $start);

$check = false;

// works only for valid range

while ($check == false) {
    $tmp_value = $start_arr[0] . '.' . $start_arr[1] . '.' . $start_arr[2] . '.0/' . $mask;
    if ($Subnets->suggest_new_subnet($sectionId, $tmp_value) == false && $tmp_value !== $subnet) {
        $check = true;
        echo $tmp_value;
    } else {


        $start_arr = loopSubnet($start_arr);
    }
}

