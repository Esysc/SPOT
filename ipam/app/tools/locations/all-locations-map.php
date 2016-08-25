<h4><?php print _('Locations Map'); ?></h4>
<hr>

<?php if ($admin && $User->settings->enableLocations == "1") { ?>
    <div class="btn-group">
        <?php if ($_GET['page'] == "administration") { ?>
            <a href="" class='btn btn-sm btn-default editLocation' data-action='add' data-id='' style='margin-bottom:10px;'><i class='fa fa-plus'></i> <?php print _('Add location'); ?></a>
        <?php } else { ?>
            <a href="<?php print create_link("administration", "locations") ?>" class='btn btn-sm btn-default' style='margin-bottom:10px;'><i class='fa fa-pencil'></i> <?php print _('Manage'); ?></a>
        <?php } ?>
        <a href="<?php print create_link("tools", "locations") ?>" class='btn btn-sm btn-default' style='margin-bottom:10px;'> <?php print _('Locations list'); ?></a>
    </div>
    <br>
<?php } ?>

<?php
/**
 * Script to print locations
 * ************************* */
# verify that user is logged in
$User->check_user_session();

# check that location support isenabled
if ($User->settings->enableLocations != "1") {
    $Result->show("danger", _("Locations module disabled."), false);
} else {
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
            ?>

            <script type="text/javascript" src="https://maps.google.com/maps/api/js<?php print $key; ?>"></script>
            <script type="text/javascript" src="js/1.2/gmaps.js"></script>
            <script type="text/javascript">

                $(document).ready(function () {
                    // init gmaps
                    var map = new GMaps({
                        el: '#gmap',
                        zoom: 2,
                        lat: 10,
                        lng: 10,
                        zoomControl: true,
                        zoomControlOpt: {
                            style: 'SMALL',
                            position: 'TOP_LEFT'
                        },
                        panControl: false,
                    });
                    var bounds = [];

                    // add markers
            <?php
            foreach ($all_locations as $i => $g) {
                //Get the link and store in var to use later in javascript
                $link = create_link('tools', 'locations', $g->id);
                // Fectch the location object
                $objects = $Tools->fetch_location_objects($g->id);
                // none
                $subnets = "";
                if ($objects != false) {
                    $subnets = "<ul>";
                    // reindex
                    $object_groups = array("racks" => array(), "devices" => array(), "subnets" => array());
                    foreach ($objects as $o) {
                        $object_groups[$o->type][] = $o;
                    }

                    // loop
                    foreach ($object_groups as $t => $ob) {

                        // print objects
                        if (sizeof($ob) > 0) {
                            foreach ($ob as $o) {
                                // link

                                if ($o->type == "subnets") {
                                    $href = create_link("subnets", $o->sectionId, $o->id);
                                    $o->name = $Tools->transform_address($o->name, "dotted") . "/" . $o->mask;
                                    $o->description = strlen($o->description) > 0 ? trim(preg_replace('/\s+/', ' ', htmlspecialchars($o->description, ENT_QUOTES, 'UTF-8'))) : "";
                                    $subnets .= "<li><a href='$href' rel='tooltip' title='$o->description'>$o->name</a></li>";
                                }
                            }
                        }
                    }
                    $subnets .= "</ul>";
                }
                // latlng
                if (strlen($g->lat) > 0 && strlen($g->long) > 0) {
                    ?>
                            map.addMarker({
                                lat: <?php echo $g->lat; ?>,
                                lng: <?php echo $g->long; ?>,
                                title: '<?php echo $g->name; ?>',
                                infoWindow: {
                                    content: "<h5><a href='<?php echo $link; ?>'><?php echo $g->name; ?></a></h5> <p class='text-muted'><?php echo $g->description; ?></p><p class='text-muted'><?php echo $g->address; ?></p><?php echo $subnets; ?>"
                                }
                            });
                    <?php
                }
                // address
                elseif (strlen($g->address) > 1) {
                    ?>
                            GMaps.geocode({
                                address: '<?php echo $g->address; ?>',
                                callback: function (results, status) {
                                    if (status == 'OK') {
                                        var latlng = results[0].geometry.location;
                                        bounds.push(latlng);
                                        map.fitLatLngBounds(bounds);
                                        map.addMarker({
                                            lat: latlng.lat(),
                                            lng: latlng.lng(),
                                            title: '<?php echo $g->name; ?>',
                                            infoWindow: {
                                                content: "<h5><a href='<?php echo $link; ?>'><?php echo $g->name; ?></a></h5> <p class='text-muted'><?php echo $g->description; ?></p><p class='text-muted'><?php echo $g->address; ?></p>"
                                            }
                                        });
                                    }
                                }
                            });
                    <?php
                }
            }
            ?>

                    function resize_map() {
                        var heights = window.innerHeight - 320;
                        $('#map_overlay').css("height", heights + "px");
                    }
                    resize_map();
                    window.onresize = function () {
                        resize_map();
                    };

                   
                    
                   

                })
            </script>

            <div style="width:100%; height:1000px;" id="map_overlay">
                <div id="gmap" style="width:100%; height:100%;"></div>
            </div>


            <?php
            # no coordinates
        } else {
            $Result->show("info", "No Locations with coordinates configured", false);
        }
    }
    ?>
    </script>
    <?php
}
?>
