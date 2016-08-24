<?php

/*
 * Suggest new IPV4 subnet
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
/* if ($_POST['subnet'] !== '') {
  $start = $_POST['subnet'];
  $subnet = $start."/24";

  } else {
  $start = "10.0.0.0";
  $start = "10.".mt_rand(0,255).".".mt_rand(0,255).".0";
  $subnet = $start."/24";
  } */

// Produce a random subnet
$first_octect = array(10,172,192);
$random_key = array_rand($first_octect);
$start = $first_octect[$random_key]."." . mt_rand(0, 255) . "." . mt_rand(0, 255) . ".0";
$subnet = $start . "/24";
$mask = 24;

$start_arr = explode(".", $start);

$check = false;

// works only for valid range

while ($check == false) {
    $tmp_value = $start_arr[0] . '.' . $start_arr[1] . '.' . $start_arr[2] . '.0/' . $mask;
    if ($Subnets->suggest_new_subnet($sectionId, $tmp_value) == false && $tmp_value !== $subnet) {
        $check = true;
        $tmp_value_array = explode('/', $tmp_value);
        echo $tmp_value_array[0];
    } else {


        $start_arr = loopSubnet($start_arr);
    }
}

