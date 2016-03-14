<?php
	set_include_path(get_include_path() . PATH_SEPARATOR . "/var/www/productiondb/1.0STD1/lib/:/var/www/productiondb/1.0STD1/shlib/");	
	require_once "commonFunctions.php";
	define("NO_GID", 0);
	class DEFAULT_ITEM
	{
		public  $version = "1.0";
		private $methods;
		private $container_name;
		private $container;
		private $add_method_ref;
		private $keyName;
		private $categoryName;
		private $logo;
		private $db_id;
		private $unsetkey;
		private $clusterCapable;
		private $cluster_field;
		private $max_gid;
		private $report;
		private $doSanityCheck;
		
		private $description;
		
		private function is_uniq($value,$param)
		{
			if ( !empty($this->container))
			{
				foreach ( $this->container as $key=>$values)
					if ( isset($values[$param]) )
						if ($values[$param] == $value )
							return False;
				
				
			}
			return True;
		}
		
		public function get_report_strings()
		{
			$out = array();
			
			foreach ( $this->report as $paramName=>$values)
			{
				if  ( $values["display"] !== false)
				{
					$out[$paramName] = $values;
				}
			}
			
			return $out;
		}
		
	
		public function get_display_report_param($param)
		{
			if ( isset($this->report[$param]) )
				return $this->report[$param]["display"];
			return $param;
			
		}
		
		public function set_display_report_param($param, $value)
		{
				$this->report[$param]["display"] = $value;
		}
		
		public function set_report_string($param, $str)
		{
			$this->report[$param]["string"] = $str;
		}
		
		public function get_report_string($param)
		{
			if ( isset($this->report[$param]["string"]) && $this->get_display_report_param($param) )
				return $this->report[$param]["string"];
			else
				return NULL;
		}
		
		private function removeNoneChar($str)
		{
			$newStr = "";
			for ( $i=0; $i < strlen($str); $i++)
			{
				if ( ctype_alpha($str[$i]) )
				{
					$newStr[] = $str[$i];
				}
			}
			
			return $newStr;
		}
		
                public function purge()
                { 
              
                   if ( $this->container !== NULL)
                   {                                
                     unset($this->container);
                   }
                }
                
		public function get_id()
		{
			return $this->db_id;
		}
		
		public function set_id($id)
		{
			$this->db_id = $id;
		}
		
	
		public function get_key_name()
		{
			return $this->keyName;
		}
		
		public function get_description()
		{
			return $this->description;
		}
		public function set_description($description)
		{
			$this->description = $description;
		}
		
		public function get_logo()
		{
			return $this->logo;
		}
		public function set_logo($b64Logo)
		{
			$this->logo = base64_encode($logo);
		}
		
		public function get_category_name()
		{
			return $this->categoryName;
		}
		
		public function set_category_name($val)
		{
			if ( empty($val) ) return false;
			return $this->categoryName = $val;
		}
		
		
		private function set_report_options($parameters)
		{
			
			
			
			foreach ( $parameters as $index=>$params)
			{
				
			
				
				if ( isset($params["report"]))
				{
					$this->set_display_report_param($params["name"], (bool)$params["report"]);
					unset($params["report"]);
					
				}
				
				
				if ( isset($params["reportString"]))
				{
					$this->set_report_string($params["name"],$params["reportString"]);
					unset($params["reportString"]);
					
				}
			
				
			}

			
			return $parameters;
		}
		
		
		public function get_add_parameters_array()
		{
			$parameters = $this->methods[$this->add_method_ref]["PARAMS"];
			if ( $parameters == NULL ) return -1;
			$array = array();
			
			
			foreach ( $parameters as $parameter )
			{
				$array[$parameter["pos"]] = $parameter["name"];
			}
			return $array;
		}
		public function set_add_method($name, $params)
		{
			
			
			$params = $this->set_report_options($params);
			
			$this->methods[$name] = array( "PARAMS"=> $params,
											"FUNC" => "add_item"
										);
			$this->add_method_ref = $name;
			
			
			
			
		}
		
		public function set_update_method($name, $params)
		{
			$this->methods[$name] = array( "PARAMS"=> $params,
											"FUNC" => "update_item"
										);	
		}
		public function set_cluster_capable()
		{
			$this->clusterCapable = true;
		}
		
		public function cluster_capable()
		{
			return $this->clusterCapable;
		}
		
		public function set_get_method($name, $params)
		{
			$this->methods[$name] = array( 
											"PARAMS"=> $params,
											"FUNC" => "get_item"
										);	
		}
		public function set_remove_method($name, $params)
		{
			$this->methods[$name] = array( "PARAMS"=> $params,
											"FUNC" => "remove_item"
										);
		}
		public function set_container_name($name)
		{	
			$this->container_name = $name;
		}
		public function get_container_name()
		{
			return $this->container_name;
		}
		
		public function get_nb_param()
		{
			return count($this->methods[$this->add_method_ref]["PARAMS"]);
		}
		public function get_add_parameters_expected_position( $paramater_name)
		{
			$parameters = $this->methods[$this->add_method_ref]["PARAMS"];
			if ( $parameters == NULL ) return -1;
			foreach ( $parameters as $parameter )
			{
					if ( $parameter["name"] == $paramater_name ) 
						return $parameter["pos"];
			}
			return -1;
		}
		
		public function __construct()
		{
			$this->methods = array();
			$this->unsetkey = 0;
			$this->clusterCapable = false;
			$this->cluster_field = "str_IDNode";
			$this->report = array();
			$this->doSanityCheck = true;
	
		}
		
		public function disable_sanity_checks()
		{
			$this->doSanityCheck = false;
		}
		
		public function enable_sanity_checks()
		{
			$this->doSanityCheck = true;
		}
		
		private function var_ex($message)
		{
			$file = fopen("/tmp/default.log",'a');
			fwrite($file, $message.'\n');
			fclose($file);
		}
		
		
		public function get_param_default_value($param)
		{
			$parameters = $this->methods[$this->add_method_ref]["PARAMS"];
			$found = false;
			$i =  0;
			$retVal = NULL;
			
			while ( ! $found  )
			{
				if ( $parameters[$i]["name"] == $param )
				{
					if ( isset($parameters[$i]["defval"]) )
					{
						$retVal = $parameters[$i]["defval"];
						settype($retVal, $parameters[$i]["type"]);
						
					}
					$found = true;
						
				}
				else
					$i++;
			}
			
			return $retVal;
		
		}
		public function add_item()
		{

			$parameters = $this->methods[$this->add_method_ref]["PARAMS"];
			$max_args = count($parameters);
			$min_args = 0;
			$key = NULL;
			
			$keysArray = array(); //Array containing the keys to be inserted
			$valuesArray = array(); //Array containing the values to be inserted
			$argsCount    = func_num_args();
			
			$indexHasBennFound = false; //Flag to know if an index has been found 
			$argsArray = func_get_args(); //Values sent to the function 
			
			
			foreach ( $parameters as $parameter )
			{
				
				$paramName     = $parameter["name"];
				$paramOptional = $parameter["optionnal"];
				$paramPos      = $parameter["pos"];
				$paramType     = $parameter["type"];
				
				if ( $paramType === "str" ) $paramType = "string";
				
				
				isset($argsArray[$paramPos])? $current_value= $argsArray[$paramPos] : $current_value  = NULL;
		
				if ( isset($parameter["defval"]) )
				{
					$paramDefVal = $parameter["defval"];
					settype($paramDefVal,$paramType);
				}
				else
					$paramDefVal = NULL;
				isset($parameter["index"])  ? $paramIndex   = $parameter["index"]   : $paramIndex     = false;
				isset($parameter["cluster"])? $paramCluster = $parameter["cluster"] : $paramCluster   = NULL;
				isset($parameter["unique"]) ? $paramUnique  = $parameter["unique"]  : $paramUnique    = false;
				
				

				
				if ( ! $paramOptional     ) $min_args++; 
				if ( $paramCluster != NULL)  $this->cluster_field = $paramCluster;
				if ( $paramUnique         )
				{
					if ( ! $this->is_uniq($argsArray[$paramPos], $paramName ) )  
						throw new Exception("[default]$paramName must be unique!");
				}
				
				if 	( $paramIndex and $indexHasBennFound == FALSE)
				{
					$this->keyName = $paramName;
					$key =  $argsArray[$paramPos];
					
					
					if ( $key == '' )
						$key = "no_serial".strval($this->unsetkey++);
					$indexHasBennFound = true;
				}
				else
				{
					
					$keysArray[$paramPos] = $paramName;
					
					if ( ! $paramOptional and  $current_value === NULL and $this->doSanityCheck ) 
						throw new Exception("Error mandatory field required ($paramName)".var_export($current_value, True), 4);
				
					
					if ( $paramOptional and  $current_value === NULL  )
					{
						
						$valuesArray [ $paramPos ]  = $paramDefVal;
						
						
					}
					else
					{

						settype($current_value, $paramType);
						
						$valuesArray[ $paramPos ] = $current_value;
						
						
						
	
					}
				}
				
				
			}
			
			
			if ( $argsCount < $min_args ) throw new Exception("Invalid number of arguments!");
			
			if ( ! $indexHasBennFound ) throw new Exception("Error : No index defined!, check metadata(s)!");
			
			
			$this->container[str_replace( ' ', '', $key )] = array_combine($keysArray, $valuesArray);
			
			
			
			
			
			
			return $key;
					
		}
		
		
		public function is_cluster_member($key)
		{
			
			if ( ! $this->cluster_capable() ) return False;
			
			
			if ( $this->container[$key][$this->cluster_field] != NULL)
			{
				
				$key_master = $this->container[$key][$this->cluster_field];
				
				if ( isset( $this->container[$key_master] ) )
					return $key_master;
			}	
			
			
			
			foreach ( $this->container as $index=>$values)
			{
				
				if ( $values[$this->cluster_field] === $key)
				{
					
					return $index;
				}
			}
		
		
			return False;
			
			
		}
		
		public function get_item()
		{
			
			if ( func_num_args() == 0 ) 
				return $this->container;
			else
				return $this->container[func_get_arg(0)];
				
		}
		
		private function update_item_index($oldIndex, $newIndex)
		{
			$newIndex = str_replace( ' ', '', $newIndex );
			if ( !  isset($this->container[$newIndex] ) ) /* We found that there is no prexistant index*/
			{
				$this->container[$newIndex] = $this->container[$oldIndex];
				
				if ( $this->cluster_capable() )
				{

					foreach ($this->container as $index=>$param)
					{
						
						if ( $param[$this->cluster_field] == $oldIndex )
						{
							$this->container[$index][$this->cluster_field] = $newIndex;
							
						}
					}
				}
				
				
				unset($this->container[$oldIndex]);
				
				return $newIndex;
			}
			else
				return False;		
		}
		
		public function update_item($key, $param, $value)
		{
			
			
			
			if ( $param === $this->keyName )
			{
				
				return $this->update_item_index($key, $value);
			}
			
			
			
			if ( isset($this->container[$key][$param] ) or  array_search($key, array_keys($this->container)) !== FALSE)
			{
				
				$parameters = $this->methods[$this->add_method_ref]["PARAMS"];
				
				foreach ( $parameters as $parameter)
				{
				
					if ( $parameter["name"] === $param and $parameter["unique"])
					{
		
						if ( ! $this->is_uniq($value,$parameter["name"]) )
						{
							
							return False;
						}
					}
					
					if  ( $parameter["index"] == TRUE AND $parameter["name"] === $param)
						$changeIndex = true;
					
				
				}
	
		
				
				$this->container[$key][$param] = $value;
				return $key;
			}
			
			
			
		return False;
		}
		
		public function set_max_gid($id)
		{
			$this->max_gid = $id;
		}
		
		public function get_max_gid()
		{
			return $this->max_gid;
		}
		
		
		public function get_cluster_field()
		{
			return $this->cluster_field;
		}
		public function remove_item($key)
		{
			
			
			
			$gid = 0;
			if ( isset($this->container[$key]) )
			{
				
				
				
				
				if ( isset($this->container[$key]["int_apcGID"]))
				{
					$gid = $this->container[$key]["int_apcGID"]; //////////////////TO BE REMOVED
				}
				
				if ( $this->cluster_capable() )
				{
				
					
					if ( $this->container[$key][$this->get_cluster_field()]  != '')
					{
						
						
						$mstNode = $this->container[$key][$this->get_cluster_field()];
						
						unset ( $this->container[$mstNode] );
						
					
					}
					else
					{
						
						$field =  $this->get_cluster_field();
						
						foreach ( array_keys($this->container) as $index )
						{
						
							
							if ( $this->container[$index][$field] == $key)
							{
								
								
								unset( $this->container[$index] );
								
								break;
							}
						}
					}
					
				}
				unset($this->container[$key]);
				
			}
			
			return  $gid ;
				
		}
		
		public function unset_gid($gid)
		{
			$unsetCount=0;
			if ( count($this->container)>0)
			{
				
				foreach ( $this->container as $index=>$values)
				{
					
					if ( isset($values["int_apcGID"] ) )
					{
						
						if ( $values["int_apcGID"] == $gid )
						{
							
							$this->container[$index]["int_apcGID"] = NO_GID;
							$this->container[$index]["apccode"] = "";
							$unsetCount++;
						}
					}
				}
			}
			return $unsetCount;
		}
		
		public function __set($name, $value)
		{
			if ( isset($this->container[$name]))
				$this->container[$name] = $value;
			else
				throw new Exception("$name not found [__set]");			
		}
		
		public function merge_container($newArray)
		{
			if ( count($this->container) == 0 and count($newArray) > 0 )
			{
				$this->container = $newArray;
				
				return count($this->container);
			}
			else if ( count($this->container) > 0 and count($newArray) > 0 ) 
			{
				if ( count(array_keys($newArray)) != count(array_keys($this->container)) )
					return 0;
				else
				{
					
					
					$this->container = array_merge($this->container, $newArray);
					
					return count($this->container);
				}
			
			}
			else
				return 0;
			
		}
		
		public function __get($name)
		{
			if ( isset($this->container[$name]))
				return $this->container[$name];
			else
				throw new Exception("$name not found [__get]");
		}
		
		public function __call($name, $arguments)//$name, $arguments)
		{
			if ( isset($this->methods[$name] ) )
			{
				
				return call_user_func_array(array($this, $this->methods[$name]["FUNC"]),$arguments);
			}
			else
				throw new Exception("$name not found [__call]");
		}
		
		public function __unset($name)
		{
			if ( isset($this->container[$name]))
				unset($this->container[$name]);
			else
				throw new Execption("$name not found [__unset]");				
		}
		public function __toString()
		{
			return $this->get_id().".".$this->get_category_name()." DEFAULT(".$this->version.")";
		}
		public function get_mandatory_parameters()
		{
			$mandatoryParams = array();
			
			foreach (  $this->methods[$this->add_method_ref]["PARAMS"] as $parameter )
			{
				
				if ( $parameter["optionnal"] !== true or $parameter["index"] === "true" )
				{
					
					$mandatoryParams[] = $parameter["name"];
				}
		
			}
			
			return $mandatoryParams;
		}
		

	}
	

			
			
	


?>
