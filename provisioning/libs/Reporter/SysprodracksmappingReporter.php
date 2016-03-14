<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Sysprodracksmapping object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class SysprodracksmappingReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `sysprodRacksMapping` table
	public $CustomFieldExample;

	public $Rack;
	public $Shelf;
	public $Cycladesip;
	public $Cycladesport;
	public $Switchip;
	public $Switchport;
	public $Bootpip;
	public $Clientid;

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
			,`sysprodRacksMapping`.`rack` as Rack
			,`sysprodRacksMapping`.`shelf` as Shelf
			,`sysprodRacksMapping`.`cycladesIP` as Cycladesip
			,`sysprodRacksMapping`.`cycladesPort` as Cycladesport
			,`sysprodRacksMapping`.`switchIP` as Switchip
			,`sysprodRacksMapping`.`switchPort` as Switchport
			,`sysprodRacksMapping`.`bootpIP` as Bootpip
			,`sysprodRacksMapping`.`clientid` as Clientid
		from `sysprodRacksMapping`";

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
		$sql = "select count(1) as counter from `sysprodRacksMapping`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>