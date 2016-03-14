<?php

session_start();






if (isset($_REQUEST['CSV'])) {

    $data = $_REQUEST['CSV'];
    
    //Simple load the array in session
    $newclients = json_decode($data,true);
    $_SESSION['CSV'] = $newclients;
    $array = array();
    $index = 1;
   /* foreach($newclients as $subarr) {
        
        $array[$index]['clientid'] = $index;
        $array[$index]['rackname'] = $subarr[0];
        $rack = intval(preg_replace('/[^0-9]+/', '', $subarr[0]), 10);
        $shelf = substr($subarr[0], -1);
        $array[$index]['rack'] = $rack;
        $array[$index]['shelf'] = $shelf;
        $index++;
      //  $_SESSION['CSV']['newclients'][$index] = $array[$index]; 
    }
   $_SESSION['CSV'] = $array;*/
    
    
    
}
var_dump($_SESSION);




