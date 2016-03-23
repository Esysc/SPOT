<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * RemotecommandsMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the RemotecommandsDAO to the remoteCommands datastore.
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
class RemotecommandsMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Remotecommands object
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
			$fm["Remotecommandid"] = new FieldMap("Remotecommandid","remoteCommands","remoteCommandID",true,FM_TYPE_INT,15,null,true);
			$fm["Salesorder"] = new FieldMap("Salesorder","remoteCommands","salesOrder",false,FM_TYPE_INT,20,null,false);
			$fm["Rack"] = new FieldMap("Rack","remoteCommands","rack",false,FM_TYPE_INT,10,null,false);
			$fm["Shelf"] = new FieldMap("Shelf","remoteCommands","shelf",false,FM_TYPE_VARCHAR,20,null,false);
			$fm["Clientaddress"] = new FieldMap("Clientaddress","remoteCommands","clientAddress",false,FM_TYPE_VARCHAR,15,null,false);
			$fm["Arguments"] = new FieldMap("Arguments","remoteCommands","Arguments",false,FM_TYPE_VARCHAR,100000,null,false);
			$fm["Exesequence"] = new FieldMap("Exesequence","remoteCommands","exeSequence",false,FM_TYPE_INT,4,null,false);
			$fm["Scriptid"] = new FieldMap("Scriptid","remoteCommands","scriptID",false,FM_TYPE_INT,4,null,false);
			$fm["Returncode"] = new FieldMap("Returncode","remoteCommands","returnCode",false,FM_TYPE_INT,4,null,true);
			$fm["Returnstdout"] = new FieldMap("Returnstdout","remoteCommands","returnStdout",false,FM_TYPE_VARCHAR,10000000,null,true);
			$fm["Returnstderr"] = new FieldMap("Returnstderr","remoteCommands","returnStderr",false,FM_TYPE_VARCHAR,10000000,null,true);
			$fm["Executionflag"] = new FieldMap("Executionflag","remoteCommands","executionFlag",false,FM_TYPE_INT,4,null,true);
			$fm["Logtime"] = new FieldMap("Logtime","remoteCommands","logTime",true,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			$fm["Exectime"] = new FieldMap("Exectime","remoteCommands","execTime",false,FM_TYPE_VARCHAR,25,null,true);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Remotecommands object
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
			$km["fk_execFlag"] = new KeyMap("fk_execFlag", "Executionflag", "Executionflagcodes", "Executionflag", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
			$km["scriptID"] = new KeyMap("scriptID", "Scriptid", "Provisioningscripts", "Scriptid", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>
