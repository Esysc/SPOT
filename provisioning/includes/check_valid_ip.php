<?php
header("Content-type: text/xml");
require_once "class.xmlresponse.php";
require_once "share.php";
require_once("config.php");

// validate script inputs
$range_json = apiWrapper(SITE_URL . '/SPOT/provisioning/api/ranges');
$ip1 = $_POST['ip1'];
$ip2 = $_POST['ip2'];
$field1 = $_POST['field1'];
$field2 = $_POST['field2'];
$xml = new xmlResponse();
$xml->start();

//Clean fields 



// $xml->command('setcontent', array('target' => 'modelAlert', 'content' => ""));

if (! filter_var($ip1, FILTER_VALIDATE_IP) && $ip1 !== '')
{
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
	$xml->command('setstyle', array('target'=> "{$field1}", 'property' => 'borderColor', 'value'  => "red"));

	$xml->command('setdefault', array('target' => "{$field1}"));
	$xml->command('focus', array('target' => "{$field1}"));
	$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, the value " . $ip1 . ", is not a valid IP !"));

}
elseif (! filter_var($ip2, FILTER_VALIDATE_IP) && $ip2 !== '') {
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
	$xml->command('setstyle', array('target'=> "{$field2}", 'property' => 'borderColor', 'value'  => "red"));

	$xml->command('setdefault', array('target' => "{$field2}"));
	$xml->command('focus', array('target' => "{$field2}"));
	$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, the value  ". $ip2 .", is not a valid IP !"));
}
elseif  ($ip1 !== '' && $ip2 !== '')
{
	if( ! subchecker($ip1, $range_json)) {
		$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, this range, $ip1-$ip2, overlaps within a previous one ..."));
		$xml->command('setdefault', array('target' => "{$field1}"));
		$xml->command('focus', array('target' => "{$field1}"));
		$xml->command('setstyle', array('target'=> "{$field1}", 'property' => 'borderColor', 'value'  => "red"));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
	}

	if( ! subchecker($ip2, $range_json)) {
		$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, this range, $ip1-$ip2, overlaps within a previous one ..."));
		$xml->command('setdefault', array('target' => "{$field2}"));
		$xml->command('focus', array('target' => "{$field2}"));
		$xml->command('setstyle', array('target'=> "{$field2}", 'property' => 'borderColor', 'value'  => "red"));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
	}


	$ip2long1= sprintf('%u',ip2long($ip1));
	$ip2long2= sprintf('%u',ip2long($ip2));
	$diff = $ip2long1 - $ip2long2;
	if (  $diff >=  0) {
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
		$xml->command('setstyle', array('target'=> "{$field2}", 'property' => 'borderColor', 'value'  => "red"));
		$xml->command('setstyle', array('target'=> "{$field1}", 'property' => 'borderColor', 'value'  => "red"));
		$xml->command('setdefault', array('target' => "{$field1}"));
		$xml->command('setdefault', array('target' => "{$field2}"));
		$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "The start value  $ip1 cannot be greater or equal to end value $ip2."));
	}

}
$xml->end();
