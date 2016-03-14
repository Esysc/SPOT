<?

  /*!
  * \file : dbwrapper.php
  * 
  * \author : david clignez
  * \date 19.06.2012
  * ---------------------------------------------------------------------------        
  * \brief	DBWrapper allow access to productiondb.
  * 		by using order object is simply translate them in SQL or fill them with sql
  * 
  * \details
  *		The main objective is to isolate the sql and database access from the other
  * 	components.
  * The advantages are 
  *   1 - reduce the time to debug
  *   2 - simply the usability of the database 
  *   3 - Check the consitency of every object before being comitted into the 
  *     database
  *    
  *****************************************************************************
  * when dbwrapper is instancianted, it connect to the database.
  * After, the client request an order and it by using getOrder the client is
  * able to work on an order without touching directly the database.
  * Once it's finished, the customer class the db wrapper ->commitOrder to save 
  * it in the database        
  ******************************************************************************
  *[ version : 1.0STD1 : Major release   : DCL :14.3.2013] 
  *[ version : 1.0STD0 : Initial release : DCL : 3.8.2012]
  *   
  */       


$LIBPATH = "/var/www/productiondb/1.0STD1/lib/";     //!Path to the main libraries 
$SHLIBPATH = "/var/www/productiondb/1.0STD1/shlib/"; //!Path to the shared function 
$LIB_EXT = "/var/www/productiondb/1.0STD1/data/lib";

	
set_include_path(get_include_path() . PATH_SEPARATOR . $LIBPATH . PATH_SEPARATOR . $SHLIBPATH .PATH_SEPARATOR . $LIB_EXT);
   
  
  
  
  require_once "order.php";
  require_once "configParser.php";   
  require_once "dbwrapper_mysql_wrapper.php";   
  require_once "AES.class.php";
  require_once "LDAPAuth.class.php";
  
  

  

  define("TBLORDERS"    ,"tblOrders");/** \def name of the order's table into db */
  define("TBLNETWORKS"  ,"tblNetworks");/** \def name of the network's table into db */

  define("TBLMODELS"    ,"tblModel");/** \def name of the model's table into db */
  define("TBLBRANDS"    ,"tblBrand");  
  

  define("TBLUSERS"     ,"users");/** \def name of the users's table into db Note : By default, the SPOT credentials are used except for admin user*/




  define("TBLSYSPACT"   , "tblSysprodActor");/** \def name of the sysprod actors into db */
  define("TBLSTOREDORDERS", "tblStoredOrders");/** \def name of the temporary order's table into db */
  define("AITBLUSERS"     ,"users");/** \def name of the SPORT users's table into spot db */
  define("MONTABLE"       , "mon_login");/** \def not used by default this table contains all the sessions time / location */
  define("ADMIN_USER_RIGHT", 10);
  define("RO_USER_RIGHT"   , 1);
  define("TBLCATALOG"      ,"catalog");
  define("CAT_ENABLED_ITEM" ,"enabledItems");
  define("CAT_CLUSTERS_ITEM","cluster_item");
  define("IDENBALED_ITEM"   ,"IDEnabledItems");
  define("APCTABLE"         ,"apcCodes");
  define("APC2CATA"         ,"apc2Catalog");
  define("POLAROID_FCTTABLE","Itemfunction");
  define("POLAROID_HOSTNAME_FLD","hostname");
  define("POLAROID_SERIAL_DLF"  , "serial");
  define("HSTIPCONV"            ,"hostnameIP");
  define("ENABLE_MODELS"        ,"enabled_models");
  define("ENABLED_APCTABLE"     ,"apcCodes_enabled");
  define("AUTH_LOCAL"           , "LOCAL");
  define("AUTH_LDAP"            ,"CDD517F546B44E6DF7CD913BD8797");
  define("DEFAULT_SESSION_SAVE_PATH", "/tmp");
  define("NOTIFY_TABLE", "mon_OrdersCurrent");
  define("NOTIFY_STATUS_INST", 5);
  
