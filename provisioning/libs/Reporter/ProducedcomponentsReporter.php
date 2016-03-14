<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Producedcomponents object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProducedcomponentsReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `producedComponents` table
	public $CustomFieldExample;

	public $Salesorder;
	public $Oralinenorev;
	public $Isbom;
	public $Id;
	public $Parentid;
	public $Verified;
	public $Serial;
	public $Code;
	public $Modelid;
	public $Functionid;
	public $Cfgver;
	public $Ip;
	public $Pimcount;
	public $Hostname;
	public $Cpucount;
	public $Ramcount;
	public $Ru;
	public $Position;
	public $Image;
	public $Ostype;
	public $Vm1;
	public $Vm2;
	public $Vm3;
	public $Vm4;
	public $Vm5;
	public $Vm6;

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
			,`producedComponents`.`salesOrder` as Salesorder
			,`producedComponents`.`oraLineNoRev` as Oralinenorev
			,`producedComponents`.`isBOM` as Isbom
			,`producedComponents`.`id` as Id
			,`producedComponents`.`parentID` as Parentid
			,`producedComponents`.`verified` as Verified
			,`producedComponents`.`serial` as Serial
			,`producedComponents`.`code` as Code
			,`producedComponents`.`modelID` as Modelid
			,`producedComponents`.`functionID` as Functionid
			,`producedComponents`.`cfgver` as Cfgver
			,`producedComponents`.`ip` as Ip
			,`producedComponents`.`pimCount` as Pimcount
			,`producedComponents`.`hostname` as Hostname
			,`producedComponents`.`cpuCount` as Cpucount
			,`producedComponents`.`ramCount` as Ramcount
			,`producedComponents`.`ru` as Ru
			,`producedComponents`.`position` as Position
			,`producedComponents`.`image` as Image
			,`producedComponents`.`ostype` as Ostype
			,`producedComponents`.`vm1` as Vm1
			,`producedComponents`.`vm2` as Vm2
			,`producedComponents`.`vm3` as Vm3
			,`producedComponents`.`vm4` as Vm4
			,`producedComponents`.`vm5` as Vm5
			,`producedComponents`.`vm6` as Vm6
		from `producedComponents`";

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
		$sql = "select count(1) as counter from `producedComponents`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>