<?php
require_once "share.php";

$location = $_POST['location'];
$url = SITE_URL."/SPOT/provisioning/api/adresses";
$json_table_content = apiWrapper($url);
$table_content = json_decode($json_table_content, true);
$table_rows = $table_content['rows'];
foreach ($table_rows as $item) {
	if (stristr($item['location'], $location) !== FALSE){
		echo "<b><span class='name btn'>".$item['location']."</span></b>";
		break;
	}
}
