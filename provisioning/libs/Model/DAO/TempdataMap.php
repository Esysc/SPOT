<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * TempdataMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the TempdataDAO to the tempData datastore.
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
class TempdataMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Tempdata object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","tempData","salesOrder",true,FM_TYPE_VARCHAR,15,null,false);
			$fm["Data"] = new FieldMap("Data","tempData","data",false,FM_TYPE_UNKNOWN,null,null,false);
			$fm["Status"] = new FieldMap("Status","tempData","status",false,FM_TYPE_TINYTEXT,null,null,false);
			$fm["Timestamps"] = new FieldMap("Timestamps","tempData","timeStamps",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			$fm["Message"] = new FieldMap("Message","tempData","message",false,FM_TYPE_TEXT,null,null,false);
			$fm["Creator"] = new FieldMap("Creator","tempData","creator",false,FM_TYPE_INT,11,null,false);
			$fm["Dwprocessed"] = new FieldMap("Dwprocessed","tempData","dwprocessed",false,FM_TYPE_TINYINT,4,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Tempdata object
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
			$km["FK__users"] = new KeyMap("FK__users", "Creator", "Users", "UId", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>