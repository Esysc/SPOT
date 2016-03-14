<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the HwModels object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class HwModelsReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `HW_MODELS` table
	public $CustomFieldExample;

	public $Id;
	public $Brand;
	public $Model;
	public $Extendedinformation;
	public $Snregexppattern;
	public $Cpucount;
	public $Ramcount;
	public $Powerconsumption;
	public $Weight;
	public $Ru;
	public $Dimension;
	public $Diskscount;
	public $Powersourcecount;
	public $Price;
	public $Enabled;
	public $Installationid;
	public $NetReachable;

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
			,`HW_MODELS`.`id` as Id
			,`HW_MODELS`.`brand` as Brand
			,`HW_MODELS`.`model` as Model
			,`HW_MODELS`.`extendedInformation` as Extendedinformation
			,`HW_MODELS`.`snRegExpPattern` as Snregexppattern
			,`HW_MODELS`.`cpuCount` as Cpucount
			,`HW_MODELS`.`ramCount` as Ramcount
			,`HW_MODELS`.`powerConsumption` as Powerconsumption
			,`HW_MODELS`.`weight` as Weight
			,`HW_MODELS`.`ru` as Ru
			,`HW_MODELS`.`dimension` as Dimension
			,`HW_MODELS`.`disksCount` as Diskscount
			,`HW_MODELS`.`powerSourceCount` as Powersourcecount
			,`HW_MODELS`.`price` as Price
			,`HW_MODELS`.`enabled` as Enabled
			,`HW_MODELS`.`installationID` as Installationid
			,`HW_MODELS`.`net_reachable` as NetReachable
		from `HW_MODELS`";

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
		$sql = "select count(1) as counter from `HW_MODELS`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>