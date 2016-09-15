<?php
$this->assign('title', 'SPOT | Install Puppet server');
$this->assign('nav', 'instpup');

$this->display('_Header.tpl.php');
?>
<link href="bootstrap/css/jquery-labelauty.css" rel="stylesheet" />

<script>

    $(document).ready(function () {

        $('.release').hide();
        $('#msg').hide();
        $('.network').hide();
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
                        var gateway = o.gateway;
                        $('#gateway').val(gateway);
                    }

                });
            });
        });
        $(document).on('click', '#install', function (e) {

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

            $('#install').attr('disabled', 'disabled');

            // return false
            var ipaddress = " -i " + $('#ipaddress').val();
            var gateway = " -g " + $('#gateway').val();
            var release = " -r " + $('#release').val();
            var datastring;
            var args = {
                "0": ipaddress,
                "1": gateway,
                "2": release,
                "3": ' &'
            };
            var scriptID = 31; //puppet install script id
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var rack = 100;

            var exesequence = 0; //putting zero no return of stdout and stderr
            var executionFlag = 1;
            var counter = 1;
            //Ok all values prsed, can we proceed to send to remote servers

            datastring = JSON.stringify(args);

            var salesOrder = SO;
            var command = {
                salesorder: salesOrder,
                rack: rack,
                shelf: 'Z',
                clientaddress: clientaddress,
                arguments: datastring,
                exesequence: exesequence,
                returnstdout: "Waiting for command execution",
                executionflag: executionFlag,
                scriptid: scriptID

            }
            var Jcommand = JSON.stringify(command);

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

                    $('#errormsg').show();

                }
            }).done(monitoring);

            counter++;
        });


        function createTR(data) {
            $('#monitorContainer').show();
            var commandId = data.remotecommandid;
            var e = $('<table id="stdout' + commandId + '" class="table-bordered table-responsive table table-striped"></table>');
            $('#monitorContainer').append(e);
        }
        ;
        var div = '<div class="blinking alert alert-danger">Starting execution.....</div>';
        function monitoring(data) {
            setInterval(function () {

                var commandId = data.remotecommandid;
                var url = "/SPOT/provisioning/api/remotecommands/" + commandId

                $.ajax({
                    url: url,
                    type: "GET",
                    async: false,
                    cache: false,
                    timeout: 30000,
                    error: function () {
                        return true;
                    },
                    success: function (data) {
                        var error = data.returncode;
                        var arguments = data.arguments;

                        //Check in tempdata if program is running or not
                        $.get("/SPOT/provisioning/api/tempdata/" + commandId, function (a) {
                            if (typeof a === 'object' && typeof a.message !== 'undefined')
                                div = a.message;

                        });
                        //stdout ID if you want the modal to display
                        $('#stdout' + commandId).html('<tr><th>Running command ' + arguments + '<br />' + div + '</th></tr><tr><td><pre class="prettyprint">' + data.returnstdout + "</pre><code> " + data.returnstderr + '</code><code>Exit code: ' + error + '</code></pre></td><tr>');
                    }
                });
            }, 4000);
        }
        ;

    });
</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Prepare Puppet server


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <div class="alert alert-danger" id="errormsg" role="alert" style="display:none"></div>
    <div id="message" class="alert alert-success" role="alert" style="display:none"></div>
    <form id="form" role="form" onsubmit="return false;">
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
                        <input type="text"  id="release"  value="" required />

                    </div>
                </td>

            </tr>



        </table>





        <table class="main table-bordered table-responsive table table-striped">
            <tr class="network">
                <th colspan="2">
            <center>
                NETWORKING
            </center>
            </th>
            </tr>

            <tr class="network">

                <th><label for="ipaddress" class="sr-only" ><b>IP address of the puppet candidate </b></label></th>

                <th>
                    <label for="netmask" class="sr-only"><b>Default Gateway</b></label>
                </th>

            </tr>
            <tr class="network">

                <td>
                    <input name="ipaddress" id="ipaddress" class="form-control ipaddress" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" />   

                </td>
                <td>
                    <input name="gateway" id="gateway" class="form-control gateway" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$"  />
                </td>

            </tr>
            <tr  class="network">
                <td colspan="2">
            <center>
                <button class='btn btn-primary ' id='install'>Install !</button>
            </center>
            </td>
            </tr>

        </table>
    </form>
    <div id="monitorContainer" hidden>


        <h3>
            <i class="icon-road"></i> Puppet installation 

        </h3>

    </div>

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
