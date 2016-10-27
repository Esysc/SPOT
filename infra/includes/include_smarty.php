<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

/* SMARTY Configuration */
require('lib'.SYSTEM_PATH_SEPARATOR.'smarty'.SYSTEM_PATH_SEPARATOR.'libs'.SYSTEM_PATH_SEPARATOR.'Smarty.class.php');

$smarty = new Smarty();

$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

if(LANGUAGE == "fr"){
	define("LN_DIR","fr");
} elseif (LANGUAGE == "en") {
	define("LN_DIR","en");
} else {
	define("LN_DIR","en");
}


$fp = fopen("VERSION", "r");
$version = fgets($fp, 4096);
fclose($fp);

//$smarty->debugging = true;

$smarty->setTemplateDir('views');
$smarty->setCompileDir('views'.SYSTEM_PATH_SEPARATOR.'smarty'.SYSTEM_PATH_SEPARATOR.'templates_c');
$smarty->setCacheDir('views'.SYSTEM_PATH_SEPARATOR.'smarty'.SYSTEM_PATH_SEPARATOR.'cache');
$smarty->setConfigDir('views'.SYSTEM_PATH_SEPARATOR.'smarty'.SYSTEM_PATH_SEPARATOR.'configs');

$smarty->force_compile = true;

$smarty->assign("VERSION",$version);
$smarty->assign("TITLE",TITLE);
$smarty->assign("FOOTER",FOOTER);
$smarty->assign("SUPPORT_ADDRESS",SUPPORT_ADDRESS);
$smarty->assign("SYSTEM_PATH_SEPARATOR",SYSTEM_PATH_SEPARATOR);

$smarty->assign("ENABLE_COPYRIGHT_BOX",ENABLE_COPYRIGHT_BOX);
$smarty->assign("ENABLE_FOOTER_SUPPORT_LINK",ENABLE_FOOTER_SUPPORT_LINK);
$smarty->assign("ALLOW_VLAN_CREATION",ALLOW_VLAN_CREATION);
$smarty->assign("ALLOW_VLAN_DELETION",ALLOW_VLAN_DELETION);
$smarty->assign("ALLOW_VLAN_EDITION",ALLOW_VLAN_EDITION);
$smarty->assign("ALLOW_PORT_TAGGING",ALLOW_PORT_TAGGING);
$smarty->assign("SET_DASHBOARD_AS_MAIN_PAGE",SET_DASHBOARD_AS_MAIN_PAGE);
$smarty->assign("DISPLAY_DASHBOARD",DISPLAY_DASHBOARD);
$smarty->assign("HIDE_DETAILS_BOX",HIDE_DETAILS_BOX);
$smarty->assign("DISABLE_DETAILS_BOX",DISABLE_DETAILS_BOX);
$smarty->assign("SHOW_SWITCH_IP_MAIN_MENU",SHOW_SWITCH_IP_MAIN_MENU);
$smarty->assign("USE_JTA_CONSOLE",USE_JTA_CONSOLE);
$smarty->assign("USE_MINDTERM_CONSOLE",USE_MINDTERM_CONSOLE);
$smarty->assign("ENABLE_CONFIGURATION_BACKUP_MANAGEMENT",ENABLE_CONFIGURATION_BACKUP_MANAGEMENT);
$smarty->assign("ENABLE_SWITH_CONFIGURATION_VIEW",ENABLE_SWITH_CONFIGURATION_VIEW);
$smarty->assign("ENABLE_SWITH_CONFIGURATION_EDITION",ENABLE_SWITH_CONFIGURATION_EDITION);
$smarty->assign("ENCRYPT_SAVED_CONFIGURATION_FILES",ENCRYPT_SAVED_CONFIGURATION_FILES);
$smarty->assign("SHOW_PORT_ALIASES_INSTEAD_OF_IDS", SHOW_PORT_ALIASES_INSTEAD_OF_IDS);
$smarty->assign("LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS",LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS);
$smarty->assign("APPLI_FOLDER_NAME",APPLI_FOLDER_NAME);

