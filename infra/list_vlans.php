<?php

/*
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

$start_time = microtime(true);
include_once("includes.php");


$switch_id = $_GET["switch_id"];
if (!isset($switch_id)) {
    die(MSG_WRONG_PARAMETERS);
}

$switch = MySwitch::retrieveById($switch_id);
if (isset($switch)) {
    try {
        $vlans = $switch->getVlans();
    } catch (Exception $e) {
        $errors[] = $e;
    }
} else {
    $errors[] = "Wrong switch id !";
}
//$port = Port::getPorts($switch_id, 1);

$rackname = $switch->getName();
$rackname = "Rack" . intval(preg_replace('/[^0-9]+/', '', $rackname), 10);
$rack['rackname'] = $rackname;
$rack['blank01'] = 7;
/* $rack['ShelfA - ports 1,11'] = 5;
  $rack['ShelfB - ports 2,12'] = 5;
  $rack['ShelfC - ports 3,13'] = 5;
  $rack['ShelfD - ports 4,14'] = 5;
  $rack['ShelfE - ports 5,15'] = 5;
  $rack['ShelfF - ports 6,16'] = 5;
  $rack['ShelfG - ports 7,17'] = 5; */
$datas = array();
foreach ($vlans as $vlan) {
    try {
        $ports[$vlan->getId()] = $switch->getAllPorts($vlan->getId());
        
        foreach ($ports[$vlan->getId()] as $port) {

            switch ($port->getId()) {
                case 1:
                case 11:
                    $shelf = "shelfA";

                    break;
                case 2:
                case 12:
                    $shelf = "shelfB";

                    break;
                    ;
                case 3:
                case 13:
                    $shelf = "shelfC";

                    break;
                    ;
                case 4:
                case 14:
                    $shelf = "shelfD";

                    break;
                    ;
                case 5:
                case 15:
                    $shelf = "shelfE";

                    break;
                    ;
                case 6:
                case 16:
                    $shelf = "shelfF";

                    break;
                    ;
                case 7:
                case 17:
                    $shelf = "shelfG";

                    break;
                    ;
                default:
                    $shelf = '';
                    break;
                    ;
            }
            if ($shelf !== '') {
                $status = $port->isUp() ? "UP" : "DOWN";

                $datas[$shelf] .= "p." . $port->getId() . "," . $status . " ";
            }
        }
        
        
    } catch (Exception $e) {
        $errors[] = $e;
    }
}

        foreach ($datas as $key => $value) {
            $rack[$key. "-" .$value] = 5;
        }

drawRack($rack, 'web/images/blankracks/42.png', $switch_id);

$smarty->assign('myRack', 'web/images/' . $rackname . '.png');

$smarty->assign("mySwitch", $switch);
$smarty->assign("vlans", $vlans);
$smarty->assign("ports", $ports);

$smarty->assign("errors", $errors);

$end_time = microtime(true);
$smarty->assign("exec_time", round($end_time - $start_time, 4));

$smarty->display("list_vlans.tpl");
?>
