<?php
/** @package    OdsDb::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * IP_valid_rangesMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the IP_valid_rangesDAO to the ip_ranges datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package OdsDb::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class IP_valid_rangesMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the IP_valid_ranges object
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
			$fm["Start"] = new FieldMap("Start","ip_ranges","ip_start",false,FM_TYPE_VARCHAR,15,null,false);
			$fm["End"] = new FieldMap("End","ip_ranges","ip_end",false,FM_TYPE_VARCHAR,15,null,false);
			$fm["Id"] = new FieldMap("Id","ip_ranges","ip_ID",true,FM_TYPE_INT,11,null,true);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the IP_valid_ranges object
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