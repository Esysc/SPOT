<?php
	$LIBPATH   = ".";
	$PHPLIBPATH= "shlib/";
	$SHLIBPATH = "/usr/share/php/";
	set_include_path(get_include_path() . PATH_SEPARATOR . $SHLIBPATH . PATH_SEPARATOR . $PHPLIBPATH .PATH_SEPARATOR . $LIBPATH );
	
	require_once "order/order_importers.php";
	//require_once "commonFunctions.php";
	

	class ORDER extends IMPORTERS
	{
		
		public function __construct($so, $modelList)
		{
			parent::__construct($so, $modelList);
		}
		
		public $version = "1.0.1";
		
		public function canBeCommited()
		{
			return parent::GENINFO_check();
		}
		
	}







?>
