<?php
/** @package    Spot::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * OrdersMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the OrdersDAO to the orders datastore.
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
class OrdersMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Orders object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","orders","salesOrder",true,FM_TYPE_INT,11,null,false);
			$fm["Crmuid"] = new FieldMap("Crmuid","orders","crmUID",true,FM_TYPE_VARCHAR,6,"1-0000",false);
			$fm["Pgm"] = new FieldMap("Pgm","orders","pgm",false,FM_TYPE_TINYTEXT,null,null,false);
			$fm["Ordertitle"] = new FieldMap("Ordertitle","orders","orderTitle",false,FM_TYPE_MEDIUMTEXT,null,null,false);
			$fm["Heacronym"] = new FieldMap("Heacronym","orders","HEAcronym",false,FM_TYPE_VARCHAR,4,null,false);
			$fm["Systemtype"] = new FieldMap("Systemtype","orders","systemType",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Snapavail"] = new FieldMap("Snapavail","orders","snapAvail",false,FM_TYPE_TINYINT,4,null,false);
			$fm["Pstartdate"] = new FieldMap("Pstartdate","orders","pStartDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Penddate"] = new FieldMap("Penddate","orders","pEndDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Rstartdate"] = new FieldMap("Rstartdate","orders","rStartDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Renddate"] = new FieldMap("Renddate","orders","rEndDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Shippmentdate"] = new FieldMap("Shippmentdate","orders","shippmentDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Status"] = new FieldMap("Status","orders","status",false,FM_TYPE_VARCHAR,50,null,false);
			$fm["Polaroidexport"] = new FieldMap("Polaroidexport","orders","polaroidExport",false,FM_TYPE_INT,11,null,false);
			$fm["Userid"] = new FieldMap("Userid","orders","userID",false,FM_TYPE_INT,11,null,false);
			$fm["Commiteddate"] = new FieldMap("Commiteddate","orders","commitedDate",false,FM_TYPE_TIMESTAMP,null,"CURRENT_TIMESTAMP",false);
			$fm["Moveorder"] = new FieldMap("Moveorder","orders","moveorder",false,FM_TYPE_BLOB,null,null,false);
			$fm["Oracleorder"] = new FieldMap("Oracleorder","orders","oracleorder",false,FM_TYPE_BLOB,null,null,false);
			$fm["Comments"] = new FieldMap("Comments","orders","comments",false,FM_TYPE_TEXT,null,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Orders object
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
			$km["so"] = new KeyMap("so", "Salesorder", "Networks", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["fk_userid_order"] = new KeyMap("fk_userid_order", "Userid", "Users", "UId", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return $km;
	}

}

?>