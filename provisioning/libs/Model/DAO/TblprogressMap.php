<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * TblprogressMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the TblprogressDAO to the tblProgress datastore.
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
class TblprogressMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Tblprogress object
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
			$fm["Id"] = new FieldMap("Id","tblProgress","ID",true,FM_TYPE_INT,11,null,true);
			$fm["User"] = new FieldMap("User","tblProgress","USER",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["Data"] = new FieldMap("Data","tblProgress","data",false,FM_TYPE_LONGTEXT,null,null,false);
			$fm["Salesorder"] = new FieldMap("Salesorder","tblProgress","salesOrder",false,FM_TYPE_INT,11,null,false);
			$fm["Creationdate"] = new FieldMap("Creationdate","tblProgress","creationDate",false,FM_TYPE_VARCHAR,25,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Tblprogress object
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