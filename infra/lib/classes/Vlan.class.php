<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.*/

class Vlan {
	
	private $id = -1;
	private $name = "";
	private $switch_id = -1;
	
	public function __construct($switch_id=-1,$id = -1,$name =""){
		$this->id = $id;
		$this->name = $name;
		$this->switch_id = $switch_id;
	}
	
	public function __toString(){
            if (  ALLOW_PORT_TAGGING) {
		return($this->id.
					"<span class=\"vlan-link\">
						<a href=\"edit_vlan_form.php?vlan_id=".$this->id."&amp;switch_id=".$this->switch_id."&amp;vlan_name=".$this->name."\">"
							.$this->name.
						"</a>
					</span>");
            } else {
                return($this->id.
					"<span class=\"vlan-link\"><a title='Edit vlan not allowed'>"
						
							.$this->name.
						
					"</a></span>");
            }
	}	
	
	public function toString_small(){
		return("<b>".$this->id."</b><a href=\"edit_vlan_form.php?vlan_id=".$this->id."&amp;switch_id=".$this->switch_id."&amp;vlan_name=".$this->name."\">".$this->name."</a>");
	}	
	
	public function getId(){
		return $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getMySwitch(){
		$mySwitch = MySwitch::retrieveById($this->switch_id);
		return $mySwitch;
	}
	
	public function setName($name){
		if(strlen($name)>12){
			die(MSG_VLAN_NAME_TOO_LONG);
		}
		if(is_numeric($name)){
			die(MSG_VLAN_BAD_NAME);
		}
		$mySwitch = $this->getMySwitch();
		if(Fonctions::simpleSnmpSet($mySwitch,OID_dot1qVlanStaticName.'.'.$this->id,"s",$name)){
			$this->name = $name;
		} else {
			return false;
		}
	}
	
}

?>
