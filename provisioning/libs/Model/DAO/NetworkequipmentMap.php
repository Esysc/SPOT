<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * NetworkequipmentMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the NetworkequipmentDAO to the networkEquipment datastore.
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
class NetworkequipmentMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Networkequipment object
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
			$fm["EquipId"] = new FieldMap("EquipId","networkEquipment","equip_id",true,FM_TYPE_INT,11,null,true);
			$fm["EquipModel"] = new FieldMap("EquipModel","networkEquipment","equip_model",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Method"] = new FieldMap("Method","networkEquipment","method",false,FM_TYPE_INT,11,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Networkequipment object
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
			$km["Method"] = new KeyMap("Method", "Method", "Mediatype", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		
                       
		
                }
		return $km;
	}

}

?>