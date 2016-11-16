<?php

/*
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

include_once("includes.php");
ini_set('display_errors', 1);
$port_id = $_POST["port_id"];
$switch_id = $_POST["switch_id"];
$source_vlan = $_POST["source_vlan"];
$dest_vlan = $_POST["dest_vlan"];

if (!isset($port_id) || !isset($switch_id) || !isset($source_vlan) || !isset($dest_vlan)) {
    die(MSG_WRONG_PARAMETERS);
}
$mySwitch = MySwitch::retrieveById($switch_id);
$port = Port::retreiveById($switch_id, $port_id, $source_vlan);

if ($port->isUntagged()) { // isUntagged in source vlan (the vlan on wihch the port number was displayed when the user clicked on it.
    if ($source_vlan != $dest_vlan) {
        try {
            $mySwitch->untagPort($port_id, $dest_vlan);
            $db = New SQLite3Database('web/db/conf.db');
            $db->connect();
            $db->insert('TRACE', array('SWITCH' => $mySwitch->getName()));
            
        } catch (Exception $e) {
            $errors[] = $e;
        }
        // next lines because snmpset set the port in the original VLAN as tagged... (and we don't want that)
        try {
            $mySwitch->removeTaggedFlagOfPortInVlan($source_vlan, $port_id);
        } catch (Exception $e) {
            $errors[] = $e;
        }
    }
} elseif ($port->isTagged()) {
    $vlanWhereThePortIsUntagged = $mySwitch->vlanWhereThePortIsUntagged($port_id); // The port can be untagged in another vlan than the source_vlan (source vlan : vlan in which the port was when the user clicked on it) and tagged anywhere else.
    if ($vlanWhereThePortIsUntagged != null && $vlanWhereThePortIsUntagged != $source_vlan) {
        try {
            $mySwitch->untagPort($port_id, $dest_vlan);
            $mySwitch->removeTaggedFlagOfPortInVlan($vlanWhereThePortIsUntagged->getId(), $port_id);
        } catch (Exception $e) {
            $errors[] = $e;
        }
    } else { // the port is tagged in source vlan and not untagged anywhere else.
        try {
            $mySwitch->addUntaggedFlagOfPortInVlan($source_vlan, $port_id);
            if ($source_vlan != $dest_vlan) {
                $mySwitch->untagPort($port_id, $dest_vlan);
            }
        } catch (Exception $e) {
            $errors[] = $e;
        }
    }
}


$smarty->assign("dest_vlan", $dest_vlan);
$smarty->assign("port_id", $port_id);
$smarty->assign("mySwitch", $mySwitch);

$smarty->assign("errors", $errors);
$smarty->display('untag_port_popup.tpl');
?>