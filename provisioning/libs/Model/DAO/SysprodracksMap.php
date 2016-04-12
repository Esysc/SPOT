<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * SysprodracksMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the SysprodracksDAO to the sysprodracks datastore.
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
class SysprodracksMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Sysprodracks object
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
			$fm["Idracks"] = new FieldMap("Idracks","sysprodracks","idracks",true,FM_TYPE_CHAR,15,null,false);
			$fm["Reponse"] = new FieldMap("Reponse","sysprodracks","reponse",false,FM_TYPE_CHAR,50,null,false);
			$fm["Machinetype"] = new FieldMap("Machinetype","sysprodracks","machinetype",false,FM_TYPE_CHAR,50,null,false);
			$fm["Ipaddress"] = new FieldMap("Ipaddress","sysprodracks","ipaddress",false,FM_TYPE_CHAR,50,null,false);
			$fm["Timestamp"] = new FieldMap("Timestamp","sysprodracks","timestamp",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Sysprodracks object
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