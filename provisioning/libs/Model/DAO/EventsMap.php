<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * EventsMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the EventsDAO to the events datastore.
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
class EventsMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Events object
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
			$fm["Id"] = new FieldMap("Id","events","id",true,FM_TYPE_INT,11,null,true);
			$fm["Title"] = new FieldMap("Title","events","title",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Content"] = new FieldMap("Content","events","content",false,FM_TYPE_LONGTEXT,1000,null,false);
			$fm["Userid"] = new FieldMap("Userid","events","userID",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Date"] = new FieldMap("Date","events","date",false,FM_TYPE_VARCHAR,20,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Events object
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