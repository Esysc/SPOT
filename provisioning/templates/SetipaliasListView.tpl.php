<?php
$this->assign('title', 'SPOT | SetIPAlias');
$this->assign('nav', 'setipalias');

$this->display('_Header.tpl.php');
?>

<script>
    $(document).ready(function() {


 
        var scriptID = 10;
        var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
        var rack = 25;
        var shelf = "Z";
        var salesorder = 11111111;
        var exesequence = 1;
        var executionFlag = 0;
        var aliascmd = {
            salesorder: salesorder,
            rack: rack,
            shelf: shelf,
            clientaddress: clientaddress,
            exesequence: exesequence,
            returnstdout: "Waiting alias IP list",
            executionflag: executionFlag,
            scriptid: scriptID

        }
        var Jcommand = JSON.stringify(aliascmd);
        var url = "/SPOT/provisioning/api/remotecommands/";
        //Post the remote command to get executed

        $.ajax({
            url: url,
            type: "POST",
            data: Jcommand,
            wait: true,
            success: function(data) {
                // reset the html document, show success message and reload button
                $('#message').html("Waiting for alias IP listing   <img src='/SPOT/provisioning/images/loader.gif' />");
                $('#message').show();
                setTimeout(function() {

                }, 12000);
                var commandID = data.remotecommandid
                var url = "/SPOT/provisioning/api/remotecommands/" + commandID;
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(data) {
                        console.log(data.executionflag);
                        var reponse = data.executionflag;
                        if (reponse == 0) {

                            $.ajax(this);
                            console.log('Retry the request');
                        } else {
                            $('#message').html("IP listing successfully retrieved");
                            $('#message').fadeOut(2000);

                            var dataarr = data.returnstdout.split('|');
                            console.log(dataarr);
                            var htmlDiv = ' <table class="table-bordered table-responsive table table-striped"><tr><th colspan="3" class="alert alert-info"><center><span class="icon-signal"</span><strong> Alias IP List currently on MGT server</strong></center></th><th colspan="3" class="alert alert-info"><center><strong><span class="icon-trash"></span> Click on the tab you want to remove</strong></center></th><tr>';
                            var htmlDiv = htmlDiv + '<tr>';
                            var count = dataarr.length -1;
                            
                            var even = 0;
                            $.each(dataarr, function(i, val) {

                                if (val.trim()) {

                                    if (i % 6 === 0) {
                                        console.log('index module 6');
                                        htmlDiv = htmlDiv + '</tr><tr>';
                                    }
                                   
                                    htmlDiv = htmlDiv + '<td class="ipalias alert  alert-success"  >' + val + '<span class="btn icon-trash"></span></td>';
                                }
                            });
                          
                            even = 6 - (count % 6);
                            
                            if ( even > 0  ) {
                           
                            htmlDiv = htmlDiv + '<td colspan="'+even+'" class="alert  alert-success"></td>';
                            }
                            htmlDiv = htmlDiv + '</tr></table>';
                            $('#alias').html(htmlDiv);
                            
                            $(document).find('.ipalias').on("click", function() {


                                var scriptID = 13;
                                var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
                                var value = $(this).text();
                                var arr = value.split('-->');
                                var arr2 = arr[0].split(' ');
                                var interfacename = arr2[0].trim();
                                var alias = arr2[1].trim();
                               
                                var rack = 25;
                                var shelf = "Z";
                                var salesorder = 11111111;
                                var exesequence = 1;
                                var executionFlag = 0;
                                var argstring = {
                                    "0": "-eth",
                                    "1": interfacename,
                                    "2": "-alias",
                                    "3": alias

                                };
                                var datastring = JSON.stringify(argstring);
                                var command = {
                                    salesorder: salesorder,
                                    rack: rack,
                                    shelf: shelf,
                                    clientaddress: clientaddress,
                                    arguments: datastring,
                                    exesequence: exesequence,
                                    returnstdout: "Waiting for command execution",
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
                                    wait: true,
                                    success: function(data) {
                                        $('#servermsg').html('Successfully sent the command to detach ' + interfacename  + ':' + alias + '. Reload the <a href="'+window.location.href+'" class="reload">Page</a> to check the results.');
                                        $('#basicModal').modal();
                                        $('.reload').on('click', function(e) {
                                            e.preventDefault();
                                            $('#servermsg').html('Waiting for page reloading.........');
                                            
                                            setTimeout(function() 
                                            { window.location = window.location.href; }, 10000);
                                        });
                                    }
                                });

                            });


                        }
                    }
                });
            },
            error: function(data) {
                $('#errormsg').html("An error occured:  " + data.statusText + " " + data.responseText);
                console.log(data);
                $('#errormsg').show();
                setTimeout(function() {
                    $('#errormsg').fadeOut(3000);
                }, 4000);
            }
        });
        $('*[required="required"]').before("<span class='icon-star' style='color:red'></span>");
        $('#newaliasButton').on('click', function(e) {

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
                setTimeout(function() {
                    $('#errormsg').fadeOut(2000);
                }, 3000);
                console.log($('*[required="required"]'));
                return false;
            } else {
                $('#errormsg').hide();
            }

            var scriptID = 7;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var ipaddress = $('#ipaddress').val();
            var netmask = $('#netmask').val();
            var rack = 25;
            var shelf = "Z";
            var salesorder = 11111111;
            var exesequence = 1;
            var executionFlag = 0;
            var argstring = {
                "0": "-ip",
                "1": ipaddress,
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
                success: function(data) {
                    // reset the html document, show success message and reload button
                    $('#message').html("The command has been succesfully sent. <button class='btn btn-primary' onclick='location.reload();'>Add another alias</button> or stay here and wait for command execution message.");
                    $('#message').show();
                    setTimeout(function() {
                        $('#message').fadeOut(10000);
                    }, 12000);
                    $('#newaliasButton').hide();
                    $('.collection').hide();
                    var commandID = data.remotecommandid
                    var url = "/SPOT/provisioning/api/remotecommands/" + commandID;
                    setInterval(function() {
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function(data) {
                                $('.monitor').show();
                                $('#stdout').html(data.returnstdout + " " + data.returnstderr);
                            }
                        });
                    }, 2000);
                },
                error: function(data) {
                    $('#errormsg').html("An error occured:  " + data.statusText + " " + data.responseText);
                    console.log(data);
                    $('#errormsg').show();
                    setTimeout(function() {
                        $('#errormsg').fadeOut(3000);
                    }, 4000);
                }
            });
        });
    });
