<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * DhcpbootpinvMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the DhcpbootpinvDAO to the dhcpBootpInv datastore.
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
class DhcpbootpinvMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Dhcpbootpinv object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","dhcpBootpInv","salesOrder",true,FM_TYPE_VARCHAR,15,null,false);
			$fm["Data"] = new FieldMap("Data","dhcpBootpInv","data",false,FM_TYPE_UNKNOWN,null,null,false);
			$fm["Status"] = new FieldMap("Status","dhcpBootpInv","status",false,FM_TYPE_TINYTEXT,null,null,false);
			$fm["Timestamps"] = new FieldMap("Timestamps","dhcpBootpInv","timeStamps",false,FM_TYPE_TIMESTAMP,null,"0000-00-00 00:00:00",false);
			$fm["Message"] = new FieldMap("Message","dhcpBootpInv","message",false,FM_TYPE_TEXT,null,null,false);
			$fm["Creator"] = new FieldMap("Creator","dhcpBootpInv","creator",false,FM_TYPE_INT,11,null,false);
			$fm["Dwprocessed"] = new FieldMap("Dwprocessed","dhcpBootpInv","dwprocessed",false,FM_TYPE_TINYINT,4,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Dhcpbootpinv object
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