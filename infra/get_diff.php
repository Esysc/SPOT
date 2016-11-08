<?php

/* ACS 2016.
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

include_once("includes.php");

if (isset($_GET["switch_ip"])) {

    $switch_ip = $_GET["switch_ip"];
} else {
    die("ERREUR : Parametre(s) incorrect(s)");
}



$diff = shell_exec("sudo su - rancid /var/lib/rancid/rancidDiff $switch_ip &");
if (trim($diff) !== '') {
    $TABLE = "<table class='table table-responsive table-striped' style='width:100%;' id='$switch_ip'><tr><th colspan='2'>"
            . "<center><i class='fa fa-warning faa-flash animated '></center></i></th></tr><tr><th>Startup config</th><th>Running config</th></tr><tr>";
    $diffArr = explode('\n', $diff);
    foreach ($diffArr as $val) {
        $line = explode('|', $val);
        $TDRUN = $line[0];
        $TDSTART = $line[1];
        
    }
    $TABLE .= "<td>$TDSTART</td><td>$TDRUN</td></tr></table>";

    echo $TABLE;
}
?>