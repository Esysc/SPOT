<?php
/** @package    Spot::Reporter */

/** import supporting libraries */
require_once("verysimple/Phreeze/Reporter.php");

/**
 * This is an example Reporter based on the Provisioningaction object.  The reporter object
 * allows you to run arbitrary queries that return data which may or may not fith within
 * the data access API.  This can include aggregate data or subsets of data.
 *
 * Note that Reporters are read-only and cannot be used for saving data.
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProvisioningactionReporter extends Reporter
{

	// the properties in this class must match the columns returned by GetCustomQuery().
	// 'CustomFieldExample' is an example that is not part of the `provisioningAction` table
	public $CustomFieldExample;

	public $Actionid;
	public $Salesorder;
	public $Codeapc;
	public $Rack;
	public $Shelf;
	public $Hostname;
	public $Timezone;
	public $Posixtz;
	public $Wintz;
	public $Dststartday;
	public $Dststopday;
	public $Dststarth;
	public $Dststoph;
	public $Os;
	public $Image;
	public $Boot;
	public $Ip;
	public $Netmask;
	public $Gateway;
	public $Iloip;
	public $Ilonm;
	public $Ilogw;
	public $Workgroup;
	public $Productkey;
        public $Creationdate;

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
			,`provisioningAction`.`actionID` as Actionid
			,`provisioningAction`.`salesOrder` as Salesorder
			,`provisioningAction`.`codeapc` as Codeapc
			,`provisioningAction`.`rack` as Rack
			,`provisioningAction`.`shelf` as Shelf
			,`provisioningAction`.`hostname` as Hostname
			,`provisioningAction`.`timeZone` as Timezone
			,`provisioningAction`.`posixTz` as Posixtz
			,`provisioningAction`.`winTz` as Wintz
			,`provisioningAction`.`dststartDay` as Dststartday
			,`provisioningAction`.`dststopDay` as Dststopday
			,`provisioningAction`.`dststartH` as Dststarth
			,`provisioningAction`.`dststopH` as Dststoph
			,`provisioningAction`.`OS` as Os
			,`provisioningAction`.`image` as Image
			,`provisioningAction`.`boot` as Boot
			,`provisioningAction`.`ip` as Ip
			,`provisioningAction`.`netmask` as Netmask
			,`provisioningAction`.`gateway` as Gateway
			,`provisioningAction`.`iloip` as Iloip
			,`provisioningAction`.`ilonm` as Ilonm
			,`provisioningAction`.`ilogw` as Ilogw
			,`provisioningAction`.`workgroup` as Workgroup
			,`provisioningAction`.`productkey` as Productkey
		from `provisioningAction`";

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
		$sql = "select count(1) as counter from `provisioningAction`";

		// the criteria can be used or you can write your own custom logic.
		// be sure to escape any user input with $criteria->Escape()
		$sql .= $criteria->GetWhere();

		return $sql;
	}
}

?>