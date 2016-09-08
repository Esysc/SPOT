<?php
$this->assign('title', 'SPOT | Install Puppet server');
$this->assign('nav', 'instpup');

$this->display('_Header.tpl.php');
?>
<link href="bootstrap/css/jquery-labelauty.css" rel="stylesheet" />
<style>
    body #modalMonitor {
        /* new custom width */
        width: 100%;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -50%;
    }
</style>
<script>

    $(document).ready(function () {
        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        $('.release').hide();
        $('#msg').hide();
        $('.network').hide();
        $('#disable').removeAttr('href');
        $('#release').on('change', function () {
            var release = $(this).val();
            if (release === '') {
                $('#check').hide();
            } else
            {
                $('#check').show();
            }
        });
        function validate(e) {
            var $myForm = $('form')
            if (!$myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $('#msg').html('Please check the errors!').show().fadeOut(10000);
                $myForm.find(':submit').click();
            }
        }
        function baseName(path)
        {

            return path.split('/').reverse()[0];
        }

        // Get all the sales order in the tblprogress table
        $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
            var Jdata = jsonResult.rows;
            console.log(Jdata);
            // $('#salesel').attr('enabled', 'true');
            $.each(Jdata, function (i, o) {

                var Jfield = JSON.parse(o.data);
                var customerACR = Jfield.CustomerACR;
                var release = "N/D";
                if (typeof Jfield.releasename !== 'undefined' && Jfield.releasename !== '')
                    release = Jfield.releasename;
                if (Jfield.completed == true) {

                    $('#salesel').append(
                            '<option value="' + release + '|' + o.salesorder + '|' + customerACR + ' ">' + o.salesorder + '|' + customerACR + '</option>'
                            );
                }
            });
            $('#salesel').chosen();
        });
        var SO;
        $('#salesel').on('change', function () {

            var salesorder = $('#salesel').val();
            var SOarr = salesorder.split('|');
            var release = SOarr[0].trim();
            $('#release').val(release);
            if (release === 'N/D')
                $('#release').val('');
            $('.release').show();
            SO = SOarr[1].trim();
            var ACR = SOarr[2].trim();
            $.get("/SPOT/provisioning/api/tblprogresses?salesorder=" + SO, function (jsonResult) {
                $('.network').show();
                var Jdata = jsonResult.rows[0].data;
                var Jsonspecs = JSON.parse(Jdata);
                $.each(Jsonspecs.clients, function (i, o) {

                    if (o.gateway !== '') {
                        var ipaddress = o.gateway;
                        $('#ipaddress').val(ipaddress);
                    }
                    if (o.netmask !== '') {
                        var netmask = o.netmask;
                        $('#netmask').val(netmask);
                    }
                });
            });

        });
        $(document).on('click', '#start', function (e) {

            if ($('*[required]').val() === '') {

                $('#msg').hide();
                $('#msg').html("Please fill all the required fields")

                $('#msg').show();
                setTimeout(function () {
                    $('#msg').fadeOut(2000);
                }, 3000);
                return false;
            } else {
                $('#msg').hide();
                validate(e);
            }
            $('#start').after("<button class='pull-right btn btn-primary' id='Results'>Get Results !</button>");
            $('#start').remove();
            $('input[type=checkbox]').prop('disabled', 'true');
            $('.uncheck').remove();
            var ipaddress = " -ip " + $('#ipaddress').val();
            var netmask = " -n " + $('#netmask').val();
            var ipaliasID = 7;
            var datastring;
            var args = {
                "0": ipaddress,
                "1": netmask,
            };
            var argsIP = JSON.stringify(args);
            //  validate(e);
            var input;
            var hosts = [];
            var keys = [];


            $('.items').each(function () {
                var modules;
                var install;
                var options;
                input = $(this).find(' input.ipaddress');
                var host = "-H " + input.val();
                var hostId = input.attr('id');
                var index = $('#' + hostId + '_index').val();
                for (var i = 0; i < index; i++) {
                    var str;
                    if (i == 0) {
                        str = '0';
                    } else {
                        str = i;
                    }

                    var download = hostId + "_download_" + str;
                    var checkId = hostId + "_install_" + str;
                    if ($('#' + download).is(':checked')) {

                        var module = $('#' + download).val().replace(/(\r\n|\n|\r)/gm, "");
                        var mod = baseName(module);
                        if (mod.toLowerCase().indexOf("nse") >= 0)
                            options = ' -s nsesoft -b nse1 -u root ';
                        if (mod.toLowerCase().indexOf("nge") >= 0)
                            options = ' -s ngesoft -b nge1 -u operator ';
                        if (mod.toLowerCase().indexOf("nre") >= 0)
                            options = ' -s nresoft -b nre1 -u root ';
                        if (mod.toLowerCase().indexOf("nsm") >= 0)
                            options = ' -s nsmsoft -b nsm1 -u operator ';
                        if (mod.toLowerCase().indexOf("noe") >= 0)
                            options = ' -s noesoft -b noe1 -u oracle ';
                        modules = " -M " + module + " ";
                        $('#' + checkId).is(':checked') ? install = " -I Yes " : install = " -I No ";

                        hosts.push(host + modules + options + install);
                        keys.push(input.val());

                    }
                }
            });
            console.log(keys);
            var unsetAlias = 13;
            var scriptID = 21;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var rack = 100;

            var exesequence = 0; //putting zero no return of stdout and stderr
            var executionFlag = 1;
            var counter = 1;
            //Ok all values prsed, can we proceed to send to remote servers
            $.each(hosts, function (index, host) {
                var argstring = {};
                var shelf = keys[index];

                argstring = {
                    "0": host,
                    "1": " &"
                };
                datastring = JSON.stringify(argstring);
                var number = index + counter;
                var salesOrder = SO + number;
                var command = {
                    salesorder: salesOrder,
                    rack: rack,
                    shelf: shelf,
                    clientaddress: clientaddress,
                    arguments: datastring,
                    exesequence: exesequence,
                    returnstdout: "Waiting for command execution",
                    executionflag: executionFlag,
                    scriptid: scriptID

                }
                var Jcommand = JSON.stringify(command);
                console.log(Jcommand);
                var url = "/SPOT/provisioning/api/remotecommands/";
                //Post the remote command to get executed
                $.ajax({
                    url: url,
                    type: "POST",
                    data: Jcommand,
                    wait: true,
                    success: createTR,
                    error: function (data) {
                        $('#errormsg').html("An error occured:  " + data.statusText + " " + data.responseText);
                        console.log(data);
                        $('#errormsg').show();

                    }
                }).done(monitoring);

                counter++;
            });
            // Send set alias IP command as last command because will be the first to be run
            var shelf = "Z";
            var command = {
                salesorder: SO,
                rack: rack,
                shelf: shelf,
                clientaddress: clientaddress,
                arguments: datastring,
                exesequence: exesequence,
                returnstdout: "Waiting for command execution",
                executionflag: executionFlag,
                scriptid: ipaliasID

            }
            var Jcommand = JSON.stringify(command);
            var url = "/SPOT/provisioning/api/remotecommands/";
            //Post the remote command to get executed
            $.ajax({
                url: url,
                type: "POST",
                data: Jcommand,
                wait: true,
                error: function (data) {
                    $('#errormsg').html("An error occured:  " + data.statusText + " " + data.responseText);
                    console.log(data);
                    $('#errormsg').show();
                    setTimeout(function () {
                        $('#errormsg').fadeOut(3000);
                    }, 4000);
                }
            });
            function createTR(data) {
                var commandId = data.remotecommandid;
                var e = $('<table id="stdout' + commandId + '" class="table-bordered table-responsive table table-striped"></table>');
                $('#monitorContainer').append(e);
            }
            ;
            function monitoring(data) {
                setInterval(function () {

                    var commandId = data.remotecommandid;
                    var url = "/SPOT/provisioning/api/remotecommands/" + commandId

                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (data) {
                            var error = data.returncode;
                            var arguments = data.arguments;
                            //stdout ID if you want the modal to display
                            $('#stdout' + commandId).html('<tr><th>Running commandt ' + arguments + '</th></tr><tr><td><pre class="prettyprint">' + data.returnstdout + "</pre><code> " + data.returnstderr + '</code><code>Exit code: ' + error + '</code></pre></td><tr>');
                        }
                    });
                }, 4000);
            }
            ;
            $(document).on('click', '#Results', function (e) {
                $('#modalMonitor').modal();
            });
        });
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Prepare machines for CCT


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <div class="alert alert-danger" id="errormsg" role="alert" style="display:none"></div>
    <div id="message" class="alert alert-success" role="alert" style="display:none"></div>
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
                <div class="pull-right release">
                    <input type="text"  id="release"  value="" required />&nbsp;<span id="check"> <a href="javascript: void(0)" id="disable"><i class="checkrelease icon-forward "></a></i>Check release path</span>

                </div>
            </td>

        </tr>



    </table>
    <center><div id="relpath" class="alert" hidden></div></center>

    <form id="form" role="form" onsubmit="return false;">


        <table class="main table-bordered table-responsive table table-striped">
            <tr class="network"><th colspan="2">
            <center>NETWORKING</center>
            </th></tr>

            <tr class="network">

                <th><label for="ipaddress" class="sr-only" ><b>IP address alias to assign </b></label></th>

                <th><label for="netmask"><b>Netmask </b></label></th>

            </tr>
            <tr class="network">

                <td>
                    <input name="ipaddress" id="ipaddress" class="form-control ipaddress" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" />   
                </td>
                <td>
                    <input name="netmask" id="netmask" class="form-control netmask" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" value="255.255.255.0" />
                </td>

            </tr>

        </table>


    </form>
    <button class='pull-right btn btn-primary' id='start'>Start !</button>
    <div class="modal hide fade" id="modalMonitor">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-road"></i> Running Processes

            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="monitorContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>

        </div>
    </div>

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
