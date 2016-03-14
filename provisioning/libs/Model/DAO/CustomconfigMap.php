<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * CustomconfigMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the CustomconfigDAO to the customConfig datastore.
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
class CustomconfigMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Customconfig object
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
			$fm["ConfigId"] = new FieldMap("ConfigId","customConfig","config_id",true,FM_TYPE_INT,11,null,true);
			$fm["Salesorder"] = new FieldMap("Salesorder","customConfig","salesOrder",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["ConfigTarget"] = new FieldMap("ConfigTarget","customConfig","config_target",false,FM_TYPE_INT,11,null,false);
			$fm["ConfigContent"] = new FieldMap("ConfigContent","customConfig","config_content",false,FM_TYPE_BLOB,null,null,false);
                        $fm["TimeStamp"] = new FieldMap("TimeStamp","customConfig","time_stamp",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);

		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Customconfig object
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
			$km["customConfig_ibfk_1"] = new KeyMap("customConfig_ibfk_1", "ConfigTarget", "Networkequipment", "EquipId", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>