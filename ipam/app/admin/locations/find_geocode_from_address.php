<?php

/**
 * Script to get Geocode from address
 * ************************* */


if (!isset($_GET['address'])) {
   return false;
} else {

$address = $_GET['address'];
# fetch all locations
# if none than print
    // Prepare string address for google api request

    $address = str_replace(" ", "+", $address);
    $address = str_replace(",", "+", $address);
    $address = str_replace("++", "+", $address);

    $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address);
    echo $geocode;
/*
    $output = json_decode($geocode);
    
    if ($output->status === "OK") {
        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;
        $long_name = $output->results[0]->formatted_address;
//ENCODE THE RESULTS
        $results = array('results' => $output, 'lat' => $lat, 'long' => $long, "long_name" => $long_name);
        echo json_encode($results);
    } else {
        return false;
    }
 * 
 * Temporarely disabled to countour the corporate restricion
 */
}
        

       
