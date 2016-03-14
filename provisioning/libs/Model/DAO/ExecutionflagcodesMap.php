<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ExecutionflagcodesMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ExecutionflagcodesDAO to the executionFlagCodes datastore.
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
class ExecutionflagcodesMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Executionflagcodes object
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
			$fm["Executionflag"] = new FieldMap("Executionflag","executionFlagCodes","executionFlag",true,FM_TYPE_INT,4,null,false);
			$fm["Description"] = new FieldMap("Description","executionFlagCodes","description",false,FM_TYPE_VARCHAR,255,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Executionflagcodes object
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
			$km["fk_execFlag"] = new KeyMap("fk_execFlag", "Executionflag", "Remotecommands", "Executionflag", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>