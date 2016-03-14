<?php
/** @package    OdsDb::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/Criteria.php");

/**
 * Customer_Ip_InventoryCriteria allows custom querying for the Customer_Ip_Inventory object.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * Add any custom business logic to the ModelCriteria class which is extended from this class.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @inheritdocs
 * @package OdsDb::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class Customer_Ip_InventoryCriteriaDAO extends Criteria
{

	public $Custipid_Equals;
	public $Custipid_NotEquals;
	public $Custipid_IsLike;
	public $Custipid_IsNotLike;
	public $Custipid_BeginsWith;
	public $Custipid_EndsWith;
	public $Custipid_GreaterThan;
	public $Custipid_GreaterThanOrEqual;
	public $Custipid_LessThan;
	public $Custipid_LessThanOrEqual;
	public $Custipid_In;
	public $Custipid_IsNotEmpty;
	public $Custipid_IsEmpty;
	public $Custipid_BitwiseOr;
	public $Custipid_BitwiseAnd;
	public $Subnet_Equals;
	public $Subnet_NotEquals;
	public $Subnet_IsLike;
	public $Subnet_IsNotLike;
	public $Subnet_BeginsWith;
	public $Subnet_EndsWith;
	public $Subnet_GreaterThan;
	public $Subnet_GreaterThanOrEqual;
	public $Subnet_LessThan;
	public $Subnet_LessThanOrEqual;
	public $Subnet_In;
	public $Subnet_IsNotEmpty;
	public $Subnet_IsEmpty;
	public $Subnet_BitwiseOr;
	public $Subnet_BitwiseAnd;
	public $Netmask_Equals;
	public $Netmask_NotEquals;
	public $Netmask_IsLike;
	public $Netmask_IsNotLike;
	public $Netmask_BeginsWith;
	public $Netmask_EndsWith;
	public $Netmask_GreaterThan;
	public $Netmask_GreaterThanOrEqual;
	public $Netmask_LessThan;
	public $Netmask_LessThanOrEqual;
	public $Netmask_In;
	public $Netmask_IsNotEmpty;
	public $Netmask_IsEmpty;
	public $Netmask_BitwiseOr;
	public $Netmask_BitwiseAnd;
	public $Account_Equals;
	public $Account_NotEquals;
	public $Account_IsLike;
	public $Account_IsNotLike;
	public $Account_BeginsWith;
	public $Account_EndsWith;
	public $Account_GreaterThan;
	public $Account_GreaterThanOrEqual;
	public $Account_LessThan;
	public $Account_LessThanOrEqual;
	public $Account_In;
	public $Account_IsNotEmpty;
	public $Account_IsEmpty;
	public $Account_BitwiseOr;
	public $Account_BitwiseAnd;
	public $Location_Equals;
	public $Location_NotEquals;
	public $Location_IsLike;
	public $Location_IsNotLike;
	public $Location_BeginsWith;
	public $Location_EndsWith;
	public $Location_GreaterThan;
	public $Location_GreaterThanOrEqual;
	public $Location_LessThan;
	public $Location_LessThanOrEqual;
	public $Location_In;
	public $Location_IsNotEmpty;
	public $Location_IsEmpty;
	public $Location_BitwiseOr;
	public $Location_BitwiseAnd;
	public $SystemName_Equals;
	public $SystemName_NotEquals;
	public $SystemName_IsLike;
	public $SystemName_IsNotLike;
	public $SystemName_BeginsWith;
	public $SystemName_EndsWith;
	public $SystemName_GreaterThan;
	public $SystemName_GreaterThanOrEqual;
	public $SystemName_LessThan;
	public $SystemName_LessThanOrEqual;
	public $SystemName_In;
	public $SystemName_IsNotEmpty;
	public $SystemName_IsEmpty;
	public $SystemName_BitwiseOr;
	public $SystemName_BitwiseAnd;
	public $Entt_Equals;
	public $Entt_NotEquals;
	public $Entt_IsLike;
	public $Entt_IsNotLike;
	public $Entt_BeginsWith;
	public $Entt_EndsWith;
	public $Entt_GreaterThan;
	public $Entt_GreaterThanOrEqual;
	public $Entt_LessThan;
	public $Entt_LessThanOrEqual;
	public $Entt_In;
	public $Entt_IsNotEmpty;
	public $Entt_IsEmpty;
	public $Entt_BitwiseOr;
	public $Entt_BitwiseAnd;
	public $RemoteAccess_Equals;
	public $RemoteAccess_NotEquals;
	public $RemoteAccess_IsLike;
	public $RemoteAccess_IsNotLike;
	public $RemoteAccess_BeginsWith;
	public $RemoteAccess_EndsWith;
	public $RemoteAccess_GreaterThan;
	public $RemoteAccess_GreaterThanOrEqual;
	public $RemoteAccess_LessThan;
	public $RemoteAccess_LessThanOrEqual;
	public $RemoteAccess_In;
	public $RemoteAccess_IsNotEmpty;
	public $RemoteAccess_IsEmpty;
	public $RemoteAccess_BitwiseOr;
	public $RemoteAccess_BitwiseAnd;
	public $Comments_Equals;
	public $Comments_NotEquals;
	public $Comments_IsLike;
	public $Comments_IsNotLike;
	public $Comments_BeginsWith;
	public $Comments_EndsWith;
	public $Comments_GreaterThan;
	public $Comments_GreaterThanOrEqual;
	public $Comments_LessThan;
	public $Comments_LessThanOrEqual;
	public $Comments_In;
	public $Comments_IsNotEmpty;
	public $Comments_IsEmpty;
	public $Comments_BitwiseOr;
	public $Comments_BitwiseAnd;
	public $Valdate_Equals;
	public $Valdate_NotEquals;
	public $Valdate_IsLike;
	public $Valdate_IsNotLike;
	public $Valdate_BeginsWith;
	public $Valdate_EndsWith;
	public $Valdate_GreaterThan;
	public $Valdate_GreaterThanOrEqual;
	public $Valdate_LessThan;
	public $Valdate_LessThanOrEqual;
	public $Valdate_In;
	public $Valdate_IsNotEmpty;
	public $Valdate_IsEmpty;
	public $Valdate_BitwiseOr;
	public $Valdate_BitwiseAnd;
	public $ValidatedBy_Equals;
	public $ValidatedBy_NotEquals;
	public $ValidatedBy_IsLike;
	public $ValidatedBy_IsNotLike;
	public $ValidatedBy_BeginsWith;
	public $ValidatedBy_EndsWith;
	public $ValidatedBy_GreaterThan;
	public $ValidatedBy_GreaterThanOrEqual;
	public $ValidatedBy_LessThan;
	public $ValidatedBy_LessThanOrEqual;
	public $ValidatedBy_In;
	public $ValidatedBy_IsNotEmpty;
	public $ValidatedBy_IsEmpty;
	public $ValidatedBy_BitwiseOr;
	public $ValidatedBy_BitwiseAnd;
	public $Lsmod_Equals;
	public $Lsmod_NotEquals;
	public $Lsmod_IsLike;
	public $Lsmod_IsNotLike;
	public $Lsmod_BeginsWith;
	public $Lsmod_EndsWith;
	public $Lsmod_GreaterThan;
	public $Lsmod_GreaterThanOrEqual;
	public $Lsmod_LessThan;
	public $Lsmod_LessThanOrEqual;
	public $Lsmod_In;
	public $Lsmod_IsNotEmpty;
	public $Lsmod_IsEmpty;
	public $Lsmod_BitwiseOr;
	public $Lsmod_BitwiseAnd;
	public $Status_Equals;
	public $Status_NotEquals;
	public $Status_IsLike;
	public $Status_IsNotLike;
	public $Status_BeginsWith;
	public $Status_EndsWith;
	public $Status_GreaterThan;
	public $Status_GreaterThanOrEqual;
	public $Status_LessThan;
	public $Status_LessThanOrEqual;
	public $Status_In;
	public $Status_IsNotEmpty;
	public $Status_IsEmpty;
	public $Status_BitwiseOr;
	public $Status_BitwiseAnd;

}

?>