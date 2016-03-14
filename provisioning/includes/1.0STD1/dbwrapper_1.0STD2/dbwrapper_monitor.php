<?php
  require_once "dbwrapper_init.php";
  
  
  
  	class DBWRAPPERMonitor extends Exception 
	{
		private $head = "[DBWMonitor]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
  class DBWMonitor extends DBWInit
  {
	public function __construct($username, $password, $auth_method)
	{
		parent::__construct($username, $password, $auth_method);
	} 
	public function list_sql_log()
	{
		if ( $this->isSuperUser() )
		{
			return file($this->configuration->get("sqlLogFile"), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}
		throw DBWRAPPERMonitor("NOT_ALLOWED", 10);		
	}
	
	public function get_mon_login()
	{
		if ( $this->isSuperUser() )
		{
			return parent::basic_select(MONTABLE, "", array("login_name", "remoteName", "date", "sess_start", "sess_end"), $orderBy='date, sess_start', $asc=false);
		}
		throw DBWRAPPERMonitor("NOT_ALLOWED", 10);		
	}
	public function pruge_login_log()
	{
		if ( $this->isSuperUser() )
		{
			
			if ( $this->delete_row(MONTABLE, "") == 0)
				return False;
			return True;
			
		}
		throw DBWRAPPERMonitor("NOT_ALLOWED", 10);		
	}
	

	
	public function save_login_log()
	{
		$this->temp_autorized = true;
		
		if ( $this->isConnected)
		{
			$this->clientIP = $_SERVER['REMOTE_ADDR'];
			$this->insert_into(array("login_name"=> $this->getInstancier(), 
									  "remoteName" => gethostbyaddr($this->clientIP), 
									  "date"       => date("Y-m-d"), //2012-11-16
									  "sess_start" => $this->session_start_time,
									  "sess_end" => date("H:i:s")
									  ), 
									  MONTABLE, False
								);
		}
	}
	
	public function getMaintenanceEnabled()
	{
		return  $this->configuration->get("maintenance") == "0" ? false : true;
	}
	
	public function setMaintenanceMode($enabled)
	{
		if ( $this->isSuperUser() )
		{
			$this->setConfigParam("maintenance", $enabled ? '1' : '0');
			
			return $this->configuration->replace();
		}
		throw DBWRAPPERMonitor("NOT_ALLOWED", 10);		
	}	
  }    

?>