<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * NetworksMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the NetworksDAO to the networks datastore.
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
class NetworksMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Networks object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","networks","salesOrder",true,FM_TYPE_INT,11,null,false);
			$fm["Name"] = new FieldMap("Name","networks","name",true,FM_TYPE_VARCHAR,50,null,false);
			$fm["Ip"] = new FieldMap("Ip","networks","ip",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Mask"] = new FieldMap("Mask","networks","mask",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Vlanno"] = new FieldMap("Vlanno","networks","vlanNo",false,FM_TYPE_INT,10,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Networks object
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
			$km["so"] = new KeyMap("so", "Salesorder", "Orders", "Salesorder", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>