<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class Group {

	private $id = "-1";
	private $name = "";
	private $color = "#fff";
	private $description = "";
	private $mySwitches = array();
	
	private static $groups = array();
	
	
	public function __construct($id = "-1", $name = "-1",$color="#fff",$description=""){
		
		$this->id = (string) $id;
		$this->name = $name;
		$this->color = $color;
		$this->description = $description;
	}
	
	static function retrieveById($id_group){
		
		if(!isset(self::$groups[$id_group])){
		
			$p = new MyXMLParser();
			$myGroups = $p->getMyGroups();
			foreach($myGroups as $g){
				if($g->getId() == $id_group){
					return $g;
				}
			}
		} else {
			return(self::$groups[$id_group]);
		}
		return false;
	}
	
	public function getMySwitches() {
		$results=array();
		if(count($this->mySwitches) ==0 or !isset($this->mySwitches)){
			$p = new MyXMLParser();
			$switches = $p->getMySwitchs();
			foreach ($switches as $s){
				$gid = $s->getGroupId();
				if($gid == $this->id){
					$results[] = $s;
				}
			}
			$this->mySwitches = $results;
			self::$groups[$this->id] = $this;
		} 
		return $this->mySwitches;
	}


	public function getId(){
		return  $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getColor(){
		return $this->color;
	}
	
	public function getDescription(){
		return $this->description;
	}
}

?>
