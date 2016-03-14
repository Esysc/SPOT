<?php
	require_once "dbwrapper_models.php";

	
	class DBWRAPPERLOADER extends Exception 
	{
		private $head = "[DBWLoader]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	

  class DBWLoader extends DBWModels
  {

	public function register_items(ORDER &$Order)
	{
		$itemSuccessFullyLoaded = 0;
		
		$idList = $this->get_itemID_list();
		
		if ( empty($idList) ) throw new DBWRAPPERINIT("No enabled items found");
		
		foreach ( $idList as $idOfItem)
		{
			try 
			{
				$this->register_new_item($Order, intval($idOfItem));
				$itemSuccessFullyLoaded++;
			}
			catch ( Exception $e)
			{
				//throw new DBWRAPPERINIT("Item [$idOfItem] could not be loaded!");
				$this->printdbg("Item id[$idOfItem] could not be loaded!", True);
				continue;
			}
		}
		return $itemSuccessFullyLoaded;
		
	}  
	public function register_new_item(&$Order, $IDItem)
	{
			
		
			
			$Order->register_item(array(
										"call_add"      => $this->get_catalog_method($IDItem, "add"),
										"add_params"    => $this->get_catalog_params($IDItem, "add"),
										"call_get"      => $this->get_catalog_method($IDItem, "get"),
										"get_params"    => $this->get_catalog_params($IDItem, "get"),
										"call_update"   => $this->get_catalog_method($IDItem, "update"),
										"update_params" => $this->get_catalog_params($IDItem, "update"),
										"call_remove"   => $this->get_catalog_method($IDItem, "remove"),
										"remove_params" => $this->get_catalog_params($IDItem, "remove"),
										"category_name" => $this->get_catalog_category($IDItem),
										"logo"			=> NULL,
										"container_name"=> $this->get_catalog_container($IDItem),
										"db_categoryID" => $IDItem,
										"clusterCapable" => $this->is_cluster($IDItem)
										),
									$this->retreiveCatalogObject($IDItem)
									);
	
								
	}  

     /*
    **************************************************************************** 
    * function : getItems 
    * scope    : public    
    * usage    : return the list of aviabale items, without the item_
    * IN      : None
    * OUT     : array of table beginning with item_ meaning that an item
    * Note    : Later, the wrapper will use more developped algo to find item in
    *           the database . item_database is not considered since it's not an item
	*			this should be corrected later by renaming it to database instead of item_database
	*			Generate warnings ? 
    **************************************************************************** */  
	
	
	
	public function getItems($showNewName=False)
	{
		
		if ( $showNewName )
			return  parent::basic_select(CAT_ENABLED_ITEM, "",array("legacy_name", "name", 'container', "IDItem"));
		return  parent::basic_select(CAT_ENABLED_ITEM, "",array("legacy_name", "IDItem"));
		
	}
	protected function loadData_build_arguments($params, $valuesIndexByParmName)
	{
		
		
		
		
		
		if (empty($params) or empty($valuesIndexByParmName)) throw new DBWRAPPERLOADER("[loadData_build_arguments]Empty arguments!");
		
		
		


		
		$excpeted = array();
		$excpectedParamType = array();

		
		foreach ($params as $parm)
		{

			$CorrectedName = explode("_", $parm);
			if ( count($CorrectedName) > 0)
				$excpectedParamType[]  = $CorrectedName[0];
			$CorrectedName = end($CorrectedName);
			$excpeted[] = strval($CorrectedName);
			
			
		}
		
		
		
		/*Remove the unnessary cells present in the database */
		$DB_SPEC_PARAMS = array(
									"salesOrder",
									"ID",
									"Type",
									"polaroid_functionID"
								);
		

		
		
		$sortedArray = array();
		$nbItem = count($valuesIndexByParmName);
		
		$sortedArray = array_pad($sortedArray, $nbItem, NULL);
		
		foreach ( $valuesIndexByParmName as $pramName=>$paramVal)
		{
			if ( in_array($pramName, $DB_SPEC_PARAMS))
			{
				unset($valuesIndexByParmName[$pramName]);
			}
			
				
		}
		
		foreach ( $valuesIndexByParmName as $pramName=>$paramVal)
		{
			
			
			if ( $pramName == "idapc" )
			{
				
				$pramName = "apccode";
				$paramVal = parent::getAPCCode($paramVal);
				if ( empty($paramVal) )
					$paramVal = "";
			}
			
			
			
			if ( ! in_array(strtolower($pramName), array_map('strtolower', array_values($excpeted))) )
			{
				$allowed_missing_params = array("polaroid_functionID", "cluster", "IDNode");
				if ( ! in_array($pramName, $allowed_missing_params))
				{
					$this->printdbg("[$pramName] found in the db that is not excpeted by the metdata");
					throw new DBWRAPPERLOADER("Parameter[$pramName] doesn't exist into metadata");
				}
				
			}
			else
			{
				$goodPos = array_search(strtolower($pramName), array_map('strtolower', array_values($excpeted)));

				if ( $goodPos === False) 
					$this->printdbg("key not found : ".$paramName);
				else
				{
					$goodPos = intval($goodPos);
					
				
						
					
					switch( $excpectedParamType[$goodPos])
					{
	
						case 'int':
							$sortedArray[$goodPos] =  intval($paramVal);
							break;
						case 'bool':
							$sortedArray[$goodPos] = $paramVal ? True : False;
							break;
						default:
							$sortedArray[$goodPos] = strval($paramVal);
							break;			
					}
	
					
				}
			}
			
			
			

		}

		
		
		return $sortedArray;
	}
	
     /*
    **************************************************************************** 
    * function : loadData 
    * scope    : private    
    * usage    : By searching with the sales order, fill the data of an order
    * IN      : $salesOrder  
    * OUT     : ORDER    
	* Note    : setSpecSheet isn't avaiable
    **************************************************************************** */ 	
	protected function loadData($salesOrder,  $models)
	{
		if ( ! $this->orderExist($salesOrder)) throw new DBWRAPPERLOADER("UNKNOW_SO");
		
		
		
		$order = new ORDER($salesOrder,$models);
		
		$fieldToShow = array
							(
							"Salesorder", 
							"ProgramManager",
							"SiteEngineer",
							"SysprodActor",
							 "Release",
							 "comment",
							 "StartDate",
							 "EndDate",
							 "Customer", 
							 "Timezone", 
							 "CCTSnapshotPath",
							 "prodStartDate", 
							 "prodEndDate",
							 "SID", 
							 "customerSigle"
							);
							
							
		$tblOrderFields = $this->basic_select(TBLORDERS, "Salesorder = '$salesOrder'", $fieldToShow); //Warning we're dependant of the order!!! 
		$tblOrderFields = $tblOrderFields[0];
	
		
		$order->setSalesOrder($tblOrderFields[0]);
		$order->setProgramManager($tblOrderFields[1]);
		$order->setSiteEngineer($tblOrderFields[2]);
		$order->setSysprodActor($tblOrderFields[3]);
		$order->setRelease($tblOrderFields[4]);
		$order->setComments($tblOrderFields[5]);
		$order->setRealStartDate($tblOrderFields[6]);
		$order->setRealEndDate($tblOrderFields[7]);
		
		
		$order->setCustomer($tblOrderFields[8]);
		$order->setCRMID($tblOrderFields[13]);
		$order->setcustomerAcronym($tblOrderFields[14]);
		$order->setCCTSnapshotPath($tblOrderFields[10]);
		$order->setProdStartDate($tblOrderFields[11]);
		$order->setProdEndDate($tblOrderFields[12]);
		
		
		$this->register_items($order);
		
		foreach ($this->getNetwork($salesOrder) as $cur_net)
			$order->add_network($cur_net[0], $cur_net[1], $cur_net[2]);
		
		
		$count = 0;
		$clusters = array();
		
		
		
		foreach ( $this->listTableItemForSo($salesOrder) as $modleID => $current_table ) 
		{
				
				$valueIndexedByColName = $this->getItemFromSo($current_table,$salesOrder, $modleID);
				
				foreach ( $valueIndexedByColName as $keys=>$itemLine)
				{
					
					$funcToCall = $this->getAddOrderMethodForTable( $order, $current_table);
					$catalogidItem = $this->get_itemIDFromContainer($current_table);
					$functionParamters  = $this->loadData_build_arguments($this->get_catalog_paramsBymethod($catalogidItem, $funcToCall),$itemLine);
					
					try
					{
						
						$out = call_user_func_array(array($order, $funcToCall), $functionParamters);
						
						if ( count($out) == 2 ) $clusters[$current_table] = $out[0]; 
						
							
						

						
					}
					catch (Exception $e)
					{
						$this->printdbg("Couln'd not load item");
						$this->printdbg($e->getMessage());
						throw $e;
					}
				
				
				    
				
					$count++;
				}
				
		}
		
	
		//$this->printdbg("loaded ".$count." items");
		return $order;
		
		
	}

	
	
	/*
	**************************************************************************** 
    * function : setDatabaseForSalesOrder 
    * scope    : private    
    * usage    : set the database for given so and order
    * IN      : (byref)$order and (byval)$salesorder
    * OUT     : nTrue
    * Note    : To avoid calling anonymly add_item_datase and loop over it this part trans   
    **************************************************************************** */	
	protected function setDatabaseForSalesOrder(ORDER &$order, $salesOrder)
	{
		
		$databases = array();
		
		$dbServersList = $this->basic_select(TBLDBSERVER, "salesOrder = '$salesOrder'", array("ID", "hostname"));
		
		$casServersList = $this->basic_select(TBLCASSERVER, "salesOrder = '$salesOrder'", array("ID", "hostname"));

		$dbCount = 0;
		
		foreach ( $casServersList as $index=>$value)
		{
			
			$casID = $value[0];
			$casHostname = $value[1];
			
			
			$tmp = $this->basic_select(TBLDATABASE, "IDCas = $casID",array("SID", "Size", "SOP","MOP", "IP"));
			
			foreach ($tmp as $key=>$val)
			{
				$order->add_item_database( $casHostname, $val[0], $val[1], $val[2], $val[3],$val[4]);
				$dbCount++;
			}
			
		}
		foreach ( $dbServersList as $index=>$value)
		{
			
			$dbServId = $value[0];
			$dbHostname = $value[1];
			
			
			$tmp = $this->basic_select(TBLDATABASE, "IDDBServer = $dbServId",array("SID", "Size", "SOP","MOP","IP"));
			
			foreach ($tmp as $key=>$val)
			{
				
				$order->add_item_database( $dbHostname, $val[0], $val[1], $val[2], $val[3],$val[4]);
				$dbCount++;
			}
			
		}
		
		return $dbCount;

	}
	protected function getRemoveOrderMethodsForTable(ORDER $order, $table)
	{
		
		$methodsTab = $order->get_methods_list();
		$functioName =  "remove_".$table;
		
		if ( array_search($functioName,$methodsTab) == False ) throw new DBWRAPPERLOADER("UNKNOW_METHOD");
		return $functioName;	
	}
/*
	**************************************************************************** 
    * function : getAddOrderMethodForTable 
    * scope    : private    
    * usage    : return the object's add method for a given table
    * IN      : $table : table in the database, $order object to look at 
    * OUT     : name of the function //string
    * Note    :     
    **************************************************************************** */		
	protected function getAddOrderMethodForTable(ORDER $order, $container)
	{
		
	
		$functionName = $this->get_catalog_method($this->get_itemIDFromContainer($container),"add");
		
		if ( empty($functionName)  ) throw new DBWRAPPERLOADER("Method for [$container] not found");
		return $functionName;
			
		
	}  
	public function __construct($username, $password, $auth_method)
	{
		parent::__construct($username, $password, $auth_method);
	} 
	public function getSearchByGeneralInformation()
	{
		return array("Salesorder" => 0, "ProgramManager" => "", "SiteEngineer" => "", "SysprodActor" => "",
					 "Release" => "", "StartDate" => "", "EndDate" => "", "Customer" => "", "Timezone" => "", 
					 "CCTSnapshotPath" => "");
					 
	}
	

	
	public function searchByGeneralInformation($ValIndexedByFName, $useLike=True, $fieldsToShow="*", $strict=True)
	{
		return $this->e_select(TBLORDERS, $ValIndexedByFName, $useLike, $fieldsToShow, $strict);
	}
	
	protected function sIBContent_strip_typeInVarName($array)
	{
		foreach ( $array as $key=>$index)
		{
			$val = "";
			$val = explode('_',$key);
			if ( count($val) > 1)
			{
				
				unset ($array[$key]);
				$array[$val[1]] = $index;
			}
			
		}
	
		return $array;
		
		
	}
	
	protected function sIBContent_removeEmptyCells($array)
	{
		foreach ( $array as $index=>$content )
			if ( empty($content) or $content == "Null" ) unset($array[$index]);
		return $array;
	}
	
	
	
	public function searchItemByContent($ValIndexedByFName, $useLike=True, $strict=True)
	{
		if ( ! in_array("itemName", array_keys($ValIndexedByFName))) throw new DBWRAPPERLOADER("1400#UNKOWN_ITEM");
		if ( ! in_array($ValIndexedByFName["itemName"], $this->getItemList()))
		{
			$this->printdbg($ValIndexedByFName["itemName"]." doesn't exist");
			throw new DBWRAPPERLOADER("1401#UNKOWN_ITEM");
		}
		if ( $ValIndexedByFName["itemName"] === "item_database" ) throw new DBWRAPPERLOADER("DB_CANNOT_BE_SEARCHED");
		
		$item_name = $ValIndexedByFName["itemName"] ;
		
		$fields = $this->sIBContent_removeEmptyCells($ValIndexedByFName);
		
		unset($fields["itemName"]);
		
		if ( isset($fields["formName"])) unset($fields["formName"]);
		
		
		
		
		$fields = $this->sIBContent_strip_typeInVarName($fields);
		

		$this->switch_mysql_rt_ASSOC();
		
		$db_items = $this->e_select(  $item_name,  $fields , $useLike, "*", $strict);
		
		if (empty($db_items)) return Null;
		
		
		
		$soList = array();
		
		foreach ( $db_items as $index=>$item )
		{

			if (in_array("salesorder", array_map('strtolower', array_keys($item))))
			{
				if (in_array("hostname", array_map('strtolower', array_keys($item))))
				{
					$temp = array_map('strtolower', array_keys($item));
					$temp = array_combine($temp, $item);
					$so = $temp["salesorder"];
					$hostname = $item["hostname"];
										
					$soList[$so][$hostname] = array_values($item);
					
					
					
					
					
				}
			}
		}
		
		
		
			
		return $soList;
	}
	//Description 	Brand 	Model 	allowedItems 	enabled
/*
	**************************************************************************** 
    * function : getNetwork 
    * scope    : private    
    * usage    : return the networks for a given so 
    * IN      : $salesorder : Sales order
    * OUT     : array of networks
    * Note    :     
    **************************************************************************** */		
	protected function getNetwork($salesorder)
	{
		return $this->basic_select(TBLNETWORKS,"SalesOrder = '".$salesorder."'", array("Name", "IP", "mask"));
		
	}
/*
	**************************************************************************** 
    * function : getItemFromSo 
    * scope    : private    
    * usage    : return the rows belonging to an sales order
    * IN      : salesOrder
    * OUT     : array of values indexed by columnName
    * Note    :     
    **************************************************************************** */	
	protected function getItemFromSo($table,$salesOrder,$cid=NULL)
	{
			
			$FieldNames = $this->ColToArray($table);
			$value = $this->basic_select($table, "salesorder = '$salesOrder'");
			if ( ! is_null($cid) )
					$keyName = $this->get_add_KeyName_fromIDItem($cid, True);
			$items = array();
			
			foreach ( $value as $index=>$content )
			{
			
				
				$item = array_combine(array_keys($FieldNames),$content );
				 
				if ( isset($item["IDNode"] )) 
				{
					
					if ( $item["IDNode"] == 0 ) $item["IDNode"] = Null;
					else
						{
							$idClu = $item["IDNode"];
							$mst_hst = parent::basic_select($table, "salesorder = '$salesOrder' and ID='$idClu'", array($keyName));
							$mst_hst = $mst_hst[0];
							$item["IDNode"] = $mst_hst;
							
						}
						
				}
				
				if ( isset($item["cluster"] )) 
				{
					
					if ( $item["cluster"] == 0 ) 
						$item["cluster"] = False;
					else
						$item["cluster"] = True;
						
				}
				
				
				$items[] = $item;
			}

			
			
			return $items;
 
	}		
  }	
?>