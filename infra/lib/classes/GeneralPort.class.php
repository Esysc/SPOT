<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class GeneralPort { // Port is defined regardless the vlan : used when clicking on a vlan (instead of a port in a specific vlan)
	

	private $id = -1;
	private $id_switch = null; 
	private $mySwitch = null;
	private $vlanUntagged = null;
	private $taggedVlans = null;
	
	private static $ports = array(array());
	
	public function __construct($id = "-1", $id_switch = "-1"){
		
		$this->id = (string) $id;
		$this->id_switch = $id_switch;
		$this->mySwitch = null;
		
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
	
	public function getVlanUntagged(){
		if(!isset($this->vlanUntagged)){
			$this->setVlanUntagged();
		}
		return $this->vlanUntagged;
	}
	
	public function getTaggedVlans(){
		if(!isset($this->taggedVlans)){
			$this->setTaggedVlans();
		}
		return $this->taggedVlans;
	}
	
	
	private function setVlanUntagged(){
		
		$vlans = $this->getMySwitch()->getVlans();
		$i=0;
		while($i<count($vlans) && !isset($this->vlanUntagged)){
			$vlan = $vlans[$i];
			$untagged_ports = $this->getMySwitch()->getUntaggedPorts($vlan->getId(),true);
			if(count($untagged_ports)>0){
				foreach($untagged_ports as $untag_port){
					if($untag_port->getId() == $this->getId()){
						$this->vlanUntagged = $vlan;
					}
				}
			}
			$i++;
		}
	}
	
	private function setTaggedVlans(){
		$vlans = $this->getMySwitch()->getVlans();
		foreach($vlans as $vlan){
			$tagged_ports = $this->getMySwitch()->getTaggedPorts($vlan->getId(),true);
			if(count($tagged_ports)>0){
				foreach($tagged_ports as $tagged_port){
					if($tagged_port->getId() == $this->getId()){
						$this->taggedVlans[] = $vlan;
					}
				}
			}
		}
	}
	
	public function isTaggedInVlan($vlan_id){
		$tagged_vlans = $this->getTaggedVlans();
		$result = false;
		$i=0;
		while ($i<count($tagged_vlans) && $result==false){
			if($tagged_vlans[$i]->getId() == $vlan_id){
				$result = true;
			}
			$i++;
		}
		return $result;
		
	}
	
	public function isTaggedInOnlyOneVlan(){
		$tagged_vlans = $this->getTaggedVlans();
		$r = count($tagged_vlans)==1;
		return $r;
	}
	
	public function isTaggedSomewhere(){
		$this->setTaggedVlans();
		return isset($this->taggedVlans);
	}
	
	public function isUntaggedSomewhere(){
		$this->setVlanUntagged();
		return $this->vlanUntagged != null;
	}
	
	public function getId(){
		return  $this->id;
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


}

?>
