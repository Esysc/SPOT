<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ProvisioningnotificationsMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ProvisioningnotificationsDAO to the provisioningNotifications datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningnotificationsMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Provisioningnotifications object
	 *
	 * @access public
	 * @return array of FieldMaps
	 */
	public static function GetFieldMaps()
	{
		static $fm = null;
		if ($fm == null)
		{
			$fm = Array();
			$fm["Notifid"] = new FieldMap("Notifid","provisioningNotifications","notifID",true,FM_TYPE_VARCHAR,100,null,false);
			$fm["Hostname"] = new FieldMap("Hostname","provisioningNotifications","hostname",false,FM_TYPE_TEXT,null,null,false);
			$fm["Installationip"] = new FieldMap("Installationip","provisioningNotifications","installationIP",false,FM_TYPE_VARCHAR,2000,null,false);
			$fm["Configuredip"] = new FieldMap("Configuredip","provisioningNotifications","configuredIP",false,FM_TYPE_TEXT,null,null,false);
			$fm["Startdate"] = new FieldMap("Startdate","provisioningNotifications","startDate",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			$fm["Status"] = new FieldMap("Status","provisioningNotifications","status",false,FM_TYPE_TEXT,null,null,false);
			$fm["Progress"] = new FieldMap("Progress","provisioningNotifications","progress",false,FM_TYPE_FLOAT,null,null,false);
			$fm["Image"] = new FieldMap("Image","provisioningNotifications","image",false,FM_TYPE_TEXT,null,null,false);
			$fm["Firmware"] = new FieldMap("Firmware","provisioningNotifications","firmware",false,FM_TYPE_TEXT,null,null,false);
			$fm["Ram"] = new FieldMap("Ram","provisioningNotifications","ram",false,FM_TYPE_TEXT,null,null,false);
			$fm["Cpu"] = new FieldMap("Cpu","provisioningNotifications","cpu",false,FM_TYPE_TEXT,null,null,false);
			$fm["Diskscount"] = new FieldMap("Diskscount","provisioningNotifications","disksCount",false,FM_TYPE_TEXT,null,null,false);
			$fm["Netintcount"] = new FieldMap("Netintcount","provisioningNotifications","NetIntCount",false,FM_TYPE_TEXT,null,null,false);
			$fm["Model"] = new FieldMap("Model","provisioningNotifications","model",false,FM_TYPE_TEXT,null,null,false);
			$fm["Serial"] = new FieldMap("Serial","provisioningNotifications","serial",false,FM_TYPE_TEXT,null,null,false);
			$fm["Os"] = new FieldMap("Os","provisioningNotifications","os",false,FM_TYPE_TEXT,null,null,false);
                        $fm["Update"] = new FieldMap("Update","provisioningNotifications","update",false,FM_TYPE_TIMESTAMP,null,null,false);

		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Provisioningnotifications object
	 *
	 * @access public
	 * @return array of KeyMaps
	 */
	public static function GetKeyMaps()
	{
		static $km = null;
		if ($km == null)
		{
			$km = Array();
		}
		return $km;
	}

}

?>