<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ConfigtemplateMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ConfigtemplateDAO to the configTemplate datastore.
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
class ConfigtemplateMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Configtemplate object
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
			$fm["VersionId"] = new FieldMap("VersionId","configTemplate","version_id",true,FM_TYPE_VARCHAR,30,null,false);
			$fm["ConfigTarget"] = new FieldMap("ConfigTarget","configTemplate","config_target",false,FM_TYPE_INT,11,null,false);
			$fm["ConfigTemplate"] = new FieldMap("ConfigTemplate","configTemplate","config_template",false,FM_TYPE_BLOB,null,null,false);
                        $fm["TimeStamp"] = new FieldMap("TimeStamp","configTemplate","time_stamp",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);



		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Configtemplate object
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