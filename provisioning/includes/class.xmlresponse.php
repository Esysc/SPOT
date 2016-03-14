<?php
class xmlResponse
{
private $xlmDoc;
private $root;
private $command;
public	 	function start() {
		$this->xmlDoc = new DOMDocument('1.0', 'utf-8');
//		header("Content-Type: text/plain");
		$this->root = $this->xmlDoc->appendChild(
				$this->xmlDoc->createElement('reponse'));
	}
	public				function command($method, $params=array(), $encoded=array())
	{
		$this->command = $this->root->appendChild(
				$this->xmlDoc->createElement('command'));
		$this->command->appendChild(
				$this->xmlDoc->createAttribute('method'))->appendChild(
				$this->xmlDoc->createTextNode($method));
		if($params) {
			foreach($params as $key => $val) {
				$this->param = $this->command->appendChild(
						$this->xmlDoc->createElement($key, $val));
			}
		}
	}
	public					function end()
	{
		//make the output pretty
//		$this->xmlDoc->formatOutput = true;
echo		$this->xmlDoc->saveXML();
		exit;
	}
}
