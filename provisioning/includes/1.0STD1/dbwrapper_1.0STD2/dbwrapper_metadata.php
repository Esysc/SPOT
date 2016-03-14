<?php
	
	define("DEFAULT_CRM_UID", "1-0000");
	
	require_once "dbwrapper_monitor.php";
        //require_once "csvEncoder.php";
        function utf82iso(&$value, $key)
        {
            $value = iconv('UTF-8','ISO-8859-1', $value);
        }

	class DBWRAPPERMetadata extends Exception 
	{
		private $head = "[DBWMetadata]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
  class DBWMetadata extends DBWMonitor
  {
	public function __construct($username, $password, $auth_method)
	{
		parent::__construct($username, $password, $auth_method);
	} 
/*****************************************************************************************************
		METADATA SUPPORT FOR ORDER CONSTRUCTION
*******************************************************************************************************/
		
	protected function get_catalog_metadata($IDItem)
	{
		
		$out = parent::basic_select(TBLCATALOG, "IDItem='$IDItem' AND enabled='1'", "metadata");
		if ( empty($out) ) throw new DBWRAPPERMetadata("Item not found or inactive");
		return new SimpleXMLElement($out[0]);
	}
	
	protected function get_supported_version($metadata)
	{
		
		return $metadata->support["class"];
	}
	
	protected function get_catalog_item_name($metadat)
	{
		return $metadat->item["name"];
	}
	protected function catalog_conatiner_name($metadat)
	{
		return $metadat->container->order["array"];
	}
	
	public function get_ip_from_hostname($hostname)
	{
		return parent::e_select(HSTIPCONV, array("hostname" => $hostname), True, "ip");
	}
	
	protected function metadata_method_category($metadata)
	{
		return (string)$metadata->category["name"];
	}
	protected function metadata_item_name($IDItem)
	{
		return $this->get_catalog_item_name($this->get_catalog_metadata($IDItem));
	}
	
	protected function metadata_supported($IDItem,$order)
	{
		return  $order->version === $this->get_supported_version($this->get_catalog_metadata($IDItem));
			
	}

	protected function metadata_method_param_getdefval($type, $defval)
	{
		if ( $type == "int")
			return intval($defval);
		if ( $type == "string")
			return $defval;
		if ( $type == "bool")
			return strtolower($defval) == "true" ? true : false;
	}
	protected function metadata_method_param($metadata, $type)
	{
		$root =  $metadata->methods;
		$root = $root->{$type}->param;
		

		
		
		
		$paramArray = array();
		
		
		foreach ( $root as $param)
		{
			
			

			$paramArray[intval($param["pos"])] = array
			(
					"name"      => (string)$param["name"], 
					"type"      => (string)$param["type"],  
					"optionnal" => strtolower(((string)$param["optionnal"])) == "false" ? False : True,
					"callback"  => NULL,
					"maxlen"    => NULL,
					"pos"       => (int)$param["pos"],
					"unique"    => strtolower(((string)$param["unique"])) == "true" ? True : False,
					"index"     => strtolower(((string)$param["index"])) == "true" ? true : false,
					"display"   => strtolower(((string)$param["display"])) == "false" ? false : true,
					"defval"    => isset($param["defval"]) ? (string)$param["defval"]  : NULL,
					"report"    => (string)$param["report"]  === "false" ? false: True,
				    "reportString" => isset($param["reportString"]) ? (string)$param["reportString"] : (string)$param["name"]
				
			);
			
		}
		
		
		
		if ( empty($paramArray) ) throw new DBWRAPPERMetadata("Parameters not foud");
		
		return $paramArray;
		
		
	}
	protected function get_catalog_category($IDitem)
	{
		$metadta = $this->get_catalog_metadata($IDitem);
		
		return $this->metadata_method_category($metadta);	
	}
	
	protected function get_catalog_params($IDItem, $type)
	{
		$metadta = $this->get_catalog_metadata($IDItem);
		
		return $this->metadata_method_param($metadta, $type);
	}
	
	protected function get_catalog_paramsBymethod($IDItem, $methods)
	{
		$paramNames = array();
		
		$in = $this->get_catalog_paramsBymethods($IDItem, NULL);
		$method = $in[$methods];
		foreach ( $method as $param)
			$paramNames[] = $param["name"];
		
		return $paramNames;
		
	}
	
	public function getItemNamFromID($ID, $showNewName=False)
	{
		
		if ( $showNewName )
			return  $this->basic_select(TBLCATALOG, "IDItem='$ID'",array("legacy_name", "name", 'container'));
		return  $this->basic_select(TBLCATALOG, "IDItem='$ID'",array("legacy_name"));
		
	}
	
	public function getModelContainer($includeID=False)
	{
		if ( $includeID )
		{
			return  $this->basic_select(CAT_ENABLED_ITEM, "",array("container","IDItem"));
		}	
		return  $this->basic_select(CAT_ENABLED_ITEM, "",array("container"));
	}
	protected function get_catalog_paramsBymethods($IDItem, $methodsArry=NULL)
	{
		
		
		if ( is_null($methodsArry) ) $methodsArry = array("add", "get", "update", "remove");
		
		$params = array();
		foreach ( $methodsArry as $metadataMIndex)
			$params[$this->get_catalog_method($IDItem, $metadataMIndex)] = $this->get_catalog_params($IDItem, $metadataMIndex);
		
		return $params;
		
	}
	
	
	public function get_catalog_method($IDItem,$type)
	{
		$metadta = $this->get_catalog_metadata($IDItem);
		
		return $this->metadata_method_name($metadta, $type);
		
	}
	
	protected function get_catalog_order_array($IDItem)
	{
		$metadta = $this->get_catalog_metadata($IDItem);
		return (string)$metadta->container->order["array"];
	}
	
	public function get_catalog_container($IDItem)
	{
		$metadata = $this->get_catalog_metadata($IDItem); 
		return (string)$this->catalog_conatiner_name($metadata);
	}
	
	public function get_catalog_table($IDItem)
	{
		$metadta = $this->get_catalog_metadata($IDItem);
		return (string)$metadta->container->db_table["name"];
	}
	
	protected function metadata_method_name($metadata, $type)
	{
		$out = (string)$metadata->methods[0]->{ $type }["call"];
		if ( empty($out) ) throw new DBWRAPPERMetadata("Method not foud");
		return $out;

	}
	
	public function updateCatalogObjects($ID, $object)
	{
		$insertedObj = base64_encode(serialize($object));
		$values = array();
		$values["object"]=$insertedObj ;
		
		parent::syslog_msg(LOG_NOTICE, "Catalog object updtaed ($id)");
		return $this->update($values, TBLCATALOG , "IDItem='$ID'");
	}
	
	protected function retreiveCatalogObject($id)
	{
		 $out = parent::basic_select(TBLCATALOG, "IDItem='$id' AND enabled='1'", "object");
		 if ( empty($out) ) throw new DBWRAPPERMetadata("Object not foud or item inactive");
		 $out = base64_decode($out[0]);
		 $out = unserialize($out);
		 return $out;
		 
	}
	/*will be replaced by loadData*/
	
		//protected function  basic_select($tableName, $condition="", $fields='*', $orderBy=Null, $asc=True, $limityFrom=0,$limite_size=0,$Count=False, $dumpTo=NULL)
	public function dump_Item_metadata($itemID)
	{
		
		parent::basic_select(TBLCATALOG, "IDItem='$itemID'", "metadata", NULL,True,0,0,False,$dumpTo="/tmp/metadata-$itemID.xml");
		
		
		
		$file = fopen("/tmp/metadata-$itemID.xml", "rb");
		
		$str = file_get_contents("/tmp/metadata-$itemID.xml");
		
		
		fclose($file);
		if ( ! unlink("/tmp/metadata-$itemID.xml") ) 
			$this->printdbg("error while removing metadata file!");
		
		return $str;
		
			
			
	}	
	public function get_itemID_list()
	{
		return parent::basic_select(IDENBALED_ITEM, "", "*");
		
	}
	protected function get_itemIDFromContainer($container)
	{
		$out = parent::basic_select(CAT_ENABLED_ITEM, "container='$container'", "IDItem");
		$out = $out[0];
		
		return intval($out);
		
	}
	
	protected function get_method_paramFromItemID($container, $methods)
	{
		$param = $this->get_catalog_params($this->get_itemIDFromContainer($container));
		$key = array_search($method, $param);
		return $param[$key];
	}
	public function get_add_parameters_fromIDItem($IDItem, $forceDisplay=true)
	{
		$out = array();
		$params = $this->get_catalog_params($IDItem, "add");
		foreach ( $params as $key=>$value)
		{
			
			
			if (   $value["display"] === true or $forceDisplay == true)
				$out[] = $value["name"];
				
		}
		return $out;
	}	
	public function get_add_KeyName_fromIDItem($IDItem, $showWType=False)
	{
		$out = array();
		$params = $this->get_catalog_params($IDItem, "add");
		
		foreach ( $params as $key=>$value)
			if ( $value["index"] )
			{
				if ( $showWType )
				{
					$var = explode("_", $value["name"]);
					return $var[1];
				}
				else
					return $value["name"];
			}
				
	
					
		return "str_hostname"; //legacy purpose, will be removed later
	}
	protected function getAPCCode($id)
	{
		
		$out =  parent::basic_select(APCTABLE, "IDAPC='".$id."'", array("APC"));
		
		return empty($out[0])? NULL : $out[0];		
	}
	
	public function getAPCID($hw)
	{
		
		$out =  parent::basic_select(APCTABLE, "APC='".$hw."'", array("IDAPC"));
		return empty($out[0]) ? NULL : $out[0];	
	}
	public function getAPCDescription($hw)
	{
		$out =  parent::basic_select(APCTABLE, "APC='".$hw."'", array("apc_description"));
		return $out[0];
	}
	public function getAPCStatus($hw)
	{
		$out =  parent::basic_select(APCTABLE, "APC='".$hw."'", array("status"));
                
                if ( ! isset($out[0]) ) throw new DBWRAPPERMetadata("Unkow APC code! : $hw",986);
		return $out[0];		
	}
	
	
	public function getAPCCatalog()
	{
		return parent::basic_select(ENABLED_APCTABLE, "", array("IDAPC","APC","apc_description"));
	}
	
	private function analyseExtendedARgs($str)
	{
		$majorField = explode(";", $str);
		$argsArry = array();
		foreach ( $majorField as $index=>$minorField)
		{
			$field = explode("=", $minorField);
			$argsArry[ $field[0] ] = $field[1];
			
			
		}
		
		
		return $argsArry;
	}
	
	public function addfromAPC(ORDER &$Order, $apcCode,$apcGID=-1, &$snStack=NULL)
	{
		
		if ( $this->getAPCStatus($apcCode) == 0 ) throw new DBWRAPPERMetadata("APC $apcCode isn't frozen cannot use it !", 987);
		if ( $apcCode == "")throw new DBWRAPPERMetadata("Empty APC code !");
		$apc_id = $this->getAPCID($apcCode);
		if ( is_null($apc_id) ) throw new DBWRAPPERMetadata("Unkow APC code!",986);
				
		$catalogItemId = parent::basic_select(APC2CATA, "IDAPC='$apc_id'", array("IDItem", "ModelID", "apcChildCode", "extendsArgs", "privateShortcut"));
		if ( $apcGID == -1 )
		{
			$this->current_apc = $apcCode;
			
			
			
			if ( $catalogItemId[0][4] == '1' )
				$apc_group_id = $Order->getNewGID(True); 
			else
				$apc_group_id = $Order->getNewGID(); 
			
			
		}
		else
			$apc_group_id = $apcGID;
			
		
		
		
		
		if ( empty($catalogItemId) ) throw new DBWRAPPERMetadata("APC code found, but there aren't any linked element", 445);
		$sn = array();
		foreach ( $catalogItemId as $values)
		{
			if ( ! empty($values[2]) )
			{
				
				$apc_code = $this->getAPCCode($values[2]);
				
				$this->addfromAPC($Order,$apc_code, $apc_group_id, $snStack);
			}
			else
			{
				$clusterCount = 0;
				$modelID = $values[1];
				$itemId  = intval($values[0]);
				
				$args = $this->analyseExtendedARgs($values[3]);
				
				$cluster = boolval($args["cluster"]);
				$privateShort = boolval($values[4]);
				
				
				$container = $this->get_catalog_container($itemId);
				$params    = $this->get_add_parameters_fromIDItem($itemId) ;
				$keyOfItem = $this->get_add_KeyName_fromIDItem($itemId);
				$addMethod = $this->get_catalog_method($itemId,"add");
				$parameters = array();
				$parameters = array_pad($parameters, count($params), NULL);
				
				$modelIdex       = array_search("int_modelID", $params);
				$apcGroudIDIndex = array_search("int_apcGID", $params);
				$apcCodeIndex    = array_search("str_apccode", $params);
				$cluster_field   = array_search("str_IDNode", $params);
                                
				
				
				
				$parameters[$modelIdex]       = intval($modelID);
				$parameters[$apcGroudIDIndex] = $apc_group_id;
				$parameters[$apcCodeIndex]     = $this->current_apc ;
				
				
				
				
                                if ( $snStack != NULL)
                                {
                                    
                                    $currentSerial = $snStack[0];
                                    unset($snStack[0]);
                                    $keyIndex = array_search($keyOfItem , $params);
                                    $parameters[$keyIndex] = $currentSerial;
                                    
                                }
                                else
                                    $Order->disable_sanity_checks();
				
				
				
				$sn[] = call_user_func_array(array($Order, $addMethod), $parameters);
				if ( $cluster )
				{
					$parameters[$cluster_field] = $out;
					$sn[] = call_user_func_array(array($Order, $addMethod), $parameters);
				}
				$Order->enable_sanity_checks();
                                
                                
                            }
			
			
		}
		
		
		return $sn;
		
	}
	
	
	
	public function apc_get_dep($showFull=false)
	{
		$return = array();
		if ( $showFull ) 
			$hwCodes = parent::basic_select(APCTABLE, "", array("APC"));
		else
			$hwCodes = parent::basic_select(ENABLED_APCTABLE, "", array("APC"));
		
		foreach ( $hwCodes as $index=>$code)
		{
			$return[$code] = $this->apc_get_dependencies($code);
		}
		
		
		return $return;
		
	}
	private function setKeysItemFromModelCode(ORDER &$order, $snList, $apcCode)
        {
            
        }
        public function setKeysItemFromAPC(ORDER &$order, $snList, $apcCode)
        {
            if ( empty($apcCode ))  return false;
            try
            {
                $depList = $this->apc_get_dependencies($apcCode);
                var_dump($depList);
            }
            catch (Exception $e)
            {
                if ( $e->getCode() == 974)
                    $this->setKeysItemFromModelCode($order, $snList, $apcCode);
                else
                    throw $e;
            }
            
        }
        
	protected function get_catalogItems($ApcID)
	{
            return parent::basic_select(APC2CATA, "IDAPC=$ApcID", array("IDItem", "ModelID", "id", "apcChildCode"));
	}
	
	protected function apc_get_dependencies($apcCode)
	{
		if ( empty($apcCode) ) return array();
		
		$idApc = $this->getAPCID($apcCode);
		
		if ( empty($idApc) )  throw new DBWRAPPERMetadata("APC code [$apcCode] not found!",974);
		
		$catalogItemId =$this->get_catalogItems($idApc);
		
		
		$dep = array();

		$i=0;
		
		foreach ( $catalogItemId as $values)
		{
			if (  $values[3] != "" )
			{
				$hwCode =  $this->getAPCCode(intval($values[3]));
				
				$dep = array_merge($dep, $this->apc_get_dependencies($hwCode));
			}
			else
			{
				$itemId  = $values[0];
				$itmName = $this->getItemNamFromID($itemId, True);
				$itmName = $itmName[0];
				
				$itmName = $itmName[1];
				if ( is_string($itmName))
				{
					if ( ! isset($dep[$itmName]) )
					{
						$dep[$itmName] = array("count" => 1 ,
											   "model" => array(
												   $this->getModelName($values[1]) => 1
																), 
											  "lid" => array ( $values[2])
											  
											  );
											  
					}
					else
					{
						$dep[$itmName]["count"]++;
						$dep[$itmName]["lid"][] = $values[2];
						if ( array_search( $this->getModelName($values[1]), array_keys($dep[$itmName]["model"])) === FALSE)
						{
							$dep[$itmName]["model"][$this->getModelName($values[1])] = 1;
						}
						else
							$dep[$itmName]["model"][$this->getModelName($values[1])]++;
						
						
						
						
					}
					
						
				}
			}
			
				
		}

		return $dep;
	}
	
	private function itemIDExist($itemID)
	{
		$out = parent::basic_select(TBLCATALOG, "IDItem='$itemID'", "enabled");
		$out = $out[0];
		
		if ( $out == 1 )
			return 1;
		elseif ( $out==0)
			return -1;
		else
			return 0;
			
			
		
	}
	
	private function apcID_exist($apcID)
	{
		
		return is_null($this->getAPCID($apcID));
	}
	
	public function createChildren($apcID, $catalogID, $modelID, $recursive=false, $cluster=false, $privateShortCut=false)
	{
		if ( ! $this->apcID_exist($apcID) ) throw new DBWRAPPERMetadata("Unkwon APC code", 558);
		if ( ! $recursive )
		{
			if ( $this->itemIDExist($catalogID) == 0 ) throw new DBWRAPPERMetadata("Catalog entry does not exist", 559);
			if ( $this->itemIDExist($catalogID) == -1) throw new DBWRAPPERMetadata("Catalog entry exist but not enabled", 560);
			if ( $this->model_disabled($modelID) == 1 ) throw new DBWRAPPERMetadata("The selected model is disabled", 560);
			if ( $this->model_disabled($modelID) == -1 ) throw new DBWRAPPERMetadata("The selected model does not exist", 560);
		}
		if ( $cluster) 
			$cluster = '1';
		else
			$cluster = '0';
			
		$arr = array( "IDAPC" => $apcID, "ModelID" => $modelID, "extendsArgs" => "cluster=$cluster", "privateShortcut" => $privateShortCut);
		
		
		
		if ( $recursive )
		{
			parent::syslog_msg(LOG_INFO, "APC recursive link created! catalog_id = $catalogID");
			
			if ( $apcID == $catalogID ) throw new DBWRAPPERMetadata("Error : you are creating a loop'ed children!", 555);
			$arr["apcChildCode"] = $catalogID;
		}
		else
		{
			parent::syslog_msg(LOG_INFO, "APC Children code created! catalog_id = $catalogID");
			$this->printdbg("create item form $catalogID");
			$arr["IDItem"] = $catalogID;
		}
		
		
		
		return parent::insert_into($arr, APC2CATA, $REMOVE_TYPE_FLAG=False);
		
	}
	
        
        
	public function createNewCode($code, $description="")
	{
		if ( $code == "" ) throw new DBWRAPPERMetadata("No code supplied", 557);
		$apcID = $this->getAPCID($code);
		if ( empty($apcID) )
		{
			parent::syslog_msg(LOG_INFO, "New apc code created : $code"); 
			return parent::insert_into(array("APC" => $code, "apc_description" => $description), APCTABLE, $REMOVE_TYPE_FLAG=False);
		}
		
		throw new DBWRAPPERMetadata("Error code already exist or code empty", 556);
	}
	
	protected function remove_childrens($id)
	{
		$ids = $this->get_catalogItems($id);
		$cpt=0;
		foreach ( $ids as $index=>$values)
		{
			$this->removeChildren($values[2]);
			$cpt++;
		}
		
		return $cpt;
		
		
	}
	
	public function removeCode($code)
	{
		$id = $this->getAPCID($code);
		if ( empty($id) ) throw new DBWRAPPERMetadata("Error code not found");
		
		$removedChildrens = $this->remove_childrens($id);
		
		parent::syslog_msg(LOG_INFO, "APC code removed : $code, $removedChildrens children removed");
		return parent::delete_row(APCTABLE, "IDAPC=$id");
		
	}
	
	public function removeChildren($id)
	{
		
		$out =  $this->delete_row(APC2CATA, "id=$id");
		$this->printdbg("removing link id : $id status : $out");
		return $out;
	}
	
	protected function is_cluster($IDItem)
	{
		$cl_list = parent::basic_select(CAT_ENABLED_ITEM, "cluster=1", array("IDItem"));
		
		return  ! (array_search($IDItem, $cl_list)  === FALSE);
		
	}

	public function is_item_cluster($IDItem)
	{
		return $this->is_cluster($IDItem);
	}
	private function search_sn($table, $serial)
	{
			return parent::e_select($table,array("serial" => $serial),true, array("salesOrder"), true, true);
	}
        private function search_serials($table, $serial)
	{
			return parent::e_select($table,array("serial" => $serial),true, array("serial"), false, true);
	}	
        public function get_serials($serial)
        {
            $MINLEN = 3;
            
            $results = array();
            if (strlen($serial) >= $MINLEN)
            {
                $item_list = $this->getItems(true);
                
                foreach ( $item_list as $index=>$values)
                {

                        $table_name = $values[2];
                        $qry = $this->search_serials($values[2], $serial);
                       
                        if ( count($qry)>0 ) 
                        {
                            $results =  array_merge($results, $qry);
                           
                            
                        }

                }
         
            }
            return $results;
        }
	
	public function search_serial($serial, $id_item=-1)
	{
		$item_list = $this->getItems(true);
		
		if ( $id_item   == -1)
		{
			$results = array();
			foreach ( $item_list as $index=>$values)
			{
					
				$table_name = $values[2];
				$qry = $this->search_sn($values[2], $serial);
				if (!empty($qry) )
					$results[$values[1]][] = $qry;
				
			}
			
			return $results;
		}
		else
		{
			$table =  $this->get_catalog_container($id_item);
			return $this->search_sn($table, $serial);
		}	
			
		
		
		
	}
	
	private function adddbquotes(&$item1, $key)
	{
		$item1 = '"'.$item1.'"';
		
	}
	
	private function sanitize_csv(&$datas)
	{
		array_walk($datas, array("DBWMetadata", "adddbquotes"));
		
	}
        
        public function alreadyExported($so)
        {
           	return false; 
            if ( $this->configuration->get("ignoredAlreadyExported") == '0')
                return false;
            else
            {
                $out = parent::basic_select(TBLORDERS, "salesOrder='$so' AND SID <> '1-0000'", "exported");
                $this->printdbg("($so)exported? = ".var_export($out,True));
            }
            
            if (  count($out) > 0 )
                return $out[0] == 1;
            else
                return true;
        
            
        }
        
        public function publishPolaroid()
        {
            $orders = parent::basic_select(TBLORDERS,"exported=1","salesOrder");
            $data = "";
            
            foreach ($orders as $order)
            {
                $this->printdbg("Exporting so $order");
                $data .= $this->exportPolaroid($this->getOrder($order))."\n";
            }
            
            return $data;
        }
    function array2csv(array &$array)
    {
       if (count($array) == 0) {
         return null;
       }
       ob_start();
       $df = fopen("php://output", 'w');
       
       foreach ($array as $row) {
           $line = $row;
          
            fputcsv($df, $line,",",'"');
       }
       fclose($df);
       
       return ob_get_clean();
    }
    

    
    private function _ToCSV($array)
    {
        $tmpfname = tempnam("/tmp", "HardwareImport");

        if ( mssafe_csv("/tmp/HardwareImport", $array) ) 
            $this->printdbg ("CSV generated");
        else
            $this->printdbg ("Error while csv generation");
        $data = file_get_contents("/tmp/HardwareImport");
        
        unlink($tmpfname);
        return $data;
        
    }
    private function ToCSV($array)
    {
        ob_start();
        $data =  fopen('php://output', 'w');

        foreach ( $array as $row )
        {
            
            if ( count($row) != 13 )
            {
                throw  new Exception("Invalid field count!!! :".count($array).  var_export($array), __LINE__);
            } 
            else
            {
              $line = $row;
              
              
              fputcsv( $data , $line,",", '"');
              
            }
        }
 
        fclose($data);
        
        
        return str_replace("\n", "\r\n", ob_get_clean()); 
    }
    private function encryptionBoard2Polaroid($data)
    {
        return strlen($data) == 13 ? preg_replace("/(zph|tph)\d\:/", "",$data) : '';
    }
	public function exportPolaroid(ORDER $order, $force=False)
	{
            $array = array();
            $so = $order->getSalesOrder();
            if ( (! $this->alreadyExported($so)) || $force   )
            {
                $datas = $order->prepareExportPolaroid();
		$str = "";
		 if ( ! is_array($datas) or count($datas) == 0 ) 
			throw new DBWRAPPERMetadata("Invalid param", 119);
	
		
		 if ( $this->configuration->get("polaroidExport"))
		 {
                        $str = "";
			foreach ( $datas as $key=>$values)
			{
                                $ploaroid_functions = $this->getPolaroidFunction($values["_modelId"], $values["HOSTNAME"], 
                                                                                                      $values["_sndSerial"] != NULL  );
				$model = $this->getModelsFromDB(intval($values["_modelId"]));
				$model = $model[0];		
				
				
                                if ( $ploaroid_functions["POLROID_FCT"] !== "none")
                                {
                                    
                                

                                    if ( ! isset($values["_modelId"]) or ! $this->modelIDExist($values["_modelId"]) or count($model) == 0 ) 
                                    {
                                            $this->printdbg(var_export($values, True));
                                            throw new DBWRAPPERMetadata("model ".$values["_modelId"]."] found wrongly set");
                                    }



                                    $values["BRAND"] = $model["BrandName"];
                                    $values["MODEL"] = $model["Model"];

                                    if ( $values["BRAND"] == "IBM")
                                        $values["SERIAL"] = str_replace ("-", "", $values["SERIAL"]);


                                    
                                    $values["CPUNUMBER"] = $this->get_modelCpuNumber( $values["_modelId"]);
                                    $values["RAMSIZEMB"] = $this->get_modelRamQty($values["_modelId"] );


                                    $this->printdbg("Fonction : ".var_export($ploaroid_functions, True));


                                    $values["PFUNCTION"] =  $ploaroid_functions["POLROID_FCT"];

                                    if ( isset($ploaroid_functions["CHILD_FCT"])  )
                                    {



                                                    $child  = $datas[$key];

                                                    $childModel = $this->getModelsFromDB($ploaroid_functions["CHILD_MID"]);
                                                    $childModel = $childModel[0];



                                                    $child = array(
                                                                                            "CRMID"     => $values["CRMID"] != ""  ? $values["CRMID"]  : DEFAULT_CRM_UID,
                                                                                            "SERIAL"    => $values["_sndSerial"],
                                                                                            "BRAND"     => $childModel["BrandName"],
                                                                                            "MODEL"     => $childModel["Model"],
                                                                                            "PFUNCTION" => $ploaroid_functions["CHILD_FCT"],
                                                                                            "HOSTNAME"  => $values["HOSTNAME"],
                                                                                            "MAINIP"    => "",
                                                                                            "CPUNUMBER" => $this->get_modelCpuNumber( $ploaroid_functions["CHILD_MID"] ),
                                                                                            "OSNAME"    => "-- none --",
                                                                                            "RAMSIZEMB" => $this->get_modelRamQty($ploaroid_functions["CHILD_MID"] ),
                                                                                            "ENCBOARD1" => $this->encryptionBoard2Polaroid($values["ENCBOARD1"]),
                                                                                            "ENCBOARD2" => $this->encryptionBoard2Polaroid($values["ENCBOARD2"]),
                                                                                            "ENCBOARD3" => $this->encryptionBoard2Polaroid($values["ENCBOARD3"])
                                                                             );




                                                    
                                                    
                                                    $array[] = $child;
                                                    $values["ENCBOARD1"] = "";
                                                    $values["ENCBOARD2"] = "";
                                                    $values["ENCBOARD3"] = "";
                                                    
                                                    

                                    }
                                
                                    
                                    unset($values["_modelId"]);
                                    unset($values["_sndSerial"]);
                                    $array[] = $values;
                                    

                                    
                                    

                                    
                            }
                        }
                            parent::execq("UPDATE tblOrders SET exported=1 WHERE salesOrder=$so");
                            $this->printdbg("Datat to be written".var_export($array,True));
                            $data = $this->ToCSV($array);
                            $this->printdbg("csv check : ".var_export(str_getcsv($data),true));
                            return $data;
                        
		 }
		 else
			throw new DBWRAPPERMetadata("Polaroid export is disabled", 118);
	}
        else
            throw new DBWRAPPERMetadata("Already exported", 119);
            
        
            
        
     }
  }  

?>
