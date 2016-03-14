<?php

$this->assign('title', 'SPOT | Set hostname, IP, CPU, RAM, OS attributes in SysprodDB');
$this->assign('nav', 'setattr');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {


        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        $('#salesel').chosen();
        // Get all the sales order in the tblprogress table

        $.ajax({
            url: "/SPOT/provisioning/api/tblprogresses",
            type: "GET",
            async: false,
            cache: false,
            wait: true,
            success: function (jsonResult) {
                //console.log(jsonResult);
                var Jdata = jsonResult.rows;
                // $('#salesel').attr('enabled', 'true');

                $.each(Jdata, function (i, o) {

                    var Jfield = JSON.parse(o.data);
                    var customerACR = Jfield.CustomerACR;
                    if (Jfield.completed == true) {
                        $('#salesel').append(
                                '<option>' + o.salesorder + '|' + customerACR + '</option>'
                                ).trigger("chosen:updated");
                    }
                });

            }
        });

        /* $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
         var Jdata = jsonResult.rows;
         // $('#salesel').attr('enabled', 'true');
         $.each(Jdata, function (i, o) {
         
         var Jfield = JSON.parse(o.data);
         var customerACR = Jfield.CustomerACR;
         if (Jfield.completed == true) {
         $('#salesel').append(
         '<option>' + o.salesorder + '|' + customerACR + '</option>'
         ).trigger("chosen:updated");
         }
         });
         }); */
        $('#salesel').on('change', function () {
            $('#create').removeAttr('disabled');
        });
        var jsonArr = [];
        var postdata = {};
        var reponse = {};
        var url = '/SPOT/provisioning/includes/setAttr.php';
        $('#create').on('click', function (e) {
            $("#DataTable").html('');
            e.preventDefault();
            var salesorder = $("#salesel option:selected").text();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            // Get all data from DB x salesorder
            $.get("/SPOT/provisioning/api/provisioningnotificationses?Notifid_IsLike=" + SO, function (jsonResult) {
                jsonArr = jsonResult['rows'];
                $('.results').show();

                $.each(jsonArr, function (index, linesObj) {
                    var serial = $.trim(linesObj.serial);
                    var hostname = $.trim(linesObj.hostname);
                    var ipaddress = $.trim(linesObj.configuredip);
                    var ram = $.trim(linesObj.ram);
                    var cpu = $.trim(linesObj.cpu);
                    var os = $.trim(linesObj.image);
                    os = os.replace('DEPLOY', '');
                    os =  $.trim(linesObj.os) + ' - ' + os;
                    postdata = {
                        serial: serial,
                        hostname: hostname,
                        ipaddress: ipaddress,
                        ram: ram,
                        cpu: cpu,
                        os: os

                    };
                    //Ready to set attributes to  Sysprod DB

                    $('#results').html('<center><img src="/SPOT/provisioning/images/loader.gif" /></center>');
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: postdata,
                        wait: true,
                        success: function (data) {



                            $('#results').html('');


                            $("#DataTable").append('<p>S/N: ' + serial + ', IP: ' + ipaddress + ', Hostname: ' + hostname + ' Message from server:</p> ' + data);
                        }
                    });

                });





            });

        });
        $('.results').hide();
        $('#msg').hide();
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Send stored attributes


    </h1>
    <div class="row-fluid" >
        <div class=" ui-state-highlight ">Get all values like hostnames, IPs, number of CPUs, RAM qty that this application have stored in the internal 
            DB and try to set the corresponding specifications in syslog. 
            The crossing KEY is the serial number of the machine.
        </div>
    </div>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="stselection table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th colspan="2">
                <label for="salesel"><strong>
                        Select a stored SO
                    </strong>
                </label>
            </th>
        </tr>
        <tr class="salesel">
            <td>
                <select class="chosen" id="salesel" name="salesel" required autofocus="autofocus">
                    <option value="">
                        Select a sales order
                    </option>

                </select>
            </td>
            <td>
                <button class="btn btn-success btn-mini" disabled="disabled" id="create">
                    Send to Sysprod DB the attributes
                </button>
            </td>

        </tr>



    </table>


    <table class="table table-bordered table-striped table-responsive results">
        <tr>
            <th>
                <label for="results"> <strong> Results </strong></label>
            </th>
        </tr>
        <tr>
            <td id="results">


            </td>
        </tr>
        <tr>
            <td>
                <div  id="DataTable">

                </div>

            </td>
        </tr>
    </table>
</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>