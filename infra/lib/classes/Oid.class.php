<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class OID {

	private $id = "-1";
	private $name = ""; 
	private $value = "-1";
	private $description = "";
	
	public function __construct($id = "-1", $name = "",$value = "-1",$description=""){
		$this->id = (string) $id;
		$this->name = $name;
		$this->value = $value;
		$this->description = $description;
	}
	
	public function __toString(){
		return $this->name." (".$this->value." : ".$this->description.")";
	}
	
	/**
	* Getters
	*/
	public function getId(){
		return $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getValue(){
		return $this->value;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	/**
	* Setters
	*/
	public function setId($id = -1){
		$this->id = $id;
	}
	
	public function setName($name = ""){
		$this->name = $name;
	}
	
	public function setValue($value = ""){
		$this->value = $value;
	}
	
	public function setDescription($description = ""){
		$this->description = $description;
	}	
	
	static function retrieveByValue($value){
		$p = new MyXmlParser();
		$oids = $p->getOids();
		foreach($oids as $oid){
			if($oid->getValue() == $value){
				return $oid;
			}
		}
		return false;
	}	
}

?>