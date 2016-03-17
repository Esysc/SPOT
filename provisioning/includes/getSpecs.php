<?php

/**
 * Php script to get specifications name from sysprod db
 */
require "share.php";
require "config.php";
$url = URL_SYSPRODDB . '/GetSpecifications';

    $results = curlGet($url, false);
  
echo $results;
?>
