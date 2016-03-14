<?php
	/***SEE http://stackoverflow.com/questions/3899971/implode-and-explode-multi-dimensional-arrays */
	

    function netmask2cidr($netmask) {
        $cidr = 0;
        foreach (explode('.', $netmask) as $number) {
            for (;$number> 0; $number = ($number <<1) % 256) {
                $cidr++;
            }
        }
        return $cidr;
    }


/*	function   boolval($val)
	{
		if ( is_string($val) )
			if ( $val === "0" ) return False; 
			else
				return True;
				
		if ( is_integer($val) )
			if ( $val == 0 ) return False;
			else
				return true;
	} */

	function multi_implode($array, $glue) {
		$ret = '';

		foreach ($array as $item) {
			if (is_array($item)) {
				$ret .= multi_implode($item, $glue) . $glue;
			} else {
				$ret .= $item . $glue;
			}
		}

		$ret = substr($ret, 0, 0-strlen($glue));

		return $ret;
	}
	function createRowFromArray($array)
	{
		return  multi_implode( $array ,"</TD><TD>" );
		
	}
	function printtolog($message)
	{
		$file = '/var/www/productiondb/1.0STD1/log/productiondb.log';
		$header = "[site]";
		$hdl = fopen($file,'a');
			fwrite($hdl, $header.$message."\n");
		fclose($hdl);
		
	}
	function printtologv($varName,$var)
	{
		printtolog("$varName=".var_export($var, TRUE));
	}
	

	function drawTable($tableData,$css_class="imagetable", $table_params="", $td_params="", $tr_paramas="")
	{
		if ( count($tableData) == 0 ) return 'Empty set';
		
		$str = "<table $table_params class='$css_class'>";
		$str .= "<tr>";
		foreach ( array_keys($tableData[0]) as $index=>$colName )
			$str .= "<th>$colName</th>";
		$str .= "</tr>";
		
		foreach ( $tableData as $tableIndex=>$tableLine)
		{
			$str .= "<tr $tr_paramas >";
			foreach ( $tableLine as $index=>$tblLine )
				$str .= "<td $td_params>$tblLine</td>";
			$str .= "</tr>";
		
		
		}
		
		$str .= "</table>";
		return $str;
	}
	function booltoi($val)
	{
		if ( is_bool($val) )	
			return $val ? 1 : 0;
		else
			return $val;
	}
	function separateCapital($str)
	{
		
		
		
		if ( strtoupper($str) === $str ) return $str;
		
		$paramName = ucwords($str);
		$finalName = $paramName;
		
		
		
		for ( $i = 0; $i < strlen($paramName); $i++)
		{

			
			if ($paramName[$i] === strtoupper($paramName[$i]))
			{
				
				if ($i > 0 )
				{
					
					$firstPart = substr($paramName, 0, $i);
					$endPart = substr($paramName,$i,strlen($paramName) - 1);
					if ( strlen($endPart) == 0) break;
					$endPart[0] = strtolower($endPart[0]);
					
					
					$finalName = $firstPart." ".$endPart;
				
				}
			}
		}		
	
		return $finalName;
	}

	function strPlusOne($str)
	{
		if ( ! is_string($str) ) return $str;
		if ( $str == "") return $str;

		$sPos=-1;
		$ePos = -1;
		foreach ( $str as $index=>$cur_char)
		{
			if ( is_numeric($cur_char) )
				if ( $sPos == -1 )
					$sPos = $index;
			else
				if ( $sPos != -1 )
				{
						$ePos = $index - 1;
						$int = mb_substr($str, $sPos, $ePos);
						$nint = strval(inval($int) + 1);
						
						if ( strlen($int) < strlen($nint) ) 
						{
							$fisrtPar = mb_substr($str,0,$sPos - 1);
							$secondPart = mb_substr($str, $ePos, strlen($str));
							$str = $fisrtPar.strval($nint).$secondPart;
						}
						else
							foreach ( $nint as $index=>$val)
								$str[$sPos++] = $val;
								
						
							
						
						
				}
		}
		
		
	
		
		//$str[$endCellIndex] = intval($str[$endCellIndex]) + 1;
		
		return $str;
	}
	function isCapital($char)
	{
		if (strlen($char) > 1 or $char == "" ) return Null;
		if ( is_numeric($char) ) return Null;
		return  strtoupper($char) === $char;
	}
 
   	/**
	*  Takes XML string and returns a boolean result where valid XML returns true
	*/
	function is_valid_xml ( $xml ) 
	{
		libxml_use_internal_errors( true );
		
		$doc = new DOMDocument('1.0', 'utf-8');
		
		$doc->loadXML( $xml );
		
		$errors = libxml_get_errors();
	
		return empty( $errors );
	}

	function getXMLFmt( $field="", $value="")
	{
		return "<".$field.">".$value."</".$field.">\n";
	}
   if ( ! defined("V_LINE") ) define("V_LINE", "<hr>");
   if ( ! defined("CR") ) define("CR", "<br>");
   
   function print_($message="")
   {
    
    if (is_array($message) )
    {
      print_r( var_dump($message));
      print CR;
    }
    else
      print ($message.CR);
    
   }
   function validateString($str)
   {
     return isset($str) and $str != "";
   } 
   
   function generateHTMLHeader($title,$subtitle , $cssFile="style.css")
   {
    $out = "<!DOCTYPE HTML><HTML><HEAD><TITLE>$title</TITLE>";
    
    
    if ( validateString ($cssFile))
      $out .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$cssFile\"\/>";
    $out .= "</HEAD><BODY><H1>$title<H1><H2>$subtitle</h2>";
    
    return $out;
    
  
  
   }
   

   function covertDateToISO($date)
   {
    if ( !( $format = checkDateFormat($date)) )
      return False;
    
    if  ($format == EN_DATE)
      return $date;
    
    if ($format == EN_EUROPE)
    { 
      $date = explode(checkDateDelimiter($date), $date);  
      $month = $date[1];
      $day   = $date[0];
      $year  = $date[2]; 
      if   ( ! checkdate($month,$day,$year) )
        return False;      
      return date("Y-m-d", mktime(0,0,0,$month,$day,$year));
    
    }
    
   }
   function checkDateDelimiter($date)
   {
    if ( ! ( $delim = strpos($date,'.')))
      if ( ! ( $delim = strpos($date,'/')))  
        if ( ! ( $delim = strpos($date,'-')))
          return False;
          
    return  $date[$delim];  
   }
   
   function checkDateFormat($date)
   {

    
     $date = explode(checkDateDelimiter($date), $date);
    
     
     if (count($date) != 3)
      return False;
    
     
     for ($cell = 0 ; $cell < count($date) - 1;$cell++)
      if  ( intval($date[$cell])  == False)
        return False;
      
        
     
     if ( $date[0]  > MAX_DAYS_IN_MONTH)
     {
      $month = $date[2];
      $day = $date[1];
      $year = $date[0];
     
      if ( ! checkdate($month,$day,$year) )
        return False;
      return EN_DATE;
     }
     if ( $date[0] <= MAX_DAYS_IN_MONTH and $date[0] > 0)
     {
      $month = $date[1];
      $day = $date[0];
      $year = $date[2]; 
      if ( ! checkdate($month,$day,$year) )
        return False;
      
      return EN_EUROPE;
           
     } 
     return False;  
      
   }
   
   function test($functionHandle, $header="", $paramList="")
   {
    if ( $header == "" )
      $header .= date("d-m-Y")."|<B>".$functionHandle."</B>]";
    else
      $header .= "[<B>".$functionHandle."</B>]";
    
    print_("===============================================================");
    print_($header);
    print_("===============================================================");
    $functionHandle($paramList);
    
    
    
    
      
   }



   /**Code from http://www.terrawebdesign.com/multidimensional.php **/
