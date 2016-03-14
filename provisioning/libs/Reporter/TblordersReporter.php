<?php
/** @package    Drbl::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Tblorders object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Drbl::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class TblordersReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `tblOrders` table
	public $CustomFieldExample;

	public $Salesorder;
	public $Programmanager;
	public $Siteengineer;
	public $Sysprodactor;
	public $Release;
	public $Comment;
	public $Startdate;
	public $Enddate;
	public $Prodstartdate;
	public $Prodenddate;
	public $Customer;
	public $Timezone;
	public $Cctsnapshotpath;
	public $Sid;
	public $Customersigle;
	public $Exported;

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
			,`tblOrders`.`Salesorder` as Salesorder
			,`tblOrders`.`ProgramManager` as Programmanager
			,`tblOrders`.`SiteEngineer` as Siteengineer
			,`tblOrders`.`SysprodActor` as Sysprodactor
			,`tblOrders`.`Release` as Release
			,`tblOrders`.`comment` as Comment
			,`tblOrders`.`StartDate` as Startdate
			,`tblOrders`.`EndDate` as Enddate
			,`tblOrders`.`prodStartDate` as Prodstartdate
			,`tblOrders`.`prodEndDate` as Prodenddate
			,`tblOrders`.`Customer` as Customer
			,`tblOrders`.`Timezone` as Timezone
			,`tblOrders`.`CCTSnapshotPath` as Cctsnapshotpath
			,`tblOrders`.`SID` as Sid
			,`tblOrders`.`customerSigle` as Customersigle
			,`tblOrders`.`exported` as Exported
		from `tblOrders`";

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
		$sql = "select count(1) as counter from `tblOrders`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>