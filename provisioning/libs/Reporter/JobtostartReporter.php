<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Jobtostart object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class JobtostartReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `jobtostart` table
	public $CustomFieldExample;

	public $Scriptid;
	public $Salesorder;
	public $Rack;
	public $Shelf;
	public $Clientaddress;
	public $Arguments;
	public $Exesequence;
	public $Scripttarget;
	public $Scriptname;
	public $Scriptcontent;
	public $Interpreter;
	public $Version;
	public $Returncode;
	public $Returnstdout;
	public $Returnstderr;
	public $Executionflag;
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
			,`jobtostart`.`scriptID` as Scriptid
			,`jobtostart`.`salesOrder` as Salesorder
			,`jobtostart`.`rack` as Rack
			,`jobtostart`.`shelf` as Shelf
			,`jobtostart`.`clientAddress` as Clientaddress
			,`jobtostart`.`Arguments` as Arguments
			,`jobtostart`.`exeSequence` as Exesequence
			,`jobtostart`.`scriptTarget` as Scripttarget
			,`jobtostart`.`scriptName` as Scriptname
			,`jobtostart`.`scriptContent` as Scriptcontent
			,`jobtostart`.`interpreter` as Interpreter
			,`jobtostart`.`version` as Version
			,`jobtostart`.`returnCode` as Returncode
			,`jobtostart`.`returnStdout` as Returnstdout
			,`jobtostart`.`returnStderr` as Returnstderr
			,`jobtostart`.`executionFlag` as Executionflag
			,`jobtostart`.`execTime` as Exectime
		from `jobtostart`";

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
		$sql = "select count(1) as counter from `jobtostart`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>