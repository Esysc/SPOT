<?php
/** @package    OdsDb::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * Customer_Ip_InventoryMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the Customer_Ip_InventoryDAO to the custip datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package OdsDb::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class Customer_Ip_InventoryMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Customer_Ip_Inventory object
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
			$fm["Custipid"] = new FieldMap("Custipid","custip","custipID",true,FM_TYPE_INT,11,null,true);
			$fm["Subnet"] = new FieldMap("Subnet","custip","SUBNET",false,FM_TYPE_VARCHAR,55,null,false);
			$fm["Netmask"] = new FieldMap("Netmask","custip","NETMASK",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["Account"] = new FieldMap("Account","custip","ACCOUNT",false,FM_TYPE_VARCHAR,43,null,false);
			$fm["Location"] = new FieldMap("Location","custip","LOCATION",false,FM_TYPE_VARCHAR,80,null,false);
			$fm["SystemName"] = new FieldMap("SystemName","custip","SYSTEM_NAME",false,FM_TYPE_LONGTEXT,null,null,false);
			$fm["Entt"] = new FieldMap("Entt","custip","ENTT",false,FM_TYPE_VARCHAR,18,null,false);
			$fm["RemoteAccess"] = new FieldMap("RemoteAccess","custip","Remote_Access",false,FM_TYPE_VARCHAR,4,null,false);
			$fm["Comments"] = new FieldMap("Comments","custip","COMMENTS",false,FM_TYPE_LONGTEXT,null,null,false);
			$fm["Valdate"] = new FieldMap("Valdate","custip","valdate",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["ValidatedBy"] = new FieldMap("ValidatedBy","custip","Validated_By",false,FM_TYPE_VARCHAR,6,null,false);
			$fm["Lsmod"] = new FieldMap("Lsmod","custip","lsmod",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			$fm["Status"] = new FieldMap("Status","custip","status",false,FM_TYPE_VARCHAR,10,"active",false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Customer_Ip_Inventory object
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
