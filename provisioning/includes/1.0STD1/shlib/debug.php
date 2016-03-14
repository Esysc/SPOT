<?
  /***********************************************************************
   *file : debug.php
   *----------------------------------------------------------------------------   
   *Allow when using the inheritance, to print or not debug messages which casn be 
   *hidden by setting the debugMode to false
   ***************************************************************************/
  
  define("NOT_RESULTS", "UNDEF");
  define("ARRAY_MSG", "ARRAY CONTENT:");
  
  require_once 'debug.php';           
  
  abstract  class C_DEBUG
  {
    private $version = 1;
    private $autor = "DCL";
   
    
    protected $state;

    // generateDBGHeader 
    //scope : private
    //In : $header /str/message acronym 
    //   : $css_class /str/class defined in style.css to identfy which style should be applied
    //   : $htmel /str or /char defined on which html element is attahec the css class
    //out : Formated html code
    //
    private function generateDBGHeader($header, $css_class, $htmlel='B')
    {
    return "<$htmlel class=$css_class>$header</$htmlel>";
    }   
    //__construct
    //Scope : public
    //  In : debugMode / bool the state of the debug mode
    //       showTimeStamps /bool defined if we want to see the time and date
    //out : <>
    
    public function __construct($debugMode, $showTimeStamps=False)
    {
      $this->state=$debugMode;
      $this->showTimeStamps = $showTimeStamps; 
    }
    
    // printdbg
    // In : $message Message to shw /str
    //      $header header see  generateDBGHeader
    // out : True / bool
    //  Rem. True is always set as we can use the printdbg as a part of the return 
                                    //state
    protected function printdbg($message, $header="")
    { 
          if ($this->state == True)
          {    
                switch($header)
                {
                  case "SQL":
                    $header = $this->generateDBGHeader("SQL", "DBG_SQL_HEADER");   
                    break;
                    
                  case "VAR":
                    $header = $this->generateDBGHeader("VAR", "DBG_OBJECT_LOADER");
                    break;
                  
                  case "SQL-SUCCESS" :
                  
                    $header = $this->generateDBGHeader("SQL", "DBG_SQL_SUCCESS");
                    break;
                     
                  case "SQL-FAILURE" :
                  
                    $header = $this->generateDBGHeader("SQL", "DBG_SQL_FAILURE");
                    break;                
                  case "OBJECT":
                     $header = $this->generateDBGHeader("OBJ", "DBG_OBJECT_LOADER");
                     break;
                  case "PARAM":
                     $header = $this->generateDBGHeader("PARAM", "DBG_PARAM_READER");
                     break;
                  default:
                    $header = $this->generateDBGHeader("INFO", "DBG_INFO_HEADER"); 
                    break; 
                }
                
                
                if ($message=="") 
                  $message = NOT_RESULTS;
                  $line = "";
  
                  if ($this->showTimeStamps)
                    $line .=  date(DATE_RFC822);
                    
                  $line .=  '['.$header.']';
                  
                  if ( is_array($message) )
                  {
                    $tmp = var_dump($message);
                    $line .= ARRAY_MSG;
                    print_r($line);
                    print_r($tmp);
                  }
                  else
                  {
                    $line .= $message;
                    print_r($line);
                  }
                  
                 }
        
           return True;     
           } // END function Print
    protected function toggleDebugMode()
    {
      $this->state = ! $this->state;   
    }
    
    
  }

?>