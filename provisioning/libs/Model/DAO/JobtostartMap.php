<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * JobtostartMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the JobtostartDAO to the jobtostart datastore.
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
class JobtostartMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Jobtostart object
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
			$fm["Scriptid"] = new FieldMap("Scriptid","jobtostart","scriptID",true,FM_TYPE_TINYINT,4,null,false);
			$fm["Salesorder"] = new FieldMap("Salesorder","jobtostart","salesOrder",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Rack"] = new FieldMap("Rack","jobtostart","rack",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Shelf"] = new FieldMap("Shelf","jobtostart","shelf",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Clientaddress"] = new FieldMap("Clientaddress","jobtostart","clientAddress",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Arguments"] = new FieldMap("Arguments","jobtostart","Arguments",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Exesequence"] = new FieldMap("Exesequence","jobtostart","exeSequence",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scripttarget"] = new FieldMap("Scripttarget","jobtostart","scriptTarget",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scriptname"] = new FieldMap("Scriptname","jobtostart","scriptName",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Scriptcontent"] = new FieldMap("Scriptcontent","jobtostart","scriptContent",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Interpreter"] = new FieldMap("Interpreter","jobtostart","interpreter",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Version"] = new FieldMap("Version","jobtostart","version",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returncode"] = new FieldMap("Returncode","jobtostart","returnCode",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returnstdout"] = new FieldMap("Returnstdout","jobtostart","returnStdout",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Returnstderr"] = new FieldMap("Returnstderr","jobtostart","returnStderr",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Executionflag"] = new FieldMap("Executionflag","jobtostart","executionFlag",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Exectime"] = new FieldMap("Exectime","jobtostart","execTime",false,FM_TYPE_TINYINT,4,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Jobtostart object
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