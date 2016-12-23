<?php
$this->assign('title', 'SPOT | Provisioning Wizard');
$this->assign('nav', 'provisioningphase2');

$this->display('_Header.tpl.php');
if (!isset($_SESSION['salesorder']) || !isset($_SESSION['imagename'])) {
    echo '<script type="text/javascript">
           window.location = "pendings"
      </script>';
}

//Loads variable from database
$JSONdata = apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/provisioning/api/provisioningimageses');

$content = json_decode($JSONdata, true);
$imgname = $_SESSION['imagename'];
$imgtarget = $_SESSION['imagetarget'];
$ostarg = $_SESSION['ostarget'];

foreach ($content['rows'] as $value) {
    //1 AIX - BOOTP
    //2 LINUX/WINDOWS - PXE
    //
    $imagetarget[] = $value['imagetarget'];
    $imagename[] = $value['imagename'];
    $ostarget[] = $value['ostarget'];
// var_dump($_SESSION);
}

array_multisort($imagename, $imagetarget, $ostarget);
array_unshift($imagetarget, $imgtarget);
array_unshift($imagename, $imgname);
array_unshift($ostarget, $ostarg);

//$JSONdata = apiWrapper('http://' . GlobalConfig::$SYSPROD_SERVER->MGT . '/SPOT/proddb/api/apccodeses');
// $content = json_decode($JSONdata, true);
$imagespxe[0] = $_SESSION['imagename'];


/* foreach ($content['rows'] as $value) {
  $APC[] = $value['apc'];
  $APCDesc[] = $value['apcDescription'];
  // var_dump($_SESSION);
  }
  array_multisort($APC, $APCDesc); */
