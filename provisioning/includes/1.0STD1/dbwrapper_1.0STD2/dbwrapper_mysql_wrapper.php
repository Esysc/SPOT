<?php
define("CHAR_COMMA", ','); /** \def Mainly used in low-level functions for building queries */
define("CHAR_FIELD", "'");/** \def Mainly used in low-level functions for building queries */
define("CHAR_APPOSTROPHE", "`"); /** \def Mainly used in low-level functions for building queries */
define("DBGPATH_LIB", "/var/www/productiondb/1.0STD1/lib/"); 
define("APACHE2_LOG", "/var/log/apache2/error.log");
error_reporting(E_ALL);	
set_include_path(get_include_path() . PATH_SEPARATOR . DBGPATH_LIB);
class MYSQL_WRAPPER_EX extends Exception 
{
	private $head = "[MYSQL_WRAPPER_EX]";
	
	public function __construct($message, $code = 0, Exception $previous = null) 
	{
		$message = $this->head.$message;
		parent::__construct($message, $code, $previous);
	}

}
	/***********************************************************
	*	CLASS   : mysql_wrapper 
	*	Autor   : David Clignez
	*	Date    : 7.1.2013
	*	version : 0.9.1
	*
	* Description : 
	* -------------
	* 27.06.2013  - Now debugArray is optionnal - DCL
	*
	* This class offers
	*  : transation mode 
	*  : translate PHP arrays into SQL queries
	*  : Return the result of sql queries into array
	*  : Get various information about the database server 
	* If you need to use this class use inhéritance 
	* Please have a look to doc/mysql_wrapper.inc to obtain the public method list
	*************************************************************/
	
	
	class MYSQL_WRAPPER extends mysqli
	{
		private $messageHeader =  "[MYSQL_WRAPPER]";
		private $lastMySQLResult = Null;   //use to save the last id for example
		private $MYSQL_RET_TYPE = MYSQLI_NUM;
		private $transactionPending = false;
		private $connectionInfo = array();
		protected $debugObj;
		protected $version = "1.0STD1";
		protected $logFilePath;
                private $_noDoubleArray = false;
	  	protected $lastFailedQuery; //Added 2.7.2014	
                
                protected function toggleNoDoubleArrays()
                {
                    $this->_noDoubleArray = ! $this->_noDoubleArray;
                }
		static function is_date($value, $format = 'd/m/Y')
		{
		 # Par Frédéric FAYS, www.blue-invoice.com source:http://blue-invoice.com/wp/?p=91
		 $format=strtolower($format);
		 if(strlen($value)>7 && strlen($format)==5){
			 # Trouver le séparateur
			 $sep = str_replace(array('m','d','y'),'', $format);
			 if(strlen($sep)==2 && $sep[0]==$sep[1]){
				 # création du regexp
				 $regexp = str_replace('m','[0-1]?[0-9]', $format);
				 $regexp = str_replace('d','[0-3]?[0-9]', $regexp);
				 $regexp = str_replace('y','[0-9]{4}', $regexp);
				 $regexp = str_replace(']'.$sep[0].'[', ']\\' . $sep[0].'[', $regexp);
				 if(preg_match('#'.$regexp.'#', $value)){
					 # Trouver les éléments de la date
					 $fmd=str_replace($sep[0],'',$format);
					 $DtExplode=explode($sep[0],$value);
					 # Tester la date
					$d = $DtExplode[strpos($fmd,'d')];
					$m = $DtExplode[strpos($fmd,'m')];
					$y = $DtExplode[strpos($fmd,'y')];
					if(@checkdate($m, $d, $y)) return true;
				 }
			 }
		 }
		 return false;
		}
        /*
	
		
		       /*
    **************************************************************************** 
    * function : createProcessedArray 
    * scope    : private    
    * usage    : return an array ready to be used by insert_into
    * IN      : assosicative array indexed by fields name, and the id use in the order 
    * OUT     : $array ready to be inserted
	* Note    : if idOrHostname is not specified, it will  skip the hostname 
	*			This is used, because all components are based on their hostname (that must be unique inside
	*			the category and when we build this array, hostname is the key of the row not the column.
	*			Therefore to be more generalist, we can build array for order and normal arrays.
    **************************************************************************** */   
	protected function createProcessedArray($itemValues,$salesOrder,$indexName, $indexValue)
	{
		
		
		
		$keyVal = explode("_", $indexName);
		if ( count($keyVal) == 2 )
			$keyVal =  $keyVal[1];
		else
			$keyVal = $indexName;
		
 		$returnedArray = array($keyVal => isset($indexValue)?$indexValue : NULL ) ;
		
		if ( ! is_array($itemValues) ) return $returnedArray;
		
		foreach ( $itemValues as $field_name=>$field_value)
		{
			if ( $field_name != "" )
			{
				
				$key = explode("_",$field_name);
				
				if ( count($key) == 2 )
				{
					
					$returnedArray[$key[1]] = $field_value;
				}
				else
					$returnedArray[$field_name] = $field_value;
			}
		}
		
		
		$returnedArray["salesOrder"] = $salesOrder;
		
		
		return $returnedArray;
	}
		protected function getMYSQLFieldType($field, $table)
		{
			$this->switch_mysql_rt_ASSOC();
			$query = "DESCRIBE $table $field";
			$out = $this->execq($query);
			return $out["Type"];
			
			
		}
		
		protected function _rollback()
		{
			$out =  parent::rollback();
			//$this->printdbg("rollback last transaction!".var_export($out, True));
			
			return $out;
		}
		
		protected function _commitTransaction()
		{
			$this->commit();

                        if (method_exists($this, "getInstancier"))
                        {
                            $this->printdbg("[".$this->getInstancier()."]commit transaction");
                        }
                        else 
                        {
                            $this->printdbg("commit transaction");     
                        }
			parent::autocommit(TRUE);
			
		}
		
		protected function _startTransaction()
		{
			$this->printdbg("start transaction");
			parent::autocommit(FALSE);
			return $this->execq("START TRANSACTION");
		}

		protected function syslog_msg($priority, $message)
		{
			if ( ! is_null($this->debugObj) )
			{
				$header = "[PDB ".$this->version."]";
				$this->debugObj->syslog_message($priority, $header.$message);
			}
		}
		
		protected function printdbg($message)
		{
			$version = $this->version;
			$message = "[PDB $version]".$message;
			if ( ! is_null($this->debugObj) )
			{
				$this->debugObj->printdbg($message);
			}
			else
				error_log($message, 0);
		}
		public function __construct($dbHost, $dbUser, $dbPassword, $dbName, $dbPort=3306, $debugArray)
		{
			
			parent::__construct($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
			if ( $this->connect_error ) throw new MYSQL_WRAPPER_EX($this->connect_error);
			
			
			parent::autocommit(TRUE);
			
			//Save the information for reconnect
			$this->connectionInfo["dbHost"] = $dbHost;
			$this->connectionInfo["dbUser"] = $dbUser;
			$this->connectionInfo["dbPassword"] = $dbPassword;
			$this->connectionInfo["dbName"] = $dbName;
			$this->connectionInfo["dbPort"] = $dbPort;
			
			
		
			if ( is_null($debugArray) )
			{
				$this->debugObj = NULL;
				$this->logFilePath = "";
			}
			else
			{
				$this->debugObj = new DEBUG(
											$debugArray["initialState"],
											$debugArray["logFilePath"],
											$debugArray["logSize"],
											$debugArray["circularLog"],
											$debugArray["syslog"]
											);
				
				
				$this->logFilePath = $debugArray["logFilePath"]; //used in __waekup
			}

			$this->lastFailedQuery = "";
			
		}
		
		
		protected function get_autommit_status()
		{
			$out =  $this->execq("SELECT @@autocommit");
			return $out[0];
		}
		protected function change_sql_user($sql_user, $sql_password)
		{
			if ( parent::change_user($sql_user, $sql_password, $this->connectionInfo["dbName"]) )
			{
				$this->connectionInfo["dbUser"]     = $sql_user;
				$this->connectionInfo["dbPassword"] = $sql_password;
			}
			else
				throw new MYSQL_WRAPPER_EX("Error while changing user!");
		}
		
		protected function getHostInfo()
		{
			
			return $this->host_info;
		}
		
		protected function reconnect()
		{
			parent::__construct($this->connectionInfo["dbHost"],
								$this->connectionInfo["dbUser"],
								$this->connectionInfo["dbPassword"],
								$this->connectionInfo["dbName"],
								$this->connectionInfo["dbPort"]);

								
		}
		protected function deconnect()
		{
			$this->close();
		}
		
		
		public function isConnected()
		{
			
			return $this->ping();
		}
		
		public function __wakeup() 
		{ 
			if ( $this->logFilePath != "")
			{
				$this->reconnect(); 
				$this->debugObj->Open($this->logFilePath);
			}
		}
		
		public function getSQLState()
		{
			return $this->sqlstate;
		}
	
		public function __destruct()
		{
			if ( ! is_null($this->debugObj) )
			{
				$this->debugObj->finalize();
			}
			$this->close();
				
		}
		
		
		public function get_db_name()
		{
			$out= $this->execq("SELECT DATABASE()");
			$out = $out[0];
			return $out;
		}
		
		/**
		 * \brief       change the return type of execq to default one : NUMERICAL arrays
		 * \details     this function change the mysql_return type
		 */
		protected function switch_mysql_rt_default()
		{
			$this->switch_mysql_rt_NUM();
		}
		/**
		 * \brief       change the return type of execq to NUMERICAL arrays
		 * \details     this function change the mysql_return type
		 */
		protected function switch_mysql_rt_NUM()
		{
			$this->MYSQL_RET_TYPE = MYSQLI_NUM;
		}
		
		/**
		 * \brief       change the return type of execq to assosiatives arrays
		 * \details     this function change the mysql_return type to assosiative array
		 */	
		protected function switch_mysql_rt_ASSOC()
		{
			
			$this->MYSQL_RET_TYPE =  MYSQLI_ASSOC;
		}
		
		protected function set_name($charset)
		{
			
			$qry = "SET NAMES '".$charset."'";
			$this->execq($qry);
			
		}	
		protected function getDBCharset()
		{
			return $this->character_set_name();
		}
		protected function setDBCharset($charset)
		{
	
			$this->set_name($charset);
			if ( $this->getDBCharset() == $charset )
				$this->printdbg("setting charset to :".$this->getDBCharset() );
			else
				$this->printdbg("failed to set charset, using ".$this->getDBCharset() );
			
		
		}		
		     /*
		**************************************************************************** 
		* function : getColumnNames 
		* scope    : private    
		* usage    : return the list of column in a given table items
		* IN      : $tableName : Name of the table 
		* OUT     : array of fields
		 **************************************************************************** */    
		protected function getColumnNames($tableName)
		{
		  $column = $this->execq("SHOW COLUMNS FROM $tableName");
		  $cols = array();
		  
		  foreach ($column as $col=>$value)
			$cols[] = $value[0];
		  
		  return $cols;
		  
		}
		private function checkEncoding($str)
		{
			if (is_string($str))
				if ( mb_detect_encoding($str) != $this->getDBCharset() )
				{
					if ( mb_detect_encoding($str) != 'UTF-8')
					{
						
						$str1 = iconv(mb_detect_encoding($str),"UTF-8",$str);
					
						return $str1;
					}
				}
			return $str;
		}

		protected function getLastFailedQuery()
		{
			return $this->lastFailedQuery;
		}
		private function mysqlTypeToPhp($var, $typeNo)
		{
			
		
			switch ( $typeNo )
			{
				case 1:
					return $var == 0 ? False : True;
				case 9:
				case 5:
				case 8:
				
				case 2:
				case 3:
					return intval($var);
				case 246:
				case 4:
					return floatval($var);
				case 10:
				case 11:
				case 12:
					return DateTime::createFromFormat('Y-m-d',$var);
				case 6:
					return NULL;
				default:
					return utf8_encode(strval($var));
				
				
				
			}
										
			
										
		}
                
                
		/*
		**************************************************************************** 
		* function : exeq (execute query)
		* scope    : private    
		* usage    : The method execute query only from dbwrapper (see scope)
		* IN      : $query : SQL query
		* OUT     : Array with result or empty array
		* Note    :  corrected cr bug when displaying muliple results 
		**************************************************************************** */     
		protected function execq($query)
		{
		  
		  $qry = $this->query($query);
                  
		  if (  $qry === false)
		  {
                        $error = $this->error;
			$this->printdbg("Query ".$query);
			$this->_rollback();
			$this->printdbg($error);
			
			
			
			$this->lastFailedQuery = $query;	
                        
			throw new MYSQL_WRAPPER_EX("[".$this->errno."] Query failed to execute :$error", 2);
		  }
		  
		  
		  if ( ! is_bool($qry) )
		  {	  
			
			  $results = array();
			  while ($row = $qry->fetch_array($this->MYSQL_RET_TYPE))
			  {
				
				if ( $this->MYSQL_RET_TYPE == MYSQL_NUM and count($row) == 1 )
				{
					$field_info = $qry->fetch_field_direct(0);
					$results[] = $this->mysqlTypeToPhp($row[0],$field_info->type);
				}
				else
				{
					
					
					foreach ( $row as $index=>$key )
					{
						if  ( $this->is_date($key, "d-m-y"))
						{
							
							
							$row[$index] =  DateTime::createFromFormat('d-m-Y',$key);
						}
						elseif ( $this->is_date($key, "Y-m-d") )
						{
							
							$row[$index] =  DateTime::createFromFormat('Y-m-d',$key);
						}
						else
						{
							if ( is_int($index) )
							{
								
								$field_info = $qry->fetch_field_direct($index);
								$value = $this->mysqlTypeToPhp($key,$field_info->type);
							
							}
							else
								$value = $key;
							$row[$index] = $value;
						}
					}
                                        if ( $this->_noDoubleArray )
                                        {
                                          $results = array_merge($results, $row);  
                                        }
                                        else
                                            $results[] =  $row;  //!!why returning an 
				}
			
				
			  }
			  
			  
			 
			 $qry->free();
			 
			 if ( $this->MYSQL_RET_TYPE == MYSQLI_ASSOC and count($results)>0)
			 {
				
				$this->switch_mysql_rt_default();
			 }
			 
			 
			 return $results;
		  }
		  else
		  {
		
			
			 $rows = $this->affected_rows;
			 
			
			 $this->lastInsertedID = $this->insert_id;
			 return $rows;
		  }
		 
	 
		}		
	protected function getLastIid()
	{
		return $this->lastInsertedID;
	}
	protected function e_select($table, $ValIndexedByFName ,$useLike=True, $fieldsToShow="*", $strict=True, $likeExtended=false)
	{
		
		if ( ! is_array( $ValIndexedByFName ) ) throw new MYSQL_WRAPPER_EX("INVALID_FLAG_1");
		if ( ! is_bool($useLike) ) throw new MYSQL_WRAPPER_EX("INVALID_FLAG_2");
		if ( count($ValIndexedByFName) == 0 ) throw new MYSQL_WRAPPER_EX("INVALID_FLAG_3");	
		
		
		$lastElement = count($ValIndexedByFName) - 1;
		$curPos = 0;
		$operande = "";
		$BOOLCOMP = "";
		
		$useLike ? $operande = "LIKE" : $operande = '=';
		$strict  ? $BOOLCOMP = 'AND'  : $BOOLCOMP = 'OR';
		$cdt = "";
		
		
		foreach ( $ValIndexedByFName as $colName=>$value)
		{
			
			if ( ! empty($value) )
			{
				if ($operande == "LIKE" )
					if ( $likeExtended )
						$value = "%".parent::real_escape_string($value)."%" ; 
					else
						$value .= '%' ; 
				
				$cdt .= " `$colName` $operande '$value' ";
				if ( $curPos != $lastElement )
				{
					$cdt .= " $BOOLCOMP ";
				}
				
				
			}
			$curPos++;
			
		}		
		
		
		
		return $this->basic_select($table, $cdt ,$fieldsToShow);;
	
	}
	
	
	   /***************************************************************************** 
		* function : insert_into 
		* scope    : private    
		* usage    : insert values in a given table
		* IN       : ValuesIndexByField , $table, $REMOVE_TYPE_FLAG
		* OUT      : result of query
		* Note     : REMOVE_TYPE_FLAG is used since the convention used is <type>_<varname>.
		*		     Basicaly this enforce the control on what is submitted to the database.
		*			 When this parameters is set to True, the function will remove the <type>
		*			 in the key.
		**************************************************************************** */	
		protected function insert_into($ValuesIndexByField, $table, $REMOVE_TYPE_FLAG=False) 
		{
		
		  $dbCharset = $this->getDBCharset();
#if ( $this->isReadOnlyAccess() and  ! $this->temp_autorized ) throw new MYSQL_WRAPPER_EX("RO_ACCESS");
		  $table = parent::real_escape_string($table);
		  $sql_query = "INSERT INTO ".$table." (";
		  
		  if (is_array($ValuesIndexByField))
		  {
			$vals = "";
			foreach ( $ValuesIndexByField as $key=>$value) 
			{
				if ($REMOVE_TYPE_FLAG === True )
				{
				
					$tempArray = explode("_",$key);
					if ( count($tempArray) === 2 ) //We have <type>|<field nam>
					{
						$key = $tempArray[1];
						$type = $tempArray[0];
					}
					
				}
			
					
				if ( mb_detect_encoding($value) != $dbCharset )
					$value = mb_convert_encoding($value, $dbCharset,  mb_detect_encoding($value) );
				if ( is_null($value))
				{
					$this->printdbg(">>>>$key is null!!!");
					$value = NULL;
				}
				else
				{
					$this->printdbg("$key not null!!");
					if ( isset($type) )
					{
					  if ( $type =="int" && $value===NULL)
					 {
						$this->printdbg("type mismatche! for $key");
					 }
					else
						$this->printdbg("no type mismatch!");
					
					}
				}
				if ( $key === "idapc" || $key == "radminActivated" || $value === '') $value = 0;
					
				$vals       .=  CHAR_FIELD.parent::real_escape_string($value).CHAR_FIELD; 
				$sql_query  .=  CHAR_APPOSTROPHE.parent::real_escape_string($key).CHAR_APPOSTROPHE;          
			  
			  
				$vals       .=  CHAR_COMMA;
				$sql_query  .=  CHAR_COMMA;
			  
			
			}
	
			
			$sql_query = rtrim($sql_query, ",");
			$vals      = rtrim($vals, ",");
			
			$sql_query  .= ") VALUES (".$vals.")";
			
			
			
			
			return $this->execq($sql_query);
			  

			 
		  }
		  return False; 
		}
		/***************************************************************************** 
		* function : sql_count 
		* scope    : private    
		* usage    : wrapper for the count function
		* IN      : tablename, condition (NOTE : in sql) $columnName to count 
		* OUT     : result of query
		**************************************************************************** */	
		protected function sql_count($tableName, $condition="", $columnName='*')
		{
				$tableName = parent::real_escape_string($tableName);
				if (  $columnName  == "" )
					$columnName = "*";
				
				if ($tableName == "" ) throw new MYSQL_WRAPPER_EX("TABLENAME_IS_MANDATORY"); 
				
				$tableName = parent::real_escape_string($tableName);
				//$condition = $condition;
				$columnName = parent::real_escape_string($columnName);
				
				$sql_query = "SELECT COUNT(".$columnName.") FROM ".$tableName;
				
				if ($condition != "")
					$sql_query .= " WHERE ".$condition;
				
				
				return $this->execq($sql_query);
		}
		
		/***************************************************************************** 
		* function : basic_select 
		* scope    : private    
		* usage    : wrapper for select
		* IN      : tablename, condition (NOTE : in sql) $field to show 
		* OUT     : result of query
		*		  : Note added array support for $fields
		**************************************************************************** */
		protected function basic_select($tableName, $condition="", $fields='*', $orderBy=Null, $asc=True, $limityFrom=0,$limite_size=0,$Count=False, $dumpTo=NULL)
		{
		  
		  
		  
		  
		 if ( is_array($tableName) ) $tableName = implode(', ',$tableName);
			
			
		 
		  
		 $tableName = parent::real_escape_string($tableName);
		  
		  
		  if (  $fields  == "" ) $fields = "*";
		  
		  $stFlName = "";
		 
		  if ( is_array($fields) )
		  {
			
			foreach ($fields as $intIndice=>$Column)
			{
				if ( strpos( $Column,'.' ) !== False)
				{
					$rCol = explode('.',$Column);
					if ( count($rCol) != 2 ) throw new MYSQL_WRAPPER_EX("INVALID_COL");
					$stFlName .= CHAR_APPOSTROPHE.$rCol[0].CHAR_APPOSTROPHE.".".CHAR_APPOSTROPHE.$rCol[1].CHAR_APPOSTROPHE.CHAR_COMMA;
				}
				else
					$stFlName .= CHAR_APPOSTROPHE.$Column.CHAR_APPOSTROPHE.CHAR_COMMA;
			}
			$stFlName = rtrim($stFlName, CHAR_COMMA);
			$stFlName = trim($stFlName, CHAR_COMMA);
		  }
		  else
			$stFlName = parent::real_escape_string($fields);
		  
			
		  if ( $orderBy === Null ) 
			$orderBy = "";
		  else
		  {
			$orderBy = "ORDER BY ".$orderBy;
			if ( $asc === True )
				$orderBy .= " ASC";
			else 
				$orderBy .= " DESC";
		  }
		  
		  $cdt = "";
		  if  ($condition != "")		  
			$cdt = " WHERE $condition";
		  
		  $limit = "";
		  
		  if ( $limite_size > 0 )
		  {
			$limit = "LIMIT $limityFrom,$limite_size"; 
		  }
		  if ( $Count )
			$sql_query = "SELECT COUNT($stFlName) FROM $tableName $cdt $orderBy $limit";
		  else
		  {
			if ( ! is_null($dumpTo) )
				$dumpArgs = "INTO DUMPFILE '$dumpTo'";
			else
				$dumpArgs = "";
				
			
			$sql_query = "SELECT $stFlName $dumpArgs FROM $tableName $cdt $orderBy $limit";
		  }
		  
		  
		  return $this->execq($sql_query);
		  
		}
		/***************************************************************************** 
		* function : delete_field 
		* scope    : private    
		* usage    : Set to null a selected field
		* IN      : $table to update, $colName column and $condition in sql
		* OUT     : None
		**************************************************************************** */  
		protected function delete_field($tableName, $colName, $condition)
		{
#if ( $this->isReadOnlyAccess() ) throw new MYSQL_WRAPPER_EX("RO_ACCESS");
		  $this->update(array($colName=>NULL), $tableName, $condition);
		}
		/***************************************************************************** 
		* function : delete_row 
		* scope    : private    
		* usage    : delete  row(s) under certain conditions
		* IN      : $table to update, $condition (in sql), note conditon is mandatory 
		*			since we don't want to clean the entire table
		*           NOTE : We accept to delete only if a crioteria is set (avoid  
							error that waste an entire table
		* OUT     : None
		**************************************************************************** */  	
		protected function delete_row($tableName, $condition)
		{
		  $ALLOWED_OPERATORS = array('>','<','=','!=','<=','>=');
		  

		  if ( $tableName == "" or $condition == "" ) throw new MYSQL_WRAPPER_EX("ERROR : Empty table name or condition use truncate instead!");
#if ( $this->isReadOnlyAccess() ) throw new MYSQL_WRAPPER_EX("RO_ACCESS");
		  $tableName = parent::real_escape_string($tableName);
		  $operator = "";
		  if ( $condition !== "" )
		  {
			//$condition = str_replace(' ', '', $condition);
			$lastIndex = strlen($condition) - 1;
			$cpt = 0;
			
			for (; $cpt <= $lastIndex ; $cpt++)
			{
				
				if ( array_search($condition[$cpt], $ALLOWED_OPERATORS) !== FALSE and $cpt < $lastIndex)
				{
					
					$operator = $condition[$cpt];
					break;
				}
				
				
			}
			if ( $operator == "" ) throw new MYSQL_WRAPPER_EX("No comparaison operator found, please use truncate instead");
			
			$condition = explode($operator, $condition);
			$condition[0] = CHAR_APPOSTROPHE.trim($condition[0]).CHAR_APPOSTROPHE;
			$condition = implode($operator, $condition);
			$condition = "WHERE $condition";
		  }
		  else
			throw new MYSQL_WRAPPER_EX("Please use truncate to empty tables");
		  
		  $sql_query = "DELETE FROM ".$tableName." $condition";
		 
		  return $this->execq($sql_query);
		}
		/***************************************************************************** 
		* function : update 
		* scope    : private    
		* usage    : delete  row(s) under certain conditions
		* IN      : $table to update, $conditon (in sql), note conditon is mandatory 
		*			since we don't want to clean the entire table
		* OUT     : None
		**************************************************************************** */ 
		protected function update($ValuesIndexByField, $table, $condition)
		{
#if ( $this->isReadOnlyAccess() ) throw new MYSQL_WRAPPER_EX("RO_ACCESS");
		  $table = parent::real_escape_string($table);
		 // $condition = parent::real_escape_string($condition);
		  
		  $sql_query = "UPDATE ".$table. " SET ";
		  foreach ( $ValuesIndexByField as $key=>$value) 
		  {
			
			$sql_query .= CHAR_APPOSTROPHE.parent::real_escape_string($key).CHAR_APPOSTROPHE." = ".CHAR_FIELD.parent::real_escape_string($value).CHAR_FIELD;
			if ( end($ValuesIndexByField) != $value )
			  $sql_query .= CHAR_COMMA;
		  }
		  
		  
		  $sql_query.=" WHERE ".$condition;
		  
		  return $this->execq($sql_query);
	   
		}  
	}
	
	
?>
