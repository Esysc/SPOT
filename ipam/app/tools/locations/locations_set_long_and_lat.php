

<?php

/**
 * Script to set Geocode from addresses
 * ************************* */
/* functions */
require( dirname(__FILE__) . '/../../../functions/functions.php');

# initialize user object
$Database = new Database_PDO;
$User = new User($Database);
$Admin = new Admin($Database);
$Tools = new Tools($Database);
$Result = new Result ();

# fetch all locations
$all_locations = $Tools->fetch_all_objects("locations", "id");
# if none than print
if ($all_locations === false) {
    $Result->show("info", "No Locations configured", false);
} else {

    # sensor check
    if (isset($gmaps_api_key)) {
        $key = strlen($gmaps_api_key) > 0 ? "?key=" . $gmaps_api_key : "";
    }

    // get all
    foreach ($all_locations as $k => $l) {
        // map used
        if (strlen($l->long) == 0 && strlen($l->lat) == 0 && strlen($l->address) == 0) {
            // map not used
            unset($all_locations[$k]);
        }
    }

    // calculate
    if (sizeof($all_locations) > 0) {

        foreach ($all_locations as $i => $g) {

            // address
            if (strlen($g->name) > 0 && strlen($g->address) == 0 ) {

                $address = $g->name;
                $address = str_replace(" ", "+", $address);
                $address = str_replace(",", "+", $address);
                $address = str_replace("++", "+", $address);

                $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');

                $output = json_decode($geocode);
                if ($output->status === "OK") {
                    $lat = $output->results[0]->geometry->location->lat;
                    $long = $output->results[0]->geometry->location->lng;
                    $newaddress = $output->results[0]->formatted_address;
                    echo "new address is $newaddress";
                    if (strlen($_POST['lat']) > 0) {
                        if (!preg_match('/^(\-?\d+(\.\d+)?).\s*(\-?\d+(\.\d+)?)$/', $lat)) {
                            $Result->show("danger", _("Invalid Latitude"), true);
                        }
                    }
                    // long
                    if (strlen($_POST['long']) > 0) {
                        if (!preg_match('/^(\-?\d+(\.\d+)?).\s*(\-?\d+(\.\d+)?)$/', $long)) {
                            $Result->show("danger", _("Invalid Longitude"), true);
                        }
                    }

                    // set values
                    $values = array(
                        "id" => $g->id,
                        "name" => $g->name,
                        "address" => $newaddress,
                        "lat" => $lat,
                        "long" => $long,
                        "description" => $g->description
                    );
                    # execute update
                    if (!$Admin->object_modify("locations", "edit", "id", $values)) {
                        $Result->show("danger", _("Location edit failed"), false);
                    } else {
                        $Result->show("success", _("Location update successful"), false);
                    }
                } else {
                    $Result->show("Status not ok: $output->status", false);
                }
            }
        }

        # no coordinates
    } else {
        $Result->show("info", "No Locations with coordinates configured", false);
    }
}
