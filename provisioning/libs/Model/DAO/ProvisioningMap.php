<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * ProvisioningMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ProvisioningDAO to the provisioning datastore.
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
class ProvisioningMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Provisioning object
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
			$fm["Provisioningid"] = new FieldMap("Provisioningid","provisioning","provisioningID",true,FM_TYPE_INT,11,null,false);
			$fm["Salesorder"] = new FieldMap("Salesorder","provisioning","salesOrder",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Rack"] = new FieldMap("Rack","provisioning","rack",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Shelf"] = new FieldMap("Shelf","provisioning","shelf",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Clientaddress"] = new FieldMap("Clientaddress","provisioning","clientAddress",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Arguments"] = new FieldMap("Arguments","provisioning","Arguments",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Exesequence"] = new FieldMap("Exesequence","provisioning","exeSequence",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scriptid"] = new FieldMap("Scriptid","provisioning","scriptID",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returncode"] = new FieldMap("Returncode","provisioning","returnCode",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returnstdout"] = new FieldMap("Returnstdout","provisioning","returnStdout",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returnstderr"] = new FieldMap("Returnstderr","provisioning","returnStderr",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Executionflag"] = new FieldMap("Executionflag","provisioning","executionFlag",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Logtime"] = new FieldMap("Logtime","provisioning","logTime",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Exectime"] = new FieldMap("Exectime","provisioning","execTime",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scriptname"] = new FieldMap("Scriptname","provisioning","scriptName",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scriptcontent"] = new FieldMap("Scriptcontent","provisioning","scriptContent",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Remotecommandid"] = new FieldMap("Remotecommandid","provisioning","remoteCommandID",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Interpreter"] = new FieldMap("Interpreter","provisioning","interpreter",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Version"] = new FieldMap("Version","provisioning","version",false,FM_TYPE_TINYINT,4,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Provisioning object
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