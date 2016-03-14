<?php

/*
 * Script to parse networking template files
 * the template should have in the header the declaration of variables used
 * ex:  #VAR    HOSTNAME
 * The results are sent back to ajax request to build form based on this variables
 */

require_once "share.php";
require_once("config.php");
$fileid = urldecode($_POST['fileid']);
//$fileid = "fgt_200D_2.0";

$url = SITE_URL . "/SPOT/provisioning/api/configtemplate/$fileid";

$json_table_content = apiWrapper($url);
//echo $json_table_content;
$filecontentobj = json_decode($json_table_content);
$filecontent = $filecontentobj->configTemplate;
//$temp = tmpfile();
$temp = tempnam(sys_get_temp_dir(), 'template');
$myfile = fopen($temp, "w") or die("Unable to open file!");
$write = fwrite($myfile, $filecontent);
fclose($myfile);
$myfile = fopen($temp, "r") or die("Unable to open file!");


$pattern = '/VAR/';
$jsonArr = array();
while (!feof($myfile)) {
    $line = fgets($myfile);


    if (preg_match($pattern, $line)) {
        $line_split = explode('#VAR', $line);
        $varname = trim(preg_replace('/\s+/', ' ', $line_split[1]));
        $varvalue = trim(preg_replace('/\s+/', ' ', $line_split[2]));
        $varhelp = trim(preg_replace('/\s+/', ' ', $line_split[3]));
        $varclass = trim(preg_replace('/\s+/', ' ', $line_split[4]));
        $jsonArr[$varname] = array('value' => $varvalue, 'help' => $varhelp, 'class' => $varclass);
    }
}
fclose($myfile);
unlink($temp);

$json = json_encode($jsonArr);
echo $json;

//echo $filecontent;

