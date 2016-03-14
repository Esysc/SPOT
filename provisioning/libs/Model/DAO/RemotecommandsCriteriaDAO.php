<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Criteria.php");

/**
 * RemotecommandsCriteria allows custom querying for the Remotecommands object.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the ModelCriteria class which is extended from this class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @inheritdocs
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class RemotecommandsCriteriaDAO extends Criteria
{

	public $Remotecommandid_Equals;
	public $Remotecommandid_NotEquals;
	public $Remotecommandid_IsLike;
	public $Remotecommandid_IsNotLike;
	public $Remotecommandid_BeginsWith;
	public $Remotecommandid_EndsWith;
	public $Remotecommandid_GreaterThan;
	public $Remotecommandid_GreaterThanOrEqual;
	public $Remotecommandid_LessThan;
	public $Remotecommandid_LessThanOrEqual;
	public $Remotecommandid_In;
	public $Remotecommandid_IsNotEmpty;
	public $Remotecommandid_IsEmpty;
	public $Remotecommandid_BitwiseOr;
	public $Remotecommandid_BitwiseAnd;
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
	public $Rack_Equals;
	public $Rack_NotEquals;
	public $Rack_IsLike;
	public $Rack_IsNotLike;
	public $Rack_BeginsWith;
	public $Rack_EndsWith;
	public $Rack_GreaterThan;
	public $Rack_GreaterThanOrEqual;
	public $Rack_LessThan;
	public $Rack_LessThanOrEqual;
	public $Rack_In;
	public $Rack_IsNotEmpty;
	public $Rack_IsEmpty;
	public $Rack_BitwiseOr;
	public $Rack_BitwiseAnd;
	public $Shelf_Equals;
	public $Shelf_NotEquals;
	public $Shelf_IsLike;
	public $Shelf_IsNotLike;
	public $Shelf_BeginsWith;
	public $Shelf_EndsWith;
	public $Shelf_GreaterThan;
	public $Shelf_GreaterThanOrEqual;
	public $Shelf_LessThan;
	public $Shelf_LessThanOrEqual;
	public $Shelf_In;
	public $Shelf_IsNotEmpty;
	public $Shelf_IsEmpty;
	public $Shelf_BitwiseOr;
	public $Shelf_BitwiseAnd;
	public $Clientaddress_Equals;
	public $Clientaddress_NotEquals;
	public $Clientaddress_IsLike;
	public $Clientaddress_IsNotLike;
	public $Clientaddress_BeginsWith;
	public $Clientaddress_EndsWith;
	public $Clientaddress_GreaterThan;
	public $Clientaddress_GreaterThanOrEqual;
	public $Clientaddress_LessThan;
	public $Clientaddress_LessThanOrEqual;
	public $Clientaddress_In;
	public $Clientaddress_IsNotEmpty;
	public $Clientaddress_IsEmpty;
	public $Clientaddress_BitwiseOr;
	public $Clientaddress_BitwiseAnd;
	public $Arguments_Equals;
	public $Arguments_NotEquals;
	public $Arguments_IsLike;
	public $Arguments_IsNotLike;
	public $Arguments_BeginsWith;
	public $Arguments_EndsWith;
	public $Arguments_GreaterThan;
	public $Arguments_GreaterThanOrEqual;
	public $Arguments_LessThan;
	public $Arguments_LessThanOrEqual;
	public $Arguments_In;
	public $Arguments_IsNotEmpty;
	public $Arguments_IsEmpty;
	public $Arguments_BitwiseOr;
	public $Arguments_BitwiseAnd;
	public $Exesequence_Equals;
	public $Exesequence_NotEquals;
	public $Exesequence_IsLike;
	public $Exesequence_IsNotLike;
	public $Exesequence_BeginsWith;
	public $Exesequence_EndsWith;
	public $Exesequence_GreaterThan;
	public $Exesequence_GreaterThanOrEqual;
	public $Exesequence_LessThan;
	public $Exesequence_LessThanOrEqual;
	public $Exesequence_In;
	public $Exesequence_IsNotEmpty;
	public $Exesequence_IsEmpty;
	public $Exesequence_BitwiseOr;
	public $Exesequence_BitwiseAnd;
	public $Scriptid_Equals;
	public $Scriptid_NotEquals;
	public $Scriptid_IsLike;
	public $Scriptid_IsNotLike;
	public $Scriptid_BeginsWith;
	public $Scriptid_EndsWith;
	public $Scriptid_GreaterThan;
	public $Scriptid_GreaterThanOrEqual;
	public $Scriptid_LessThan;
	public $Scriptid_LessThanOrEqual;
	public $Scriptid_In;
	public $Scriptid_IsNotEmpty;
	public $Scriptid_IsEmpty;
	public $Scriptid_BitwiseOr;
	public $Scriptid_BitwiseAnd;
	public $Returncode_Equals;
	public $Returncode_NotEquals;
	public $Returncode_IsLike;
	public $Returncode_IsNotLike;
	public $Returncode_BeginsWith;
	public $Returncode_EndsWith;
	public $Returncode_GreaterThan;
	public $Returncode_GreaterThanOrEqual;
	public $Returncode_LessThan;
	public $Returncode_LessThanOrEqual;
	public $Returncode_In;
	public $Returncode_IsNotEmpty;
	public $Returncode_IsEmpty;
	public $Returncode_BitwiseOr;
	public $Returncode_BitwiseAnd;
	public $Returnstdout_Equals;
	public $Returnstdout_NotEquals;
	public $Returnstdout_IsLike;
	public $Returnstdout_IsNotLike;
	public $Returnstdout_BeginsWith;
	public $Returnstdout_EndsWith;
	public $Returnstdout_GreaterThan;
	public $Returnstdout_GreaterThanOrEqual;
	public $Returnstdout_LessThan;
	public $Returnstdout_LessThanOrEqual;
	public $Returnstdout_In;
	public $Returnstdout_IsNotEmpty;
	public $Returnstdout_IsEmpty;
	public $Returnstdout_BitwiseOr;
	public $Returnstdout_BitwiseAnd;
	public $Returnstderr_Equals;
	public $Returnstderr_NotEquals;
	public $Returnstderr_IsLike;
	public $Returnstderr_IsNotLike;
	public $Returnstderr_BeginsWith;
	public $Returnstderr_EndsWith;
	public $Returnstderr_GreaterThan;
	public $Returnstderr_GreaterThanOrEqual;
	public $Returnstderr_LessThan;
	public $Returnstderr_LessThanOrEqual;
	public $Returnstderr_In;
	public $Returnstderr_IsNotEmpty;
	public $Returnstderr_IsEmpty;
	public $Returnstderr_BitwiseOr;
	public $Returnstderr_BitwiseAnd;
	public $Executionflag_Equals;
	public $Executionflag_NotEquals;
	public $Executionflag_IsLike;
	public $Executionflag_IsNotLike;
	public $Executionflag_BeginsWith;
	public $Executionflag_EndsWith;
	public $Executionflag_GreaterThan;
	public $Executionflag_GreaterThanOrEqual;
	public $Executionflag_LessThan;
	public $Executionflag_LessThanOrEqual;
	public $Executionflag_In;
	public $Executionflag_IsNotEmpty;
	public $Executionflag_IsEmpty;
	public $Executionflag_BitwiseOr;
	public $Executionflag_BitwiseAnd;
	public $Logtime_Equals;
	public $Logtime_NotEquals;
	public $Logtime_IsLike;
	public $Logtime_IsNotLike;
	public $Logtime_BeginsWith;
	public $Logtime_EndsWith;
	public $Logtime_GreaterThan;
	public $Logtime_GreaterThanOrEqual;
	public $Logtime_LessThan;
	public $Logtime_LessThanOrEqual;
	public $Logtime_In;
	public $Logtime_IsNotEmpty;
	public $Logtime_IsEmpty;
	public $Logtime_BitwiseOr;
	public $Logtime_BitwiseAnd;
	public $Exectime_Equals;
	public $Exectime_NotEquals;
	public $Exectime_IsLike;
	public $Exectime_IsNotLike;
	public $Exectime_BeginsWith;
	public $Exectime_EndsWith;
	public $Exectime_GreaterThan;
	public $Exectime_GreaterThanOrEqual;
	public $Exectime_LessThan;
	public $Exectime_LessThanOrEqual;
	public $Exectime_In;
	public $Exectime_IsNotEmpty;
	public $Exectime_IsEmpty;
	public $Exectime_BitwiseOr;
	public $Exectime_BitwiseAnd;

}

?>