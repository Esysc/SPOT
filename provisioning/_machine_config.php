<?php

/**
 * @package SPOT
 *
 * MACHINE-SPECIFIC CONFIGURATION SETTINGS
 *
 * The configuration settings in this file can be changed to suit the
 * machine on which the app is running (ex. local, staging or production).
 *
 * This file should not be added to version control, rather a template
 * file should be added instead and then copied for each install
 */
require_once 'verysimple/Phreeze/ConnectionSetting.php';
require_once("verysimple/HTTP/RequestUtil.php");



//DB config for custip inventory
//

/** database connection settings */
/*
 * The only method to have more than one DB par APP is to create an array of possible routes and apply a new connection string when have it in the URL
 * The following configuration is on two DB but with switch/case use  it can be extended to n DB
 */
$infra = array('tempdata', 'remotecommands');
$productiondb = array('tblorderses', 'tblorders', 'loadvisio');
$URL = RequestUtil::GetUrlParts();
GlobalConfig::$CONNECTION_SETTING = new ConnectionSetting();
GlobalConfig::$CONNECTION_SETTING->ConnectionString = "chx-raripam-01:3306";
GlobalConfig::$CONNECTION_SETTING->Username = "root";
GlobalConfig::$CONNECTION_SETTING->Password = "***REMOVED***";
GlobalConfig::$CONNECTION_SETTING->Type = "MySQL_PDO";
GlobalConfig::$CONNECTION_SETTING->Charset = "utf8";
GlobalConfig::$CONNECTION_SETTING->Multibyte = true;
GlobalConfig::$CONNECTION_SETTING->DBName = "spot";
if (!empty(array_intersect($custip, $URL))) {


    GlobalConfig::$CONNECTION_SETTING->DBName = "custip";
}
if (!empty(array_intersect($productiondb, $URL))) {
    /** database connection settings */
    GlobalConfig::$CONNECTION_SETTING->DBName = "drbl";
}
//Setting DB infos for users database production db
//
//
GlobalConfig::$CONNECTION_SETTING->ConnectionStringDRBL = "chx-sysprod-01.my.compnay.com";
GlobalConfig::$CONNECTION_SETTING->DBNameDRBL = "drbl";
GlobalConfig::$CONNECTION_SETTING->UsernameDRBL = "pdb";
GlobalConfig::$CONNECTION_SETTING->PasswordDRBL = "***REMOVED***";

// Prepare global connection for adodb
$connString = explode(":", GlobalConfig::$CONNECTION_SETTING->ConnectionString);
$adoDBconn = $connString[0];
define("CHARTPHP_DBTYPE", "mysqli"); // or mysqli
define("CHARTPHP_DBHOST", $adoDBconn);
define("CHARTPHP_DBUSER", GlobalConfig::$CONNECTION_SETTING->Username);
define("CHARTPHP_DBPASS", GlobalConfig::$CONNECTION_SETTING->Password);
define("CHARTPHP_DBNAME", GlobalConfig::$CONNECTION_SETTING->DBName);
GlobalConfig::$CONNECTION_SETTING->BootstrapSQL = "SET SQL_BIG_SELECTS=1";

/** the root url of the application with trailing slash, for example http://localhost/spot/ */
GlobalConfig::$ROOT_URL = RequestUtil::GetServerRootUrl() . 'SPOT/provisioning/';

/** timezone */
// date_default_timezone_set("UTC");

/** functions for php 5.2 compatibility */
if (!function_exists('lcfirst')) {

    function lcfirst($string) {
        return substr_replace($string, strtolower(substr($string, 0, 1)), 0, 1);
    }

}

// if Multibyte support is specified then we need to check if multibyte functions are available
// if you receive this error then either install multibyte extensions or set Multibyte to false
if (GlobalConfig::$CONNECTION_SETTING->Multibyte && !function_exists('mb_strlen'))
    die('<html>Multibyte extensions are not installed but Multibyte is set to true in _machine_config.php</html>');

/** level 2 cache */
// require_once('verysimple/Util/MemCacheProxy.php');
// GlobalConfig::$LEVEL_2_CACHE = new MemCacheProxy(array('localhost'=>'11211'));
// GlobalConfig::$LEVEL_2_CACHE_TEMP_PATH = sys_get_temp_dir();
// GlobalConfig::$LEVEL_2_CACHE_TIMEOUT = 5; // default is 5 seconds which will not be highly noticable to the user

/** additional machine-specific settings */
?>
