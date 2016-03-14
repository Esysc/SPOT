<?

  /************************************************************************
   *ConfigParser:
   *-------------
   *
   *Parse the configuration file and store it as memory hash table
   *The class does not allow the write since all changes should be 
   *made by the local administrator
   ********************************************************************/
   //Exceptions definition
   define("EXCEPTION_FILE_ERROR", "+FILE_ACCESS_ERROR");    //Error message raised when the class can't access the file sysproddb.cfg
   define("EXCEPTION_FILE_ERROR_EC", 10);                       //Error code assosicated 
   define("EXCEPTION_FILE_CONTENT_ERROR", "+FILE_ERROR_UNREADABLE");  //The file is malformated
   define("EXCEPTION_FILE_CONTENT_ERROR_EC", 20);                          //Er code
   define("EXCEPTION_KEY_NOT_FOUND", "+KEY_NOT_FOUND");         //Error message raised when the key don't exist in the file
   define("EXCEPTION_KEY_NOT_FOUND_EC", 15);                    //Error code assisciated
   define("CONFIG_FILE_PATH", "/var/www/productiondb/1.0STD1/param/productiondb.cfg");   
   require_once 'debug.php'; 
   
   class CONFIGPARSER
   {
      
      private $version = 1 ;
      private $author  = "DCL";
      
      private $debug = false; 
      private $filePath = "";   
      private $config;  //The configutation file's hanle
    
    
      private function printdbg($message="")
      {
        if ( $this->debug ) { echo "<code / >[DBG] ".$message."<br>";} 
      }
      private function setConfigFile()
      {
      if ( ($this->config = parse_ini_file($this->filePath) ) === FALSE)
	  {
		  
          throw new Exception(EXCEPTION_FILE_CONTENT_ERROR) ;      // Read the file and store the result in config      
      }
	  }
      public function __construct()
      { 
		
	$pathToConfig = CONFIG_FILE_PATH;

        if ( $pathToConfig == "" or  ! is_readable( $pathToConfig ))            ///Test if the file is correct
          throw new Exception(EXCEPTION_FILE_ERROR."[".$pathToConfig."]",$code=EXCEPTION_FILE_ERROR_EC);
         
        $this->filePath = $pathToConfig;
        $this->setConfigFile();      //Parse the file and raise an exception
                                     // if the parse_ini_file return False (malformated)
        $this->debug =  $this->get("DEBUG");
        
        
        
        
        
      }  
      
	  
	  public function replace()
	  {
		
		if ( ! copy($this->filePath, $this->filePath.'-'.date("dd-mm-YY") )) 
		{
			
			
			throw new Exception("ERROR_SAVING_FILE");
		}

		
		$content = parse_ini_file($this->filePath, true);

		
		unlink($this->filePath);
		$file = fopen($this->filePath, 'w');
		fwrite($file, "; Updated on ".date("dd-mm-YY")."\n");
		foreach ( array_keys($content) as $index=>$category )
		{
			
			fwrite($file, "[$category]\n");
			foreach ( $content[$category] as $param=>$paramVal )
				fwrite($file, "$param='".$this->config[$param]."'\n");
		
		}
		fclose($file);

		return true;
		
		
		
	  }
	  
      public function set($key, $newVal)
	  {
		 if ( ! isset($this->config[$key]) ) throw new Exception("UNKOWN_PARAM");
		$this->config[$key] = $newVal;
		
		
		
		
		
		
		 
	  }
      /////////////////////////////////////////////////////////////////////////
      // getConfig
      // In : string/key stored in the ini file
      // Out: return the file key(as well as its type)
      /////////////////////////////////////////////////////////////////////////
      public function get($key)
      {
        if ( isset($this->config[$key]) )
          return $this->config[$key];
        else
          throw new Exception(EXCEPTION_KEY_NOT_FOUND, $code=EXCEPTION_KEY_NOT_FOUND_EC);
          
      }
      /////////////////////////////////////////////////////////////////////////
      // reload_config
      // In : <no>
      // Out: <no>
      /////////////////////////////////////////////////////////////////////////
      public function reload_config()
      {
        unset($this->config);
        $this->setConfigFile();  
      }

      public function __destruct()
      {
      
        unset($config);
      }
      public function getConfig()
	  {
		return parse_ini_file($this->filePath, true);
	  }
      public function dumpConfig()
      {
        //return $this->config;
		return file_get_contents($this->filePath);
      }

   }             
  
?>