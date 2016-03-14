<?php

	require_once "dbwrapper_metadata.php";
	require_once "commonFunctions.php";

	
	
	class DBWRAPPERModels extends Exception 
	{
		private $head = "[DBWModels]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}	
	class DBWModels extends DBWMetadata
	{
	public function __construct($username, $password, $auth_method)
	{
		parent::__construct($username, $password, $auth_method);
	} 

	public function insertNewModel($fields)
	{
		if ( count(array_diff(array_keys($field), $this->getModelsColName()) > 0 )) throw new DBWRAPPERModels("INVALID_PARAMS");
		parent::syslog_msg(LOG_NOTICE, "New model is inseted :".var_export($fields, True));

		return $this->insert_into($fields,TBLMODELS);
		
		
	}
	
	public function getModelsColName()
	{
		return $this->getColumnNames(TBLMODELS);
	}
	
	public function getFullModelsFromDB($modelID=NULL)
	{
		$this->switch_mysql_rt_ASSOC();
		
		if ( $modelID !== NULL and is_int($modelID)) 
			return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand AND IDModel='$modelID'", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model", "extendedInformation"));
		return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model", "extendedInformation"));
		
		 
	}
	
	public function getDisabledItems($modelID=NULL)
	{
		$this->switch_mysql_rt_ASSOC();
		
		if ( $modelID !== NULL and is_int($modelID)) 
			return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand AND IDModel='$modelID' AND enabled='0'", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model"));
		return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand AND enabled='0'", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model"));	
	}
      /*
    **************************************************************************** 
    * function : ColToArray 
    * scope    : private    
    * usage    : return an assosiative array (indexed by column name)
    * IN      : $tableName : Name of the table 
    * OUT     : array indexed by column name
     **************************************************************************** */   
    protected function ColToArray($tableName)
    {
      $columns = $this->getColumnNames($tableName);
   
      $buffer = array();
      
      foreach($columns as $col)
		$buffer[strval($col)] = ""; 
		
      
      

     
      return $buffer;
          
    }
     /*
    **************************************************************************** 
    * function : getItemFullList 
    * scope    : private    
    * usage    : return an assosiative array (indexed by column name)  only for item
    * IN      : $tableName : Name of the table 
    * OUT     : array indexed by column name
    * Note    : Not used at this time, reserved for futur usage    
    **************************************************************************** */     
    protected function getItemFullList()
    {
      $itemList = $this->getItemList();
      foreach ($itemList as $item)
        $mapped[$item] = $this->ColToArray($item);  
      return $mapped;
      
    }	
	public function getBrands()
	{
		return $this->basic_select(TBLBRANDS, "",  array("BrandName", "IDBrand"));
	}
     /*
    **************************************************************************** 
    * function : getItemList 
    * scope    : private    
    * usage    : return the list of aviabale items
    * IN      : None
    * OUT     : array of table beginning with item_ meaning that an item
    * Note    : Later, the wrapper will use more developped algo to find item in
    *           the database . item_database is not considered since it's not an item
	*			this should be corrected later by renaming it to database instead of item_database
    **************************************************************************** */    
    protected function getItemList()
    {
	
       
	   return $this->getModelContainer(False);

    }	
	
	public function getBrandIDFromName($brandName)
	{
		return $this->basic_select(TBLBRANDS, "BrandName='$brandName'", "IDBrand");
	}
	public function getBrandFromID($idBrand)
	{
		return $this->basic_select(TBLBRANDS, "IDBrand='$idBrand'", "BrandName");
	}
	protected function getModelName($id)
	{
		$out= parent::basic_select(TBLMODELS, "IDModel=$id","Model");
		if ( isset($out[0]))
			return $out[0];
		else
			return "<no model name>";
	}
	public function updateModel($modelID, $newFields)
	{
		if ( $modelID > 0 and  ! $this->isReadOnlyAccess() )
		{
			if ( ($brandName = array_search("BrandName", array_keys($newField))) )
			{
				$newFields["IDBrand"] = $this->getBrandIDFromName($newFields["BrandName"]);
				unset($newFields["BrandName"]);
			}
			parent::syslog_msg(LOG_NOTICE, "Model id=$modelID is updated :");
			return $this->update($newFields, TBLMODELS, "IDModel='$modelID'");
		}
		throw new DBWRAPPERModels("CANNOT_UPDATE");
	}
	
	
