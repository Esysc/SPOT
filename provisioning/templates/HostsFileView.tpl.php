<?php
$this->assign('title', 'SPOT | Generate hosts file');
$this->assign('nav', 'hostsfile');

$this->display('_Header.tpl.php');
?>
<link href="bootstrap/css/jquery-labelauty.css" rel="stylesheet" />

<script>

    $(document).ready(function () {
        var subnet = $('.subnet');
        subnet.chosen({allow_single_deselect: true, });
        subnet.on('change', function () {
            subnet.trigger('chosen:updated');
            // console.log('change')
            $("#exportIpam").hide();
            if (subnet.val() !== "") {
                $('#subnet').val(subnet.val());
                $("#exportIpam").show();
            }
        });

        // lOAD RESUTLS FROM IPAM 
        // console.log("<?php echo $_SESSION['token']; ?>")
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "/SPOT/ipam/api/SYS01/sections/1/subnets/",
            "method": "GET",
            "headers": {
                "token": "<?php echo $_SESSION['token']; ?>",
                "cache-control": "no-cache",
                "postman-token": "64638560-aa42-f5d7-871d-b885334d4e37"
            }
        }
        $('.loader').html(' <img src="/SPOT/provisioning/images/loader.gif" />').attr({title: "Loading subnets from NAGRA ipam"});
        $.ajax(settings).done(function (response) {
            $('.loader').html('');
            subnet
                    .append($('<option>', {value: ""})
                            .text(""));

            $.each(response.data, function (obj) {
                subnet
                        .append($('<option>', {value: response.data[obj].id})
                                .text(response.data[obj].subnet));

            })
            $(".subnet option").each(function () {
                if ($(this).text() === $('#subnet').val())
                    $(this).attr("selected", "selected");
            });
            // trigger the update
            subnet.trigger("chosen:updated");
        });
        // Get all the sales order in the tblprogress table
        $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
            var Jdata = jsonResult.rows;
            //console.log(Jdata);
            // $('#salesel').attr('enabled', 'true');
            $.each(Jdata, function (i, o) {

                var Jfield = JSON.parse(o.data);
                var customerACR = Jfield.CustomerACR;
                if (Jfield.completed == true) {

                    $('#salesel').append(
                            '<option value="' + o.salesorder + '|' + customerACR + ' ">' + o.salesorder + '|' + customerACR + '</option>'
                            );
                }
            });
            $('#salesel').chosen();
        });

        var SO;

        $('#salesel').on('change', function () {
            $('.extraHosts').remove();
            var salesorder = $('#salesel').val();
            var SOarr = salesorder.split('|');
            SO = SOarr[0].trim();
            var ACR = SOarr[1].trim();
            $('#salesorder').val('Salesorder_' + SO + '_Customer_' + ACR);
            $.ajax({
                type: "GET",
                url: "/SPOT/provisioning/api/tblprogresses?salesorder=" + SO,
                success: function (jsonResult) {
                    var Jdata = jsonResult.rows[0].data;
                    var Jsonspecs = JSON.parse(Jdata);
                    $('#subnet').val(Jsonspecs.network).trigger('chosen:updated)');

                    $.each(Jsonspecs.clients, function (i, o) {

                        $('<div/>', {
                            'class': 'extraHosts', html: GetHtml(i)
                        }).appendTo('#container');
                        $("input[name^=ipaddress]:eq(" + i + ")").val(o.ip);
                        $("input[name^=hostname]:eq(" + i + ")").val(o.hostname);

                    });

                    // trigger the update
                    $(".subnet option").filter(function () {
                        //may want to use $.trim in here
                        return $(this).text() == $('#subnet').val();
                    }).attr('selected', true).trigger('chosen:updated');



                    var n = $(".extraHosts").length;
                    if (n > 0) {
                        $('#export').show();
                    } else {
                        $('#export').hide();
                    }

                  
                }
            });

           



        });
        $('<div/>', {
            'class': 'extraHosts', html: GetHtml()
        }).appendTo('#container');
        $('#addRow').click(function (event) {
            event.preventDefault();

            $('<div/>', {
                'class': 'extraHosts', html: GetHtml('', '')
            }).hide().appendTo('#container').slideDown('slow');
            var n = $(".extraHosts").length;
            if (n > 0) {
                $('#export').show();
            } else {
                $('#exportHosts').hide();
            }

        });

        $(document).on('click', '.delRow', function (event) {
            event.preventDefault();
            var parent = $(this).parent()
            //Remove two levels of parent
            parent.parent().remove();

            var n = $(".extraHosts").length;
            if (n > 0) {
                $('#export').show();
            } else {
                $('#export').hide();
            }

        });

        function GetHtml(i)
        {
            if (typeof i === 'undefined' || i === '')
                var i = $('.extraHosts').length + 1;


            var $html = $('.extraHostsTemplate').clone();

            //console.log(i)

            $html.find('[name^=ipaddress]')[0].name = "ipaddress[" + i + "]";
            $html.find('[name^=hostname]')[0].name = "hostname[" + i + "]";
            $html.find('[name^=vlan]')[0].name = "vlan[" + i + "]";
            return $html.html();
        }
        $('.extraHosts').remove();
        $('#export').hide();
        $('#exportHosts').on('click', function (event) {
            event.preventDefault();

            var filename = $('#salesorder').val();

            var frm = $('#formHosts').serialize();
            var data = frm + '&salesorder=' + filename;

            $.ajax({
                type: "POST",
                url: "includes/export_hosts_file.php",
                data: frm + '&salesorder=' + filename,
                success: function (response) {
                    var data = JSON.parse(response);
                    var url = data.url;
                    var filename = data.filename;

                    $("#secretIFrame").attr("src", "includes/downloadStoredFile.php?url=" + url + "&filename=" + filename);
                }
            });
        });
        $('#exportLabels').on('click', function (event) {
            event.preventDefault();

            var filename = $('#salesorder').val();

            var frm = $('#formHosts').serialize();
            var data = frm + '&salesorder=' + filename + "&label=export";

            $.ajax({
                type: "POST",
                url: "includes/loadSession.php",
                data: data,
                success: function (data) {

                    var url = 'libs/App/excellabelexport.php?title=' + filename + '.xlsx';

                    window.location.href = url;


                }
            });
        });

        $('#exportIpam').on('click', function (event) {
            event.preventDefault();
            var c = 0;
            $('#message').html('').hide();
            $('#errormsg').html('').hide();
            $('.loader').html(' <img src="/SPOT/provisioning/images/loader.gif" />').attr({title: "Creating hosts in IPAM inventory....."});
            //$('.subnet').trigger('change')
            var subnetId = $('#subnet').val();
            //console.log('subnetID: '+ subnetId);

            $('.ipaddress').each(function () {

                var ipaddress = $(this).val();
                if (ipaddress !== "") {
                    var hostname = $(this).nextAll('input').first().focus().val();

                    var settings = {
                        "async": true,
                        "crossDomain": true,
                        "url": "/SPOT/ipam/api/SYS01/addresses/create/?subnetId=" + subnetId + "&hostname=" + hostname + "&description=Added from SPOT&ip_addr=" + ipaddress,
                        dataType: 'json',
                        "type": "POST",
                        "headers": {
                            "token": "<?php echo $_SESSION['token']; ?>",
                            "cache-control": "no-cache",
                            "postman-token": "64638560-aa42-f5d7-871d-b885334d4e37"
                        },
                        "success": function (obj) {

                            $('#message').append("<p>" + ipaddress + " " + obj.data + "</p>").show();
                        },
                        "error": function (xhr, status, error) {

                            $('#errormsg').append("<p>" + ipaddress + " " + xhr.responseText + "</p>").show();
                        }

                    }

                    $.ajax(settings).done();
                    c++;
                }

            });
            $('.loader').html('');

            $('#message').append("<p>" + c + " IP addresses sent to IPAM</p>").show();
        });


    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Generate Hosts file


    </h1>

    <div class="alert alert-danger" id="errormsg" role="alert" style="display:none"></div>
    <div id="message" class="alert alert-success" role="alert" style="display:none"></div>
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
                <select  class="subnet" data-placeholder="Choose the subnet" >

                </select>
                <span class="loader"></span>
                <input type="hidden" id="subnet" />

                <p class="help-inline pull-right"><span class="icon-info-sign" > Used only to add hosts on IPAM</span></p>
            </td>

        </tr>



    </table>


    <form id="formHosts"  onsubmit="return false;">
        <input type="hidden" id="salesorder" />
        <input type="hidden" id="tmp" />
        <table class="main table-bordered table-responsive table table-striped">
            <tr class="network"><th colspan="2">

            <div id="export">
                <center>Hosts in database</center>
                <button class='pull-right btn btn-primary' id='exportHosts'>Export hosts file</button>

                <button class='pull-left btn btn-success' id='exportLabels'>Create Excel for Labels</button>
                <center><button class='btn btn-warning' id='exportIpam'>Add hosts to IPAM</button></center>
            </div>
            </th></tr>



        </table>




        <div class="extraHostsTemplate">
            <div class="controls controls-row">

                <a href="#" class="delRow pull-right"><i class="icon-minus-sign icon-white"></i> Delete this line</a>
                <input class="span3 ipaddress" placeholder="IP address" type="text"   name="ipaddress">
                <input class="span3 hostname" placeholder="Hostname" type="text"   name="hostname">
                <input class="span3" placeholder="Vlan name" type="text"   name="vlan" value="CTRL">

            </div>

        </div>

        <table class="main  table-responsive table table-striped" id="container"></table>
        <a href="#" id="addRow"><i class="icon-plus-sign icon-white"></i> Add a line</a>

        <input type="hidden" id="salesorder" />
    </form>
</div> <!-- /container -->
<style>
    .extraHostsTemplate {
        display:none;
    }
</style>
<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>
<?php
$this->display('_Footer.tpl.php');
?>
