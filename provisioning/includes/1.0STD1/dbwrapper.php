<?php
	/*! DBWrapper 
	
		This class allows user to mange / conult database (productiondb).
		There are plenty of method that will be described 
		
		
		Main functionnalities / 
			- Manage order (by using class order )
		
	
	*/
	
	require_once "dbwrapper/dbwrapper_storedOrders.php";
        
        
	ini_set('session.gc_maxlifetime', 60*60*8);	
	class DBWrapper_EX extends Exception 
	{
		private $head = "[DBWrapper]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	
	class dbwrapper extends DBWSTO
	{
            public function getUserData($key)
                {
                    $this->printdbg("$key::".parent::getSessionVars($key));
                    return parent::getSessionVars($key);
                }            
		public function __construct($username, $password, $auth_method=AUTH_LOCAL)
		{
			
			parent::__construct($username, $password, $auth_method);
			
		}  
		
	}

	
?>
