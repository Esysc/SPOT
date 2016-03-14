<?php
/** @package    OdsDb::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * HotlineSyncDateMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the HotlineSyncDateDAO to the hotline_sync_date datastore.
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
class HotlineSyncDateMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the HotlineSyncDate object
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
			$fm["Id"] = new FieldMap("Id","hotline_sync_date","id",true,FM_TYPE_INT,3,null,true);
			$fm["LastSyncDate"] = new FieldMap("LastSyncDate","hotline_sync_date","last_sync_date",false,FM_TYPE_DATE,null,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the HotlineSyncDate object
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