function do_offset($level){
    $offset = "";             // offset for subarry 
    for ($i=1; $i<$level;$i++){
    $offset = $offset . "<td></td>";
    }
    return $offset;
}

function show_array($array, $level, $sub){
    if (is_array($array) == 1){          // check if input is an array
       foreach($array as $key_val => $value) {
           $offset = "";
           if (is_array($value) == 1){   // array is multidimensional
           echo "<tr>";
           $offset = do_offset($level);
           echo $offset . "<td>" . $key_val . "</td>";
           show_array($value, $level+1, 1);
           }
           else{                        // (sub)array is not multidim
           if ($sub != 1){          // first entry for subarray
               echo "<tr nosub>";
               $offset = do_offset($level);
           }
           $sub = 0;
           echo $offset . "<td main ".$sub." width=\"120\">" . $key_val . 
               "</td><td width=\"120\">" . $value . "</td>"; 
           echo "</tr>\n";
           }
       } //foreach $array
    }  
    else{ // argument $array is not an array
        return;
    }
}

function html_show_array($array){
  echo "<table cellspacing=\"0\" border=\"2\">\n";
  show_array($array, 1, 0);
  echo "</table>\n";
}

	function getMethodList($object, $allowedPattern=Null, $forbidden_method=Null)
	{
		$class = get_class($object);
		$forbidden_methods = array("__destruct", "__construct");
		if ( $forbidden_method!=null )
				array_push($forbidden_methods, $forbidden_method);
		
		$allowed_scope     = "public"; //NYI
		if ( $allowedPattern == Null ) 
			$allowedPattern = array("set", "get");
		
		$method_list = get_class_methods($class);
		
		foreach ($forbidden_methods as $toDel)
		{ 
			$indexToDel = array_search($toDel,$method_list);
			unset ( $method_list[$indexToDel] );
		}
		
		
		foreach ($method_list as $notDel)
		{
			$key = substr($notDel, 0, 3);
			
			
			if ( ! in_array($key,$allowedPattern))
				unset ($method_list[array_search($notDel,$method_list)]);
			
				
		}

		return $method_list;
			
			
		
		
	}
	function callFunc($object, $funcName, $callbackParsm=Null)
	{
		if ( $funcName == "" or ! is_object($object) ) throw new Exception("Error : Wrong parameters");
		
		if ( is_string($callbackParsm) ) $callbackParsm = array( $callbackParsm );
		
			if ($callbackParsm != Null ) 
			{
					return call_user_func_array(array($object, $funcName) , $callbackParsm);
			}
			else
				return $object->$funcName();		
	}
	
	function getParamNameList( $object, $function )
	{
		
		$rflOrder = new ReflectionMethod($object, $function);
		return $rflOrder->getParameters();
	}
	
	function getTypeAsStr($paramVal, $delim='_')
	{
		if ($paramVal == "" ) return Null;
		
		$paramVal = (string)$paramVal;
		
		$paramVal = explode($delim,$paramVal);
		
		
		if (count($paramVal) == 1) return $paramVal;
		
		
		
		$Types = array("int", "str", "bool", "float");
		if ( in_array($paramVal[0], $Types ) )
		{
			
			return $paramVal[0];
		}
		
		return Null;
	}
	
	
	function toType($param, $value, $delim='_')
	{
		if ( $value === "NULL" ) return NULL;
		if ($param == "") return Null;
		if  ( is_string($value) and $value == "") return "";
		
		
		$param = (string)$param;
		
		$param = explode($delim,$param);
		
		
		if (count($param) == 1) return $value;
		
		$Types = array("int", "str", "bool", "float");
		if ( in_array($param[0], $Types ) )
		{
			switch($param[0])
			{
				case "bool":
					
					if ( is_string($value) )
					{
						$value == '1' ? $value = True : $value = False;
						
						return $value;
					}
					if ( is_int($value) ) 
					{
						$value == 1 ? $value = True : $value = False;
						return $value;
					}
					else
						return (bool)$value;
				case "int":
					return (int)$value;
				case "float":
					return (float)$value;
				default:
					return $value;
			}
		
		}
		
		return Null;
	}
	
	
	function getParamName($param, $delim='_')
	{
		
		$paramName = explode($delim, $param);
		
		if (count($paramName) == 1)
			return $param;
		else
			return $paramName[1];
	}
	
	function htmldump($variable, $height="9em")
	{
		echo "<pre style=\"border: 1px solid #000; height: {$height}; overflow: auto; margin: 0.5em;\">";
		var_dump($variable);
		echo "</pre>\n";
	}
	
	function mkSimpleForm($title, $main_title, $subtitle, $content, $filePath)
	{
		if ( file_exists($filePath) )
		{
			$str = file_get_contents($filePath);
			
			$str = str_replace("{wnd_titlele}", $title, $str);
			$str = str_replace("{main_title}", $main_title, $str);
			$str = str_replace("{subsitile}", $subtitle, $str);
			$str = str_replace("{content}", $content, $str);
			
			return $str;
			
		}
		return NULL;
	}
	
	
?>
