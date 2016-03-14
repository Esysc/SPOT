<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ProvisioningscriptsMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ProvisioningscriptsDAO to the provisioningScripts datastore.
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
class ProvisioningscriptsMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Provisioningscripts object
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
			$fm["Scriptid"] = new FieldMap("Scriptid","provisioningScripts","scriptID",true,FM_TYPE_INT,4,null,false);
			$fm["Scripttarget"] = new FieldMap("Scripttarget","provisioningScripts","scriptTarget",false,FM_TYPE_INT,4,null,false);
			$fm["Scriptname"] = new FieldMap("Scriptname","provisioningScripts","scriptName",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Scriptdescription"] = new FieldMap("Scriptdescription","provisioningScripts","scriptDescription",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Scriptcontent"] = new FieldMap("Scriptcontent","provisioningScripts","scriptContent",false,FM_TYPE_LONGBLOB,null,null,false);
			$fm["Interpreter"] = new FieldMap("Interpreter","provisioningScripts","interpreter",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["Version"] = new FieldMap("Version","provisioningScripts","version",false,FM_TYPE_VARCHAR,10,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Provisioningscripts object
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
			$km["scriptID"] = new KeyMap("scriptID", "Scriptid", "Remotecommands", "Scriptid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["scriptTarget"] = new KeyMap("scriptTarget", "Scripttarget", "Mediatype", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>