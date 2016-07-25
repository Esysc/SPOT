<?php
$this->assign('title', 'SPOT | Generate hosts file');
$this->assign('nav', 'hostsfile');

$this->display('_Header.tpl.php');
?>
<link href="bootstrap/css/jquery-labelauty.css" rel="stylesheet" />

<script>

    $(document).ready(function () {

        //Plugin function to dowload a file on ajax post
        $.download = function (url, data, method) {
            //url and data options required
            if (url && data) {
                //data can be string of parameters or array/object
                data = typeof data == 'string' ? data : $.param(data);
                //split params into form inputs
                var inputs = '';
                $.each(data.split('&'), function () {
                    var pair = this.split('=');
                    inputs += '<input type="hidden" name="' + pair[0] + '" value="' + pair[1] + '" />';
                });
                //send request
                $('<form action="' + url + '" method="' + (method || 'post') + '">' + inputs + '</form>')
                        .appendTo('body').submit().remove();
            }
            ;
        };
        // Get all the sales order in the tblprogress table
        $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
            var Jdata = jsonResult.rows;
            console.log(Jdata);
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
            $.get("/SPOT/provisioning/api/tblprogresses?salesorder=" + SO, function (jsonResult) {
                var Jdata = jsonResult.rows[0].data;
                var Jsonspecs = JSON.parse(Jdata);
                var tr = '';
                $.each(Jsonspecs.clients, function (i, o) {

                    $('<div/>', {
                        'class': 'extraHosts', html: GetHtml(i)
                    }).appendTo('#container');
                    $("input[name^=ipaddress]:eq(" + i + ")").val(o.ip);
                    $("input[name^=hostname]:eq(" + i + ")").val(o.hostname);

                });
                var n = $(".extraHosts").length;
                if (n > 0) {
                    $('#export').show();
                } else {
                    $('#export').hide();
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

            console.log(i)

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


    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Generate Hosts file


    </h1>

    <span class="alert alert-danger" id="errormsg" role="alert" style="display:none"></span>
    <span id="message" class="alert alert-success" role="alert" style="display:none"></span>
    <!-- underscore template for the collection -->

    <table class="stselection table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th>
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

        </tr>



    </table>


    <form id="formHosts"  onsubmit="return false;">
        <input type="hidden" id="salesorder" />

        <table class="main table-bordered table-responsive table table-striped">
            <tr class="network"><th colspan="2">
            <center>Hosts in database</center>
            <div id="export">
                <button class='pull-right btn btn-primary' id='exportHosts'>Export hosts file</button>
                <button class='pull-left btn btn-success' id='exportLabels'>Create Excel for Labels</button>
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
