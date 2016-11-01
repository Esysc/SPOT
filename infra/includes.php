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

if (AD_ACTIVE == 1 && !isset($_SESSION['login'])) {
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
        $arp = file_get_contents("http://spmgt.my.compnay.com/arp.php?mac=".trim($results[0]));
        if ($arp == "") {
            $arp = file_get_contents("http://spdrbl01.my.compnay.com/arp.php?mac=".trim($results[0]));
        }
        $arp = ($arp == "" ) ? "IP not found." : $arp;
        echo "<span class='infoBox pull-left'><strong>MAC: </strong>" . $results[0] . "<br /><strong>IP:</strong> " . $arp . "</span>";
    } else {
        echo "<span class='infoBox pull-left'>No data querying arp table.</span>";
    }
}

function getNet($switchIP, $port_id) {


    $macs = shell_exec("perl /usr/sbin/switchlabel.pl " . $switchIP . " public | grep -w 'port" . $port_id . "'");
    $results = explode("-", $macs);
    if (isset($results[0]) && $results[0] != NULL) {

        $arp = file_get_contents("http://spmgt.my.compnay.com/arp.php?mac=".trim($results[0]));
        if ($arp == "") {
            $arp = file_get_contents("http://spdrbl01.my.compnay.com/arp.php?mac=".trim($results[0]));
        }
        $arp = ($arp == "" ) ? "N/A" : trim($arp);
        return " IP: " . $arp;
    } else {
        return "No data";
    }
}

function drawRack($r, $racktemplate, $id_switch) {
    $switch = MySwitch::retrieveById($id_switch);


    $template = imagecreatefrompng($racktemplate);

    # Initial offset from the top left corner to begin placing 'servers'
    $dstX = 27;
    $dstY = 22;
    # place the procurve image

    foreach ($r as $name => $u) {

        # This defines the nameplate at the top of the rack
        if ($name == 'rackname') {
            $rackname = $u;
            $nameplate = imagecreate(150, 20);
            $background = imagecolorallocate($nameplate, 255, 255, 255);
            $text_colour = imagecolorallocate($nameplate, 0, 0, 0);
            imageCenterString($nameplate, 5, $u, $text_colour);
            imagecopy($template, $nameplate, 52, 1, 0, 0, 150, 20);
            $deviceimg = imagecreatefromjpeg("web/images/procurve.jpg");
            drawRackDevice($deviceimg, $u, 'right', $switch);
            imagecopy($template, $deviceimg, $dstX, 20, 0, 0, 200, 20);
            imagedestroy($deviceimg);
            continue;
        }

        $deviceimg = imagecreate(200, (20 * $u));
        drawRackDevice($deviceimg, $name, 'center', $switch);

        imagecopy($template, $deviceimg, $dstX, $dstY, 0, 0, 200, (20 * $u));

        $dstY += (20 * $u);
        imagedestroy($deviceimg);
    }

    # You could also save it to a file on disk
    //header( "Content-type: image/png" );
    unlink('web/images/' . $rackname . '.png');
    imagepng($template, 'web/images/' . $rackname . '.png');

    imagedestroy($template);
}

function drawRackDevice(&$img, $text, $pos = 'center', $switch = NULL) {
    $background = imagecolorallocate($img, 157, 158, 156);
    $text_colour = imagecolorallocate($img, 0, 0, 0);
    $line_colour = imagecolorallocate($img, 0, 0, 0);

    # If this should be a blank space.. make a transparent 'server' to fill the gap
    if ((strlen($text) == 0) or strstr($text, 'blank')) {
        imagecolortransparent($img, $background);
    } else {
        if ($pos === 'center') {
            $analyze = explode(' ', $text);
            foreach ($analyze as $key => $color) {
                if (strpos($color, 'UP')) {
                    $text_colour = imagecolorallocate($img, 0, 255, 0);
                    if ($switch != NULL) {
                        $switch_ip = $switch->getIp();
                        $port_id = intval(preg_replace('/[^0-9]+/', '', $color), 10);
                        $macs = getNet($switch_ip, $port_id);

                        $color .=$macs;
                    }
                } elseif (strpos($color, 'DOWN')) {
                    $text_colour = imagecolorallocate($img, 255, 0, 0);
                } else {
                    $text_colour = imagecolorallocate($img, 0, 0, 0);
                }
                $lines = explode('-',$color);
                foreach ($lines as $l => $line) {
                    imageCenterString($img, 3, $line, $text_colour, $key * 60 + ($l -1) * 30 );
                }
            }
        } else {
            if ($switch != NULL)
                $switchname = $switch->getName();
            $text = $switchname;
            $width = ceil(strlen($text) * 16);
            $x = imagesx($img) - $width - 8;
            $y = imagesy($img) - 16;
            imagestring($img, 5, $x, $y, $text, $text_color);
        }
        imageline($img, 0, 0, 200, 0, $line_colour);
        imageline($img, 0, imagesy($img) - 1, 200, imagesy($img) - 1, $line_colour);
    }
}

# Found in comments on the php docs site

function imageCenterString(&$img, $font, $text, $color, $y = 0) {
    if ($font < 0 || $font > 5) {
        $font = 0;
    }
    $num = array(array(4.6, 6),
        array(4.6, 6),
        array(5.6, 12),
        array(6.5, 12),
        array(7.6, 16),
        array(8.5, 16));
    $width = ceil(strlen($text) * $num[$font][0]);
    $x = imagesx($img) - $width -8;
    $y = imagesy($img) - ($num[$font][1] + 2) + $y;
    imagestring($img, $font, $x / 2, $y / 2, $text, $color);
}

?>
