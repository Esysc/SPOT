<?php
	$LIBPATH="/var/www/productiondb/1.0STD1/lib/";
	$PHPLIBPATH="/var/www/productiondb/1.0STD1/shlib/";
	$SHLIBPATH="/usr/share/php/";
        
        
        
	set_include_path(get_include_path() . PATH_SEPARATOR . $SHLIBPATH . PATH_SEPARATOR . $PHPLIBPATH .PATH_SEPARATOR . $LIBPATH );
	require_once "commonFunctions.php";
	define("MIN_GID", 100);
	define("DEFAULT_CRM_ID", "1-0000");
	class GENINFO_EX extends Exception 
	{
		private $head = "[ORDER_GENINFO]";
		
		public function __construct($message, $code = 0, Exception $previous = null) 
		{
			$message = $this->head.$message;
			parent::__construct($message, $code, $previous);
		}

	}
	class GENINFO
	{
		protected $salesOrder;
		protected $ProgramManager;
		protected $SolArchitecht;     
		protected $sysprodActor;     
		protected $release;
		protected $comment;
		protected $realStartDate;
		protected $realEndDate;
		protected $pendDate; 
		protected $pstartDate; 
		protected $networks;
		protected $cctsnapshotPath;
		protected $orderComment;
		protected $orderDescription;
		protected $customerAcronym;
		protected $sp_nbMachines = 0; //SharePoint Machines count
		protected $account;
		protected $systemID;
		protected $canBeCommited;
		protected $apcGID = MIN_GID; //used to create an index of apcItems
		protected $version = "1.1STD1";

		public function getExpMachinesCount()
		{
			return $this->sp_nbMachines;
		}
		
		public function setExpMachinesCount($value)
		{
                        $val = intval($value);
			if ( $val > 0 ) 
				$this->sp_nbMachines = $val;
	
		}
		
		public function get_min_gid()
		{
			return MIN_GID;
		}
		public function getNewGID($no_apc=False)
		{
			if ( $no_apc )
				return 0;
			else
				return $this->apcGID++;
		}
		
		public function setcustomerAcronym($val)
		{
			$this->customerAcronym = strtoupper($val);
		}
		
		public function getcustomerAcronym()
		{
			return $this->customerAcronym;
		}
		
		public function setCRMID($val)
		{
			$this->systemID = $val;
		}
		
		public function getCRMID()
		{
			return $this->systemID;
		}
		
		protected function GENINFO_check()
		{
			return ! ( 
					   empty($this->salesOrder)     or
					   empty($this->ProgramManager) or 
				       empty($this->sysprodActor)   or 
				       empty($this->realStartDate)  or 
				       empty($this->realEndDate)    or 
				       empty($this->pendDate)       or 
				       empty($this->pstartDate)     or 
				       empty($this->customerAcronym) 
					  ) ;
				 
				
				 
		}
		
		protected function __construct($salesOrder)
		{
			
			$this->salesOrder = $salesOrder;
			$this->canBeCommited = false;
			$this->realStartDate = DateTime::createFromFormat("d-m-Y",date("d-m-Y"));
			$this->realEndDate = DateTime::createFromFormat("d-m-Y",date("d-m-Y"));
			$this->pendDate = DateTime::createFromFormat("d-m-Y",date("d-m-Y")); 
			$this->pstartDate = DateTime::createFromFormat("d-m-Y",date("d-m-Y")); 
			$this->setSysprodActor("DCL");
			$this->apcGID = MIN_GID;
			$this->setCCTSnapshotPath("/NA");
                        $this->setCRMID(DEFAULT_CRM_ID);
			
		}

		public function setSalesOrder($so)
		{
		 if ( is_numeric($so) )
			if ( ( $so  = intval($so)) != 0 ) 
				return $this->salesOrder = $so;
		 
		 
		  throw new GENINFO_EX("Invalid sales order supplied:");
		}
		
		public function getSalesOrder()
		{
		  return $this->salesOrder;
		}
		
		
		public function setProgramManager($value)
		{
			$this->ProgramManager = $value;
		}
		public function getProgramManager()
		{
		  return $this->ProgramManager;
		}
		
		public function setPGM($value)
		{
			$this->ProgramManager = $value;
		}
		public function getPgm()
		{
		  return $this->ProgramManager;
		}
		
		public function getSiteEngineer()
		{
		  return $this->SolArchitecht;
		}
		
		public function getSolArch()
		{
		  return $this->SolArchitecht;
		}

		public function setSiteEngineer($newVal)
		{
			if (strlen($newVal) >= 2)
				return $this->SolArchitecht = $newVal;
			else
				$this->SolArchitecht = "";
		}
		
		public function setSolArchitecht($newVal)
		{
			if (strlen($newVal) >= 2)
				return $this->SolArchitecht = $newVal;
			else
				$this->SolArchitecht = "";
		}	
		public function getCCTSnaptshot()
		{
			return $this->cctsnapshotPath;
		}	
		public function setCCTSnapshotPath($val)
		{
			if ( ! is_string($val) ) throw new GENINFO_EX("CCT snaptshot isn't valid ");
			
			$this->cctsnapshotPath = $val;
			
		}
		public function setSysprodActor($newVal)
		{
		   $this->sysprodActor = strtoupper($newVal);
		}
		public function getSysprodActor()
		{
		  return $this->sysprodActor;
		}
		
		public function setRelease($newVal)
		{
		  return $this->release = $newVal;
		}
		
		public function getRelease()
		{
		  return $this->release;
		}		
		public function setCustomer($customer)
		{
			
			return $this->orderDescription = $customer;
		}
		public function getCustomer()
		{
			return $this->orderDescription;
		}
		public function setComments($comment)
		{
		  return $this->comment = $comment;
		}
		public function getComments()
		{
		  return $this->comment;
		}
		public function setRealStartDate($dateISO)
		{

			if ($dateISO == "")
				return $this->realStartDate = $this->realEndDate;
			else if ( $dateISO instanceof DateTime )
				return $this->realStartDate = $dateISO;
			else
				return $this->realStartDate = DateTime::createFromFormat('d-m-Y',$dateISO);

				
		   
		   
		   return true;
		}
		
		public function setProdStartDate($dateISO)
		{
			
			if ($dateISO == "")
				return $this->pstartDate = $this->realEndDate;
			else if ( $dateISO instanceof DateTime )
				return $this->pstartDate = $dateISO;
			else
				return $this->pstartDate = DateTime::createFromFormat('d-m-Y',$dateISO);		  
		  
		}	
		
		public function getProdStartDate()
		{
			if ( ! $this->pstartDate instanceof DateTime )return "";
				return $this->pstartDate->format('d-m-Y');
			
		}
		public function getProdEndDate()
		{
			if ( ! $this->pendDate instanceof DateTime )return "";
				return $this->pendDate->format('d-m-Y');		
			 
		}
		public function setProdEndDate($newDate)
		{
			if ($newDate == "")
				return $this->pendDate = $this->realEndDate;
			else if ( $newDate instanceof DateTime )
				return $this->pendDate = $newDate;
			else
				return $this->pendDate = DateTime::createFromFormat('d-m-Y',$newDate);

		  		
		}
		
		
		public function setStartDate($dateISO)
		{
		
			return $this->setRealStartDate($dateISO);
		}
		
		public function setEndDate($dateISO)
		{
			return $this->setRealEndDate($dateISO);
		}
		public function getStartDate()
		{
			return $this->getRealStartDate();
		}
		public function getEndDate()
		{
			return $this->getRealEndDate();
		}
		
		
		public function getRealStartDate()
		{
			
		  if ( ! $this->realStartDate instanceof DateTime )return "";
			return $this->realStartDate->format('d-m-Y');
		}


		public function setRealEndDate($endDate)
		{
		
			if ($endDate == "")
				return $this->realEndDate = $this->realEndDate;
			else if ( $endDate instanceof DateTime )
				return $this->realEndDate = $endDate;
			else
				return $this->realEndDate = DateTime::createFromFormat('d-m-Y',$endDate);

		}

		protected function printdbg($message)
		{
			echo "<code><B>ORDER:</B>$message</code>";
		}
		
		public function getRealEndDate()
		{
		  if ( ! $this->realEndDate instanceof DateTime )return "";
			return $this->realEndDate->format('d-m-Y');		
		  
		}
		public function add_network($str_name, $str_ip, $str_mask)
		{ 
			
			if ( $this->isValidIP($str_ip))
			{
				if ( count( $this->networks ) == 0 )
					return $this->networks[$str_name]  = array($str_ip, $str_mask);
						
						
				
					
					return $this->networks[$str_name]  = array($str_ip, $str_mask);

				 
			}
			else
				throw new GENINFO_EX("INVALID_IP");
		}
		
		public function update_network($name, $str_ip, $mask)
		{
		  $this->remove_network($name);
		  $this->add_network($name, $str_ip, $mask);
		  
		}
		public function remove_network($name)
		{
		  if ( isset($this->networks[$name]) )
			unset ( $this->networks[$name] ); 
		  else
			throw new Exception("No network found");
		}
		public function getNetwork($name)
		{
		  if ( isset($this->networks[$name]) )
			return $this->networks[$name];
		  else
			throw new Exception("No network found");
		}
		
		public function getNetworks()
		{
		  return $this->networks;
		}		
		public function isValidIP($str_ip)
		{
			
		  if ( $str_ip === "DHCP" ) return True;
		  
		  
		  return filter_var($str_ip, FILTER_VALIDATE_IP) !== false;
		}		
		
		public function merge_networks($appendArray)
		{
			if ( $this->getNetworks() !== NULL)
				$this->networks = array_merge($this->getNetworks(), $appendArray);
			else
				$this->networks = $appendArray;
		}

	}

?>