/*
	**************************************************************************** 
    * function : getModelsFromDB 
    * scope    : public    
    * usage    : return the orders from the database
    * IN       : None 
    * OUT      : array of model indexed by ID
    * Note     :     
    **************************************************************************** */	
	public function getModelsFromDB($modelID=NULL)
	{
		$this->switch_mysql_rt_ASSOC();
		
		if ( $modelID !== NULL and is_int($modelID)) 
			return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand AND IDModel='$modelID'", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model", "extendedInformation"));
		return $this->basic_select(array(TBLMODELS,TBLBRANDS), TBLBRANDS.".IDBrand=".TBLMODELS.".IDBrand", array("IDModel", "Description", "allowedItems","enabled", TBLBRANDS.".BrandName", "Model", "extendedInformation"));
	}
	
	public function modelIDExist($modelID)
	{
		$out = $this->basic_select(TBLMODELS,"IDModel='$modelID'", "IDModel");
		return empty($out) ? false : true;
		
	}
	private function getPolaroidHostRule($polaroidID)
	{
			$out = $this->basic_select(POLAROID_FCTTABLE, "ID=$polaroidID",array("hstrule"));
			return $out[0];
	}
	
	private function getPolaroidIDFromModel($modelID)
	{
		$out = $this->basic_select(TBLMODELS,"IDModel='$modelID'","polaroid_functionID");
		$ploaroid = explode(";", $out[0]);
		
		
		return $ploaroid;	
	}
	
	private function getPolaroidID($modelID)
	{
		$out = $this->basic_select(array(TBLMODELS,POLAROID_FCTTABLE),TBLMODELS.".polaroid_functionID=".POLAROID_FCTTABLE.".ID AND IDModel='$modelID'",array(POLAROID_FCTTABLE.".ID"));
		return $out[0];
	}
	
	private function getPolaroidFunctionChild($polaroidID)
	{
		$out = $this->basic_select(POLAROID_FCTTABLE,"ID=$polaroidID" ,array("childID"));	
		return $out[0];
	}
	private function getPolaroidFunctionChildModel($polaroidID)
	{
		$out = $this->basic_select(POLAROID_FCTTABLE,"ID=$polaroidID" ,array("childModelID"));	
		return $out[0];
	}	
	
	private function getPolroidFunction_str($polaroidID)
	{
		$out = $this->basic_select(POLAROID_FCTTABLE,"ID=$polaroidID" ,array("VALUE"));	
		if ( empty($out) )
		{
			$out = $this->basic_select(POLAROID_FCTTABLE,"ID=1" ,array("VALUE"));
			return  $out[0];
		}
		else
			return $out[0];
	}
	
	public function getPolaroidFunction($modelID, $hostname, $searchForChild)
	{
		
		
		$polaroid_ids  = $this->getPolaroidIDFromModel($modelID);
		
		
		$goodPolaroidID = 1;
		
		if ( count($polaroid_ids) == 1 )
		{
			$goodPolaroidID = $polaroid_ids[0];
		
		}
		else
		{
			foreach ( $polaroid_ids as $index=>$polID)
			{
			
				$hostFule = $this->getPolaroidHostRule($polID);
				
				
				$hostRule = explode(";", $hostFule);
				
				$key = 0;
				$found = false;
				while ( ! $found && $key < count($hostRule))
				{
					//$this->printdbg("hostrule : ".$hostRule[$key]);
					if ( strpos($hostname, $hostRule[$key]) !== FALSE )
					{
						$goodPolaroidID = $polID;
						$found = true;
					}
					else
						$key++;
				}
				if ( $key == 0 and ! $found ) throw new DBWRAPPERModels("No matching rule(s) for $hostname...");
			}
				
			
			
		}
		
		
		$out["POLROID_ID"] = $goodPolaroidID;
		$out["POLROID_FCT"] = $this->getPolroidFunction_str($goodPolaroidID);
		$children = $this->getPolaroidFunctionChild($goodPolaroidID);
		if ( $children > 0 and $searchForChild)
		{
			
			
			$out["CHILD_FCT"] = $this->getPolroidFunction_str($children);
			$out["CHILD_MID"] = $this->getPolaroidFunctionChildModel($goodPolaroidID);
			
		}
		
		
	
		
		return $out;
	}
	public function get_modelRamQty($modelID)
	{
		if ( $this->modelIDExist($modelID) )
		{
			$out = $this->basic_select(TBLMODELS,"IDModel='$modelID'", "ram");
			return $out[0];
		}
		else
			throw new DBWRAPPERModels("Model $modelID does not exist");
	}	
	public function get_modelCpuNumber($modelID)
	{
		if ( $this->modelIDExist($modelID) )
		{
			$out = $this->basic_select(TBLMODELS,"IDModel='$modelID'", "cpu_number");
			return $out[0];
		}
		else
			throw new DBWRAPPERModels("Model $modelID does not exist");
	}

	public function getModelsFromCatalogName($catID)
	{
		$itemName = $this->getItemNamFromID($catID);
		
		
		
		if ( empty($itemName) ) return "";
		$itemName = $itemName[0];
		
		parent::printdbg("item name=".$itemName);
		return $this->e_select(ENABLE_MODELS, array( "allowedItems" => $itemName ),True , array("IDModel", "apcCode", "ibm_model_type"), True, True);
	}
	
	protected function model_disabled($id)
	{
			$out =  $this->basic_select(TBLMODELS, "IDModel='$id'", array("enabled"));
			$out = $out[0];
			if ( empty($out) )
				return -1;
			
			if ( $out == 1)
				return 0;
			else
				return 1;
			
	}
	
	public function disable_model($id)
	{
		parent::syslog_msg(LOG_NOTICE, "Model id=$id is disabled :");
		return parent::update(array("enabled" => 0), TBLMODELS, "IDModel=$id");
	}
	
	public function enable_model($id)
	{
		return parent::update(array("enabled" => 1), TBLMODELS, "IDModel=$id");
	}

	public function freeze_apcCode($apcCode)
	{
		return parent::update(array("status" => 1), APCTABLE, "APC='$apcCode'");
	}
	
	
	
	
}  
?>
