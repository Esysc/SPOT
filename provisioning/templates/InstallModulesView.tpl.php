<?php
$this->assign('title', 'SPOT | Prepare machines for CCT');
$this->assign('nav', 'instmod');

$this->display('_Header.tpl.php');
?>
<style>
    fieldset {
        width: 50%;
    }
    label {
        float: left;
    }
    .item {
        float: right;
        width: 100%;
    }
    br {
        display: block;
        margin: 10px 0;
    }
    p {
        width: 100%;
        font-weight: bold;
            
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
            $(document).on('click', '.checkrelease', function (e) {
                $('#relpath').hide();
                e.preventDefault();
                $('#release').prop('disabled', true);
                release = $('#release').val();
                $('#check').html("<img src='/SPOT/provisioning/images/loader.gif' alt='Waiting for script execution, please be patient.....' title='Waiting for script execution, please be patient.....'/>");
                var scriptID = 22; // the scriptID for find release path
                var rack = '25';
                var shelf = 'Z';
                var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
                var exesequence = 1;
                var executionFlag = 0;
                var argument = {
                    "0": release
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
                    url: "/SPOT/provisioning/api/remotecommands",
                    type: "POST",
                    data: Jcommand,
                    wait: true,
                    success: function (data) {
                        var commandID = data.remotecommandid
                        var url = "/SPOT/provisioning/api/remotecommands/" + commandID;
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function (data) {


                                switch (data.executionflag) {
                                    case '1':
                                        if ($.trim(data.returnstdout) !== '' && typeof data.returnstdout !== 'undefined') {
                                            $('#check').html("Please be patient, loading modules and servers..  <img src='/SPOT/provisioning/images/loader.gif' alt='Waiting for script execution, please be patient.....' title='Waiting for script execution, please be patient.....'/>");
                                            $('#release').val(data.returnstdout);
                                            $('#release').hide();
                                            $('select').hide();
                                            $('#relpath').html('<strong>' + data.returnstdout + '</strong>');
                                            $('#relpath').addClass('alert-success');
                                            $('#relpath').show();
                                            // Get all the machines in sales order
                                            scriptID = 23; // the scriptID for find release path
                                            argument = {
                                                "0": data.returnstdout
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
                                                url: "/SPOT/provisioning/api/remotecommands",
                                                type: "POST",
                                                data: Jcommand,
                                                wait: true,
                                                success: function (data) {
                                                    commandID = data.remotecommandid;
                                                    url = "/SPOT/provisioning/api/remotecommands/" + commandID;
                                                    $.ajax({
                                                        url: url,
                                                        type: "GET",
                                                        success: function (data) {

                                                            switch (data.executionflag) {
                                                                case '1':
                                                                    $('#check').html('');
                                                                    var modules = data.returnstdout;
                                                                    var MODarr = modules.split(' ');
                                                                    $.get("/SPOT/provisioning/api/tblprogresses?salesorder=" + SO, function (jsonResult) {
                                                                        $('.network').show();
                                                                        var Jdata = jsonResult.rows[0].data;
                                                                        var Jsonspecs = JSON.parse(Jdata);
                                                                        var tr;
                                                                        tr = ("<tr><th><center>HOSTS</center></th><th><center>MODULES</center></th></tr>");
                                                                        $.each(Jsonspecs.clients, function (i, o) {

                                                                            tr = tr + "<tr><th>" + o.hostname + "<br />IP: " + o.ip + "</th>";
                                                                            tr = tr + "<td>";
                                                                            tr = tr + "<fieldset><input type='hidden' value='" + o.ip + "' class='ipaddress' id='host_" + i + "' />";
                                                                            $.each(MODarr, function (index, value) {
                                                                                var Ychecked = "";
                                                                                var Nchecked = "checked";
                                                                                var mod = baseName(value);
                                                                                if (value.toLowerCase().indexOf("mgt") < 0) {
                                                                                    if (value.toLowerCase().indexOf("nse") >= 0) {
                                                                                        Ychecked = 'checked';
                                                                                        Nchecked = "";
                                                                                    }
                                                                                    tr = tr + "<div class='item'>";
                                                                                    tr = tr + "<label class='checkbox'><input class='checkbox-inline' type='checkbox' value='" + value + "'  id='host_" + i + "_" + index + "' checked/>";
                                                                                    tr = tr + "" + mod + "</label>";
                                                                                    tr = tr + '<label class="radio-inline pull-right">Install ? <input name="optionInst_' + i + "_" + index + '" ' + Ychecked + ' type="radio"  class="host_' + i + '_' + index + '" value="Y" > Yes&nbsp;';
                                                                                    tr = tr + '<input name="optionInst_' + i + "_" + index + '" ' + Nchecked + ' type="radio"  value="N" class="host_' + i + '_' + index + '">  No</label></div>';
                                                                                }
                                                                            });
                                                                            tr = tr + "</fieldset>";
                                                                            tr = tr + "</td></tr>";
                                                                        });
                                                                        tr = tr + "<tr><th colspan='2'><button class='pull-right btn btn-primary' id='start'>Start !</button></th></tr>"
                                                                        $('.main tr:last').after(tr);
                                                                        console.log(tr);
                                                                    });
                                                                    break;
                                                                case '2':
                                                                    $('#check').html('');
                                                                    $('#release').hide();
                                                                    $('#relpath').html('<strong>The remote script execution return an error, please contact the site administrator to debug</strong>');
                                                                    $('#relpath').addClass('alert-error');
                                                                    $('#relpath').show();
                                                                    break;
                                                                default:
                                                                    $.ajax(this);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        } else {
                                            $('#check').html('<a href="javascript: void(0)"><i class="checkrelease icon-forward "></a></i>Check release path');
                                            $('#release').show();
                                            $('#release').prop('disabled', false);
                                            $('#relpath').html('<strong>Release not found. check the name and try again</strong>');
                                            $('#relpath').addClass('alert-error');
                                            $('#relpath').show();
                                        }
                                        break;
                                    case '2':
                                        $('#check').html('');
                                        $('#release').hide();
                                        $('#relpath').html('<strong>The remote script execution return an error, please contact the site administrator to debug</strong>');
                                        $('#relpath').addClass('alert-error');
                                        $('#relpath').show();
                                        break;
                                    case '0':
                                        $.ajax(this);
                                }


                            }
                        });
                    },
                    error: function (data) {
                        $('#errormsg').html("An error occured:  " + data.statusText + " " + data.responseText);
                        console.log(data);
                        $('#errormsg').show();
                        setTimeout(function () {
                            $('#errormsg').fadeOut(3000);
                        }, 4000);
                    }
                });
            });
        });
        // $('#salesel').trigger("change")
        //   $('*[required]').before("<span class='icon-star' style='color:red'></span>");



        $(document).on('click', '#start', function (e) {
            if ($('*[required]').val() === '') {

                $('#msg').hide();
                $('#msg').html("Please fill all the required fields")

                $('#msg').show();
                setTimeout(function () {
                    $('#msg').fadeOut(2000);
                }, 3000);
                console.log($('*[required]'));
                return false;
            } else {
                $('#msg').hide();
                validate(e);
            }
            $('#start').after("<button class='pull-right btn btn-primary' id='Results'>Get Results !</button>");
            $('#start').remove();
            var ipaddress = "-ip " + $('#ipaddress').val();
            var netmask = "-n " + $('#netmask').val();
            var ipaliasID = 7;
            var datastring;
            var args = {
                "0": ipaddress,
                "1": netmask,
            };
            var argsIP = JSON.stringify(args);
            //  validate(e);
            var hosts = [];
            var modules = [];
            var install = [];
            var options = [];
            $('fieldset').each(function () {
                var input = $(this).find(' input.ipaddress');
                var host = "-H " + input.val();
                hosts.push(host);
                $(this).find('input.checkbox-inline:checked').each(function () {
                    var module = $(this).val();
                    var mod = baseName(module);
                    if (mod.toLowerCase().indexOf("nse") >= 0)
                        options.push('-s nsesoft -b nse1 -u root');
                    if (mod.toLowerCase().indexOf("nge") >= 0)
                        options.push('-s ngesoft -b nge1 -u operator');
                    if (mod.toLowerCase().indexOf("nre") >= 0)
                        options.push('-s nresoft -b nre1 -u root');
                    if (mod.toLowerCase().indexOf("nsm") >= 0)
                        options.push('-s nsmsoft -b nsm1 -u operator');
                    if (mod.toLowerCase().indexOf("noe") >= 0)
                        options.push('-s noesoft -b noe1 -u oracle');
                    modules.push("-M " + module);
                    var id = $(this).attr('id');
                    //The id of checkboxes is the class of the radio button

                    var check = $('.' + id + ':checked').val();
                    install.push("-I " + check);
                });
            });
            var scriptID = 13;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var rack = 25;
            var shelf = "Z";
            var exesequence = 1;
            var executionFlag = 1;
            
            //Ok all values prsed, can we proceed to send to remote servers
            $.each(hosts, function (index, host) {
                var argstring = {};
                $.each(modules, function (index1, module) {
                    argstring = {
                        "0": host,
                        "1": module,
                        "2": install[index1],
                        "3": options[index1],
                        "4": " &"
                    };
                    datastring = JSON.stringify(argstring);
                     var number = 1 + Math.floor(Math.random() * 6);
                    var command = {
                        salesorder: number,
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
                });
            });
            // Send set alias IP command as last command because will be the first to be run
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
                var e = $('<table id="stdout' + commandId + '" class="able-bordered table-responsive table table-striped"></table>');

                $('#servermsg').append(e);
             
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
                            var arguments = data.arguments;
                            var parsed = $.parseJSON(arguments);
                            var arr = [];
                                    $.each(parsed , function(i, val) {
                                        arr.push(val);
                                    });
                                    var host = arr[0].split('H');
                                    var command = baseName(arr[1]);
                            
                            $('#stdout' + commandId).html('<tr><th>Host</th><th>Module</th><th>Params</th><th>Return messages</th></tr><tr><td>' + host[1] + '</td><td>'+command+'</td><td>' + arr[3] +'</td><td>' + data.returnstdout + " " + data.returnstderr+'</td><tr>');
                        }
                    });
                }, 4000);
            }
            ;
            $(document).on('click', '#Results', function (e) {
                $('#basicModal').modal();
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


</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
