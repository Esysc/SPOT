<?
	require_once "order_geninfo.php";
	require_once "objects_defs/default.php";
	
	class ITEM_EX extends Exception 
	{
		private $head = "[ORDER_ITEM]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	
	class ITEMS extends GENINFO
	{
		private $items_method;
		private $items_extended_informations;
		private $objs_def;
		private $modelList;
		
		public function get_object_ref_from_DBID($db_id)
		{
			foreach ( $this->objs_def as $index=>$object)
			{
				if ( $object->get_id() == $db_id )
					return $index;
			}
			return NULL;
		}
		
		public function get_obj_def()
		{
			return $this->objs_def;
		}
		
		public function get_methodByType($type)
		{
			$getMethods = array();
			foreach ( $this->items_method as $methodName => $method)
			{
				
				if ( $method["type"] === $type ) 
				{
					$getMethods[] = $methodName;
				}
			}
			
			return $getMethods;
		}
		
                public  function purge_items()
                {
                        
			
			foreach ( $this->objs_def as $index=>$obj)
			{
				$obj->purge();
				
			}                
                }
                protected function get_items_list()
		{
			$single_list = array();
			$apc_list    = array();
			
			$methods = $this->get_methodByType("get");
			
			foreach ( $methods as $method_call )
			{
				$items = call_user_func(array($this, $method_call));
				if ( ! is_null($items) )
				{
					
					foreach ( $items as $index=>$values)
					{
						$objID = $this->items_method[$method_call]["reference"];
						
						$categoryName   = $this->objs_def[$this->items_method[$method_call]["reference"]]->get_category_name();
						$remoteNode = $this->objs_def[$this->items_method[$method_call]["reference"]]->is_cluster_member($index);
						
						if ( ! is_bool($remoteNode) ) $categoryName = "Cluster ".$categoryName;
						
						if ( intval($values["int_apcGID"]) < $this->get_min_gid() )
						{
							$single_list[$categoryName][$index]["data"] = $values;
							$single_list[$categoryName]["_objID_"] = $objID;
						}
						else
						{
							$groupID = intval($values["int_apcGID"]);
							$apcCode = $values["str_apccode"];
							$apc_list[$apcCode][$groupID][$categoryName][$index]["data"] = $values;
							$apc_list[$apcCode][$groupID][$categoryName]["_objID_"] = $objID;
						}
					}
				}
			}
			
			return array ( "SINGLE" => $single_list, "APC" => $apc_list);
		}
		
		public function getItemsById()
		{
			$methods = $this->get_methodByType("get");
			$returnedArray = array( );
			foreach ( $methods as $method)
			{
				$objIndex = $this->objs_def[$this->items_method[$method]["reference"]]->get_id();
				$returnedArray[$objIndex][] = call_user_func(array($this, $method));

			}
			return $returnedArray;
		}
		
		public function get_obj_ref_from_container($ctn)
		{
			$methods = $this->get_methodByType("get");
			foreach ($methods as $method)
			{
				$container_name = $this->objs_def[$this->items_method[$method]["reference"]]->get_container_name();
				if ( $container_name == $ctn)
					return $this->items_method[$method]["reference"];
			}
			return null;
		}
	
		
		public function getItems($legacyIndex=False)
		{
			$item_list = array();
			
			$methods = $this->get_methodByType("get");
			
			foreach ($methods as $method)
			{
				
				$out = call_user_func(array($this, $method));
				$container_name = $this->objs_def[$this->items_method[$method]["reference"]]->get_container_name();
				
				
				
				if ( ! $legacyIndex )
				{
					$categoryName   = $this->objs_def[$this->items_method[$method]["reference"]]->get_category_name();
					$idItem         = $this->items_method[$method]["reference"];
					$item_list[$categoryName.';'.$container_name.";".$idItem] =  $out;
					
				}
				else
					$item_list[$container_name] =  $out;
					
				
			}
			
			
			return $item_list;
			
		}

		
		public function count_items()
		{
			$item = $this->getItems();
			
			$count = 0;
			foreach ( $item as $key=>$val)
			{
				if ( ! is_null($val))
					$count += count(array_keys($val));
			}
			
			return $count;
		}
		
		public function analyseFromHostname($hostname)
		{
			
			switch(strtolower($hostname))
			{
				
				case  strpos($hostname, "aio")!== False:
					return 'add_item_aio';
				case strpos($hostname, "cms") !== False:
				case strpos($hostname , "racdb") !== False:				
					return 'add_item_dbserver';
				case strpos($hostname , "ims") !== False:	
				case strpos($hostname , "sas") !== False:	
				case strpos($hostname , "cas") !== False:				
				case strpos($hostname , "prm") !== False:
					return 'add_item_cas';
				
				case strpos($hostname , "san") !== False:
					return 'add_item_san';
				
				case strpos($hostname,  "vio")!== False:
				case strpos($hostname , "ims") !== False:
				case strpos($hostname , "rtm") !== False:
				case strpos($hostname , "sdp") !== False:
					return 'add_item_applicationserver';
				
				case strpos($hostname , "nsm") !== False:
					return 'add_item_nimserver';
				
				case strpos($hostname , "vpn") !== False:
				case strpos($hostname , "fsw") !== False:
				case strpos($hostname , "esw") !== False:
				case strpos($hostname , "acs") !== False:			
					return 'add_item_network';
			
				
				case strpos($hostname , "mgt") !== False:
					return 'add_item_mgt';
				case strpos($hostname , "mdi") !== False:
				case strpos($hostname , "ecb") !== False:
				case strpos($hostname , "ecs") !== False:
				case strpos($hostname , "emb") !== False:
				case strpos($hostname , "eme") !== False:
				case strpos($hostname , "cps") !== False:
					return 'add_item_peripheral';
				
				default:
					return Null;
				
			}
		}
		protected function __construct($so, $modelList)
		{
			
			parent::__construct($so);
			$this->setModelList($modelList);
			
			
			
			
			
		}
		public function setModelList($modelList)
		{
			$this->modelList = $modelList;
		}
		public function __call($name, $arguments)
		{
			
			if ( isset($this->items_method[$name]))
			{
				if ( $this->items_method[$name]["type"] == "remove")
				{
					$gid = call_user_func_array(array(
										$this->objs_def[$this->items_method[$name]["reference"]],
										$name
					), 
					$arguments);
					
					
					foreach ( $this->objs_def as $index=>$object)
						$object->unset_gid($gid);
					
					return $gid;
					
					
				}
				else
					return call_user_func_array(array(
											$this->objs_def[$this->items_method[$name]["reference"]],
											$name
						), 
						$arguments);
			}
			throw new ITEM_EX("Method [$name] not found! or inacessible in this context"); 
			
				
			
		}
		
		public function get_item_list()
		{
				return $this->objs_def;
		}
	
		public function get_item_objects($id=-1)
		{
			if ( $id == -1) return $this->objs_def;
			return $this->objs_def[$id];
		}
		public function register_item($item_definition, $itemObject)
		{
			$this->objs_def[$item_definition["db_categoryID"]] = $itemObject;
			$IndexOfObject = $item_definition["db_categoryID"];
			
			$this->items_method[$item_definition["call_add"]] = array(
																"parameters" => $item_definition["add_params"],
																"reference"  => $IndexOfObject,
																"type" 		 => "add"
																	);
			$this->items_method[$item_definition["call_get"]] = array(
																"parameters" => $item_definition["get_params"],
																"reference"  => $IndexOfObject,
																"type" 		 => "get"
																	);	
			$this->items_method[$item_definition["call_update"]] = array(
																"parameters" => $item_definition["update_params"],
																"reference"  => $IndexOfObject,
																"type" 		 => "update"
																	);	
			$this->items_method[$item_definition["call_remove"]] = array(
																"parameters" => $item_definition["remove_params"],
																"reference"  => $IndexOfObject,
																"type" 		 => "remove"
			);
		
			
			
			
			$this->objs_def[$IndexOfObject]->set_add_method($item_definition["call_add"], $item_definition["add_params"]);
			$this->objs_def[$IndexOfObject]->set_get_method($item_definition["call_get"], $item_definition["get_params"]);
			$this->objs_def[$IndexOfObject]->set_update_method($item_definition["call_update"], $item_definition["update_params"]);
			$this->objs_def[$IndexOfObject]->set_remove_method($item_definition["call_remove"], $item_definition["remove_params"]);
			$this->objs_def[$IndexOfObject]->set_category_name($item_definition["category_name"]);
			$this->objs_def[$IndexOfObject]->set_container_name($item_definition["container_name"]);
			$this->objs_def[$IndexOfObject]->set_id($item_definition["db_categoryID"]);
			$this->objs_def[$IndexOfObject]->set_max_gid($this->get_min_gid());
			
			
			
			
			if ( $item_definition["clusterCapable"] )
				$this->objs_def[$IndexOfObject]->set_cluster_capable();
							
			
		}
		public function merge_orders_items($order)
		{
			
			$sndObj = $order->get_item_list();
			$count = 0;
			foreach ( $this->objs_def as $index=>$obj)
			{
				$container = $obj->get_container_name();
				
				foreach ( $sndObj as $index=>$sndobject)
				{
					if ( $sndobject != null)
					{
						if ( $sndobject->get_container_name() == $container )
						{
							$count += $this->objs_def[$index]->merge_container($sndobject->get_item());
						}
					}
					
				}
				
				
			}
			
			return $count;
		
		}
		public function is_cluster_capable($container_name)
		{
			
			$i = count( $this->objs_def );
			$clCap = NULL;
			
			foreach($this->objs_def as $Index=>$object)
			{
				
				if ( $object->get_container_name() === $container_name )
				{
					
					$clCap = $object->cluster_capable();
					break;
				}
				
			}
			return $clCap;
			
	
		}
		
		public function get_parameters_method($method)
		{
			return $this->items_method[$method]["parameters"];
		}
		
		public function get_methods_list()
		{
			return array_keys($this->items_method);
		}
		public function getModelFromID($id)
		{
			
			if ( ! is_int($id) ) throw new ITEM_EX("WRONG_PARAMS");
			

			
			foreach ($this->getModels() as $curID=>$value)
			{


				if ( $id == $value["IDModel"] ) 
				{
					$model_line = array();
					
					$model_line["APCCODE"] = $value['Description'];
					$model_line["BRAND"]   = $value['BrandName'];
					$model_line["MODEL"]   = $value['Model'];
					
					
					if ( $value["extendedInformation"] != "NULL")
						$model_line["IBMMT"]   = $value["extendedInformation"];
					
					
					
						
					
					return  $model_line;

				}
				
			}
			return Null;
			
			
			
		}
		
		public function enable_sanity_checks()
		{
			foreach ( $this->objs_def as $index=>$object)
				$object->enable_sanity_checks();
		}
		
		public function disable_sanity_checks()
		{
			foreach ( $this->objs_def as $index=>$object)
				$object->disable_sanity_checks();		
		}
		
		public function getModels($item_name=null)
		{
			
			if ( $item_name == null)  return $this->modelList;
			
			
			if ( ! in_array($item_name, array_keys($this->getItems(True) ) ) ) 
			{
					
					return null;
			}
			$models = $this->modelList;
			
			$tmp = array();
			
			
			foreach ( $models as $index=>$value)
			{
				
				
				$allowed = explode(";",$value["allowedItems"]);
				
				
				
				
				if ( in_array( $item_name,$allowed)) 
				{
					
					$tmp[$index] = $value;
					
				}
				
					
				
				
				
				
					
			}
			
			
			
			return $tmp;
			
			
		}		
		
		
	}


?>
