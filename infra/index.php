<?php


/*
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

include_once("includes.php");
$smarty->assign('USER', $_SESSION['login']);
if (SET_DASHBOARD_AS_MAIN_PAGE) {
    include_once("dashboard.php");
} else {
    $smarty->display("index.tpl");
}
?>