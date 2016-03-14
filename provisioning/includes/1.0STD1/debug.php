<?php
	
	class DEBUG_EX extends Exception 
	{
		private $head = "[DEBUG_EX]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}
	}
	
	
	
	class DEBUG
	{
		protected $debug;
		protected $logSize;
		protected $logFilePath;
		public $logHandle;
		private $circularLog ;
		private $showTime;
		private $syslog;
		
		public function __destruct()
		{
			if ( $this->syslog )
				closelog();
		}
		
		public function __construct($initialState, $logFilePath,$logSize,$circularLog,$showTime=true, $useSyslog=True)
		{
			$this->debug = $initialState;
			if ( $logFilePath == NULL ) new DEBUG_EX("No filename given");
			$this->logFilePath = $logFilePath;
			$this->Open($this->logFilePath);
			$this->logSize = 2048 * $logSize;
			$this->circularLog = $circularLog;
			$this->showTime = $showTime;
			$this->syslog = $useSyslog;
			
			if ( $this->syslog )
				$this->start_syslog();
			
			
		}
		public function Open($logFilePath)
		{
			
			if (  file_exists($logFilePath) and  is_writeable($logFilePath)) 
			{
				
				$this->logHandle = fopen($this->logFilePath, 'a') or die("Cannot create file or in use!");
			}
			else
				throw new DEBUG_EX("Cannot create file or in use!");
			
			if ( ! $this->logHandle )
				throw new DEBUG_EX("Cannot create file or in use!");
				
			
			
			
						
		}
		

		
		
		public function printdbg($message, $displayTime=False)
		{
			if ( $this->debug )
                        {
                            if ( $this->logSize <= filesize($this->logFilePath))
                                    if ( $this->circularLog )
                                    {
                                            fclose($this->logHandle);
                                            $this->logHandle = fopen($this->logFilePath,'w');
                                    }


                            if ( $this->showTime or $displayTime)
                                    fwrite($this->logHandle, strtotime("d/m/Y H:m:i"));	
                            if ( ($record = fwrite($this->logHandle, $message."\n")) === FALSE)
                                    throw new DEBUG_EX("Write failed on debug log");
			
                        }
			
			
		}
		
		public function syslog_message($priority, $message)
		{
			if ( $this->syslog )
				syslog ( $priority ,  $message );
			else
				$this->printdbg($message);
				
		}
		
		public function start_syslog()
		{
			$SYSOG_HEADER = "[PRODUCTIONDDB][1.0STD1]";
			openlog($SYSOG_HEADER, LOG_PID | LOG_ODELAY, LOG_LOCAL7);
		}
		
		public function finalize()
		{
			if ( ! is_int($this->logHandle) )
				fclose($this->logHandle);
		}
		
		
	}

?>