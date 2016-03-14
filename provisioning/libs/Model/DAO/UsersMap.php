<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * UsersMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the UsersDAO to the users datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Spot::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class UsersMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Users object
	 *
	 * @access public
	 * @return array of FieldMaps
	 */
	public static function GetFieldMaps()
	{
		static $fm = null;
		if ($fm == null)
		{
			$fm = Array();
			$fm["UId"] = new FieldMap("UId","users","U_id",true,FM_TYPE_INT,11,null,true);
			$fm["Username"] = new FieldMap("Username","users","username",false,FM_TYPE_VARCHAR,20,null,false);
			$fm["Password"] = new FieldMap("Password","users","password",false,FM_TYPE_VARCHAR,32,null,false);
			$fm["URight"] = new FieldMap("URight","users","U_right",false,FM_TYPE_INT,11,null,false);
			$fm["UAdUser"] = new FieldMap("UAdUser","users","U_AD_User",false,FM_TYPE_VARCHAR,12,null,false);
			$fm["UAdPassword"] = new FieldMap("UAdPassword","users","U_AD_Password",false,FM_TYPE_BLOB,null,null,false);
			$fm["UPhone"] = new FieldMap("UPhone","users","U_Phone",false,FM_TYPE_VARCHAR,18,null,false);
			$fm["UFullName"] = new FieldMap("UFullName","users","U_Full_Name",false,FM_TYPE_VARCHAR,40,null,false);
			$fm["UAdEmail"] = new FieldMap("UAdEmail","users","U_AD_email",false,FM_TYPE_VARCHAR,40,null,false);
			$fm["Token"] = new FieldMap("Token","users","token",false,FM_TYPE_VARCHAR,255,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Users object
	 *
	 * @access public
	 * @return array of KeyMaps
	 */
	public static function GetKeyMaps()
	{
		static $km = null;
		if ($km == null)
		{
			$km = Array();
			$km["userID"] = new KeyMap("userID", "UId", "Notifications", "Userid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["fk_userid_order"] = new KeyMap("fk_userid_order", "UId", "Orders", "Userid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["fk_userid"] = new KeyMap("fk_userid", "UId", "Orderslog", "Userid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["userExist"] = new KeyMap("userExist", "UId", "Provisioningnotifications", "Userid", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK__users"] = new KeyMap("FK__users", "UId", "Tempdata", "Creator", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>