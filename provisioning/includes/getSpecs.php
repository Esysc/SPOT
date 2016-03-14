<?php

/**
 * Php scripèt to get specifications name from sysprod db
 */
require "share.php";
require "config.php";
$url = URL_SYSPRODDB . '/GetSpecifications';

    $results = curlPost($url, false);
  
echo $results;
?>
