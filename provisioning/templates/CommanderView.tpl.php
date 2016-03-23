<?php

$this->assign('title', 'SPOT | Run a command/script on remote host');
$this->assign('nav', 'commander');

$this->display('_Header.tpl.php');
?>

<script>
    $(document).ready(function () {

        $('.host').chosen();
        $('.user').chosen();
        $('#run').on('click', function (e) {
            $('#msg').hide();
            if ($('.host option:selected').val() === "start" && $.trim($('.command').val()) === "") {
                $('#msg').html('<strong>Please fill all fields!</strong>').show();
                return

            }
            var url = "/SPOT/provisioning/api/remotecommands/";
            $("#DataTable").html('');
            $("#modalMonitor").show();
            e.preventDefault();
            var command = $('#command').val();
            var scriptID = 100; // the scriptID runCommander
            var rack = '25';
            var shelf = 'Z';
            var clientaddress = $(".host option:selected").val();

            var user = $(".user option:selected").text();
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
                wait: true,
                success: createTR,
                error: function (data) {
                    $('#msg').html("An error occured:  " + data.statusText + " " + data.responseText);
                    console.log(data);
                    $('#msg').show();

                }
            }).done(monitoring);

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
                var host = data.clientaddress;
                var url = "/SPOT/provisioning/api/remotecommands/" + commandId

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (data) {

                        var arguments = data.arguments;
                        var error = data.returncode;
                        //stdout ID if you want the modal to display
                        $('#stdout' + commandId).html('<tr><th>Running command ' + arguments + ' on host '+host+'</th></tr><tr><td><pre class="prettyprint">' + data.returnstdout + "</pre><code> " + data.returnstderr + '</code><code>Exit code: '+error+'</code></pre></td><tr>');
                    }
                });
            }, 4000);
        }
        ;

        $('#msg').hide();

    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Run a command/script on remote host


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="table-bordered table-responsive table table-striped">


        <tr>
            <th colspan="2">
        <div class="row-fluid" >
            <div class=" ui-state-highlight ">Select a host from the list below and copy/paste your pattern.
            </div>
            Examples:
            <div class="ui-state-highlight"><code> ls -la /home </code> <cite class="pull-right">Simple command</cite></div>
            <div class=" ui-state-highlight "><code> for i in a b c;do echo $i;done </code> <cite class="pull-right">Oneliner loop </cite></div>
            <div class=" ui-state-highlight "><code> winexe -U administrator%***REMOVED*** //10.0.142.31 "cmd /c dir" </code><cite class="pull-right">Complex command with double quotes</cite></div>
        </div>
        </th>
        </tr>
        <tr>
            <td>
                <select class="host"  required autofocus="autofocus">
                    <option value="start">Select Host....</option>
                    <option  value="x.x.x.204">mgt-ai</option>
                    <option value="192.168.1.17">mon01</option>
                    <option value="x.x.x.203">drbl01</option>
                    <option value="x.x.x.25">sysprodai01</option>
                    <option value="x.x.x.26">sysprodai02</option>
                </select>
                <br />
                <select class="user"  required autofocus="autofocus">
                    <option value="root">root</option>
                    <option  value="operator">operator</option>
                    <option value="sysprod">sysprod</option>
                </select>.

            </td>
            <td>

                <textarea id="command" placeholder="copy and paste your command/script" class="ui-resizable"></textarea>
            </td>



        </tr>
        <th colspan="2">
            <button class="btn btn-success btn-mini create pull-left"  id="run">
                Start the command
            </button>

        </th>


    </table>

    <div id="modalMonitor" class="hide">

        <h3>
            <i class="icon-road"></i> Running Command

        </h3>


        <div id="monitorContainer"></div>

    </div>
</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>