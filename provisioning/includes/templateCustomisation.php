<?php

/*
 * Script to take templates and create the custom network configuration
 */

require_once "share.php";
require_once("config.php");
include 'vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$postData = json_decode($_POST['data'], true);

$fileid = $postData['fileid'];
//$fileid = "fgt_200D_2.0";
$salesorder = $postData['salesorder'];
$hostname = $postData['hostname'];
$salesorder = $salesorder ." ".$hostname;
$configname = $postData['configname'];
$targetid = $postData['configtarget'];
$args = $postData['args'];
//var_dump($args);
$url = SITE_URL . "/SPOT/provisioning/api/configtemplate/$fileid";

$json_table_content = apiWrapper($url);
$filecontentobj = json_decode($json_table_content);
$filecontent = $filecontentobj->configTemplate;

$temp = tempnam(sys_get_temp_dir(), 'template');
$filename = basename($temp);
$myfile = fopen($temp, "w") or die("Unable to open file!");




$write = fwrite($myfile, $filecontent);
fclose($myfile);
removeLines($temp, '#VAR');



try {
    // specify where to look for templates
    $loader = new Twig_Loader_Filesystem('/tmp');

    // initialize Twig environment
    $twig = new Twig_Environment($loader);

    // load template
    $template = $twig->loadTemplate($filename);

    // set template variables
    // render template
    $results = $template->render($args);
    // var_dump($args);
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}
$url = SITE_URL . '/SPOT/provisioning/api/customconfig';
$sendData = array(
    'configId' => null,
    'salesorder' => $salesorder,
    'configTarget' => $targetid,
    'configContent' => $results,
    'timeStamp' => ''
);
apiPOST($url, json_encode($sendData));
echo 'Success!';
//$write = fwrite($myfile, $result);
//var_dump($args);
unlink($temp);

