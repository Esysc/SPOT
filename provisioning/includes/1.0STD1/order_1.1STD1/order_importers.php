<?php
	require_once "order_exporters.php";
	
	class IMPORTER_EX extends Exception 
	{
		private $head = "[IMPORTER_EX]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	class IMPORTERS extends EXPORTERS
	{
		
		
		
		public function __construct($so, $modelList)
		{
			
			parent::__construct($so, $modelList);
		}
		public function set_new_container($src_array, $new_array)
		{
			if ( $new_array === NULL ) return False;
			
			if ( property_exists($this, $src_array) )
			{
				if ( $src_array != NULL )
					if ( count(array_diff(array_keys($new_array),array_keys($this->$src_array))) === 0 )
					{
						$this->$src_array = $new_array;
						return true;
					}
				else
				{
					$this->$src_array = $new_array;
					return true;
				}
					
				throw new Exception("MALFORMED ARRAY");
			}
			
		}
		
	}
	

?>