<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
session_start();
include('lib/conf_parser.php');
include('lib/notify.php');
require_once('lib/PHPMailer/class.phpmailer.php');

if (OS_TYPE == "WINDOWS") {
    define("APPLI_FOLDER_NAME_WINDOWS", '/' . APPLI_FOLDER_NAME);
}

require_once('include_path.php');
require_once('include_smarty.php');
require_once('include_classes.php');

$lbls_xml = new MyLangXMLParser(LANGUAGE);
$labels = $lbls_xml->getLabels();

foreach ($labels as $label) {
    $smarty->assign("LBL_" . $label->getView_id() . "_" . $label->getName(), $label->getValue());
}

$msgs = $lbls_xml->getMessages();
foreach ($msgs as $message) {
    define((string) $message->getName(), (string) $message->getValue());
    $smarty->assign((string) $message->getName(), (string) $message->getValue());
}

if (extension_loaded("snmp") === false) {
    if (OS_TYPE == "WINDOWS") {
        die(MSG_SNMP_NOT_INSTALLED_WINDOWS);
    } elseif (OS_TYPE == "UNIX") {
        die(MSG_SNMP_NOT_INSTALLED_UNIX);
    }
}

if (ENCRYPT_SAVED_CONFIGURATION_FILES) {
    if (extension_loaded("mcrypt") === false) {
        if (OS_TYPE == "WINDOWS") {
            die(MSG_MCRYPT_NOT_INSTALLED_WINDOWS);
        } elseif (OS_TYPE == "UNIX") {
            die(MSG_MCRYPT_NOT_INSTALLED_UNIX);
        }
    }
}


if (!DISPLAY_PHP_WARNINGS) {
    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
}

if (LOCAL_AUTHENTICATION == 1 && AD_ACTIVE == 1) {
    die(MSG_ONLY_ONE_AUTHENTICATION_TYPE_ALLOWED);
}

if (AD_ACTIVE == 1 && ! isset($_SESSION['login'])) {
    //require_once('ad.php');
    
    header('Location: login_form.php');
}

if (LOCAL_AUTHENTICATION == 1) {
    require_once('local_authentication.php');
}

$p = new MyXMLParser();
$mySwitchs = $p->getMySwitchs();
$all_groups = $p->getMyGroups();

$groups_of_switches = array();
if (isset($all_groups)) {
    foreach ($all_groups as $group) {
        $groups_of_switches[$group->getId()] = $group->getMySwitches();
    }
}

$oids = $p->getOids();
foreach ($oids as $oid) {
    define("OID_" . $oid->getName(), $oid->getValue());
}

$smarty->assign("mySwitchs", $mySwitchs);
$smarty->assign("allGroups", $all_groups);
$smarty->assign("groups_of_switches", $groups_of_switches);

$errors = null;

function getMacs($switchIP, $port_id) {

    //$macs = Fonctions::simpleSnmpGet($mySwitch->getIp(),OID_getMacTable);
    $macs = shell_exec("perl /usr/sbin/switchlabel.pl " . $switchIP . " public | grep -w 'port" . $port_id . "'");
    $results = explode("-", $macs);
    if (isset($results[0]) && $results[0] != NULL) {
        $arp = shell_exec("arp -a | grep -i " . trim($results[0] . " | awk -F '(' '{print $2}' | awk -F ')' '{print $1   }' "));
        $arp = ($arp == "" ) ? "IP not found." : $arp;
        echo "<span class='infoBox pull-left'><strong>MAC: </strong>" . $results[0] . "<br /><strong>IP:</strong> " . $arp . "</span>";
    } else {
        echo "<span class='infoBox pull-left'>No data querying arp table.</span>";
    }
}


?>
