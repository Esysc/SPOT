<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */


class Label
{
	private $name = "undefined";
	private $value = "undefined";
	private $path = "undefined";
	private $view_id = -1;

	public function __construct($name="undefined",$value="undefined",$view_id=-1,$path="undefined"){
		$this->name = $name;
		$this->value = $value;
		$this->path = $path;
		$this->view_id = $view_id;
	}
	
	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getView_id() {
		return $this->view_id;
	}
}

class Message
{
	private $name = "undefined";
	private $value = "undefined";

	public function __construct($name="undefined",$value="undefined"){
		$this->name = $name;
		$this->value = $value;
	}
	
	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}
}
	
class MyLangXMLParser
{
	private $labels = array();
	private $messages = array();

	public function __construct(){
		
		$this->labels = array();
		$this->messages = array();
		$xml = simplexml_load_file("params/labels/labels_".LANGUAGE.".xml");
		
		foreach($xml->view as $view){
			foreach($view->labels->label as $label){
				$this->labels[] = new Label($label->name,$label->value,$view->id,$view->path);
			}
		}
		
		foreach($xml->messages->message as $message){
			
			$this->messages[] = new Message((string) $message->name, (string) $message->value);
		}
	}

	function getLabels(){
		return $this->labels;
	}
	
	function getMessages(){
		return $this->messages;
	}
}

?>
