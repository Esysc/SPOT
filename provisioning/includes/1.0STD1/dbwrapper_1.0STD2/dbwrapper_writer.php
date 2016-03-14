<?php

	require_once "dbwrapper_loader.php";

	
	
	
	class DBWRAPPERWriter extends Exception 
	{
		private $head = "[DBWWriter]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	
	class DBWWriter extends DBWLoader
	{
	private $polaroid_fieldsArray;
	
	public function removeOrder($salesOrder)
	{
		
		
		$count=0;
		if ( $this->isReadOnlyAccess() ) throw new DBWRAPPERWriter("Access denied");
		
		if ( ! parent::orderExist($salesOrder) ) 
		{
			
			throw new DBWRAPPERWriter("Sales order not found");
		}
		
		$creator = parent::basic_select(TBLORDERS,"Salesorder='$salesOrder'",array("SysprodActor"));
		$creator = $creator[0];
		
		if ( strtoupper($this->getInstancier()) != strtoupper($creator) )
			if ( $this->getInstancier() != $this->configuration->get("adminUser"))
			{
				
				throw new DBWRAPPERWriter("User [".$this->getInstancier()."] don't have the ownership");
			}
			
		parent::_startTransaction();
		$tables = $this->listTableItemForSo($salesOrder);
		
		
		$networks = $this->delete_row(TBLNETWORKS, "Salesorder = $salesOrder");
		
		
		
		foreach ( $tables as $index=>$table)
		{



			$this->delete_row($table, "salesOrder = $salesOrder");
			$count++;
			
			
		}
		
		$this->delete_row(TBLORDERS, "Salesorder = $salesOrder");
		$count++;
		parent::_commitTransaction();
		parent::syslog_msg(LOG_WARNING, "Removing order $salesOrder");
		return $count;
		
	}	
	

	
	
	private function polaroid_formatHostname($hostname)
	{
		$formattedHostname = "";
		if ($hostname == "" ) return "";
		$char  = 0;
		while (  ctype_alpha( $hostname[$char++] ) )
			$formattedHostname .= $hostname[$char - 1];
		
		
		return $formattedHostname;
		
	}
	private function polaroid_findFctFromHst($hostname)
	{
		$return = parent::e_select(POLAROID_FCTTABLE, array("hstrule" => $this->polaroid_formatHostname($hostname)), True,
											array("ID", "VALUE", "childID")
						);
		
		if ( empty($return) )  return -1;
			
		$return = $return[0];
		
		$value = $return[1];
		$id   = $return[0];
		
	
		if ( $return[2] != '')
		{
			
			$childID = $return[2];
			
			$child = parent::basic_select(POLAROID_FCTTABLE, "ID=$childID", array("VALUE", "hstrule"));
			
			if ( ! empty($child) ) 
			{
				if ( isset($child[0][1]) )
				{
					$childField = $child[0][1];
					return array("function" => $value , 
								  "childFunction" => $childField);
					
				}
			}			
			
		}
		if ( $value == '') return false;
		return array("function" => $value);
		
	}
	
	private function polaroid_formatSN($sn)
	{
		$CHAR_RMOVE = array ( '-' );
		return str_replace($CHAR_RMOVE, '', $sn);
	}
	
	private function add_to_polaroidArray($CrmID, $line)
	{
		$lineToAdd = array();
		$count=0;
		if ( $CrmID == NULL or ! is_array($line) or empty($line)) return False;
		
		
		if (! isset($line[POLAROID_HOSTNAME_FLD])  )
		{
			$function = parent::basic_select(POLAROID_FCTTABLE, "ID='0'",array("VALUE"));
			$function = array ( "function" => $function[0]);
		}
		else
			$function = $this->polaroid_findFctFromHst($line[POLAROID_HOSTNAME_FLD]);
		
		
		if ( isset($function["childFunction"]) )
		{
			$key = false;
			foreach ( $line as $index=>$value)
			{
				if ( strpos($index, $function["childFunction"]) !== FALSE)
				{
					$key = $index;
					break;
				}
			}
			if ( $key !== FALSE)
			{
				
				$this->polaroid_fieldsArray[] = array( 
										"CRMID" => $CrmID,
										"name"  => $function["childFunction"], 
										"value" => $line[$key],
										"parentSN" => $this->polaroid_formatSN($line["serial"])
										);
				unset($line[$key]);	
				$count++;
				
			}
		}		
		$this->polaroid_fieldsArray[] = array( 
								"CRMID" => $CrmID,
								"name"  => $function["function"],
								"value" => $this->polaroid_formatSN($line["serial"])
							);
		

			
		return $count++;
		
		
		
	}
	public function polaroid_export($destPath="/tmp")
	{
		$str = "";
		
		if ( $this->configuration->get("polaroidExport") )
		{
			if ( empty($this->polaroid_fieldsArray) ) 
				throw new EXPORTER_EX("No lines to export!.");
			
			
			foreach ( $this->polaroid_fieldsArray as $entry=>$values)
			{
				
				$str .= implode(",",$values);
				$str .= "\n";
			}
			
			return $str;
		}
		
		
		
	}	
	
  /*
    **************************************************************************** 
    * function : saveOrder 
    * scope    : public    
    * usage    : save an order in the database
    * IN      : $order 
    * OUT     : True if success
	* Note    : The spec sheet uploa
    **************************************************************************** */     
    public function saveOrder(ORDER $order, $write=True)
    {
		
		
		if ( $this->getMaintenanceEnabled() )
		{
			$this->storeOrder($order, $origin="internal", $status=1, $message="Maintenance in progress");
		}
		
		if ( $this->orderExist($order->getSalesOrder()))
		{
			
			throw new DBWRAPPERWriter("An order was previously saved with the same sales order", 110);
		}
		if ( ! $order->canBeCommited()) throw new DBWRAPPERWriter("Order is not ready to be commited, please fill all the required information");
	
		$cur_so =  $order->getSalesOrder();
		
		
	
		parent::_startTransaction();
		
		$OrderAtr = array(
							 "Salesorder" =>$cur_so,
							 "ProgramManager" => $order->getProgramManager(),
							 "SiteEngineer" => $order->getSiteEngineer(),
							 "SysprodActor" => $order->getSysprodActor(), 
							 "Release" => $order->getRelease(),
							 "comment" => $order->getComments(), 
							 "StartDate" =>  date("Y-m-d", strtotime($order->getStartDate())), 
							 "EndDate" => date("Y-m-d", strtotime($order->getEndDate())),
							 "Customer" => $order->getCustomer(), 
							 "CCTSnapshotPath" => $order->getCCTSnaptshot(),
							 "prodStartDate" => date("Y-m-d", strtotime($order->getProdStartDate())), 
							 "prodEndDate" => date("Y-m-d", strtotime($order->getProdEndDate())),
							  "customerSigle" => $order->getcustomerAcronym(),
							  "SID"           => $order->getCRMID(), 
							  
							  
						 );
		
		
		
		if ( ! in_array($order->getSysprodActor(), $this->getSysprodActors()) )
			throw new DBWRAPPERWriter("Sysprod actor doesn't exist");
		
		$crmID = $order->getCRMID();
		try
		{
			$this->insert_into($OrderAtr, TBLORDERS);
		}
		catch ( Exception $e)
		{
			if ( $e->getCode() == 2 )
			{
				
				throw new DBWRAPPERWriter("Order could not be saved into the database!");
			}
		}
	
		
		
	  
	 
	  
		if ( count($order->getNetworks()) > 0 )  
		{
			foreach ($order->getNetworks() as $nwkName => $ipAndMask)
				$this->insert_into(array("Name" => $nwkName, "IP" => $ipAndMask[0],
								     "mask" => $ipAndMask[1], "SalesOrder" => $cur_so), TBLNETWORKS);
		
		}
		
		
		$item_list = $order->getItemsById(True);
		$objects   = $order->get_obj_def();
		

		foreach ( $item_list as $categoryIndex=>$item)
		{
			
			
			$destContainer  = $this->get_catalog_container($categoryIndex);
			$keyName        = $this->get_add_KeyName_fromIDItem($categoryIndex);
			$cluster        = $this->is_cluster($categoryIndex);
			
			$mandatoryParam = $objects[$order->get_object_ref_from_DBID($categoryIndex)]->get_mandatory_parameters();
			$niceCategoryName = $objects[$order->get_object_ref_from_DBID($categoryIndex)]->get_category_name();
		
			foreach ( $item as $index=>$values)
			{
				if ( ! is_null($values) )
				foreach ( $values as $entryID=>$value)
				{
					if ( strpos($entryID, "no_serial") !== FALSE) 
					{
						parent::_rollback();
						throw new DBWRAPPERWriter("Parameter <b>[$keyName]</b> is mandatory! for item $niceCategoryName($entryID)", 113);
					}
					if ( ! is_null($value))
					{
						
						
						foreach ( $value as $index=>$val)
						{
												
							if ( array_search($index, $mandatoryParam) !==  FALSE)
							{
								
								if ( $val === NULL or $val === "" )
								{
											
									parent::_rollback();
									parent::printdbg(__LINE__."[writer]Error : Parameter not filled!");
									parent::printdbg(__LINE__."[val]".var_export($val, True));
									parent::printdbg(__LINE__."[index]".var_export($index, True));									
									
									throw new DBWRAPPERWriter("Parameter [$index] is mandatory! for item $niceCategoryName($entryID)", 113);
									

									
								}
								
							}
						}					

						if ( $cluster )
						{
							
							
							if ( isset($value["str_IDNode"]) )
							if (  $value["str_IDNode"] != '' )
							{
								
								
								if ( ($master = $value["str_IDNode"]) != "" )
								{
									$db_key = explode("_", $keyName);
									$db_key = $db_key[1];
									
									$idMaster =  $this->basic_select($destContainer, "salesOrder = $cur_so AND `$db_key` = '$master'", array("ID"));
									
									unset($value["str_IDNode"]);
									$value["int_IDNode"] = $idMaster[0];						   
								}
							}
							
			
							
							
							
						}
					if ( ! isset($value["int_idapc"]))
                                        {
						$this->printdbg("fix for int apcid");
                                            if ( is_string($value["int_idapc"]) || is_null($value["int_idapc"]))
                                            {
                                                $value["int_idapc"] = 0;
                                            }
                                        }	
					if ( array_key_exists("str_apccode", $value))
					{
						$id = parent::getAPCID($value["str_apccode"]);
						
						
						unset($value["str_apccode"]);
						if ( isset($value["apccode"] ) )
							unset($value["apccode"]);
						$value["idapc"] = $id;
						
						
						
						
						
					}
					$proceceedArry = $this->createProcessedArray( $value, $cur_so ,$keyName, $entryID);
					
					$this->printdbg(var_export($proceceedArry,true));						
					
					$this->add_to_polaroidArray($crmID,$proceceedArry);
					
					$this->insert_into(
												$proceceedArry,
												$destContainer,
												False,
												$keyName
											);						
					}
				}
			}
			
		}
		
		
		if ( $write ) 
		{
			$orderStored = $this->getStoredOrderIDFromSo($cur_so);
			if ( ! empty($orderStored) )
				foreach ( $orderStored as $index=>$cur_sto)
					$this->removeStoredOrder($cur_sto);
				
			
			parent::_commitTransaction();
			parent::syslog_msg(LOG_INFO, "Writing order $cur_so");
                        parent::update(array("id_status" => NOTIFY_STATUS_INST ), NOTIFY_TABLE, "salesOrder='$cur_so'");
		}
		else
			parent::_rollback();
			
		if ( $this->configuration->get("polaroidExport") === "true" )
		{
			$this->printdbg(count($this->polaroid_fieldsArray)." exported to polaroid!");
			$this->polaroid_export();
			parent::syslog_msg(LOG_INFO, "Polroid export done");
		}
		
               
                
		return True;
    
	}
	


		public function __construct($username, $password, $auth_method)
		{
			
			parent::__construct($username, $password, $auth_method);
			$this->polaroid_fieldsArray = array();
		}  
	
	}  

?>
