<?php
$this->assign('title', 'SPOT | Provisioning Wizard 1 of 2');
$this->assign('nav', 'provisioninggeneral');

$this->display('_Header.tpl.php');
//var_dump($_SESSION);
$letters = range('A', 'G');
unset($_SESSION['CSV']);
unset($_SESSION['csvclients']);
for ($i = 1; $i <= 24; $i++) {
    $rack = "rack$i";
    foreach ($letters as $str) {
        $shelf = "shelf$str";
        $arr[$rack . '_' . $shelf] = $rack . '_' . $shelf;
        $display[$rack . '_' . $shelf] = "Rack $i Shelf $str";
        $selected = "";
    }
}
$tz = (isset($_SESSION['tz']) ? $_SESSION['tz'] : '');
$posix = (isset($_SESSION['posix']) ? $_SESSION['posix'] : '');
$olson = (isset($_SESSION['olson']) ? $_SESSION['olson'] : '');
$windowstz = (isset($_SESSION['windowstz']) ? $_SESSION['windowstz'] : '');
$racks = $arr;
$startshelf = (isset($_SESSION['startshelf']) ? $_SESSION['startshelf'] : '');
$stopshelf = (isset($_SESSION['stopshelf']) ? $_SESSION['stopshelf'] : '');
?>