//var_dump($_SESSION);
?>
<link href="bootstrap/css/jquery-labelauty.css" rel="stylesheet" />
<script type="text/javascript">
    $LAB.script("scripts/app/provisioningphase2.js").wait(function () {
        $(document).ready(function () {
            page.init();
        });
        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function () {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });</script>

<script type="text/javascript">

    localStorage.setItem("options", "<?php
foreach ($imagename as $kvalue => $image) {
    $optvalue = $imagetarget[$kvalue] . "|" . $image . "|" . $ostarget[$kvalue];

    echo "<option value='$optvalue'>$image</option>";
};
?>");
    $(document).ready(function () {
        var loading;
        var curruser;
        $.get("/SPOT/provisioning/api/tempdata/MESSAGE", function (data) {
            var status = data.status;
            curruser = "<?php echo $_SESSION['login']; ?>";
            curruser = curruser.toLowerCase();
            if (status.toLowerCase().indexOf(curruser) < 0) {
                loading = setInterval(blink, 710);
            } else {
                $('#readme').val("No updates");
                $('#readme').addClass('btn-inverse');
            }
        });
        function blink() {
            var elm = $("#readme");
            if (elm.hasClass('btn-danger')) {
                elm.removeClass('btn-danger');
            } else
            {
                elm.addClass('btn-danger');
            }

            // elm.animate({color: mycolor}, 300);
        }

        $('#readme').on('click', function () {
            clearInterval(loading);
            $(this).val("I've read!");
            $(this).addClass('btn-inverse');
            $.get("/SPOT/provisioning/api/tempdata/MESSAGE", function (data, status) {
                if (status === 'success') {
                    var title = data.data
                    var update = data.status + ', ' + curruser;
                    var message = data.message;
                    if (message !== '') {
                        $('#servermsg').html(title + message);
                    } else {
                        $('#servermsg').html('No notes founds');
                    }
                    $('#basicModal').modal();
                    if (data.status.toLowerCase().indexOf(curruser) < 0) {
                        var updateobj = {};
                        updateobj.status = update;
                        $.ajax({
                            url: "/SPOT/provisioning/api/tempdata/MESSAGE",
                            type: "PUT",
                            data: JSON.stringify(updateobj),
                            async: true
                        });
                    }
                }
            });
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('.detect').on('click', function () {
            var id = $(this).attr('id');
            var idrackname = id.replace("detect", "rackname");
            var idrack = id.replace("detect", "rack");
            var idshelf = id.replace("detect", "shelf");
            var rackname = $('#' + idrackname).val();
            var rack = $('#' + idrack).val();
            var shelf = $('#' + idshelf).val();
            var content = {reponse: "99"};
            var Jcontent = JSON.stringify(content);
            $.ajax({
                url: "/SPOT/provisioning/api/sysprodracks/" + rackname,
                type: "PUT",
                data: Jcontent,
                async: true
            });
            $('.imagename').trigger('change');
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>';
            var scriptid = "0"; //The id for checkRacks script

            var salesorder = <?php echo $_SESSION['salesorder']; ?>;
            var args = '{"0": "-pos", "1" : "' + rackname + '", "2" : "&"}';
            //arguments = JSON.stringify(args);
            //alert(arguments);
            var exesequence = 1;
            var executionFlag = 0;
            var command = {
                salesorder: salesorder,
                rack: rack,
                shelf: shelf,
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
                async: true,
                success: function () {


                }
            });
        });
//check if sysprod servers are all alive before to continue
        var drbl = '<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>';
        var mdt = '<?php echo GlobalConfig::$SYSPROD_SERVER->MDT; ?>';
        var nima = '<?php echo GlobalConfig::$SYSPROD_SERVER->NIMA; ?>';
        var nimb = '<?php echo GlobalConfig::$SYSPROD_SERVER->NIMB; ?>';
        var servers = {
            0: drbl,
            1: mdt,
            2: nima,
            3: nimb


        }

        $('#alive').html('Checking Sysprod Servers recheability   <img src="/SPOT/provisioning/images/loader.gif" />');
        var serializedData = JSON.stringify(servers);
        var request = $.ajax({
            url: "includes/ping.php",
            type: "post",
            data: 'hosts=' + serializedData,
            cache: false,
            success: function (data) {

                var alive = JSON.parse(data);
                var html = '';
                for (var key in alive) {
                    if (alive.hasOwnProperty(key)) {
                        html = html + alive[key];
                    }
                }

                $('#alive').html(html);
            }
        });
        //Apply dom modification on alive and all dynamic dom element
        function applyTooltip() {
            $('[data-toggle="tooltip"]').tooltip()
        }
        $('#alive, .ipaddress, .netmask, .gateway, .hostname').on("DOMSubtreeModified", applyTooltip);
        $('*[required="required"]').before("<span class='icon-star' style='color:red'></span>");
        $('.bootsms').chosen({width: '50%'});
        $('.paging').chosen({width: '50%'});
        $('.disksize').chosen({width: '50%'});
        $('.imagename').chosen({allow_single_deselect: true,
            display_disabled_options: false,
            width: '250px'
        });
        /* var apccode = $('.apccode');
         apccode.chosen({allow_single_deselect: true}); */
        // don't let the width to be "0"
        $(".chosen").each(function () {
            if ($(this).width() == 0) {
                $(this).width(240);
            }
        });
        var network = $('.network').html();
        if (typeof (network) !== "undefined" && network !== null && network !== '') {

            /**
             * the value exists, going on modifying other fields
             */


            var netmask = '255.255.255.0'; // default

            var octets = network.split('.');
            var counter = octets[3];
            $('.autoip').each(function () {
                counter++;
                $(this).val($.trim(octets[0]) + '.' + $.trim(octets[1]) + '.' + $.trim(octets[2]) + '.' + counter);
            });
            $('.netmask').each(function () {
                $(this).val(netmask);
            });
            $('.gateway').each(function () {
                $(this).val($.trim(octets[0]) + '.' + $.trim(octets[1]) + '.' + $.trim(octets[2]) + '.252');
            });
        }
        $('.gwcopy').on('click', function () {
            var val = $(this).prev().val();
            $('.gateway').val(val);
            $('#servermsg').html('<center>Successfully set the gateway to ' + val + '</center>');
            $('#basicModal').modal();
        });
        $('.nmcopy').on('click', function () {
            var val = $(this).prev().val();
            $('.netmask').val(val);
            $('#servermsg').html('<center>Successfully set the netmask to ' + val + '</center>');
            $('#basicModal').modal();
        });
        $(".productkey").keyup(function () {
            $(".productkey").each(function () {
                if ($(this).val().length === 5 ||
                        $(this).val().length === 11 ||
                        $(this).val().length === 17 ||
                        $(this).val().length === 23)
                {
                    $(this).val($(this).val() + '-');
                    $(this).val($(this).val().toUpperCase());
                }
                if ($(this).val().length > 29)
                {
                    $(this).val($(this).val().toUpperCase());
                    alert('You exceed 29 chars!');
                }
            });
        });
        /*
         * 
         * If a CSV has been uploaded take the values from there
         */
<?php
if (isset($_SESSION['CSV'])) {
    $JSON = json_encode($_SESSION['CSV']);
    //   var_dump($_SESSION['CSV']);
    ?>
            var jsonCSV = '<?php echo $JSON; ?>';
            var CSV = JSON.parse(jsonCSV);
            $.each(CSV, function (key, value) {
                var index = key + 1;
                var rackshelf = value[0];
                var hostname = value[1];
                var ipaddress = value[2];
                //  var imagename = value[3];
                $('#rackshelf' + index).val(rackshelf);
                $('#hostname' + index).val(hostname);
                $('#ipaddress' + index).val(ipaddress);
                // $('.imagename').append('<option>' + imagename + '</option>').trigger("chosen:updated");
                // $('#imagename' + index).val(imagename).trigger("chosen:updated");

            });
    <?php
    //  unset($_SESSION['CSV']);
}
?>
        /*
         * 
         * Check duplicates on all type=text fields
         * 
         */
        $('.checkdup').
                change(function () {
                    findDuplicate();
                });
        function findDuplicate() {
            $('.checkbadge').remove();
            var textArr = $(".checkdup:visible").get();
            var idArr = [];
            $('.checkdup:visible').each(function () {
                idArr.push(this.id);
            });
            var len = textArr.length;
            var inner = 0, outer = 0, index = 0, dupLen = 0;
            var dupArr = new Array();
            for (outer = 0; outer < len; outer++) {
                for (inner = outer + 1; inner < len; inner++) {
                    if (textArr[outer].value === textArr[inner].value && textArr[outer].value !== '' && idArr[outer] !== idArr[inner] && textArr[inner].value !== '') {
                        if (jQuery.inArray(textArr[outer], dupArr) == -1) {
                            dupArr.push(textArr[outer]);
                        }
                        if (jQuery.inArray(textArr[inner], dupArr) == -1) {
                            dupArr.push(textArr[inner]);
                        }
                    }
                }
            }

            if (dupArr.length > 0) {
                var html = 'Duplicates values found: <br />';
                var ids = new Array();
                for (var i = 0; i < dupArr.length; i++) {



                    html += "<b>" + dupArr[i].value + " on ID: " + dupArr[i].id + "</b>,<br />";
                    var text = '<b class="badge badge-warning checkbadge">Duplicate value "' + dupArr[i].value + '" for field "' + dupArr[i].name + '" found.</b>';
                    $('#' + dupArr[i].id).after(text);
                    ids.push(dupArr[i].id);
                }

                $('#errormsg').html(html);
                $('#errormsg').show();
                setTimeout(function () {
                    $('#errormsg').fadeOut(5000);
                }, 6000);
            }
        }
        $('#newProvisioningactionButton').on('click', function (e) {
            e.preventDefault();
            /*
             * 
             * Do the validation, check if
             * all the required fields are filled.
             * if not add class error and return false
             */

            if ($('*[required="required"]').val() === '') {

                $('#errormsg').hide();
                $('#errormsg').html("Please fill all the required fields")

                $('#errormsg').show();
                setTimeout(function () {
                    $('#errormsg').fadeOut(2000);
                }, 3000);
                //  console.log($('*[required="required"]'));
                return false;
            } else {
                $('#errormsg').hide();
            }



            // `this` is the div

            /*
             * Get all data
             * mondoWrapper ID is 4 and imagetarget is 2
             * mdtWrapper ID is 5 and imagetarget is 2
             * nimWrapper ID is 3 and imagetarget is 1
             */

            /*
             * Common data
             */
            var mdtwrapper = 5;
            var mondoWrapper = 4;
            var nimWrapper = 3;
            var mondoWrapper_2 = 14;
            var salesorder = '<?php echo $_SESSION['salesorder']; ?>';
            var wintz = '<?php echo $_SESSION['windowstz']; ?>';
            var aixtz = '<?php echo $_SESSION['posix']; ?>';
            var timezone = '<?php echo $_SESSION['olson']; ?>';
<?php
if (isset($_SESSION['releasename']) && $_SESSION['releasename'] !== '') {
    ?>
                var releasename = '<?php echo $_SESSION['releasename']; ?>';
    <?php
} else {
    ?>
                var releasename = '';
    <?php
}
?>
            //Remove empty values from tblprogress
            $.get("/SPOT/provisioning/api/tblprogresses?filter=" + salesorder, function (data, status) {
                var Jdata = JSON.parse(data.rows[0].data);
                ;
                var id = data.rows[0].id;
                /*    $.each(Jdata.newclients, function(i, val) {
                 if (val.hostname === '' || typeof val.hostname === 'undefined' || typeof val.hostname == null) {
                 delete Jdata.clients[i];
                 console.log('deleteing empty Rack ID: ' + i);
                 }
                 }); */

                delete Jdata.newclients; // Remove newclients array from json obj
                var JSONdata = JSON.stringify(Jdata);
                var toSend = {data: JSONdata};
                var stringSend = JSON.stringify(toSend);
                $.ajax({
                    type: "PUT",
                    url: "/SPOT/provisioning/api/tblprogress/" + id,
                    data: stringSend,
                    success: function (data) {
                        //   console.log('successfully removed emtpy racks from tblprogress');
                    }

                });
            });
            function getClients() {
                var res;
                return  $.ajax({
                    url: "/SPOT/provisioning/api/tblprogresses?filter=" + salesorder,
                    type: 'GET',
                    dataType: 'json',
                    contentType: "application/json; charset=utf-8",
                    async: false
                });
            }
            var obj = {};
            var indexTbl;
            getClients().done(function (data) {
                obj = JSON.parse(data.rows[0].data);
                //    console.log(obj);
                indexTbl = getindex(obj);
                //  console.log('index tbl is inside the function: ' + indexTbl);
                return indexTbl;
            });
            //get stored values in tbl progress

            function getindex(x) {
                var count = 0;
                //  console.log(x);
                var toparse = x.clients;
                var i;
                for (i in toparse) {
                    if (toparse.hasOwnProperty(i)) {
                        count++;
                    }
                }
                //    console.log('coubt is: ' + count)
                return count;
            }
            console.log(obj);
            // var indexTbl = getindex(obj);

            //  console.log('index tbl is: ' + indexTbl);
            var temp = {};
            /*
             * Client specific data
             */
            $('.loop').each(function () {
                ++indexTbl;
                var level = $(this).val();
                //  var apccode = $('#apccode' + level).val();
                var ipaddress = $('#ipaddress' + level).val();
                var netmask = $('#netmask' + level).val();
                var gateway = $('#gateway' + level).val();
                var iloipaddress = $('#iloipaddress' + level).val();
                var ilonetmask = netmask;
                var image = $('#imagename' + level).val();
                var imagearr = image.split('|');
                var imagename = imagearr[1];
                var rack = $('#rack' + level).val();
                var shelf = $('#shelf' + level).val();
                var hostname = $('#hostname' + level).val();
                var bootsms = $('#bootsms' + level).val();
                var workgroup = $('#workgroup' + level).val();
                var productkey = $('#productkey' + level).val();
                var radmin = $('#radmin' + level).val();
                var machinetype = $('#machinetype' + level).val();
                var mirror = $("input[name=raidconfig" + level + "]:checked").val();
                var paging = $('#paging' + level).val();
                var disksize = $('#disksize' + level).val();
                //      console.log('machinetype ' + machinetype);
                /*
                 * Update tbl progress
                 * 
                 */


                var strRackShelf = 'rack' + rack + '_shelf' + shelf;
                var imagetarget = $('#imagename' + level).val();
                var imagetargetarr = imagetarget.split('|');
                var ostargetarr = imagetargetarr[2].split(':');
                var ostarget = ostargetarr[1].trim();
                temp[indexTbl] = {clientid: indexTbl,
                    rackname: strRackShelf,
                    rack: rack,
                    shelf: shelf,
                    // apccode: apccode,
                    hostname: hostname,
                    imagename: imagename,
                    ostarget: ostarget,
                    ip: ipaddress,
                    netmask: netmask,
                    gateway: gateway
                };
                // Jdata.clients.push(temp[level]);


                switch (machinetype) {
                    case '4':
                    case '1':

                        /*
                         * 
                         * smsboot not configured in this case because the machine is not connected
                         */
                        /* usage ./nimWrapper
                         -a [aixtz]
                         -c [nimclient]
                         -d [disksize(GB)]
                         -g [default gateway]
                         -h [hostname]
                         -i [image name]
                         -ip [ip address]
                         -m [mirror:0|1]
                         -n [netmask]
                         -p [paging size]
                         -s [sales order number]
                         -boot [normal|factory (default to not initiate boot)]
                         -nocheck (without values args.) If you don't want to check for nimclient validity for ex. to install LPAR that physically are not placed on a rack shelf
                         
                         [2015-01-19 12:17:28] INFO: Only 3 parameters are mandatory: -a [aixtz] -c [nimclient] -i [image name]
                         [2015-01-19 12:17:29] WARN: If only the [-c nimclient] parameter is given, the script check if the connection is ok
                         [2015-01-19 12:17:29] WARN: To boot in diag or maintenance mode, only three parameters are needed: -c nimclient -i [diag|maint_boot] -spot [spot name]. note that if -spot is empty, it will return a list of possible object
                         
                         */

                        var argstring = {
                            "0": "-c",
                            "1": "rack" + rack + "_shelf" + shelf,
                            "2": "-ip",
                            "3": ipaddress,
                            "4": "-n",
                            "5": netmask,
                            "6": "-a",
                            "7": aixtz,
                            "8": "-h",
                            "9": hostname,
                            "10": "-g",
                            "11": gateway,
                            "12": "-d",
                            "13": disksize,
                            "14": "-m",
                            "15": mirror,
                            "16": "-i",
                            "17": imagename,
                            "18": "-p",
                            "19": paging,
                            "20": "-s",
                            "21": salesorder,
                            "22": "-nocheck"
                        };
                        if (machinetype == 1) {
                            if (bootsms !== '') {
                                argstring[22] = "-boot";
                                argstring[23] = bootsms;
                            }
                        }
                        var scriptID = 3;
                        // Load balancing between NIM Servers
                        var server;
                        if (indexTbl % 2) {

                            server = '<?php echo GlobalConfig::$SYSPROD_SERVER->NIMA; ?>';
                        }
                        else
                        {

                            server = '<?php echo GlobalConfig::$SYSPROD_SERVER->NIMB; ?>';
                        }

                        var clientaddress = server;
                        break;
                    case '2':

                        var imagetarget = $('#imagename' + level).val();
                        var imagetargetarr = imagetarget.split('|');
                        var ostargetarr = imagetargetarr[2].split(':');
                        var ostarget = ostargetarr[1].trim();
                        var argstring = {
                            "0": "-pos",
                            "1": "rack" + rack + "_shelf" + shelf,
                            "2": "-ip",
                            "3": ipaddress,
                            "4": "-n",
                            "5": netmask,
                            "6": "-h",
                            "7": hostname,
                            "8": "-g",
                            "9": gateway,
                            "10": "-t",
                            "11": imagename,
                            "12": "-s",
                            "13": salesorder,
                            "14": "-iloip",
                            "15": iloipaddress,
                            "16": "-ilonm",
                            "17": ilonetmask
                        };
                        var index = 17;
                        switch (ostarget) {
                            case 'WINDOWS':
                                /* [2015-01-19 12:19:28] INFO: usage ./mdtWrapper -a [wintz]
                                 -pos [rack_shelf | client ip]
                                 -g [default gateway]
                                 -h [hostname]
                                 -d [workgroup]
                                 -t [task ID]
                                 -ip [ip address]
                                 -n [netmask]
                                 -p [product key]
                                 -r [customer release(s) (separated by comma, ex release1,release2, etc.) ]
                                 -s [sales order number]
                                 -iloip [ilo ip address]
                                 -ilonm [ilo netmask]
                                 -cus [customer acr]
                                 -radmin [0|1 (default to 0 - not activate ]
                                 [2015-01-19 12:19:29] INFO: IMM and ILO are threated same way
                                 [2015-01-19 12:19:30] INFO: Only 2 parameter are mandatory: -pos [rack_shelf | client IP] -t [task ID]. Note that if taskID is a clonezilla image the wrapper recognize automatically it
                                 [2015-01-19 12:19:30] INFO: The parameter -pos can specify either the rack shelf position and directly the ip client (usefull to install virtual machines)
                                 [2015-01-19 12:19:31] WARN: Note that if the server is a Proliant either a IBM system X, the mirror is automatically built if two disks are found
                                 [2015-01-19 12:19:32] WARN: If only one argument is given [-pos rackposition] it checks for the existance of the pxe client
                                 */
                                index++;
                                if (workgroup !== '' && typeof workgroup !== 'undefined') {
                                    //      console.log('workgroup not empty');
                                    argstring[index] = "-d";
                                    index++;
                                    argstring[index] = workgroup;
                                    index++;
                                }
                                argstring[index] = "-a";
                                index++;
                                argstring[index] = '\"' + wintz + '\"';
                                if (releasename !== '') {
                                    index++;
                                    argstring[index] = "-r";
                                    index++;
                                    argstring[index] = releasename;
                                    index++;
                                    argstring[index] = "-cus";
                                    index++;
                                    argstring[index] = "<?php echo $_SESSION['CustomerACR']; ?>";
                                }
                                if (productkey !== '') {
                                    index++;
                                    argstring[index] = "-p";
                                    index++;
                                    argstring[index] = productkey;
                                }
                                if ($('#radmin' + level).is(":checked")) {
                                    index++;
                                    argstring[index] = "-radmin";
                                    index++;
                                    argstring[index] = radmin;
                                }
                                var scriptID = 5;
                                var clientaddress = $.trim($("#clientaddress" + level).val());
                                // Next line is commented out because the script is run from client directly
                                // var clientaddress = '<?php //echo GlobalConfig::$SYSPROD_SERVER->DRBL;                                                   ?>';
                                break;
                            case 'LINUX':

                                /*
                                 * 
                                 * Get the dhcp ip client
                                 * it'll be the client directly to execute the script
                                 */

                                /*
                                 
                                 [2015-01-19 12:18:40] INFO: usage ./mondoWrapper
                                 -pos [rack_shelf | client ip]
                                 -g [default gateway]
                                 -h [hostname]
                                 -t [ISO name]
                                 -ip [ip address]
                                 -n [netmask]
                                 -s [sales order number]
                                 -iloip [ilo ip address]
                                 -ilonm [ilo netmask]
                                 [2015-01-19 12:18:41] INFO: Only 2 parameter are mandatory: -pos [rack_shelf | client IP] -t [ISO name]
                                 [2015-01-19 12:18:42] INFO: The parameter -pos can specify either the rack shelf position and directly the ip client (usefull to install visrtual machines)
                                 [2015-01-19 12:18:43] WARN: Note that if the server is a Proliant, the mirror is automatically built if two disks are found
                                 [2015-01-19 12:18:44] WARN: If only one argument is given [-pos rackposition] it checks for the existance of the pxe client
                                 */

                                /*
                                 * 
                                 * Check if image name contains GEN suffix (kickstart and mondoi wrapper difference
                                 * so we know which ID run
                                 */

                                if (imagename.toLowerCase().indexOf("gen") > -1) {
                                    //This is a generic image
                                    // restore via kickstart redhat
                                    var scriptID = 14; //
                                }
                                else
                                {
                                    var scriptID = 17;
                                }


                                //  var clientaddress = '<?php // echo GlobalConfig::$SYSPROD_SERVER->DRBL;                                                                    ?>';

                                var clientaddress = $.trim($("#clientaddress" + level).val());
                                //      console.log("clientaddress: " + clientaddress);
                                break;
                        }
                        // var clientaddress = '<?php //echo GlobalConfig::$SYSPROD_SERVER->DRBL;                                                                    ?>';
                        break;
                }

                console.log(argstring);
                /*
                 * Now datastring contains all arguments for wrappers, lets prepare to insert in DB later
                 */
                var datastring = JSON.stringify(argstring);
                //    console.log(datastring);
                /*
                 * Prepare send to table provisioningactions
                 */
                var imagetarget = $('#imagename' + level).val();
                var imagetargetarr = imagetarget.split('|');
                var ostargetarr = imagetargetarr[2].split(':');
                var ostarget = ostargetarr[1].trim();
                var provisioningactions = {
                    salesorder: salesorder,
                    // codeapc: apccode,
                    rack: rack,
                    shelf: shelf,
                    hostname: hostname,
                    posixtz: aixtz,
                    wintz: wintz,
                    timezone: timezone,
                    image: imagename,
                    os: ostarget,
                    boot: machinetype,
                    ip: ipaddress,
                    netmask: netmask,
                    gateway: gateway,
                    productkey: productkey
                };
                var provactionsJson = JSON.stringify(provisioningactions);
                $.ajax({
                    type: "POST",
                    url: "/SPOT/provisioning/api/provisioningaction/",
                    data: provactionsJson,
                    success: function (data) {
                        //              console.log('data sent to provisioningactions table');
                    }
                });
                /*
                 * Send to remote commands table
                 */


                var exesequence = 0;
                var executionFlag = 0;
                var command = {
                    salesorder: salesorder,
                    rack: rack,
                    shelf: shelf,
                    clientaddress: clientaddress,
                    arguments: datastring,
                    exesequence: exesequence,
                    executionflag: executionFlag,
                    scriptid: scriptID
                }
                var Jcommand = JSON.stringify(command);
                $.ajax({
                    url: "/SPOT/provisioning/api/remotecommands",
                    type: "POST",
                    data: Jcommand,
                    wait: true
                });
            });
            //Update tblprogress
            $.get("/SPOT/provisioning/api/tblprogresses?filter=" + salesorder, function (data, status) {
                var Jdata = JSON.parse(data.rows[0].data);
                var id = data.rows[0].id;
                Jdata.completed = true;
                //Push new elements into 
                // Jdata.clients.push(temp);
                //Jdata.clients = temp;
                if (!Jdata.hasOwnProperty('clients')) {
                    Jdata.clients = {};
                }
                $.each(temp, function (i, val) {
                    Jdata.clients[i] = temp[i];
                    //     console.log('Adding Client ID: ' + i);
                    //    console.log(temp);
                });
                var JSONdata = JSON.stringify(Jdata);
                var toSend = {data: JSONdata};
                var stringSend = JSON.stringify(toSend);
                $.ajax({
                    type: "PUT",
                    url: "/SPOT/provisioning/api/tblprogress/" + id,
                    data: stringSend,
                    success: function (data) {
                        console.log('Successfully updated from tblprogress');
                    }

                });
            });
            //Set an ip alias on mgt workstation
            var scriptID = 7;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var gateway = $('.gateway').val();
            if (gateway !== '') {
                var rack = 25;
                var shelf = 'Z';
                var exesequence = 1;
                var executionFlag = 0;
                var argstring = {
                    "0": "-ip",
                    "1": gateway,
                    "2": "-n",
                    "3": netmask
                };
                var datastring = JSON.stringify(argstring);
                var command = {
                    salesorder: salesorder,
                    rack: rack,
                    shelf: shelf,
                    clientaddress: clientaddress,
                    arguments: datastring,
                    exesequence: exesequence,
                    executionflag: executionFlag,
                    scriptid: scriptID

                };
                var Jcommand = JSON.stringify(command);
                var url = "/SPOT/provisioning/api/remotecommands/";
                //Post the remote command to get executed

                $.ajax({
                    url: url,
                    type: "POST",
                    data: Jcommand,
                });
            }
            // End set ip alias part
            //  $('#dashboard').html('</b>The data has been successfully saved, futhermore ip alias on MGT will be set to the gateway address. If you need to add more ip alias, go to <a href="./setipalias">Set Ip Alias</a>.</b> Click the button to get the provisioning dashboard:  <a href=\"./provisioningnotificationses\" class=\"btn btn-large btn-primary\">Dashboard');
            $('#newProvisioningactionButton').hide();
            // $('#dashboard').show();
            $('#servermsg').html('The data has been successfully saved, futhermore ip alias on MGT will be set to the gateway address. If you need to add more ip alias, go to <a href="./setipalias">Set Ip Alias</a>.</b> Click the button to get the provisioning dashboard:  <a href=\"./provisioningnotificationses\" class=\"btn btn-large btn-primary\">Dashboard');
            $('#basicModal').modal();
        });
        $('#runcommander').on('click', function (e) {
            var button = $(this);
            button.hide();
            // var command = 'ssh -i .ssh/id_rsa  cristall@my.compnay.com@chx-sysprod-01 "cmd /c C:\\SPOT\\nodejs\\nssm-2.24\\win64\\nssm restart SPOT_check_racks"';
            var command = ' winexe -U sysprod%***REMOVED*** //chx-sysprod-01 "cmd /c C:\\SPOT\\nodejs\\nssm-2.24\\win64\\nssm restart SPOT_check_racks"';
            var url = "/SPOT/provisioning/api/remotecommands/";
            e.preventDefault();
            var scriptID = 100; // the scriptID runCommander
            var rack = '25';
            var shelf = 'Z';
            var clientaddress = "<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>";
            var user = "root";
            var exesequence = 0;
            var executionFlag = 0;
            var SO = "99999999";
            var argument = {
                "0": "-u " + user,
                "1": '-c \'' + command + '\''
            };
            var datastring = JSON.stringify(argument);
            var command = {
                salesorder: SO,
                rack: rack,
                shelf: shelf,
                clientaddress: clientaddress,
                arguments: datastring,
                exesequence: exesequence,
                returnstdout: "",
                executionflag: executionFlag,
                scriptid: scriptID
            };
            var Jcommand = JSON.stringify(command);
            $.ajax({
                url: url,
                type: "POST",
                data: Jcommand,
                success: createTR,
                error: function (data) {
                    $('#servermsg').html("An error occured:  " + data.statusText + " " + data.responseText);

                    $('#basicModal').modal();

                }
            }).done(monitoring);
            function createTR(data) {
                var commandId = data.remotecommandid;
                var e = $('<p>Automatically waiting for results</p><table id="stdout' + commandId + '" class="table-bordered table-responsive table table-striped"></table>');
                $('#servermsg').append(e);
                $('#basicModal').modal();
            }
            ;
            function monitoring(data) {

                var setIntervalID = setInterval(function () {

                    var commandId = data.remotecommandid;
                    var host = data.clientaddress;
                    var url = "/SPOT/provisioning/api/remotecommands/" + commandId

                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (data) {

                            var arguments = data.arguments;
                            var error = data.returncode;
                            if (data.returnstdout === '')
                                data.returnstdout = "The command has not been yet executed... please wait....";
                            //stdout ID if you want the modal to display
                            $('#stdout' + commandId).html('<tr><th>Running command ' + arguments + ' on host ' + host + '</th></tr><tr><td><pre class="prettyprint">' + data.returnstdout + "</pre><code> " + data.returnstderr + '</code><code>Exit code: ' + error + '</code></pre></td><tr>');
                        }
                    });
                    $('#close').on('click', function () {
                        $('#runcommander').show();
                        clearInterval(setIntervalID);

                    });
                }, 2000);
            }

        });

    });</script>

<style>
    .col1 {
        min-width: 180px;
    }
</style>
<div class="container">


    <h1>
        <i class="icon-th-list"></i> Provisioning Wizard - 2 of 2


    </h1>


    <?php
//($_SESSION);
    if (!isset($_SESSION['newclients'])) {
        echo "<p class='alert alert-danger'>You need to load an order first</p>";
        echo "<p class='text-primary'>Got to 'Pending orders' section:  <a href='./pendings'>New orders</a> or <a href='./tblprogresses'>Stored orders</a> and select the one you wish to start or complete.</p>";
    } else {
        ?>
        <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
        <div id="message"></div>
        <h3 class="ui-widget-header">System Provisioning - <small class="icon-star" style="color:red"> mark a field as required</small><input id="readme" type="button"  class ="btn pull-right" value="Readme"> </h3>

        <!-- Header Section -->
        <table  class="collection table table-bordered" >
            <tr>

                <th>
                    <span class="icon-credit-card"></span> Sales Order
                </th>
                <th>
                    <span class="icon-time"></span> Time zone
                </th>
                <th>

                    <span class="icon-signal"></span> Customer Subnet
                </th>
                <th>

                    <span class="icon-folder-open"></span> Release(s)
                </th>
                <th>

                    <span class="icon-hdd"></span> Default HE Image
                </th>
                <th>
                    <span class="icon-flag-checkered"></span> Sysprod Server Status
                </th>
            </tr>
            <tr>
                <td id="">
                    <?php echo $_SESSION['salesorder']; ?>

                </td>
                <td>
                    <?php echo $_SESSION['tz'] . " | " . $_SESSION['posix'] . " | " . $_SESSION['windowstz']; ?>
                </td>
                <td class="network">
                    <?php echo $_SESSION['network']; ?>

                </td>
                <td>
                    <?php echo $_SESSION['releasename']; ?>
                </td>
                <td>
                    <?php echo $_SESSION['imagename']; ?>
                </td>
                <td >
                    <div id="alive"></div>      
                </td>
            </tr>
        </table>

        <!-- End Header Section -->
        <a class="btn btn-mini btn-success" id="runcommander" title="Restart the check racks service on chx-sysprod-01">Restart detecting service</a>
        <div class="col-md-12 text-center" >


            <p class="pagenum"></p>
        </div>
        <!-- Begin machines form -->

        <form class="form-horizontal" id="provisioningForm" onsubmit="return false;">
            <fieldset>
                <table class="collection table table-bordered">
                    <thead id="headerTable">
                        <tr>

                            <th>
                                <span class="icon-road"></span> Client Position   
                            </th>
                            <th>
                                <span class="icon-user"></span> Hostname 
                            </th>
                            <th>
                                <span class="icon-cloud"></span> Network
                            </th>
                            <th>
                                <span class="icon-hdd"></span> HE Image   
                            </th>
                            <th>
                                <span class="icon-unchecked"></span> Optional  
                            </th>

                        </tr>
                    </thead>
                    <tbody id="paginationTable" class="update">
                        <?php
                        foreach ($_SESSION['newclients'] as $client) {
                            foreach ($client as $key => $value) {
                                ?>

                                <tr id="tr<?php echo $key; ?>" class="items">

                                    <td class="col1">
                                        <img style="width:15%;display:none" id="imgaix<?php echo $key; ?>" data-toggle="tooltip" title="AIX" alt="AIX" src="data:image/png;base64,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" />
                                        <img style="width:15%;display:none" id="imgwin<?php echo $key; ?>" data-toggle="tooltip" title="Windows" alt="WINDOWS" src="data:image/jpg;base64,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"/>

                                        <img style="width:15%;display:none" id="imgrhel<?php echo $key; ?>" data-toggle="tooltip"  title="RHEL" alt="RHEL" src="data:image/png;base64,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"/>


                                        <input type="hidden" class="loop" value="<?php echo $key; ?>" />
                                        <input type="hidden" id="rack<?php echo $key; ?>" value="<?php echo $value['rack']; ?>" />
                                        <input type="hidden" id="shelf<?php echo $key; ?>" value="<?php echo $value['shelf']; ?>" />
                                        <input type="hidden" id="rackname<?php echo $key; ?>" value="<?php echo $value['rackname']; ?>" />
                                        <input type="hidden" id="clientaddress<?php echo $key; ?>" value="" />

                                        <p>N. <?php echo $key . " | " . $value['rackname']; ?></p>
                                        <p id="deleteRow<?php echo $key; ?>" class="btn btn-mini btn-danger removeButton"><i class="icon-trash icon-white"></i> Delete Row</p>
                                        <p id="detect<?php echo $key; ?>" class="btn btn-mini btn-success detect"><i class="icon-eye-open icon-white"></i>Detect again</p>

                                        <div id="clientaddressShow<?php echo $key; ?>"></div>
                                        <input type="hidden" class="<?php echo $value['rackname']; ?> sectionSettings"  id ="<?php echo $key; ?>" value="" />



                                        <p id="<?php echo $value['rackname']; ?>"><img src="/SPOT/provisioning/images/loader.gif" data-toggle="tooltip" title="Please, patience while I'm checking the connections...." /></p>
                                     <!--   <p> <label for="apccode<?php echo $key; ?>">Apc Code</label>
                                            <select name="apccode<?php echo $key; ?>" id ="apccode<?php echo $key; ?>" class="chosen apccode" placeholder="Optional select product code" >
                                        <?php
                                        /*  foreach ($APC as $ids => $apc) {

                                          echo "<option value='$apc'>$apc - $APCDesc[$ids]</option>";
                                          } */
                                        ?>
                                            </select>

                                        </p> -->
                                    </td>
                                    <td>
                                        <input type="text" name="hostname<?php echo $key; ?>" data-toggle="tooltip" title="Hostname" id="hostname<?php echo $key; ?>" class="hostname checkdup" placeholder="hostname" required="required" data-toggle="tooltip" />
                                    </td>
                                    <td>

                                        <p>

                                            <input type="text" data-toggle="tooltip" name="ipaddress<?php echo $key; ?>" id="ipaddress<?php echo $key; ?>" class="ipaddress checkdup autoip ipaddress_<?php echo $key; ?>" placeholder="Ip address"  data-toggle="tooltip" title="IP address" required="required" required/>

                                        </p>
                                        <p>


                                            <input type="text" data-toggle="tooltip" name="netmask<?php echo $key; ?>" id="netmask<?php echo $key; ?>" class="netmask" placeholder="netmask" data-toggle="tooltip" title="Netmask" required="required"/>

                                            <span class="icon-copy nmcopy" data-toggle="tooltip" title="click to set as default"></span>


                                        </p>
                                        <p>

                                            <input type="text" data-toggle="tooltip" name="gateway<?php echo $key; ?>" id="gateway<?php echo $key; ?>" class="gateway ipaddress" placeholder="gateway" data-toggle="tooltip" title="Default gateway" required="required"/>

                                            <span class="icon-copy gwcopy" data-toggle="tooltip" title="click to set as default"></span>
                                        </p>

                                    </td>
                                    <td>

                                        <div class="label label-info" id="optgroup<?php echo $key; ?>"></div>
                                        <select id="imagename<?php echo $key; ?>" name="imagename<?php echo $key; ?>" class="chosen imagename" required="required">

                                        </select>
                                        <script>

                                            $('#deleteRow<?php echo $key; ?>').click(function (event) {
                                                event.preventDefault();
                                                var numItems = $('.removeButton').length;
                                                if (numItems == 1) {
                                                    $('#servermsg').html('You cannot remove the last element.');
                                                    $('#basicModal').modal();
                                                } else {
                                                    $('#tr<?php echo $key; ?>').remove();
                                                    $('.pager').remove();
                                                    $('#pagination').after('<ul class="pagination  pager" id="myPager"></ul>');
                                                    page.pageMe();
                                                }
                                            });</script>

                                        <div id="imglblcontainerimagename<?php echo $key; ?>"></div>
                                    </td>
                                    <td>
                                        <div id="pbootsms<?php echo $key; ?>">
                                            <p><span class="label label-info">SMS menu configuration:</span></p>
                                            <p>
                                                <select id="bootsms<?php echo $key; ?>" name="bootsms<?php echo $key; ?>" class="bootsms chosen">
                                                    <option value="factory">Launch and configure bootp FACTORY mode</option>
                                                    <option value="normal">Launch and configure bootp NORMAL mode</option>
                                                    <option value="">Do NOTHING </option>
                                                </select>
                                            </p>
                                        </div>
                                        <div id="imagedata<?php echo $key; ?>">
                                            <p>
                                                <span class="label label-info">Paging size:</span>
                                            </p>
                                            <p>
                                                <select id="paging<?php echo $key; ?>" class="paging chosen" name="paging<?php echo $key; ?>">

                                                    <option value="8">8</option>
                                                    <option value="12">12</option>
                                                    <option value="16">16</option>
                                                    <option value="20" selected>20</option>
                                                    <option value="24">24</option>
                                                    <option value="28">28</option>
                                                    <option value="32">32</option>
                                                </select>
                                            </p>
                                            <p>
                                                <span class="label label-info">Disk size GB:</span>
                                            </p>
                                            <p>
                                                <select id="disksize<?php echo $key; ?>" class="disksize chosen" name="disksize<?php echo $key; ?>">

                                                    <option value="80">80</option>
                                                    <option value="135">135</option>
                                                    <option value="279" selected>279</option>
                                                    <option value="500">500</option>
                                                    <option value="519">519</option>
                                                </select>
                                            </p>
                                            <p>
                                                <span class="label label-info">Mirror:</span>
                                            </p>
                                            <p>
                                                <input type="radio" checked name="raidconfig<?php echo $key; ?>"  value="0" data-toggle="tooltip" title=""/>Mirror on hdisk1<br />
                                                <input type="radio" name="raidconfig<?php echo $key; ?>"  value="1" data-toggle="tooltip" title="Install only on hdisk0 (take care if raid 5 to build before to deploy)"/>No mirror (take care if raid 5 to build before to deploy)
                                            </p>
                                        </div>
                                        <div id="piloipaddress<?php echo $key; ?>">
                                            <p><span class="label label-info">ILO/IMM ip address:</span></p>
                                            <p>

                                                <input type="text" data-toggle="tooltip" name="iloipaddress<?php echo $key; ?>" id="iloipaddress<?php echo $key; ?>" class="ipaddress checkdup autoip iloipaddress_<?php echo $key; ?>" placeholder="ILO/IMM ip address" />
                                            </p>
                                        </div>
                                        <div id="windows<?php echo $key; ?>">
                                            <span class="label label-info"> Windows OS specific:</span>
                                            <label for="">Workgroup</label>   

                                            <input type="text" name="workgroup<?php echo $key; ?>" class="workgroup<?php echo $key; ?> hostname"  id="workgroup<?php echo $key; ?>" placeholder="Workgroup"/>


                                            <label for="productkey<?php echo $key; ?>">Product Key</label>
                                            <input type="text" name="productkey<?php echo $key; ?>"  class="productkey checkdup" id="productkey<?php echo $key; ?>" placeholder="xxxxx-xxxxx-xxxxx-xxxxx-xxxxx" />
                                            <span><i class="icon-check-sign grabpk"  id="progress<?php echo $key; ?>" title="Click here to read the firmware PK" name="clientaddress<?php echo $key; ?>_productkey<?php echo $key; ?>"></i>
                                                <img src="/SPOT/provisioning/images/loader.gif" data-toggle="tooltip" id="imgprogress<?php echo $key; ?>" title="Please, patience while I\'m checking the PK...." style="display:none"/>  
                                            </span>

                                            <div class='checkboxes span6'>
                                                <input type="checkbox"  name="radmin<?php echo $key; ?>"  id="radmin<?php echo $key; ?>" value="1" /><label for="radmin<?php echo $key; ?>"></label>
                                                <span class='badge badge-info modules'>Radmin activation</span>
                                            </div>

                                        </div>

                                    </td>


                                </tr>
                            <script >
                                // Hide all optional value to show them later
                                // 
                                $('#pbootsms<?php echo $key; ?>').hide();
                                $('#piloipaddress<?php echo $key; ?>').hide();
                                $('#windows<?php echo $key; ?>').hide();
                                $('#deleteRow<?php echo $key; ?>').hide();
                                $('#detect<?php echo $key; ?>').hide();
                                $('#imagedata<?php echo $key; ?>').hide();
                                $('#imagename<?php echo $key; ?>').hide();
                                // Get the rack reponse
                            </script>

                            <?php
// End foreach 
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </fieldset>
        </form>



        <div class="col-md-12 text-center" id="pagination">

            <ul class="pagination  pager" id="myPager"></ul>
            <div class="pagenum center"></div>
        </div>

        <div id="collectionAlert">

            <p class="alert alert-success" id="dashboard" role="alert" style="display:none"></p>
            <p class="alert alert-error" id="errormsg" role="alert" style="display:none"></p>
        </div>

        <div id="provisioningactionCollectionContainer" class="collectionContainer">

        </div>

        <p id="newButtonContainer" class="buttonContainer">
            <button id="newProvisioningactionButton" class="btn btn-primary pull-right">Save</button>
        </p>


    </div> <!-- /container -->
    <script>
        $(document).ready(function () {

            $('.productkey').on('change', function () {
                if ($(this).val() === '')
                    $(this).next('i').find('.grabpk').show();
            });
            $('.grabpk').on('click', function () {

                var name = $(this).attr('name');
                var parsed = name.split('_');
                var id = "img" + $(this).attr('id');
                var scriptID = 27;
                $(this).hide();
                /*
                 * parsed[0] contains id for ipaddress
                 * parsed[1] contains name for the field to fill
                 * id is the spinning to show while loading
                 * scriptID the script to execute
                 */
                var ipaddress = $('#' + parsed[0]).val();
                runScript(ipaddress, parsed[1], id, scriptID);
            })
            $(".sectionSettings").on("change", function () {

                var removal;
                var settings = $(this).val();
                if (!JSON.parse(settings))
                    return;
                var decoded = JSON.parse(settings);
                var id = $(this).attr('id');
                if (decoded.reponse == 99) {
                    $("#" + decoded.idracks).html('<img src="/SPOT/provisioning/images/loader.gif" data-toggle="tooltip" title="Please, patience while I\'m checking the connections...." />');
                    return;
                }

                $('#deleteRow' + id).show();
                var label = '';
                var client = '';
                var selects = $('#imagename' + id);
                var boot = decoded.machinetype
                switch (decoded.reponse) {

                    case '1':
                        client = '<span class=\'label label-success\' data-toggle="tooltip" title=\'Client Power AIX\'>' + boot + '</span>';
                        $('#detect' + id + ', #piloipaddress' + id + ', #windows' + id + ', #imgwin' + id + ',#imgrhel' + id).hide();
                        $('#pbootsms' + id + ', #imagedata' + id + ', #imgaix' + id).show();
                        $('#clientaddressShow' + id).html('');
                        removal = 2;
                        label = 'AIX images:';
                        break;
                    case '2':
                        $('#detect' + id + ', #pbootsms' + id + ', #imagedata' + id).hide();
                        var ip = decoded.ipaddress;
                        $('#clientaddress' + id).val(ip);
                        $('#clientaddressShow' + id).html('<span class="label label-success">The dhcp address is: ' + ip + '</span>')
                        client = '<span class=\'label label-warning\' data-toggle="tooltip" title=\'Client PXE Linux/Windows\'>pxe</span>';
                        $('#piloipaddress' + id + ', #windows' + id + ', #imgwin' + id + ',#imgrhel' + id).show();
                        label = 'RH/WIN images:';
                        removal = 1;
                        break;
                    case '4':

                        client = '<span class=\'label label-default\'  data-toggle="tooltip" title=\'Client Undefined. If this is not a VM click the button to delete this client. If this is a physical machine, check the connections and run this page again\'>undef</span>';
                        $('#clientaddressShow' + id).html('');
                        $('#detect' + id + ', #imagedata' + id + ', #imgaix' + id).show();
                        $('#questionMark' + id).remove();
                        $('#imgaix' + id).after('<b id="questionMark' + id + '">?</b>');
                        $('#piloipaddress' + id + ', #windows' + id + ', #imgwin' + id + ', #imgrhel' + id + ', #pbootsms' + id).hide();
                        removal = 2;
                        label = 'AIX images:';
                        break;
                }

                $('#' + decoded.idracks).html(client + '');
                selects.chosen('destroy');
                selects.empty().append(localStorage.getItem("options")).trigger('chosen:updated');
                selects.find("option[value^='" + removal + "']").remove().trigger('chosen:updated');
                var optGrps = $("#optgroup" + id);
                optGrps.html(label);
                //   optGrps.label = label

                // selects.trigger("change");
                $('#machinetype' + id).remove();
                selects.show();
                selects.after('<input type="hidden" class="machinetype" id="machinetype' + id + '" value="' + decoded.reponse + '" />');
                selects.chosen({allow_single_deselect: true,
                    display_disabled_options: false,
                    width: '250px'
                });
            });
            $('input:checkbox').on('click', function () {
                //  console.log($(this).val())
            });
            //console.log($('input:checkbox').val());
            $(document).on('change', '.imagename', function () {
                //Filter fields to show only pertinents input fileds
                var subIot = "IOT";
                var subWin = "DEPLOY";
                var subClonezilla = 'MGT';
                //Check loop not so logic, but a quick way to check all values 
                $('.loop').each(function () {
                    var level = $(this).val();
                    var imagename = $('#imagename' + level).val();
                    //Check 1
                    if (imagename.indexOf(subWin) != -1 || imagename.indexOf(subClonezilla) != -1) {
                        $('#windows' + level).show();
                        // Ok it's a window deployment, going to check 2
                        if (imagename.indexOf(subIot) != -1) {
                            $('#productkey' + level).attr('disabled', true).show();
                            $('#progress' + level).hide();
                            $('i#progress' + level).after('<div id="keymsg' + level + '" class="label label-info">IOT image activation goes through VAMT3.1!<p>The VAMT server is at x.x.x.228!</p></div>')
                            //  $(this).next('.productkey').find('input').hide();

                        } else {
                            $('#productkey' + level).attr('disabled', false).show();
                            $('#progress' + level).show();
                            $('#keymsg' + level).remove();
                        }
                    } else {
                        $('#windows' + level).hide();
                    }
                });
            });
        })
    </script>
    <?php
} // End of if exist a selected order for thi session
$this->display('_Footer.tpl.php');
?>
