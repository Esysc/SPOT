<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * EventcategoryMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the EventcategoryDAO to the eventCategory datastore.
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
class EventcategoryMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Eventcategory object
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
			$fm["Category"] = new FieldMap("Category","eventCategory","category",true,FM_TYPE_VARCHAR,50,null,false);
			$fm["Description"] = new FieldMap("Description","eventCategory","description",false,FM_TYPE_VARCHAR,50,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Eventcategory object
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
			$km["category"] = new KeyMap("category", "Category", "Notifications", "Eventcategory", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>