<script>

    /*
     * 
     * @jquery logic
     * manage the updates
     * updates php session
     * updates DB
     * read DB
     * validate Form
     * 
     * 
     * 
     * 
     */
    $(document).ready(function () {
        $('*[required="required"]').before("<span class='icon-star' style='color:red'></span>");

        var tz = $('.tz');
        tz.chosen({allow_single_deselect: true});
        tz.chosen().change(function () {
            var timestr = tz.val();
            var timeobj = timestr.split('<>');
            var windowstz = timeobj[0];
            var posix = timeobj[1];
            var olson = timeobj[2];
            $('#olson').val(olson);

            $('#posix').val(posix);

            $('#posixmsg').html('<a class="btn btn-primary" href="http://www.timeanddate.com/worldclock/results.html?query=' + olson + '" target="_blank">Check on <span class="badge">timeanddate.com</span> to validate.</a>');
            $('#windowstz').val(windowstz);

            if ($('#posix').val() == '') {
                $('#posix').prop('readonly', true);
            }
            else
            {

                $('#posix').prop('readonly', false);
            }

        });
        $('#stopshelf').chosen({display_disabled_options: false});
        $('#stopshelf').chosen().change(function () {
            $('#stopshelf').trigger('change');
        });

        var shelf = $('.shelf');
        shelf.chosen({allow_single_deselect: true});
        $('#startshelf').chosen().change(function () {
            var start = $(this).val();
            $('#stopshelf').val(start);
        });
        shelf.chosen().change(function (e, params) {
            if ($('#startshelf').val() == '') {
                $('#stopshelf').prop('readonly', true);
            }
            else
            {


                $('#stopshelf').prop('readonly', false);
            }
            var selected = [];


            // add all selected options to the array in the first loop

            shelf.find("option").each(function () {
                if (this.selected) {

                    //   selected[this.value] = this;
                    return false;
                }
                selected[this.value] = this;




            })

                    // then either disabled or enable them in the second loop:
                    .each(function () {




                        // if the current option is already selected in another select disable it.
                        // otherwise, enable it.
                        this.disabled = selected[this.value] && selected[this.value] !== this;
                    });
            // trigger the change in the "chosen" selects
            shelf.trigger("chosen:updated");


        });
        $('#phase1').click(function (event) {
            var valid = true;
            $('#failed').hide();
            $('.control-group').removeClass('error');
            $('.help-inline').html('');
            $('*[required="required"]').each(function () {
                if ($(this).val() === '') {

                    $('#failed').html('Please, fill all the required fields! ');
                    $('#failed').show();


                    valid = false;
                }
            });
            if (valid == false)
                return valid;

            $.get("/SPOT/provisioning/api/tblprogresses?filter=<?php echo $_SESSION['salesorder']; ?>", function (data, status) {

                var Jdata = JSON.parse(data.rows[0].data);
                var id = data.rows[0].id;
                //Add new elements
                var olson = $('#olson').val();
                var posix = $('#posix').val();
                var windowstz = $('#windowstz').val();
                var tz = $('#tz').children(':selected').text();
                
                var startshelf = $('#startshelf').val();
                var stopshelf = $('#stopshelf').val();
                Jdata.olson = olson;
                Jdata.tz = tz;
                Jdata.windowstz = windowstz;
                Jdata.posix = posix;
                Jdata.newclients = [];

                var JSONdata = JSON.stringify(Jdata);
                var toSend = {data: JSONdata};
                var stringSend = JSON.stringify(toSend);
                $.ajax({
                    url: "/SPOT/provisioning/api/tblprogress/" + id,
                    type: "PUT",
                    data: stringSend,
                    wait: true,
                    success: function () {


                        var strRack = '';
                        var strShelf = '';
                        var strRackShelf = '';
                        var putDB = false;
                        var strCreate = '';
                        var key = '';
                        var count = 0;
                        var alphabet = "ABCDEFG".split("");


                        for (var i = 1, limit = 25; i < limit; i++) {
                            strRack = 'rack' + i + '_';

                            _.each(alphabet, function (letter) {
                                strShelf = 'shelf' + letter;
                                strRackShelf = strRack + strShelf;
                                if (strRackShelf === startshelf) {
                                    putDB = true;
                                }
                                if (putDB == true) {

                                    count += 1;
                                    var temp = {};
                                    temp[count] = {clientid: count, rackname: strRackShelf, rack: i, shelf: letter};
                                    //Start check racks command to check the clients (clean before)
                                    var content = {reponse: "99"};
                                    var Jcontent = JSON.stringify(content);
                                    $.ajax({
                                        url: "/SPOT/provisioning/api/sysprodracks/" + strRackShelf,
                                        type: "PUT",
                                        data: Jcontent,
                                        wait: true
                                    });
                                    var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>';
                                    var scriptid = "0"; //The id for checkRacks script

                                    var salesorder = <?php echo $_SESSION['salesorder']; ?>;

                                    var args = '{"0": "-pos", "1" : "' + strRackShelf + '", "2" : "&"}';
                                    //arguments = JSON.stringify(args);
                                    //alert(arguments);
                                    var exesequence = 1;
                                    var executionFlag = 0;

                                    var command = {
                                        salesorder: salesorder,
                                        rack: i,
                                        shelf: letter,
                                        clientaddress: clientaddress,
                                        arguments: args,
                                        exesequence: exesequence,
                                        executionflag: executionFlag,
                                        scriptid: scriptid
                                    }
                                    var Jcommand = JSON.stringify(command);
                                    //Write the check command that return rack situation
                                    $.ajax({
                                        url: "/SPOT/provisioning/api/remotecommands",
                                        type: "POST",
                                        data: Jcommand,
                                        wait: true
                                    });

                                    // Jdata.clients[count] = {clientid: count, rackname: strRackShelf, rack: i, shelf: letter};

                                    Jdata.newclients.push(temp);

                                    /* strCreate = {rack: i,
                                     shelf: letter,
                                     salesorder: <?php echo $_SESSION['salesorder']; ?>
                                     
                                     };
                                     var Jstr = JSON.stringify(strCreate);
                                     $.ajax({
                                     url: "api/provisioningaction",
                                     type: "PUT",
                                     data: Jstr
                                     }); */

                                    if (strRackShelf === stopshelf) {


                                        putDB = false;
                                    }
                                }

                            });
                        }

                        var Jstr = JSON.stringify(Jdata);
                        var Jstrdata = {data: Jstr};
                        var Jstrsend = JSON.stringify(Jstrdata);

                        console.log(Jstrsend);
                        $.ajax({
                            url: "/SPOT/provisioning/api/tblprogress/" + id,
                            type: "PUT",
                            data: Jstrsend,
                            wait: true,
                            success: function () {

                                var session = 'data=' + Jstr;
                                // reload session within new values
                                $.ajax({
                                    url: "/SPOT/provisioning/includes/loadSession.php",
                                    type: "POST",
                                    data: session,
                                    wait: true,
                                    success: function () {
                                        // All OK , pass to phase 2
                                      //
                                       window.location.href = "/SPOT/provisioning/provisioning2";

                                    }
                                });
                            }
                        });





                    }



                });

            });
            // don't post !
            event.preventDefault();

        });
        $('#netprov').on('click', function () {
            var toSend = {provisioning: 'network'};
            var JSONdata = JSON.stringify(toSend);
            $.ajax({
                url: "includes/loadSession.php",
                type: "POST",
                data: {salesorder: '<?php echo $_SESSION['salesorder']; ?>',
                    data: JSONdata}

            });
            window.location.href = "/SPOT/provisioning/customconfigsbuilder";
        });
    });

