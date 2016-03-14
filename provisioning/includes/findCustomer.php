<?php
require_once "share.php";
require_once("config.php");
$acr = $_POST['acr'];
$url = SITE_URL."/SPOT/provisioning/api/adresses";
$json_table_content = apiWrapper($url);
$table_content = json_decode($json_table_content, true);
$table_rows = $table_content['rows'];
foreach ($table_rows as $item) {
	if (stristr($item['account'], $acr) !== FALSE){
		echo "<b><span class='name btn'>".$item['account']."</span></b>";
		break;
	}
}
