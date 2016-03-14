<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * SysprodracksmappingMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the SysprodracksmappingDAO to the sysprodRacksMapping datastore.
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
class SysprodracksmappingMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Sysprodracksmapping object
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
			$fm["Rack"] = new FieldMap("Rack","sysprodRacksMapping","rack",false,FM_TYPE_INT,11,null,false);
			$fm["Shelf"] = new FieldMap("Shelf","sysprodRacksMapping","shelf",false,FM_TYPE_CHAR,50,null,false);
			$fm["Cycladesip"] = new FieldMap("Cycladesip","sysprodRacksMapping","cycladesIP",false,FM_TYPE_CHAR,50,null,false);
			$fm["Cycladesport"] = new FieldMap("Cycladesport","sysprodRacksMapping","cycladesPort",false,FM_TYPE_CHAR,50,null,false);
			$fm["Switchip"] = new FieldMap("Switchip","sysprodRacksMapping","switchIP",false,FM_TYPE_CHAR,50,null,false);
			$fm["Switchport"] = new FieldMap("Switchport","sysprodRacksMapping","switchPort",false,FM_TYPE_CHAR,50,null,false);
			$fm["Bootpip"] = new FieldMap("Bootpip","sysprodRacksMapping","bootpIP",false,FM_TYPE_CHAR,50,null,false);
			$fm["Clientid"] = new FieldMap("Clientid","sysprodRacksMapping","clientid",true,FM_TYPE_INT,5,null,true);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Sysprodracksmapping object
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