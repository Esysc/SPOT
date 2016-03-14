<?php
/** @package    OdsDb::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Customer_Ip_Inventory object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package OdsDb::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class Customer_Ip_InventoryReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `custip` table
	public $CustomFieldExample;

	public $Custipid;
	public $Subnet;
	public $Netmask;
	public $Account;
	public $Location;
	public $SystemName;
	public $Entt;
	public $RemoteAccess;
	public $Comments;
	public $Valdate;
	public $ValidatedBy;
	public $Lsmod;
	public $Status;

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
			,`custip`.`custipID` as Custipid
			,`custip`.`SUBNET` as Subnet
			,`custip`.`NETMASK` as Netmask
			,`custip`.`ACCOUNT` as Account
			,`custip`.`LOCATION` as Location
			,`custip`.`SYSTEM_NAME` as SystemName
			,`custip`.`ENTT` as Entt
			,`custip`.`Remote_Access` as RemoteAccess
			,`custip`.`COMMENTS` as Comments
			,`custip`.`valdate` as Valdate
			,`custip`.`Validated_By` as ValidatedBy
			,`custip`.`lsmod` as Lsmod
			,`custip`.`status` as Status
		from `custip`";

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
		$sql = "select count(1) as counter from `custip`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>