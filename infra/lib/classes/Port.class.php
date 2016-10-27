<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class Port {

	private $id = "-1";
	private $id_switch = "-1"; 
	private $name = "";
	private $alias = "";
	private $description = "";
	private $inErrors = "";
	private $outErrors = "";
	private $speed = "";
	private $mySwitch = null;
	private $tagged = false;
	private $untagged = false;
	private $vlan_id = "-1";
	
	private static $ports = array(array());
	
	public function __construct($id = "-1", $id_switch = "-1",$tagged = false, $untagged = false, $vlan_id="-1"){
		
		$this->id = (string) $id;
		$this->id_switch = $id_switch;
		$this->tagged = $tagged;
		$this->untagged = $untagged;
		$this->mySwitch = null;
		$this->vlan_id = (string) $vlan_id;
		
		self::$ports[$id][$id_switch] = $this;
	
		snmp_set_oid_numeric_print(TRUE);
		snmp_set_quick_print(TRUE);
		snmp_set_enum_print(TRUE);
	}
	
	public function getMySwitch(){
		if($this->mySwitch == null){
			$this->mySwitch = MySwitch::retrieveById($this->id_switch);
		} 
		return $this->mySwitch;
	}
	
	public static function retreiveById($switch_id = "-1", $port_id = "-1",$vlan_id = "-1",$force_update=false){
		if(!isset(self::$ports[$port_id][$switch_id]) or $force_update){
			if($mySwitch = MySwitch::retrieveById($switch_id)){
				$nbPorts = $mySwitch->getNbPorts();
				$untagged_ports = $mySwitch->getUntaggedPorts($vlan_id);
				$tagged_ports = $mySwitch->getTaggedPorts($vlan_id);
				$found = false;
				$i=0;
				while($i<count($untagged_ports) && !$found){
					if($untagged_ports[$i]->getId() == $port_id){
						$result = $untagged_ports[$i];
						$result->isUntagged = true;
						$result->isTagged = false;
						$found = true;
					}
					$i++;
				}
				if(!$found){
					$i= 0;
					while($i<count($tagged_ports) && !$found){
						if($tagged_ports[$i]->getId() == $port_id){
							$result = $tagged_ports[$i];
							$result->isTagged = true;
							$result->isUntagged = false;
							$found = true;
						}
						$i++;
					}
				}
				if(!$found){
					return false;
				}
				return $result;		
			} else {
				return false;
			}
		} else {
			return(self::$ports[$port_id][$switch_id]);
		}
	}
	
	private function getNameAliasOrId(){
		$mySwitch = $this->getMySwitch();
		if(strpos($this->getName(),"Trk")!=-1 or strpos($this->getDescription(),"Trk")!=-1) {
			if($mySwitch->getHp3ComCompat()) {
				return $this->getId();
			} else {
				return $this->getName();
			}
		}
		if($this->getAlias()=="" && SHOW_PORT_ALIASES_INSTEAD_OF_IDS){
			return $this->getId();
		}
		if($this->getName()=="" && SHOW_PORT_NAMES_INSTEAD_OF_IDS){
			return $this->getId();
		}
		if (SHOW_PORT_NAMES_INSTEAD_OF_IDS) {
			return $this->getName();
			
		}
		if (SHOW_PORT_ALIASES_INSTEAD_OF_IDS) {
			return $this->getAlias();
		}
		return $this->getId();
	}
	
	public function __toString(){
		$return = "";
		if(LANGUAGE == 'fr'){
			
			if($this->untagged){
				$title = "Port ".$this->getNameAliasOrId()." untaggu&eacute;. Statut : ";
			} else {
				$title = "Port ".$this->getNameAliasOrId()." taggu&eacute;. Statut : ";
			} 
			if($this->isUp()){
				$status = "Allum&eacute;";
			} else {
				$status = "Eteint";
			}
		} else {
			if($this->untagged){
				$title = "Port ".$this->getNameAliasOrId()." untagged. Status : ";
			} else {
				$title = "Port ".$this->getNameAliasOrId()." tagged. Status : ";
			} 
			if($this->isUp()){
				$status = "UP";
			} else {
				$status = "DOWN";
			}
		}
		$title.=$status;
              
		if($this->untagged){
			if($this->isUp()){
				$color = UP_UNTAGGED_PORT_COLOR;
                                $class = UP_UNTAGGED_PORT_BTN;
                                $badge = "label-success";
			} else {
				$color = DOWN_UNTAGGED_PORT_COLOR;
                                $class = DOWN_UNTAGGED_PORT_BTN;
                                $badge = "label-danger";
			}
		} elseif ($this->tagged){
			if($this->isUp()){
				$color = UP_TAGGED_PORT_COLOR;
                                $class = UP_TAGGED_PORT_BTN;
                                $badge = "label-primary";
			} else {
				$color = DOWN_TAGGED_PORT_COLOR;
                                $class = DOWN_TAGGED_PORT_BTN;
                                $badge = "label-warning";
			}
		} else {
			return("ERROR : a port must be either tagged or untagged");
		}
		
		if(($this->untagged) || ($this->tagged && ALLOW_PORT_TAGGING)){
			$return = "<a style=\"color:".$color."\" class=\"label $badge label-as-badge map\" href=\"edit_port_form.php?port_id=".$this->getId()."&amp;switch_id=".$this->id_switch."&amp;source_vlan=".$this->vlan_id."\" title=\"".$title."\">".$this->getNameAliasOrId()."</a>";
		} else {
                    $return = "<button style=\"color:".$color."\" class=\"label $badge label-as-badge map\" title=\"".$title."\" onclick=\"return false\"><strong>".$this->getNameAliasOrId()."</strong></button>";
                }
              
             
           
		return $return;
	}
	
	public function getId(){
		return  $this->id;
	}
	
	public function isTagged(){
		return $this->tagged;
	}
	
	public function isUnTagged(){
		return $this->untagged;
	}
	
	public function getName(){
		if($this->name == ""){
			$mySwitch = $this->getMySwitch();
			$this->name = str_replace('"',"",Fonctions::simpleSnmpGet($mySwitch,OID_ifName.".".$this->id));
		}
		return $this->name;
	}
	public function getAlias(){
	
		if($this->alias == ""){
			$mySwitch = $this->getMySwitch();
			try {
				$this->alias = str_replace('"',"", Fonctions::simpleSnmpGet($mySwitch,OID_ifAlias.".".$this->id));
			} catch (Exception $e) {
				return "";
			}
		}
		return $this->alias;
	}
	
	public function getDescription(){
		if($this->description == ""){
			$mySwitch = $this->getMySwitch();
			$this->desctiption = Fonctions::simpleSnmpGet($mySwitch,OID_ifDescr.".".$this->id);
		}
		return $this->description;
	}
	
	public function getSpeed(){
		if($this->speed == ""){
			$mySwitch = $this->getMySwitch();
			$this->speed = Fonctions::simpleSnmpGet($mySwitch,OID_ifSpeed.".".$this->id);
		}
		return $this->speed;
	}
	
	public function getInErrors(){
		if($this->inErrors == ""){
			$mySwitch = $this->getMySwitch();
			$this->inErrors = Fonctions::simpleSnmpWalk($mySwitch,OID_ifInErrors.".".$this->id);
		}
		return $this->inErrors;
	}
	
	public function getOutErrors(){
		if($this->outErrors == ""){
			$mySwitch = $this->getMySwitch();
			$this->outErrors = Fonctions::simpleSnmpWalk($mySwitch,OID_ifOutErrors.".".$this->id);
		}
		return $this->outErrors;
	}
		
	public function isUp(){
		$mySwitch = $this->getMySwitch();
		$up = Fonctions::simpleSnmpGet($mySwitch,OID_ifOperStatus.".".$this->id);
		if($up == "1"){
			return true;
		} elseif ($up == "2"){
			return false;
		}
	}
	
	public function setTagged($bool){
		if($bool){
			$this->tagged = true;
		} else {
			$this->tagged = false;
		}
		self::$ports[$this->id][$this->id_switch] = $this;
	}
	
	public function setUnTagged($bool){
		if($bool){
			$this->untagged = true;
		} else {
			$this->untagged = false;
		}
		self::$ports[$this->id][$this->id_switch] = $this;
	}
	
	public function removeTagged(){ 
	
		/* This funtion is used because when a port is untagged in another VLAN with snmpset function, the port is not set to NO in the original vlan but set to TAGGED
		     And we don't want that... */
			 
		$switch = MySwitch::retrieveById($this->id_switch);
			 
		$ports_hexa = $switch->getEgressPortsInHexa($this->vlan_id);
		$ports_bin =  Fonctions::hex2bin($ports_hexa);
		$ports_bin[$port_id - 1] = '0';
		$ports_hexa = Fonctions::bin2Hex($ports_bin);
		
		$this->setTagged(false);
		self::$ports[$this->id][$this->id_switch] = $this;
	}
	
	public function setVlan($vlan_id){
		$this->vlan_id = $vlan_id;
		self::$ports[$this->id][$this->id_switch] = $this;
	}

}

?>
