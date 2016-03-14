<?php
require_once "share.php";
require_once("config.php");
$data = $_POST['data'];
$term = $_POST['term'];
$url = $_POST['url'];
$json_table_content = apiWrapper('http://'.$url);
$table_content = json_decode($json_table_content, true);
$table_rows = $table_content['rows'];
foreach ($table_rows as $item) {
	if (stristr($item[$data], $term) !== FALSE){
		echo "<b><span class='name btn'>".$item[$data]."</span></b>";
		break;
	}
}
