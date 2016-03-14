<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * TblPasswordMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the TblPasswordDAO to the tbl_password datastore.
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
class TblPasswordMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the TblPassword object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","tbl_password","salesorder",true,FM_TYPE_VARCHAR,20,null,false);
			$fm["Results"] = new FieldMap("Results","tbl_password","results",false,FM_TYPE_LONGTEXT,null,null,false);
			$fm["Time"] = new FieldMap("Time","tbl_password","time",false,FM_TYPE_TIMESTAMP,null,"0000-00-00 00:00:00",false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the TblPassword object
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
