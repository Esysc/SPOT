<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ProvisioningactionMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ProvisioningactionDAO to the provisioningAction datastore.
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
class ProvisioningactionMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Provisioningaction object
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
			$fm["Actionid"] = new FieldMap("Actionid","provisioningAction","actionID",true,FM_TYPE_INT,11,null,true);
			$fm["Salesorder"] = new FieldMap("Salesorder","provisioningAction","salesOrder",false,FM_TYPE_TEXT,null,null,false);
			$fm["Codeapc"] = new FieldMap("Codeapc","provisioningAction","codeapc",false,FM_TYPE_TEXT,null,null,false);
			$fm["Rack"] = new FieldMap("Rack","provisioningAction","rack",false,FM_TYPE_TEXT,null,null,false);
			$fm["Shelf"] = new FieldMap("Shelf","provisioningAction","shelf",false,FM_TYPE_TEXT,null,null,false);
			$fm["Hostname"] = new FieldMap("Hostname","provisioningAction","hostname",false,FM_TYPE_TEXT,null,null,false);
			$fm["Timezone"] = new FieldMap("Timezone","provisioningAction","timeZone",false,FM_TYPE_TEXT,null,null,false);
			$fm["Posixtz"] = new FieldMap("Posixtz","provisioningAction","posixTz",false,FM_TYPE_TEXT,null,null,false);
			$fm["Wintz"] = new FieldMap("Wintz","provisioningAction","winTz",false,FM_TYPE_TEXT,null,null,false);
			$fm["Dststartday"] = new FieldMap("Dststartday","provisioningAction","dststartDay",false,FM_TYPE_TEXT,null,null,false);
			$fm["Dststopday"] = new FieldMap("Dststopday","provisioningAction","dststopDay",false,FM_TYPE_TEXT,null,null,false);
			$fm["Dststarth"] = new FieldMap("Dststarth","provisioningAction","dststartH",false,FM_TYPE_TEXT,null,null,false);
			$fm["Dststoph"] = new FieldMap("Dststoph","provisioningAction","dststopH",false,FM_TYPE_TEXT,null,null,false);
			$fm["Os"] = new FieldMap("Os","provisioningAction","OS",false,FM_TYPE_TEXT,null,null,false);
			$fm["Image"] = new FieldMap("Image","provisioningAction","image",false,FM_TYPE_TEXT,null,null,false);
			$fm["Boot"] = new FieldMap("Boot","provisioningAction","boot",false,FM_TYPE_TEXT,null,null,false);
			$fm["Ip"] = new FieldMap("Ip","provisioningAction","ip",false,FM_TYPE_TEXT,null,null,false);
			$fm["Netmask"] = new FieldMap("Netmask","provisioningAction","netmask",false,FM_TYPE_TEXT,null,null,false);
			$fm["Gateway"] = new FieldMap("Gateway","provisioningAction","gateway",false,FM_TYPE_TEXT,null,null,false);
			$fm["Iloip"] = new FieldMap("Iloip","provisioningAction","iloip",false,FM_TYPE_TEXT,null,null,false);
			$fm["Ilonm"] = new FieldMap("Ilonm","provisioningAction","ilonm",false,FM_TYPE_TEXT,null,null,false);
			$fm["Ilogw"] = new FieldMap("Ilogw","provisioningAction","ilogw",false,FM_TYPE_TEXT,null,null,false);
			$fm["Workgroup"] = new FieldMap("Workgroup","provisioningAction","workgroup",false,FM_TYPE_TEXT,null,null,false);
			$fm["Productkey"] = new FieldMap("Productkey","provisioningAction","productkey",false,FM_TYPE_TEXT,null,null,false);
                        $fm["Creationdate"] = new FieldMap("Creationdate","provisioningAction","creationdate",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Provisioningaction object
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