<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Orders object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class OrdersReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `orders` table
	public $CustomFieldExample;

	public $Salesorder;
	public $Crmuid;
	public $Pgm;
	public $Ordertitle;
	public $Heacronym;
	public $Systemtype;
	public $Snapavail;
	public $Pstartdate;
	public $Penddate;
	public $Rstartdate;
	public $Renddate;
	public $Shippmentdate;
	public $Status;
	public $Polaroidexport;
	public $Userid;
	public $Commiteddate;
	public $Moveorder;
	public $Oracleorder;
	public $Comments;

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
			,`orders`.`salesOrder` as Salesorder
			,`orders`.`crmUID` as Crmuid
			,`orders`.`pgm` as Pgm
			,`orders`.`orderTitle` as Ordertitle
			,`orders`.`HEAcronym` as Heacronym
			,`orders`.`systemType` as Systemtype
			,`orders`.`snapAvail` as Snapavail
			,`orders`.`pStartDate` as Pstartdate
			,`orders`.`pEndDate` as Penddate
			,`orders`.`rStartDate` as Rstartdate
			,`orders`.`rEndDate` as Renddate
			,`orders`.`shippmentDate` as Shippmentdate
			,`orders`.`status` as Status
			,`orders`.`polaroidExport` as Polaroidexport
			,`orders`.`userID` as Userid
			,`orders`.`commitedDate` as Commiteddate
			,`orders`.`moveorder` as Moveorder
			,`orders`.`oracleorder` as Oracleorder
			,`orders`.`comments` as Comments
		from `orders`";

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
		$sql = "select count(1) as counter from `orders`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>