</script>
<div class="container">

    <h1>
        <i class="icon-th-list"></i> Set IP Alias

    </h1>
    <div class="alert alert-danger" id="errormsg" role="alert" style="display:none"></div>
    <div id="message" class="alert alert-success" role="alert" style="display:none"></div>
    <h3 class="ui-widget-header">Set IP Alias on the management workstation CTRL interface - <small class="icon-star" style="color:red"> mark a field as required</small></h3>
     
    <h4 class="alert alert-info">         *tip: download the executable to add a temporary route to your PC. <a href="tools/routeAdd.exe" class="badge badge-inverse">routeAdd.exe</a></h4>
          
        </tr>
    <div id="alias"></div>
    <table  class="collection table table-bordered" >
        <tr>

            <th>
                <span class="icon-signal"></span> IP address
            </th>
            <th>
                <span class="icon-cloud"></span> Netmask
            </th>
        </tr>
       
        <tr>
            
            <td>

                <p><input type="text" name="ipaddress" id="ipaddress" class="ipaddress form-inline" placeholder="Ip address"  required="required" /></p>
            </td>
            <td>
                <p><input type="text" name="netmask" id="netmask" class="netmask form-inline" placeholder="netmask" required="required" value="255.255.255.0"/></p>


            </td>


        </tr>
    </table>
    <table  class="monitor table table-bordered" style="display:none" >
        <tr>

            <th>
                <span class="icon-coffee"></span> Response
            </th>

        </tr>
        <tr>
            <td>

                <p id="stdout"></p>
            </td>

        </tr>
    </table>
    <p id="newButtonContainer" class="buttonContainer">
        <button id="newaliasButton" class="btn btn-primary pull-right">Set Alias</button>
    </p>

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
