<?php

Class Fonctions {

	static function hex2bin($string_hex){
		
		$values["0"] = "0000";
		$values["1"] = "0001";
		$values["2"] = "0010";
		$values["3"] = "0011";
		$values["4"] = "0100";
		$values["5"] = "0101";
		$values["6"] = "0110";
		$values["7"] = "0111";
		$values["8"] = "1000";
		$values["9"] = "1001";
		$values["A"] = "1010";
		$values["B"] = "1011";
		$values["C"] = "1100";
		$values["D"] = "1101";
		$values["E"] = "1110";
		$values["F"] = "1111";
		
		$string_conv = "";
		for($i=0;$i<strlen($string_hex);$i++){
			if(isset($values[$string_hex[$i]])){
				$string_conv .= $values[$string_hex[$i]];
			}
		}
		return $string_conv;
	}
	
	
	static function bin2Hex($string_bin){
	
		$values["0000"] = "0";
		$values["0001"] = "1";
		$values["0010"] = "2";
		$values["0011"] = "3";
		$values["0100"] = "4";
		$values["0101"] = "5";
		$values["0110"] = "6";
		$values["0111"] = "7";
		$values["1000"] = "8";
		$values["1001"] = "9";
		$values["1010"] = "A";
		$values["1011"] = "B";
		$values["1100"] = "C";
		$values["1101"] = "D";
		$values["1110"] = "E";
		$values["1111"] = "F";
		
		$string_conv = "";
		if(strlen($string_bin) % 4 != 0){
			die("ERROR : string_bin must be a multiple of 4 (size : ".strlen($string_bin).")");
		}
		$i = 0;
		$j = 0;
		$string_conv = "";
		while($i<strlen($string_bin)){
			$ind = "";
			$j = $i +4;
			for($s=$i;$s<$j;$s++){
				$ind .= $string_bin[$s];
			}
			$string_conv .= $values[$ind];
			$i = $i + 4;
		}
		
		return $string_conv;
	}
	
	public static function parsePortsListInHexa($ports_hexa=array(),
												$switch_id="-1",
												$vlan_id="-1",
												$type_of_ports=""){
												
		/* ******
		*	returns a list of ports parsed from an hexa string got with snmpget 
		*	@params :
		*	$ports_exa = hexa string got from snmpget
		*	$switch_id = we need it because we need to know the total number of ports
		*	$vlan_id = Port contructor needs Vlan_id
		*	$type_of_ports = Must be TAGGED  or UNTAGGED
		*/
		
		$untagged = false;
		$tagged = false;
		
		if($type_of_ports == "UNTAGGED"){
			$untagged = true;
		} 
		
		if ($type_of_ports == "TAGGED"){
			$tagged = true; 
		} 
		$ports_convert = Fonctions::hex2bin($ports_hexa);
		$switch = MySwitch::retrieveById($switch_id);
		$i=1;
		$ports = array();
		$nbPorts = $switch->getNbPorts();
		$portsmap = $switch->getPortsmap();
		//Hexa string converted in binary : each '1' position (first, second...) in the binary string represent a port number (first->port 1, second ->port 2 etc.).  
		while($i <= strlen($ports_convert) && $i <=count($portsmap)){
			if($ports_convert[$portsmap[$i]-1]=="1"){
				$ports[] = new Port((string) $portsmap[$i],$switch_id,$tagged,$untagged,$vlan_id);
			}
			$i++;
		}
		return $ports;
	}
	
	public static function getEmptyBitMap($size){
		/** 
		*	 Returns an "empty" hexa string 
		*/
		if(!is_int($size) || $size % 4 != 0){
			return false;
		}
		$result = "";
		for($i=0;$i<$size/4;$i++){
			if($i % 2 == 0 && $i != ($size/4) && $i != 0){
				$result .= " ";
			}
			$result .= "0";
		}
		return $result;
	}
	
	public static function simpleSnmpWalk($switch,$oid="-1"){
		/***
		*  simple snmpwalk (used in order to throw execptions easier, to abstract snmp version and to avoid most of paramerters)
		*/
		$version = $switch->getSnmpVersion();
		if($version == 3){
			$user = $switch->getSnmpV3User();
			$passphrase = $switch->getSnmpV3Passphrase();
			$secLevel = $switch->getSnmpV3SecLevel();
			$authProtocol = $switch->getSnmpV3AuthProtocol();
			$privProtocol = $switch->getSnmpV3PrivProtocol();
			$privPassphrase  =  $switch->getSnmpV3PrivPassphrase();
			$r = snmp3_walk ($switch->getIp(),$user,$secLevel,$authProtocol,$passphrase,$privProtocol,$privPassphrase,$oid);
		} elseif ($version == 2){
			$r = snmp2_walk($switch->getIp(),$switch->getCommunity(),$oid);
		} else {
			$r = snmpwalk($switch->getIp(),$switch->getCommunity(),$oid);
		}
		
		if(!$r){
			$value = $oid;
			$oid_object = Oid::retrieveByValue($value);
			if(!$oid_object){
				$value = substr($oid,0,strlen($value)-3);
				$oid_object = Oid::retrieveByValue($value);
			}
			throw new Exception("ERROR snmpwalk v$version : OID = ".$oid_object." on the switch ".$switch->getIp().".");
			return false;
		} else {
			return($r);
		}
	}
	

	public static function simpleSnmpRealWalk($switch,$oid="-1"){
		/***
		*  simple snmpwalk (used in order to throw execptions easier, to abstract snmp version and to avoid most of paramerters)
		*/
		$version = $switch->getSnmpVersion();
		if($version == 3){
			$user = $switch->getSnmpV3User();
			$passphrase = $switch->getSnmpV3Passphrase();
			$secLevel = $switch->getSnmpV3SecLevel();
			$authProtocol = $switch->getSnmpV3AuthProtocol();
			$privProtocol = $switch->getSnmpV3PrivProtocol();
			$privPassphrase  =  $switch->getSnmpV3PrivPassphrase();
			/*array snmp3_walk ( string $host , string $sec_name , string $sec_level , string $auth_protocol , string $auth_passphrase , string 

			$priv_protocol , string $priv_passphrase , string $object_id [, string $timeout [, string $retries ]] )*/
			$r = snmp3_real_walk ($switch->getIp(),$user,$secLevel,$authProtocol,$passphrase,$privProtocol,$privPassphrase,$oid);
		} elseif ($version == 2){
			$r = snmp2_real_walk($switch->getIp(),$switch->getCommunity(),$oid);
		} else {
			$r = snmprealwalk($switch->getIp(),$switch->getCommunity(),$oid);
		}
		
		if(!$r){
			$value = $oid;
			$oid_object = Oid::retrieveByValue($value);
			if(!$oid_object){
				$value = substr($oid,0,strlen($value)-3);
				$oid_object = Oid::retrieveByValue($value);
			}
			throw new Exception("ERROR snmprealwalk v$version : OID = ".$oid_object." on the switch ".$switch->getIp().".");
			return false;
		} else {
			return($r);
		}
	}
	
	public static function simpleSnmpGet($switch,$oid="-1",$oid3com=false){
		/***
		*  simple snmpget (used in order to throw execptions easier, to abstract snmp version and to avoid most of paramerters)

		*/
		$version = $switch->getSnmpVersion();
		if($version == 3){
			$user = $switch->getSnmpV3User();
			$passphrase = $switch->getSnmpV3Passphrase();
			$secLevel = $switch->getSnmpV3SecLevel();
			$authProtocol = $switch->getSnmpV3AuthProtocol();
			$privProtocol = $switch->getSnmpV3PrivProtocol();
			$privPassphrase  =  $switch->getSnmpV3PrivPassphrase();
			$r = snmp3_get ($switch->getIp(),$user,$secLevel,$authProtocol,$passphrase,$privProtocol,$privPassphrase,$oid);
		} elseif ($version == 2){
			$r = snmp2_get($switch->getIp(),$switch->getCommunity(),$oid);
		} else {
			$r = snmpget($switch->getIp(),$switch->getCommunity(),$oid);
		}
		if(!$r){
			$value = $oid;
			$oid_object = Oid::retrieveByValue($value);
			if(!$oid_object){
				$value = substr($oid,0,strlen($value)-3);
				$oid_object = Oid::retrieveByValue($value);
			}
			if($oid_object==null){
				die("An error has occured while sending a snmp request on OID $value on switch  $switch->getIp()");
			} else {
				throw new Exception("ERROR snmpget v$version : OID = ".$oid_object." on the switch ".$switch->getIp().".");
			}
			return false;
		} else {
			return($r);
		}
	}
	

	public static function simpleSnmpSet($switch,$oid="-1",$type="error:no-type-provided",$value=null){
		/***
		*  simple snmpset (used in order to throw execptions easier, to abstract snmp version and to avoid most of paramerters)
		*/
		$version = $switch->getSnmpVersion();
		/*var_dump($type);
		var_dump($value);
		var_dump($oid);*/
		if($version == 3){
			$user = $switch->getSnmpV3User();
			$passphrase = $switch->getSnmpV3Passphrase();
			$secLevel = $switch->getSnmpV3SecLevel();
			$authProtocol = $switch->getSnmpV3AuthProtocol();
			$privProtocol = $switch->getSnmpV3PrivProtocol();
			$privPassphrase  =  $switch->getSnmpV3PrivPassphrase();
			$r = snmp3_set ($switch->getIp(),$user,$secLevel,$authProtocol,$passphrase,$privProtocol,$privPassphrase,$oid,$type,$value);
		} elseif ($version == 2){
			$r = snmp2_set($switch->getIp(),$switch->getCommunity(),$oid,$type,$value);
		} else {
			$r = snmpset($switch->getIp(),$switch->getCommunity(),$oid,$type,$value);
		}
		
		if(!$r){
			$value = $oid;
			$oid_object = Oid::retrieveByValue($value);
			if(!$oid_object){
				$value = substr($oid,0,strlen($value)-3);
				$oid_object = Oid::retrieveByValue($value);
			}
			throw new Exception("ERROR snmpset v$version : OID = ".$oid_object." on the switch ".$switch->getIp()." Value : ".$value." Type : ".$type.".");
			die();
			return false;
		} else {
			return($r);
		}
	}
	
	
	public static function multiSnmpSet($switch,$oids = array(),$types=array(),$values=array()){
	
		/** 
		*	To send several snmpset at the same time
		*	We need this to create a vlan (hp devices reject sequential commands).
		* 	It doesn't exist for the moment in php : snmpset does not support arrays as arguments. A bug report has already been posted on PHP.net by someone 
		* 	A patch is provided here : http://bugs.php.net/bug.php?id=37865 
		* 	Maybe you could make it work with this patch... I haven't tried... 
		*	Orelse, maybe we could do the job with exec command ?
		*/
		
		/* code should look like this : 
			$no_errors = true;
			if(!snmpset($ip,$community,$oids,$types,$values)){
				$no_errors = false;
				throw new Exception("ERROR multi snmp : OID = ".$oid." on the switch ".$ip." (community : ".$community.").");
			}
			return $no_errors;
		*/
		
		$i=0;
		$no_errors = true;
		$version = $switch->getSnmpVersion();
		if($version == 3){
			$user = $switch->getSnmpV3User();
			$passphrase = $switch->getSnmpV3Passphrase();
			$secLevel = $switch->getSnmpV3SecLevel();
			$authProtocol = $switch->getSnmpV3AuthProtocol();
			$privProtocol = $switch->getSnmpV3PrivProtocol();
			$privPassphrase  =  $switch->getSnmpV3PrivPassphrase();
		}

		foreach($oids as $oid){

			if($version == 3){
				$r = snmp3_set($switch->getIp(),$user,$secLevel,$authProtocol,$passphrase,$privProtocol,$privPassphrase,$oid,$types[$i],$values[$i]);
			} elseif ($version == 2){
				$r = snmp2_set($switch->getIp(),$switch->getCommunity(),$oid,$types[$i],$values[$i]);
			} else {
				$r = snmpset($switch->getIp(),$switch->getCommunity(),$oid,$types[$i],$values[$i]);
			}

			if(!$r){
				$no_errors = false;
				$value = $oid;
				$oid_object = Oid::retrieveByValue($value);
				if(!$oid_object){
					$value = substr($oid,0,strlen($value)-3);
					$oid_object = Oid::retrieveByValue($value);
				}
				throw new Exception("ERROR snmpset : OID = ".$oid_object." on the switch ".$switch->getIp()." Value : ".$values[$i]." Type : ".$types[$i].".");
			}
			$i++;	
		}
		return $no_errors;
	}
}
 
?>