//! DBWRAPPERINIT
/*!
Class Exception raised by DBWInit. The code range are between 100-300
*/  
	class DBWRAPPERINIT extends Exception 
	{
		private $head = "[DBWInit]";
		
		public function __construct($message, $code = 100, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}
                
                
                public function __toString() {
                    return "Error ".$this->message;
                    
                }

	}
  
  $VERSION="1.0STD1"; 
	//! DBWInit
	/*!
	Main init class
	*/ 
  class DBWInit extends MYSQL_WRAPPER
  {
    
    
	private $classversion = 1.0;
        protected $session = array("ad_user" => "", "ad_pass" => "", "rights" => 1);
    
        private $isConnected = False;
        private $debug; // attributes that indicated if the object was instanciated with debug mode 
	private $errorHanle = Null;
        private $errorLog; //file name 
	private $user;
	
	protected function msg_error($message)
        {
            error_log( date("Y/m/D h:i:s")."PDB|ERROR|$message", 3,  $this->configuration->get("errorLog") );
        }
	protected function msg_info($message)
        {
            error_log( date("Y/m/D h:i:s")."PDB|INFO|$message", 3,  $this->configuration->get("errorLog") );
        }
	protected function msg_warning($message)
        {
            error_log( date("Y/m/D h:i:s")."PDB|WARN|$message", 3,  $this->configuration->get("errorLog") );
        }          
	 //! Get the library path of sharepoint (defined into config file)
	/*!
	\sa get_sp_lib()
	*/	
        
       protected  function getSessionVars($key)
       {
           return $this->session[$key];
       }


       public function get_sp_lib()
	{
		$this->configuration->reload_config();
		if ( $this->spUploadActived() )
		{
			return $this->configuration->get("sp_library");
		}
		return "";
	}	
	 //! Get the site path of sharepoint (defined into config file)
	/*!
	\sa get_sp_url()
	*/		
	public function get_sp_url()
	{
		if ( $this->spUploadActived() )
		{
			return $this->configuration->get("sp_url");
		}
		return "";
	}	
	
	 //! Get the full site path of sharepoint (defined into config file)
	/*!
	\sa get_sp_site_url()
	*/
	public function get_sp_site_url()
	{
		if ( $this->spUploadActived() )
		{
			return $this->configuration->get("sp_site_url");
		}
		return "";
	}
	 //! Return if sharepoint upload is enabled
	/*!
	\sa spUploadActived()
	*/	
	public function spUploadActived()
	{
		
		return $this->configuration->get("sharePointUpload");
	}
	 //! Update the configuration file with the modifications
	/*!
	\sa updateCFGFile()
	*/	
	public function updateCFGFile()
	{
		if ( $this->isSuperUser() )
			return $this->configuration->replace();
		throw new DBWRAPPERINIT("NOT_ALLOWED", 150);		
	}
	 //! Update a key of the configuration file
	/*!
	\sa setConfigParam()
	*/	
	public function setConfigParam($paramName, $paramVal)
	{
		if ( $this->isSuperUser() )
			return $this->configuration->set($paramName, $paramVal);
		throw new DBWRAPPERINIT("NOT_ALLOWED", 151);	
	}
	 //! Get the current memory configuration 
	/*!
	\sa getConfigArray()
	*/	
	public function getConfigArray()
	{
		
		if ( $this->isSuperUser() )
			return $this->configuration->getConfig();
		throw new DBWRAPPERINIT("Only administrator are allowed to do this operation", 152);
		
	}
	 //! Dump the configuration file 
	/*!
	\sa dumpConfig()
	*/	
	public function dumpConfig()
	{
		
		if ( $this->isSuperUser() )
			return $this->configuration->dumpConfig();
		throw new DBWRAPPERINIT("NOT_ALLOWED", 153);
		
	}
	 //! Determine wether the current user has the "superuser" flag enabled
	/*!
	\sa isSuperUser()
	\return boolean
	*/		
	protected function isSuperUser()
	{
		return $this->session["right"] === ADMIN_USER_RIGHT;
	}
	 //! Determine the connection source(legacy)
	/*!
	\sa getConnectionSource()
	\return always "SOIT"
	*/	
	public function getConnectionSource()
	{
		
		return $this->session["auth_source"] ;
	}

	
	/**
	 * \brief      get wrapper instancier's name
	 * \details    Return the name of the user who instaciated the wrapper
	 * \return    acronym of the user 
	 */

	public function getInstancier() { return $this->session["login"]; }



	/**
	 * \brief    will return a string containing all element of an given array
	 * \details    only usefull for Yx1 array (dump the content)
	 * \param    $array message to display
	 * \return   None
	 */
	protected function arrayDump($array)
	{
		if (is_array($array)) return "arrayDump\n\t".implode("|", array_keys($array))."\n\t".implode("|", array_values($array));
		else return $array;
	}
	
	 //! return the nt login infos
	/*!
	\sa get_nt_credential()
	\return always "SOIT"
	*/	      
	public function get_nt_credential()
	{
		return  array(  "USER"     => $this->session["ad_user"], 
                                "PASSWORD" => $this->session["ad_pass"]);
				 
	}

	/**
	 * \brief    will isReadOnlyAccess
	 * \details    only usefull for Yx1 array (dump the content)
	 * \param    $array message to display
	 * \return   None
	 */
	public function isReadOnlyAccess()
	{
		return $this->session["right"] == RO_USER_RIGHT;
	}
	
	/* used, when serializing the wrapprt */
	public function __wakeup() { parent::__wakeup(); }
    /*
    **************************************************************************** 
    * function : getSysprodActor
    * scope    : public    
    * usage    : return the list of sysprod actor (acronym max 4 char)
    * IN      : None
    * OUT     : Array of sysprod actor
    **************************************************************************** */  
	public function getSysprodActors()
	{
		return $this->basic_select(AITBLUSERS, "", "U_login");
	}
        
        
        public function load_user_local($lusername, $lpassword)
        {
            
        }
        
        public function load_user_ldap($ntuser,$password)
        {
                $flag = False;
                $options = Array('base_dn'            => 'OU=Cheseaux,DC=hq,DC=k,DC=grp',
                             'search_dn'	     => 'DC=hq,DC=k,DC=grp',
                             'account_suffix'     => '@my.compnay.com',
                             'domain_controllers' => Array('auriga.my.compnay.com'),
                             'use_ad'             => true,
			);
                    $ldap = new LDAPAuth($options);
                    if  ( ! $ldap->authenticate($ntuser,  $password) )
                    {
                        $flag = False;                   
                                
                    }
                    else
                        $ldap->close();
                    
                    return $flag;
                        
        }
        
    /*
    **************************************************************************** 
    * function : checkUser
    * scope    : public    
    * usage    : return true if a given username/password are present in the db
    * IN      : $username, $password
    * OUT     : True if ok, False if not
    **************************************************************************** */    
    public function load_user($username,$password, $auth_method)
	{
		
	
		
		try
                {
		
		if ( strval($auth_method) === AUTH_LOCAL )
		{
			$this->printdbg("auth = LOCAL");
			$dbLoginInfo =  parent::basic_select(TBLUSERS, "U_login = '".strtoupper(parent::real_escape_string($username))."'", array("U_login", "U_password", "U_right","U_AD_User","U_AD_Password","U_Phone", "U_Full_Name", "U_id"));
			if ( empty($dbLoginInfo)) return False;
                        $dbLoginInfo = $dbLoginInfo[0];
			$this->session["login"] = $dbLoginInfo[0];
			if ( empty($dbLoginInfo) ) throw new DBWRAPPERINIT("Error login not found");
					
			if ( md5($password) !== $dbLoginInfo[1] ) return False; 
			
			$this->session["right"] = intval($dbLoginInfo[2]);
			if ( $this->spUploadActived() )
			{
				if ( empty($dbLoginInfo[4]) or empty($dbLoginInfo[3] )) 
				{
					$this->session["ad_pass"]  = NULL;
					$this->session["ad_user"] = NULL;
				}
				else
				{
					$this->session["ad_pass"]   = base64_decode($dbLoginInfo[4]);
					$this->session["ad_user"] = $dbLoginInfo[3];
				}
			}
			$this->session["auth_source"] = $this->get_db_name();
                        $this->session["phone"] = $dbLoginInfo[5];
                        $this->session["full_name"] = $dbLoginInfo[6];
                        $this->session["U_id"] = $dbLoginInfo[7];
			
		}
		elseif ( $auth_method == AUTH_LDAP )
		{
                        $this->printdbg("auth = LDAP");
			$dbLoginInfo =  parent::basic_select(TBLUSERS, "U_AD_User = '$username'", array("U_login", "U_password", "U_right","U_AD_User","U_AD_Password", "U_Phone", "U_Full_Name", "U_id"));
                        if ( empty($dbLoginInfo)) return False;
                        $dbLoginInfo = $dbLoginInfo[0];
                        
                        
			$this->session["right"] =  intval($dbLoginInfo[2]);
			$this->session["login"] = $dbLoginInfo[0];
			
			if ( $this->spUploadActived() )
			{
				$key = $this->configuration->get("aes_key");
				$aes = new AES($key);
				$this->session["ad_pass"]   = $aes->encrypt($password);
				$this->session["ad_user"] = $username;
                                
				
				unset($aes);
				
			}		
			$this->session["auth_source"] = "my.compnay.com";	
                        $this->session["phone"] = $dbLoginInfo[5];
                        $this->session["full_name"] = $dbLoginInfo[6];
                        $this->session["U_id"] = $dbLoginInfo[7];
		}
		else
		{
			throw new DBWRAPPERINIT("Unkow authentification method / Wrong key ", 145);
		}
			
		
                }
                catch (Exception $e)
                {
                    $this->printdbg("Error : ".$e->getMessage()." class ".get_class($e));
                    throw  $e;
                }
		

		
		
		$this->printdbg("user autenticated!");
		return True;


	
			
			
	}

	private function set_session_path()
	{
                
		if ( $this->get_session_path() != "default")
		{
                        $new_sessPath = $this->get_session_path();
			session_save_path($this->get_session_path());	
		}
		else
                {
                         $new_sessPath = DEFAULT_SESSION_SAVE_PATH;
			 session_save_path(DEFAULT_SESSION_SAVE_PATH);
                }
		if (session_save_path() != $new_sessPath)
                    $this->printdbg ("Error could not set session save path to $new_sessPath");
                
            $this->printdbg ("session dir : ".session_save_path ());
                                
                
		ini_set('session.gc_probability', 1);
	}
	private function get_session_path()
	{
		return $this->configuration->get("phpsessionpath");
	}



    /*
    **************************************************************************** 
    * function : __construct (class constructor)
    * scope    : public    
    * usage    : class constructotr, load the config file, and set the debugmode
    * IN      : None
    * OUT     : None
    **************************************************************************** */    
    public function __construct($username ,$password, $auth_method)
    {
	

		/****************
		Read the configuration file $pathToConfig
		****************/
		try
		{
			$this->configuration = new CONFIGPARSER();  
			$this->debug = (bool)$this->configuration->get("DEBUG");
			$this->errorLog = $this->configuration->get("errorLog");
			
		}
		catch (Exception $e)
		{
			throw $e;
		}


		
		
		$debugParams = array(
							"initialState" => $this->debug,
							"logFilePath"  => $this->configuration->get("errorLog"),
							"logSize"      => intval($this->configuration->get("maxErrorLogSize")),
							"circularLog"  => (bool)($this->configuration->get("useLogRotation")),
							"circularLog"  => (bool)($this->configuration->get("useLogRotation")),
							"syslog"       => (bool)($this->configuration->get("useSyslog")),
						   );



		$this->session = array
					(
						"login"      => $username == "" ? "anonynous" : $username, 
						"right"     => RO_USER_RIGHT,
						"superUser"  => false,
						"start"      => new DateTime("now", new DateTimeZone("Europe/Zurich")),
						"end"        => NULL
						
					);
		


		parent::__construct(   $this->configuration->get("host"),
							   $this->configuration->get("ro_user"),
							   $this->configuration->get("ro_password"),
							   $this->configuration->get("dbname"),
							   $this->configuration->get("port"),
							   $debugParams);	
			
	  
	  
		
		
	  

	  
	  $this->set_session_path();
	  /****We use different user if the SQL login is read-only***/
          $this->printdbg("SQL user is $username");
	  if ( $username !== "")
	  {
		  if ( ! $this->load_user($username, $password, $auth_method) )
			throw new DBWRAPPERINIT("Access denied for $username", 119);
		  
			
			if ( $this->session["right"] == ADMIN_USER_RIGHT )
			{
				parent::change_sql_user($this->configuration->get("user"),$this->configuration->get("password"));
		
			}
	   }
	   else
	   {
               
			if ( ! $this->configuration->get("anonymousLogin") and $username == "")
				throw new DBWRAPPERINIT("Anonymous login is disabled", 2);
	    }
	   if ( $this->getMaintenanceEnabled() and $this->session["right"] == RO_USER_RIGHT) 
		throw new DBWRAPPERINIT("MAINTENANCE_IN_PROGRESS", 1);
	
		
	}
    
 


	public function getOrdersCount()
	{
		
		$out= $this->basic_select(TBLORDERS, "","*",Null,False,0,0,True);
		return $out[0];
		
	}
	
	public function getOrdersBrief()
        {
            $fields = array ( "Salesorder",
                              "ProgramManager",
                              "customerSigle",
                              "Customer",
                              "SysprodActor",
                              "StartDate",
                              "EndDate",
                              "prodStartDate",
                              "prodEndDate",
                              
                              "SID");
            
            $out = $this->basic_select(TBLORDERS, "", $fields);
            
            foreach ( $out as  $id=>$values)
            {
                foreach ( $values as $index=>$val)
                {
                    if ( $val instanceof DateTime )
                    {
                        $out[$id][$index] = $val->format ("Y-m-d");
                        
                    }
                
                    
                 }
                 
            }
            
            return $out;
            
        }
     /*
    **************************************************************************** 
    * function : getOrders 
    * scope    : public    
    * usage    : return the list of orders stored in the database
    * IN      : None
    * OUT     : array of the following format :
	*	So|ProgramManager|SiteEngineer|SpecSheet|SysprodActor|Release|ImageVersion|Customer|StartDate|EndDate�Customer
    * Note    : 
    **************************************************************************** */	
	public function getOrders($orderBy="",$asc='true',$salesOrder="", $from=0, $to=0)
	{
		
		$asc == 'true' ? $asc = True : $asc = False;
	
		$fields = array(
			"Salesorder", 
			"ProgramManager",
			"SiteEngineer",
			"SysprodActor",
			"Release",
			"comment",
			"StartDate",
			"EndDate",
			"Customer",
			"EndDate",
			"customerSigle",
			"SID");
		
		$cdt = "";
		if ( $salesOrder != "" ) $cdt = "Salesorder = '$salesOrder'";
		
		
		
		
		if ( ! in_array($orderBy ,$fields) or $orderBy == "")
		{
			if ( $to>0 or $from >0)
			{
				
				return $this->basic_select(TBLORDERS, $cdt, $fields, Null,True, $from, $to);
			}
			else
				return $this->basic_select(TBLORDERS, $cdt, $fields);
		}
		
		
		
	
	  
		if ( $to>0 or $from >0 )
			
			return $this->basic_select(TBLORDERS, $cdt, $fields,$orderBy,$asc,$from, $to);
		else
			return $this->basic_select(TBLORDERS, $cdt, $fields,$orderBy,$asc);
		
		
		

	}



 
	public function countItems($salesOrder)
	{
		if ( ! $this->orderExist($salesOrder) ) throw new DBWRAPPERINIT("UNKNOW_SO");
		$count = 0;
		foreach ($this->getItemList() as $key=>$index)
		{
	
			$tmp = $this->sql_count($index, "SalesOrder = '$salesOrder'");
			$count += $tmp[0];
			
		}

		return $count;
	}
	

	


	

	
	/*
	**************************************************************************** 
    * function : listTableItemForSo 
    * scope    : private    
    * usage    : return all the table (items) where an given so is found
    * IN      : salesOrder
    * OUT     : array of tablename
    * Note    :     
    **************************************************************************** */ 
	protected function listTableItemForSo($salesOrder)
	{
		$itemList = $this->getModelContainer(True);
		$itemBelongsToSo = array();
		
		
		
		foreach ($itemList as $table)
		{
				
				if ( count($this->basic_select($table[0], "Salesorder = '$salesOrder'")) > 0)
				{
					$itemBelongsToSo[$table[1]] =  $table[0];
					
					
				}				
		}
		return $itemBelongsToSo;
		
	}

      /*
    **************************************************************************** 
    * function : orderExist 
    * scope    : public    
    * usage    : return if a given order is present in the database
    * IN      : $initialSalesOrder : sales order 
    * OUT     : True is yes, Flase if no
    **************************************************************************** */    
	public function orderExist($initialSalesOrder)
	{
		
		$out = $this->basic_select(TBLORDERS, $cdt="Salesorder = $initialSalesOrder");
				
		$out = count($out) > 0;
		
		
		return $out;
	}
	/*
    **************************************************************************** 
    * function : getOrder 
    * scope    : public    
    * usage    : return an order initialised by a given sales order
    * IN      : $initialSalesOrder : sales order 
    * OUT     : empty ORDER
    **************************************************************************** */    
    public function getOrder($initialSalesOrder=-1)
    {
	   
	  $models = $this->getModelsFromDB();
	  
      if ( $this->orderExist($initialSalesOrder) )
		return $this->loadData($initialSalesOrder,$models);
	  
	 $order = new ORDER($initialSalesOrder, $models);
	 $this->register_items($order);
	 return $order;
	 
      
    }

	
	
  
	





  
  }
  
  

//============================================================+
// END OF FILE                                                 
//============================================================+
  
?>
