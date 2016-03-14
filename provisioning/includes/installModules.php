<?php

/* 
 * Accept a release name (mandatory and return a list of modules
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("config.php");
require_once("share.php");

function modules_find($release) {
    $dir = URL_PACKAGER.'/delivery/Customers';
        if( isset($release) ) {
           $a = curlGet($dir);
         echo $a;       
        }

}
modules_find("test");
