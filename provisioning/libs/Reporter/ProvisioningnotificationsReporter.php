<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Provisioningnotifications object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningnotificationsReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `provisioningNotifications` table
	public $CustomFieldExample;

	public $Salesorder;
	public $Rack;
	public $Shelf;
	public $Hostname;
	public $Installationip;
	public $Configuredip;
	public $Startdate;
	public $Userid;
	public $Status;
	public $Progress;
	public $Image;
	public $Firmware;
	public $Ram;
	public $Cpu;
	public $Diskscount;
	public $Netintcount;
	public $Model;
	public $Serial;
	public $Os;

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
			,`provisioningNotifications`.`salesOrder` as Salesorder
			,`provisioningNotifications`.`rack` as Rack
			,`provisioningNotifications`.`shelf` as Shelf
			,`provisioningNotifications`.`hostname` as Hostname
			,`provisioningNotifications`.`installationIP` as Installationip
			,`provisioningNotifications`.`configuredIP` as Configuredip
			,`provisioningNotifications`.`startDate` as Startdate
			,`provisioningNotifications`.`userID` as Userid
			,`provisioningNotifications`.`status` as Status
			,`provisioningNotifications`.`progress` as Progress
			,`provisioningNotifications`.`image` as Image
			,`provisioningNotifications`.`firmware` as Firmware
			,`provisioningNotifications`.`ram` as Ram
			,`provisioningNotifications`.`cpu` as Cpu
			,`provisioningNotifications`.`disksCount` as Diskscount
			,`provisioningNotifications`.`NetIntCount` as Netintcount
			,`provisioningNotifications`.`model` as Model
			,`provisioningNotifications`.`serial` as Serial
			,`provisioningNotifications`.`os` as Os
		from `provisioningNotifications`";

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
		$sql = "select count(1) as counter from `provisioningNotifications`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>