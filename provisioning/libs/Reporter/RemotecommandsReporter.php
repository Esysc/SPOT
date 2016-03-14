<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Remotecommands object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class RemotecommandsReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `remoteCommands` table
	public $CustomFieldExample;

	public $Remotecommandid;
	public $Salesorder;
	public $Rack;
	public $Shelf;
	public $Clientaddress;
	public $Arguments;
	public $Exesequence;
	public $Scriptid;
	public $Returncode;
	public $Returnstdout;
	public $Returnstderr;
	public $Executionflag;
	public $Logtime;
	public $Exectime;

	/*
	* GetCustomQuery returns a fully formed SQL statement.  The result columns
	* must match with the properties of this reporter object.
	*
	* @see Reporter::GetCustomQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomQuery($criteria)
	{
		$sql = "select
			'custom value here...' as CustomFieldExample
			,`remoteCommands`.`remoteCommandID` as Remotecommandid
			,`remoteCommands`.`salesOrder` as Salesorder
			,`remoteCommands`.`rack` as Rack
			,`remoteCommands`.`shelf` as Shelf
			,`remoteCommands`.`clientAddress` as Clientaddress
			,`remoteCommands`.`Arguments` as Arguments
			,`remoteCommands`.`exeSequence` as Exesequence
			,`remoteCommands`.`scriptID` as Scriptid
			,`remoteCommands`.`returnCode` as Returncode
			,`remoteCommands`.`returnStdout` as Returnstdout
			,`remoteCommands`.`returnStderr` as Returnstderr
			,`remoteCommands`.`executionFlag` as Executionflag
			,`remoteCommands`.`logTime` as Logtime
			,`remoteCommands`.`execTime` as Exectime
		from `remoteCommands`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();
		$sql .= $criteria->GetOrder();

		return $sql;
	}
	
	/*
	* GetCustomCountQuery returns a fully formed SQL statement that will count
	* the results.  This query must return the correct number of results that
	* GetCustomQuery would, given the same criteria
	*
	* @see Reporter::GetCustomCountQuery
	* @param Criteria $criteria
	* @return string SQL statement
	*/
	static function GetCustomCountQuery($criteria)
	{
		$sql = "select count(1) as counter from `remoteCommands`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>