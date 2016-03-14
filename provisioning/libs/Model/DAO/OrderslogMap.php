<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * OrderslogMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the OrderslogDAO to the orderslog datastore.
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
class OrderslogMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Orderslog object
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
			$fm["Id"] = new FieldMap("Id","orderslog","id",true,FM_TYPE_INT,11,null,true);
			$fm["Salesorder"] = new FieldMap("Salesorder","orderslog","salesOrder",false,FM_TYPE_INT,11,null,false);
			$fm["Title"] = new FieldMap("Title","orderslog","title",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Text"] = new FieldMap("Text","orderslog","text",false,FM_TYPE_LONGTEXT,null,null,false);
			$fm["Userid"] = new FieldMap("Userid","orderslog","userid",false,FM_TYPE_INT,11,null,false);
			$fm["Date"] = new FieldMap("Date","orderslog","date",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Orderslog object
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
			$km["fk_userid"] = new KeyMap("fk_userid", "Userid", "Users", "UId", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>