<?php
header("Content-type: text/xml");
require_once("config.php");
require_once "class.xmlresponse.php";
require_once "share.php";
if ( isset($_POST['netmask'])) {
	$value = $_POST['netmask'];
}
else
{
	$value = "24";
}
$subnet = $_POST['subnet'];
$field = $_POST['field'];
$table = "custip";
if ($subnet === '') die;
require_once "Net/IPv4.php";
#         require_once "../contenu/functions/remote.php";
$ip_calc = new Net_IPv4();
$ip_calc->ip = "$subnet";
$ip_calc->bitmask = $value;
$error = $ip_calc->calculate();
$broadcast = $ip_calc->broadcast;
$network = $ip_calc->network;
$broadcast_array = explode('.', $broadcast);
$network_array = explode('.', $network);

$range_json = apiWrapper( SITE_URL .'/SPOT/provisioning/api/ranges');
$table_json = apiWrapper(SITE_URL .'/SPOT/provisioning/api/adresses', 'admin', '***REMOVED***');
$xml = new xmlResponse();
$xml->start();

if (! filter_var($subnet, FILTER_VALIDATE_IP)) {
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
	$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
	$xml->command('setstyle', array('target'=> "{$field}", 'property' => 'borderColor', 'value'  => "red"));

	$xml->command('setdefault', array('target' => "{$field}"));
	$xml->command('focus', array('target' => "{$field}"));
	$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, the value " . $subnet . ", is not a valid IP !"));

}
else
{
	if(subchecker($subnet, $range_json)) {
		$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, the subnet " . $subnet . ", is not in the valid ranges: ( $sub_string )"));
		$xml->command('setdefault', array('target' => "{$field}"));
		$xml->command('focus', array('target' => "{$field}"));
		$xml->command('setstyle', array('target'=> "{$field}", 'property' => 'borderColor', 'value'  => "red"));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
		$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));

	}
	else
	{

		for ($i = $network_array[2]; $i <= $broadcast_array[2]; $i++ ) {
			//  $xml = new xmlResponse();
			//$xml->start();
			// generate commands in XML format
			$subnet_tmp = $network_array[0].'.'.$network_array[1].'.'.$i.'.'.$network_array[3];
			if(isDuplicate($table_json, $subnet_tmp)) {
				$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "Sorry, the $field '" . stripslashes($value) . "' for subnet " . $subnet . ", overlaps with " . $subnet_tmp . " !"));
				$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
				$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'red'));
				$xml->command('setstyle', array('target'=> "{$field}", 'property' => 'borderColor', 'value'  => "red"));
				$xml->command('setdefault', array('target' => "{$field}"));
				$xml->command('focus', array('target' => "{$field}"));
				break;
			}
			else
			{
				$xml->command('setcontent', array('target' => 'modelAlert', 'content' => "$subnet/$value cheked: OK"));
				$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'font-weight', 'value' => 'bold'));
				$xml->command('setstyle', array('target'=> 'modelAlert', 'property' => 'color', 'value' => 'green'));

			}
		}
	}
}
$xml->end();
