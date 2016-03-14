<?php
/** @package    Drbl::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");

/**
 * TblordersMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the TblordersDAO to the tblOrders datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Drbl::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class TblordersMap implements IDaoMap
{
	/**
	 * Returns a singleton array of FieldMaps for the Tblorders object
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
			$fm["Salesorder"] = new FieldMap("Salesorder","tblOrders","Salesorder",true,FM_TYPE_INT,11,null,false);
			$fm["Programmanager"] = new FieldMap("Programmanager","tblOrders","ProgramManager",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Siteengineer"] = new FieldMap("Siteengineer","tblOrders","SiteEngineer",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Sysprodactor"] = new FieldMap("Sysprodactor","tblOrders","SysprodActor",false,FM_TYPE_VARCHAR,20,null,false);
			$fm["Release"] = new FieldMap("Release","tblOrders","Release",false,FM_TYPE_VARCHAR,25,null,false);
			$fm["Comment"] = new FieldMap("Comment","tblOrders","comment",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Startdate"] = new FieldMap("Startdate","tblOrders","StartDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Enddate"] = new FieldMap("Enddate","tblOrders","EndDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Prodstartdate"] = new FieldMap("Prodstartdate","tblOrders","prodStartDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Prodenddate"] = new FieldMap("Prodenddate","tblOrders","prodEndDate",false,FM_TYPE_DATE,null,null,false);
			$fm["Customer"] = new FieldMap("Customer","tblOrders","Customer",false,FM_TYPE_VARCHAR,20,null,false);
			$fm["Timezone"] = new FieldMap("Timezone","tblOrders","Timezone",false,FM_TYPE_VARCHAR,10,null,false);
			$fm["Cctsnapshotpath"] = new FieldMap("Cctsnapshotpath","tblOrders","CCTSnapshotPath",false,FM_TYPE_VARCHAR,255,null,false);
			$fm["Sid"] = new FieldMap("Sid","tblOrders","SID",false,FM_TYPE_VARCHAR,25,"1-0000",false);
			$fm["Customersigle"] = new FieldMap("Customersigle","tblOrders","customerSigle",false,FM_TYPE_VARCHAR,5,null,false);
			$fm["Exported"] = new FieldMap("Exported","tblOrders","exported",false,FM_TYPE_INT,1,null,false);
		}
		return $fm;
	}

	/**
	 * Returns a singleton array of KeyMaps for the Tblorders object
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
			$km["FK_item_applicationserver_tblOrders"] = new KeyMap("FK_item_applicationserver_tblOrders", "Salesorder", "ItemApplicationserver", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_cas_tblOrders"] = new KeyMap("FK_item_cas_tblOrders", "Salesorder", "ItemCas", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_dbserver_tblOrders"] = new KeyMap("FK_item_dbserver_tblOrders", "Salesorder", "ItemDbserver", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_mgt_tblOrders"] = new KeyMap("FK_item_mgt_tblOrders", "Salesorder", "ItemMgt", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_network_tblOrders"] = new KeyMap("FK_item_network_tblOrders", "Salesorder", "ItemNetwork", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_nimserver_tblOrders"] = new KeyMap("FK_item_nimserver_tblOrders", "Salesorder", "ItemNimserver", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_parts_tblOrders"] = new KeyMap("FK_item_parts_tblOrders", "Salesorder", "ItemParts", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_peripheral_tblOrders"] = new KeyMap("FK_item_peripheral_tblOrders", "Salesorder", "ItemPeripheral", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_san_tblOrders"] = new KeyMap("FK_item_san_tblOrders", "Salesorder", "ItemSan", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_item_vio_tblOrders"] = new KeyMap("FK_item_vio_tblOrders", "Salesorder", "ItemVio", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
			$km["FK_tblNetworks_tblOrders"] = new KeyMap("FK_tblNetworks_tblOrders", "Salesorder", "Tblnetworks", "Salesorder", KM_TYPE_ONETOMANY, KM_LOAD_LAZY);  // use KM_LOAD_EAGER with caution here (one-to-one relationships only)
		}
		return $km;
	}

}

?>