</script>  

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Provisioning Wizard - 1 of 2


    </h1>

    <?php
    //var_dump($_SESSION['provisioning']);
    if (!isset($_SESSION['salesorder'])) {
        echo "<p class='alert alert-danger'>You need to load an order first</p>";
        echo "<p class='text-primary'>Got to 'Pending orders' section:  <a href='./pendings'>New orders</a> or <a href='./tblprogresses'>Stored orders</a> and select the one you wish to start or complete.</p>";
    } else {
        ?>
        <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
        <div id="message"></div>

        <form id="general">


            <div class="form-group">

                <div id="accordion">
                    <div>
                        <h3 class="ui-widget-header">System Provisioning - <small class="icon-star" style="color:red"> mark a field as required</small></h3>

                        <div id="posixmsg" class="pull-right"></div>
                        <fieldset>
                            <div id="tz-group" title="Timezone Confirmation">

                                <table  class="collection table table-bordered table-hover">
                                    <tr>
                                        <th>
                                            <span class="icon-time"></span> TimeZone Confirmation
                                        </th>
                                        <th>

                                            <label for="windowstz" >Windows</label>
                                        </th><th>
                                            <label for="posix" >Posix</label> 

                                        </th>
                                        <th>
                                            <label for="olson" >Olson</label>
                                        </th>

                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="tz" id="tz" class="chosen tz " data-placeholder="Choose a time zone"  required="required">
                                                <option value="<?php echo $tz; ?>"><?php echo $tz; ?></option>
                                                <option value="Dateline Standard Time<>GMT12<>Etc/GMT+12">(UTC-12:00) International Date Line West</option>
                                                <option value="UTC-11<>GMT11<>Etc/GMT+11">(UTC-11:00) Coordinated Universal Time-11</option>
                                                <option value="Hawaiian Standard Time<>HST10<>Pacific/Honolulu">(UTC-10:00) Hawaii</option>
                                                <option value="Alaskan Standard Time<>AKST9AKDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Anchorage">(UTC-09:00) Alaska</option>
                                                <option value="Pacific Standard Time<>PST8PDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Los_Angeles">(UTC-08:00) Pacific Time (US & Canada)</option>
                                                <option value="US Mountain Standard Time<>MST7<>America/Phoenix">(UTC-07:00) Arizona</option>
                                                <option value="Mexico Standard Time 2<>MST7MDT,M4.1.0/2:00:00,M10.5.0/2:00:00<>America/Chihuahua">(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>
                                                <option value="Mountain Standard Time<>MST7MDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Denver">(UTC-07:00) Mountain Time (US & Canada)</option>
                                                <option value="Central Standard Time<>CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Chicago">(UTC-06:00) Central Time (US & Canada)</option>
                                                <option value="Central America Standard Time<>CST6<>America/Guatemala">(UTC-06:00) Central America</option>
                                                <option value="Central Standard Time (Mexico)<>CST6CDT,M4.1.0/2:00:00,M10.5.0/2:00:00<>America/Mexico_City">(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>
                                                <option value="Canada Central Standard Time<>CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Winnipeg">(UTC-06:00) Saskatchewan</option>
                                                <option value="SA Pacific Standard Time<>COT5<>America/Bogota">(UTC-05:00) Bogota, Lima, Quito</option>
                                                <option value="Eastern Standard Time<>EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/New_York">(UTC-05:00) Eastern Time (US & Canada)</option>
                                                <option value="US Eastern Standard Time<>EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00<>America/Indiana/Indianapolis">(UTC-05:00) Indiana (East)</option>
                                                <option value="Venezuela Standard Time<>VET4:30<>America/Caracas">(UTC-04:30) Caracas</option>
                                                <option value="Paraguay Standard Time<>PYT4PYST,M10.1.0/0:00:00,M3.4.0/0:00:00<>America/Asuncion">(UTC-04:00) Asuncion</option>
                                                <option value="Atlantic Standard Time<>AST4ADT,M3.2.0,M/2:00:00,11.1.0/2:00:00<>America/Halifax">(UTC-04:00) Atlantic Time (Canada)</option>
                                                <option value="Central Brazilian Standard Time<>AMT4AMST,M10.3.0/0:00:00,M2.3.0/0:00:00<>America/Cuiaba">(UTC-04:00) Cuiaba</option>
                                                <option value="SA Western Standard Time<>BOT4<>America/La_Paz">(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>
                                                <option value="Pacific SA Standard Time<>CLT4CLST,M9.2.0/0:00:00,M4.4.0/0:00:00<>America/Santiago">(UTC-04:00) Santiago</option>
                                                <option value="Newfoundland Standard Time<>NST3:30NDT,M3.2.0/2:00:00:01,M11.1.0/2:00:00:01<>America/St_Johns">(UTC-03:30) Newfoundland</option>
                                                <option value="E. South America Standard Time<>BRT3BRST,M10.3.0/0:00:00,M2.3.0/0:00:00<>America/Sao_Paulo">(UTC-03:00) Brasilia</option>
                                                <option value="Argentina Standard Time<>ART3<>America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires</option>
                                                <option value="SA Eastern Standard Time<>GFT3<>America/Cayenne">(UTC-03:00) Cayenne, Fortaleza</option>
                                                <option value="Greenland Standard Time<>WGT3WGST,M3.5.6/22:00:00,M10.4.6/23:00:00<>America/Godthab">(UTC-03:00) Greenland</option>
                                                <option value="Montevideo Standard Time<>UYT3UYST,M10.1.0/2:00:00,M3.2.0/2:00:00<>America/Montevideo">(UTC-03:00) Montevideo</option>
                                                <option value="Bahia Standard Time<>BRT3<>America/Bahia">(UTC-03:00) Salvador</option>
                                                <option value="UTC-02<>GMT2<>Etc/GMT+2">(UTC-02:00) Mid-Atlantic</option>
                                                <option value="Azores Standard Time<>AZOT1AZOST,M3.5.0/0:00:00,M10.5.0/1:00:00<>Atlantic/Azores">(UTC-01:00) Azores</option>
                                                <option value="Cape Verde Standard Time<>CVT1<>Atlantic/Cape_Verde">(UTC-01:00) Cape Verde Is.</option>
                                                <option value="Morocco Standard Time<>WET0<>Africa/Casablanca">(UTC) Casablanca</option>
                                                <option value="UTC<>GMT0<>Etc/GMT">(UTC) Coordinated Universal Time</option>
                                                <option value="GMT Standard Time<>GMT0BST,M3.5.0/1:00:00,M10.5.0/2:00:00<>Europe/London">(UTC) Dublin, Edinburgh, Lisbon, London</option>
                                                <option value="Greenwich Standard Time<>GMT0<>Atlantic/Reykjavik">(UTC) Monrovia, Reykjavik</option>
                                                <option value="W. Europe Standard Time<>CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00<>Europe/Berlin">(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                                <option value="Central Europe Standard Time<>CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00<>Europe/Budapest">(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                                <option value="Romance Standard Time<>CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00<>Europe/Paris">(UTC+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                                <option value="Central European Standard Time<>CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00<>Europe/Warsaw">(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                                <option value="W. Central Africa Standard Time<>WAT-1<>Africa/Lagos">(UTC+01:00) West Central Africa</option>
                                                <option value="Namibia Standard Time<>WAT-1WAST,M9.1.0/2:00:00,M4.1.0/2:00:00<>Africa/Windhoek">(UTC+01:00) Windhoek</option>
                                                <option value="GTB Standard Time<>EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00<>Europe/Bucharest">(UTC+02:00) Athens, Bucharest</option>
                                                <option value="Middle East Standard Time<>EET-2EEST,M3.5.0/0:00:00,M10.5.0/0:00:00<>Asia/Beirut">(UTC+02:00) Beirut</option>
                                                <option value="Egypt Standard Time<>EET-2<>Africa/Cairo">(UTC+02:00) Cairo</option>
                                                <option value="Syria Standard Time<>EET-2EEST,M4.1.5/0:00:00,M10.5.5/0:00:00<>Asia/Damascus">(UTC+02:00) Damascus</option>
                                                <option value="E. Europe Standard Time<>EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00<>Asia/Nicosia">(UTC+02:00) E. Europe</option>
                                                <option value="South Africa Standard Time<>SAST-2<>Africa/Johannesburg">(UTC+02:00) Harare, Pretoria</option>
                                                <option value="FLE Standard Time<>EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00<>Europe/Kiev">(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                                <option value="Turkey Standard Time<>EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00<>Europe/Istanbul">(UTC+02:00) Istanbul</option>
                                                <option value="Israel Standard Time<>IST-2IDT,M3.5.5/2:00:00,M10.5.0/2:00:00<>Asia/Jerusalem">(UTC+02:00) Jerusalem</option>
                                                <option value="Jordan Standard Time<>EET-2EEST,M3.5.5/0:00:00,M10.5.5/1:00:00<>Asia/Amman">(UTC+03:00) Amman</option>
                                                <option value="Arabic Standard Time<>AST-3<>Asia/Baghdad">(UTC+03:00) Baghdad</option>
                                                <option value="Kaliningrad Standard Time<>EET-2EEST<>Europe/Kaliningrad">(UTC+03:00) Kaliningrad, Minsk</option>
                                                <option value="Arab Standard Time<>AST-3<>Asia/Riyadh">(UTC+03:00) Kuwait, Riyadh</option>
                                                <option value="E. Africa Standard Time<>EAT-3<>Africa/Nairobi">(UTC+03:00) Nairobi</option>
                                                <option value="Iran Standard Time<>IRST-3:30IRDT,M3.4.0/0:00:00,M9.4.2/0:00:00<>Asia/Tehran">(UTC+03:30) Tehran</option>
                                                <option value="Arabian Standard Time<>GST-4<>Asia/Dubai">(UTC+04:00) Abu Dhabi, Muscat</option>
                                                <option value="Azerbaijan Standard Time<>AZT-4AZST,M3.5.0/4:00:00,M10.5.0/5:00:00<>Asia/Baku">(UTC+04:00) Baku</option>
                                                <option value="Russian Standard Time<>MSK-4<>Europe/Moscow">(UTC+04:00) Moscow, St. Petersburg, Volgograd</option>
                                                <option value="Mauritius Standard Time<>MUT-4<>Indian/Mauritius">(UTC+04:00) Port Louis</option>
                                                <option value="Georgian Standard Time<>GET-4<>Asia/Tbilisi">(UTC+04:00) Tbilisi</option>
                                                <option value="Armenian Standard Time<>AMT-4<>Asia/Yerevan">(UTC+04:00) Yerevan</option>
                                                <option value="Afghanistan Standard Time<>AFT-4:30<>Asia/Kabul">(UTC+04:30) Kabul</option>
                                                <option value="Pakistan Standard Time<>PKT-5<>Asia/Karachi">(UTC+05:00) Islamabad, Karachi</option>
                                                <option value="West Asia Standard Time<>UZT-5<>Asia/Tashkent">(UTC+05:00) Tashkent</option>
                                                <option value="India Standard Time<>IST-5:30<>Asia/Calcutta">(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                                <option value="Sri Lanka Standard Time<>IST-5:30<>Asia/Colombo">(UTC+05:30) Sri Jayawardenepura</option>
                                                <option value="Nepal Standard Time<>NPT-5:45<>Asia/Kathmandu">(UTC+05:45) Kathmandu</option>
                                                <option value="Central Asia Standard Time<>ALMT-6<>Asia/Almaty">(UTC+06:00) Astana</option>
                                                <option value="Bangladesh Standard Time<>BDT-6<>Asia/Dhaka">(UTC+06:00) Dhaka</option>
                                                <option value="Ekaterinburg Standard Time<>YEKT-6<>Asia/Yekaterinburg">(UTC+06:00) Ekaterinburg</option>
                                                <option value="Myanmar Standard Time<>MMT-6:30<>Asia/Rangoon">(UTC+06:30) Yangon (Rangoon)</option>
                                                <option value="SE Asia Standard Time<>ICT-7<>Asia/Bangkok">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
                                                <option value="N. Central Asia Standard Time<>NOVT-7<>Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>
                                                <option value="China Standard Time<>CST-8<>Asia/Shanghai">(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                                <option value="North Asia Standard Time<>KRAT-8<>Asia/Krasnoyarsk">(UTC+08:00) Krasnoyarsk</option>
                                                <option value="Singapore Standard Time<>SGT-8<>Asia/Singapore">(UTC+08:00) Kuala Lumpur, Singapore</option>
                                                <option value="W. Australia Standard Time<>WST-8<>Australia/Perth">(UTC+08:00) Perth</option>
                                                <option value="Taipei Standard Time<>CST-8<>Asia/Taipei">(UTC+08:00) Taipei</option>
                                                <option value="Ulaanbaatar Standard Time<>ULAT-8<>Asia/Ulaanbaatar">(UTC+08:00) Ulaanbaatar</option>
                                                <option value="North Asia East Standard Time<>IRKT-9<>Asia/Irkutsk">(UTC+09:00) Irkutsk</option>
                                                <option value="Tokyo Standard Time<>JST-9<>Asia/Tokyo">(UTC+09:00) Osaka, Sapporo, Tokyo</option>
                                                <option value="Korea Standard Time<>KST-9<>Asia/Seoul">(UTC+09:00) Seoul</option>
                                                <option value="Cen. Australia Standard Time<>CST-9:30CST,M10.1.0/2:00:00,M4.1.0/3:00:00<>Australia/Adelaide">(UTC+09:30) Adelaide</option>
                                                <option value="AUS Central Standard Time<>CST-9:30<>Australia/Darwin">(UTC+09:30) Darwin</option>
                                                <option value="E. Australia Standard Time<>EST-10<>Australia/Brisbane">(UTC+10:00) Brisbane</option>
                                                <option value="AUS Eastern Standard Time<>EST-10EST,M10.1.0/2:00:00,M4.1.0/3:00:00<>Australia/Sydney">(UTC+10:00) Canberra, Melbourne, Sydney</option>
                                                <option value="West Pacific Standard Time<>PGT-10<>Pacific/Port_Moresby">(UTC+10:00) Guam, Port Moresby</option>
                                                <option value="Tasmania Standard Time<>EST-10EST,M10.1.0/2:00:00,M4.1.0/3:00:00<>Australia/Hobart">(UTC+10:00) Hobart</option>
                                                <option value="Yakutsk Standard Time<>YAKT-10<>Asia/Yakutsk">(UTC+10:00) Yakutsk</option>
                                                <option value="Central Pacific Standard Time<>SBT-11<>Pacific/Guadalcanal">(UTC+11:00) Solomon Is., New Caledonia</option>
                                                <option value="Vladivostok Standard Time<>VLAT-11<>Asia/Vladivostok">(UTC+11:00) Vladivostok</option>
                                                <option value="New Zealand Standard Time<>NZST-12NZDT,M9.5.0/2:00:00,M4.1.0/3:00:00<>Pacific/Auckland">(UTC+12:00) Auckland, Wellington</option>
                                                <option value="UTC+12<>GMT-12<>Etc/GMT-12">(UTC+12:00) Coordinated Universal Time+12</option>
                                                <option value="Fiji Standard Time<>FJT-12<>Pacific/Fiji">(UTC+12:00) Fiji</option>
                                                <option value="Magadan Standard Time<>MAGT-12<>Asia/Magadan">(UTC+12:00) Magadan</option>
                                                <option value="Kamchatka Standard Time<>PETT-12<>Asia/Kamchatka">(UTC+12:00) Petropavlovsk-Kamchatsky</option>
                                                <option value="Tonga Standard Time<>TOT-13<>Pacific/Tongatapu">(UTC+13:00) Nuku'alofa</option>
                                                <option value="Samoa Standard Time<>WST13<>Pacific/Apia">(UTC+13:00) Samoa</option>


                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="windowstz" required="required" id="windowstz" placeholder="Select to populate this field"  value="<?php echo $windowstz; ?>" readonly="readonly" /> 
                                        </td>
                                        <td>
                                            <input type="text" name="posix"  required="required" id="posix" placeholder="Select to populate this field" value="<?php echo $posix; ?>" readonly="readonly"  /> 

                                        </td>
                                        <td>
                                            <input type="text" name="olson" required="required" id="olson" placeholder="Select to populate this field"  value="<?php echo $olson; ?>" readonly="readonly" /> 
                                        </td>
                                    </tr>

                                </table>


                            </div>


                            <div id="rackselection" title="Rack Selection">
                                <table  class="collection table table-bordered table-hover">
                                    <tr>
                                        <th colspan="2">
                                            <span class="icon-tasks"></span> Rack start and end point selection
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="startshelf" id="startshelf" class="chosen shelf" data-placeholder="Choose the start point"  required="required">
                                                <?php
                                                echo "<option value='$startshelf'>$startshelf</option>";
                                                foreach ($racks as $rack) {
                                                    echo "<option value='$rack'>$rack</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select readonly="readonly" name="stopshelf" id="stopshelf" class="chosen shelf" data-placeholder="Choose the end point"  required="required">
                                                <?php
                                                echo "<option value='$stopshelf'>$stopshelf</option>";
                                                foreach ($racks as $rack) {
                                                    echo "<option value='$rack'>$rack</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <a href="javascript:void(0)" id="netprov" class="pull-left btn btn-info">Skip to Network provisioning</a>
                            <button id="phase1"  class="btn btn-primary pull-right" >Go to step 2  </button>
                        </fieldset>





                    </div>
                </div>
            </div>



        </form>



    </div> <!-- /container -->

    <?php
} // End of if exist a selected order for thi session
$this->display('_Footer.tpl.php');
?>
