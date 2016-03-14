<?php
/** @package    Drbl::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Criteria.php");

/**
 * TblordersCriteria allows custom querying for the Tblorders object.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the ModelCriteria class which is extended from this class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @inheritdocs
 * @package Drbl::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class TblordersCriteriaDAO extends Criteria
{

	public $Salesorder_Equals;
	public $Salesorder_NotEquals;
	public $Salesorder_IsLike;
	public $Salesorder_IsNotLike;
	public $Salesorder_BeginsWith;
	public $Salesorder_EndsWith;
	public $Salesorder_GreaterThan;
	public $Salesorder_GreaterThanOrEqual;
	public $Salesorder_LessThan;
	public $Salesorder_LessThanOrEqual;
	public $Salesorder_In;
	public $Salesorder_IsNotEmpty;
	public $Salesorder_IsEmpty;
	public $Salesorder_BitwiseOr;
	public $Salesorder_BitwiseAnd;
	public $Programmanager_Equals;
	public $Programmanager_NotEquals;
	public $Programmanager_IsLike;
	public $Programmanager_IsNotLike;
	public $Programmanager_BeginsWith;
	public $Programmanager_EndsWith;
	public $Programmanager_GreaterThan;
	public $Programmanager_GreaterThanOrEqual;
	public $Programmanager_LessThan;
	public $Programmanager_LessThanOrEqual;
	public $Programmanager_In;
	public $Programmanager_IsNotEmpty;
	public $Programmanager_IsEmpty;
	public $Programmanager_BitwiseOr;
	public $Programmanager_BitwiseAnd;
	public $Siteengineer_Equals;
	public $Siteengineer_NotEquals;
	public $Siteengineer_IsLike;
	public $Siteengineer_IsNotLike;
	public $Siteengineer_BeginsWith;
	public $Siteengineer_EndsWith;
	public $Siteengineer_GreaterThan;
	public $Siteengineer_GreaterThanOrEqual;
	public $Siteengineer_LessThan;
	public $Siteengineer_LessThanOrEqual;
	public $Siteengineer_In;
	public $Siteengineer_IsNotEmpty;
	public $Siteengineer_IsEmpty;
	public $Siteengineer_BitwiseOr;
	public $Siteengineer_BitwiseAnd;
	public $Sysprodactor_Equals;
	public $Sysprodactor_NotEquals;
	public $Sysprodactor_IsLike;
	public $Sysprodactor_IsNotLike;
	public $Sysprodactor_BeginsWith;
	public $Sysprodactor_EndsWith;
	public $Sysprodactor_GreaterThan;
	public $Sysprodactor_GreaterThanOrEqual;
	public $Sysprodactor_LessThan;
	public $Sysprodactor_LessThanOrEqual;
	public $Sysprodactor_In;
	public $Sysprodactor_IsNotEmpty;
	public $Sysprodactor_IsEmpty;
	public $Sysprodactor_BitwiseOr;
	public $Sysprodactor_BitwiseAnd;
	public $Release_Equals;
	public $Release_NotEquals;
	public $Release_IsLike;
	public $Release_IsNotLike;
	public $Release_BeginsWith;
	public $Release_EndsWith;
	public $Release_GreaterThan;
	public $Release_GreaterThanOrEqual;
	public $Release_LessThan;
	public $Release_LessThanOrEqual;
	public $Release_In;
	public $Release_IsNotEmpty;
	public $Release_IsEmpty;
	public $Release_BitwiseOr;
	public $Release_BitwiseAnd;
	public $Comment_Equals;
	public $Comment_NotEquals;
	public $Comment_IsLike;
	public $Comment_IsNotLike;
	public $Comment_BeginsWith;
	public $Comment_EndsWith;
	public $Comment_GreaterThan;
	public $Comment_GreaterThanOrEqual;
	public $Comment_LessThan;
	public $Comment_LessThanOrEqual;
	public $Comment_In;
	public $Comment_IsNotEmpty;
	public $Comment_IsEmpty;
	public $Comment_BitwiseOr;
	public $Comment_BitwiseAnd;
	public $Startdate_Equals;
	public $Startdate_NotEquals;
	public $Startdate_IsLike;
	public $Startdate_IsNotLike;
	public $Startdate_BeginsWith;
	public $Startdate_EndsWith;
	public $Startdate_GreaterThan;
	public $Startdate_GreaterThanOrEqual;
	public $Startdate_LessThan;
	public $Startdate_LessThanOrEqual;
	public $Startdate_In;
	public $Startdate_IsNotEmpty;
	public $Startdate_IsEmpty;
	public $Startdate_BitwiseOr;
	public $Startdate_BitwiseAnd;
	public $Enddate_Equals;
	public $Enddate_NotEquals;
	public $Enddate_IsLike;
	public $Enddate_IsNotLike;
	public $Enddate_BeginsWith;
	public $Enddate_EndsWith;
	public $Enddate_GreaterThan;
	public $Enddate_GreaterThanOrEqual;
	public $Enddate_LessThan;
	public $Enddate_LessThanOrEqual;
	public $Enddate_In;
	public $Enddate_IsNotEmpty;
	public $Enddate_IsEmpty;
	public $Enddate_BitwiseOr;
	public $Enddate_BitwiseAnd;
	public $Prodstartdate_Equals;
	public $Prodstartdate_NotEquals;
	public $Prodstartdate_IsLike;
	public $Prodstartdate_IsNotLike;
	public $Prodstartdate_BeginsWith;
	public $Prodstartdate_EndsWith;
	public $Prodstartdate_GreaterThan;
	public $Prodstartdate_GreaterThanOrEqual;
	public $Prodstartdate_LessThan;
	public $Prodstartdate_LessThanOrEqual;
	public $Prodstartdate_In;
	public $Prodstartdate_IsNotEmpty;
	public $Prodstartdate_IsEmpty;
	public $Prodstartdate_BitwiseOr;
	public $Prodstartdate_BitwiseAnd;
	public $Prodenddate_Equals;
	public $Prodenddate_NotEquals;
	public $Prodenddate_IsLike;
	public $Prodenddate_IsNotLike;
	public $Prodenddate_BeginsWith;
	public $Prodenddate_EndsWith;
	public $Prodenddate_GreaterThan;
	public $Prodenddate_GreaterThanOrEqual;
	public $Prodenddate_LessThan;
	public $Prodenddate_LessThanOrEqual;
	public $Prodenddate_In;
	public $Prodenddate_IsNotEmpty;
	public $Prodenddate_IsEmpty;
	public $Prodenddate_BitwiseOr;
	public $Prodenddate_BitwiseAnd;
	public $Customer_Equals;
	public $Customer_NotEquals;
	public $Customer_IsLike;
	public $Customer_IsNotLike;
	public $Customer_BeginsWith;
	public $Customer_EndsWith;
	public $Customer_GreaterThan;
	public $Customer_GreaterThanOrEqual;
	public $Customer_LessThan;
	public $Customer_LessThanOrEqual;
	public $Customer_In;
	public $Customer_IsNotEmpty;
	public $Customer_IsEmpty;
	public $Customer_BitwiseOr;
	public $Customer_BitwiseAnd;
	public $Timezone_Equals;
	public $Timezone_NotEquals;
	public $Timezone_IsLike;
	public $Timezone_IsNotLike;
	public $Timezone_BeginsWith;
	public $Timezone_EndsWith;
	public $Timezone_GreaterThan;
	public $Timezone_GreaterThanOrEqual;
	public $Timezone_LessThan;
	public $Timezone_LessThanOrEqual;
	public $Timezone_In;
	public $Timezone_IsNotEmpty;
	public $Timezone_IsEmpty;
	public $Timezone_BitwiseOr;
	public $Timezone_BitwiseAnd;
	public $Cctsnapshotpath_Equals;
	public $Cctsnapshotpath_NotEquals;
	public $Cctsnapshotpath_IsLike;
	public $Cctsnapshotpath_IsNotLike;
	public $Cctsnapshotpath_BeginsWith;
	public $Cctsnapshotpath_EndsWith;
	public $Cctsnapshotpath_GreaterThan;
	public $Cctsnapshotpath_GreaterThanOrEqual;
	public $Cctsnapshotpath_LessThan;
	public $Cctsnapshotpath_LessThanOrEqual;
	public $Cctsnapshotpath_In;
	public $Cctsnapshotpath_IsNotEmpty;
	public $Cctsnapshotpath_IsEmpty;
	public $Cctsnapshotpath_BitwiseOr;
	public $Cctsnapshotpath_BitwiseAnd;
	public $Sid_Equals;
	public $Sid_NotEquals;
	public $Sid_IsLike;
	public $Sid_IsNotLike;
	public $Sid_BeginsWith;
	public $Sid_EndsWith;
	public $Sid_GreaterThan;
	public $Sid_GreaterThanOrEqual;
	public $Sid_LessThan;
	public $Sid_LessThanOrEqual;
	public $Sid_In;
	public $Sid_IsNotEmpty;
	public $Sid_IsEmpty;
	public $Sid_BitwiseOr;
	public $Sid_BitwiseAnd;
	public $Customersigle_Equals;
	public $Customersigle_NotEquals;
	public $Customersigle_IsLike;
	public $Customersigle_IsNotLike;
	public $Customersigle_BeginsWith;
	public $Customersigle_EndsWith;
	public $Customersigle_GreaterThan;
	public $Customersigle_GreaterThanOrEqual;
	public $Customersigle_LessThan;
	public $Customersigle_LessThanOrEqual;
	public $Customersigle_In;
	public $Customersigle_IsNotEmpty;
	public $Customersigle_IsEmpty;
	public $Customersigle_BitwiseOr;
	public $Customersigle_BitwiseAnd;
	public $Exported_Equals;
	public $Exported_NotEquals;
	public $Exported_IsLike;
	public $Exported_IsNotLike;
	public $Exported_BeginsWith;
	public $Exported_EndsWith;
	public $Exported_GreaterThan;
	public $Exported_GreaterThanOrEqual;
	public $Exported_LessThan;
	public $Exported_LessThanOrEqual;
	public $Exported_In;
	public $Exported_IsNotEmpty;
	public $Exported_IsEmpty;
	public $Exported_BitwiseOr;
	public $Exported_BitwiseAnd;

}

?>