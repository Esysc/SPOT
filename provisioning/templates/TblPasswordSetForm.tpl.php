<?php
$this->assign('title', 'SPOT | Customize passwords');
$this->assign('nav', 'password');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {
        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        function generatePassword(len) {
            var pwd = [], cc = String.fromCharCode, R = Math.random, rnd, i;
            pwd.push(cc(48 + (0 | R() * 10))); // push a number
            pwd.push(cc(65 + (0 | R() * 26))); // push an upper case letter

            for (i = 2; i < len; i++) {
                rnd = 0 | R() * 62; // generate upper OR lower OR number
                pwd.push(cc(48 + rnd + (rnd > 9 ? 7 : 0) + (rnd > 35 ? 6 : 0)));
            }

            // shuffle letters in password
            return pwd.sort(function () {
                return R() - .5;
            }).join('');
        }

        function validate(e) {
            var $myForm = $('form')
            if (!$myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $('#msg').html('Please check the errors!').show().fadeOut(10000);
                $myForm.find(':submit').click();
            }
        }

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
                            '<option>' + o.salesorder + '|' + customerACR + '</option>'
                            );
                }
            });
        });
        $('#salesel').on('change', function () {
            var salesorder = $('.salesorder').val();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            $.get("/SPOT/provisioning/api/tblprogresses?salesorder=" + SO, function (jsonResult) {
                var Jdata = jsonResult.rows[0].data;
                console.log('salesel change function' + Jdata);
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
        $('#salesel').trigger("change")
        $('*[required]').before("<span class='icon-star' style='color:red'></span>");
        $('.root').val(generatePassword(8));
        $('.oracle').val(generatePassword(8));
        $('.operator').val(generatePassword(8));
        $('.results').hide();
        $('.password').hide();
        var $network = $('.network');
        var $salesel = $('.salesel');
        $('.selection').chosen();
        $('.salesel').hide();
        $('#msg').hide();
        $('.selection').on('change', function () {


            if ($(this).val() === '' || typeof $(this).val() === 'undefined') {
                $('.password').hide();
                console.log('value is undefined');
            }
            if ($(this).val() == "1") {
                $('.password').show();
                if ($network) {
                    $network.prependTo('.password');
                }
                $('.salesel').remove();
                console.log('value is 1');
                var scriptID = 9; // script to parse a subnet
            }
            if ($(this).val() == "0") {
                console.log('value is 0');
                $('.password').hide();
                if ($salesel) {
                    console.log($salesel);
                    $salesel.appendTo('.stselection');
                    $salesel.show();
                    $('#salesel').chosen();
                }
                // $('.salesel').show()
                $('#salesel').on('change', function () {
                    $('.salesorder').val($('#salesel').val());
                    $('.password').show();
                    // $('.network').remove();
                });
                var scriptID = 8 // script to contact well defined IP and OS
            }
            $('.root').on('click', function () {
                $('.root').val(generatePassword(8));
            });
            $('.operator').on('click', function () {
                $('.operator').val(generatePassword(8));
            });
            $('.oracle').on('click', function () {
                $('.oracle').val(generatePassword(8));
            });
            $('.scriptID').val(scriptID);
        });
        $('.save').on('click', function (e) {
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
            //  validate(e);

            var rack = '25';
            var shelf = 'Z';
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var scriptID = $('.scriptID').val();
            var root = $('.root').val();
            var operator = $('.operator').val();
            var oracle = $('.oracle').val();
            var oldroot = $('.oldroot').val();
            var oldoperator = $('.oldoperator').val();
            var exesequence = 0;
            var executionFlag = 0;
            var ipaddress = $('#ipaddress').val();
            var netmask = $('#netmask').val();
            var url = "/SPOT/provisioning/api/remotecommands/";
            var salesorder = $('.salesorder').val();
            if (salesorder.indexOf('|') === -1)
            {
                var msg = "<strong>Please, enter a salesorder and customer acronym separated by '|' as for example 99999999|ZZZ</strong>";
                $('#servermsg').html(msg);

                $('#basicModal').modal();
                return false;
            }
            var SOarr = salesorder.split('|');
            var TEST = /^[a-zA-Z]+$/.test(SOarr[1]);

            
            if (! TEST) {
                var msg = "<strong>The customer acronym cannot be empty!</strong>";
                $('#servermsg').html(msg);

                $('#basicModal').modal();
                return false;
                }
            var SO = SOarr[0].trim();


            var argument = argument = {
                "0": "-root",
                "1": root,
                "2": "-operator",
                "3": operator,
                "4": "-oracle",
                "5": oracle,
                "6": "-oldroot",
                "7": oldroot,
                "8": "-oldoperator",
                "9": oldoperator,
                "10": "-so",
                "11": "'" + salesorder + "'"
            };

            if (scriptID == 8) {

                //takes the server from the DB
                //

                $.get("/SPOT/provisioning/api/tblprogresses?salesorder=" + SO, function (jsonResult) {
                    var Jdata = jsonResult.rows[0].data;
                    // console.log(Jdata);
                    var Jsonspecs = JSON.parse(Jdata);
                    console.log(Jsonspecs);
                    if (Jsonspecs.completed == true) {
                        var counter = 0;
                        var index = 11; // the same as argstringend
                        var SOservers = '';
                        $.each(Jsonspecs.clients, function (i, o) {
                            counter++;
                            index++;
                            var ip = o.ip;
                            var os = o.ostarget;
                            netmask = o.netmask;
                            var param1name = "-server" + counter;
                            var param2name = "-os" + counter;
                            argument[index] = param1name;
                            index++;
                            argument[index] = ip;
                            index++;
                            argument[index] = param2name;
                            index++;
                            argument[index] = os;
                            SOservers += '<p>' + ip + '   ' + os + '</p>';
                            console.log('IN THE LOOP: ' + argument);
                        });
                        $('#servermsg').html(SOservers);
                        $('.results').after('<p id="detals" class="pull-right btn btn-mini">Click for details...</p>');


                        var exesequence = 0;
                        var executionFlag = 0;
                        var datastring = JSON.stringify(argument);
                        console.log('ARGUMENTS: ' + argument);
                        console.log('DATASTRING: ' + datastring);
                        var passcommand = {
                            salesorder: SO,
                            rack: rack,
                            shelf: shelf,
                            clientaddress: clientaddress,
                            arguments: datastring,
                            exesequence: exesequence,
                            returnstdout: "Waiting for command execution",
                            executionflag: executionFlag,
                            scriptid: scriptID
                        };
                        var Jpass = JSON.stringify(passcommand);
                        console.log('Jpass: ' + Jpass);
                        $.ajax({
                            url: "/SPOT/provisioning/api/remotecommands",
                            type: "POST",
                            data: Jpass,
                            wait: true,
                            success: function () {

                                $('.stselection').hide();
                                $('.save').hide();
                                $('#header').html('<th colspan="2"><label for="root" class="sr-only"><b>root</b></label><th colspan="2"><label for="operator" class="sr-only"><b>operator</b></label></th><th colspan="2"><label for="oracle" class="sr-only"><b>oracle</b></label></th>');
                                $('.results').show();
                                $('.results').html('<h3>Go to <a href="/SPOT/provisioning/tblpasswords">Passwords archive  table</a> look for ' + SO + ' and wait for results.</h3>');
                                $(':input').prop('disabled', true);
                            },
                            error: function (data) {
                                $('#msg').html("An error occured:  " + data.statusText + " " + data.responseText);
                                console.log(data);
                                $('#msg').show();
                                setTimeout(function () {
                                    $('#rmsg').fadeOut(3000);
                                }, 4000);
                                return false;
                            }
                        });


                    }
                    else
                    {
                        $('#msg').hide();
                        $('#msg').html("The salesorder you've selected " + salesorder + " is not in fully installed (results from DB). You can change passwords selecting the subnet method.");
                        $('#msg').show();
                        setTimeout(function () {
                            $('#msg').fadeOut(2000);
                            window.location.href = "/SPOT/provisioning/setpasswords";
                        }, 3000);

                        return false;
                    }

                });


            }

            if (scriptID == 9) {

                argument[12] = "-ip";
                argument[13] = ipaddress;
                argument[14] = "-netmask";
                argument[15] = netmask;
                var exesequence = 0;
                var executionFlag = 0;
                var datastring = JSON.stringify(argument);
                console.log('ARGUMENTS: ' + argument);
                console.log('DATASTRING: ' + datastring);
                var passcommand = {
                    salesorder: SO,
                    rack: rack,
                    shelf: shelf,
                    clientaddress: clientaddress,
                    arguments: datastring,
                    exesequence: exesequence,
                    returnstdout: "Waiting for command execution",
                    executionflag: executionFlag,
                    scriptid: scriptID
                };
                var Jpass = JSON.stringify(passcommand);
                console.log('Jpass: ' + Jpass);
                $.ajax({
                    url: "/SPOT/provisioning/api/remotecommands",
                    type: "POST",
                    data: Jpass,
                    wait: true,
                    success: function () {

                        $('.stselection').hide();
                        $('.save').hide();
                        $('#header').html('<th colspan="2"><label for="root" class="sr-only"><b>root</b></label><th colspan="2"><label for="operator" class="sr-only"><b>operator</b></label></th><th colspan="2"><label for="oracle" class="sr-only"><b>oracle</b></label></th>');
                        $('.results').show();
                        $('.results').html('<h2>Go to <a href="/SPOT/provisioning/tblpasswords">Passwords archive  table</a> look for ' + SO + ' and wait for results.');
                        $(':input').prop('disabled', true);
                    },
                    error: function (data) {
                        $('#msg').html("An error occured:  " + data.statusText + " " + data.responseText);
                        console.log(data);
                        $('#msg').show();
                        setTimeout(function () {
                            $('#rmsg').fadeOut(3000);
                        }, 4000);
                        return false;
                    }
                });
            }

            /* window.onbeforeunload = function() {
             
             var _message = "Before to leave, be sure that all results has been shown. If the button to download the document appeared, is OK.\n\nChoose 'Leave this page' to exit the page.\nChoose 'Stay on this page' if not sure.";
             return _message;
             
             } */
            var ipaddress = $('#ipaddress').val();
            var netmask = $('#netmask').val();

            var ipaliasID = 7;
            var args = {
                "0": "-ip",
                "1": ipaddress,
                "2": "-n",
                "3": netmask
            };
            var datastring = JSON.stringify(args);
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

            };

            var Jcommand = JSON.stringify(command);

            $.ajax({
                url: url,
                type: "POST",
                data: Jcommand,
                wait: true,
                success: function (data) {
                    console.log('IPALIAS:' + data);
                },
                error: function (data) {
                    $('#msg').html("An error occured:  " + data.statusText + " " + data.responseText);
                    console.log(data);
                    $('#msg').show();
                    setTimeout(function () {
                        $('#msg').fadeOut(3000);
                    }, 4000);
                    return false;
                }
            });





        });
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Customize passwords


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="stselection table-bordered table-responsive table table-striped">
        <tr>
            <th>
                <label for="selection"><strong>Select the method</strong></label>
            </th>
        </tr>
        <tr>
            <td>
                <select class="chosen selection" id="selection" name="selection">
                    <option value=''>
                        Select a value
                    </option>
                    <option value="0">
                        Based on stored sale order
                    </option>
                    <option value="1">
                        Based on subnet definition
                    </option>
                </select>
            </td>

        </tr>
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

    <form id="form" role="form" onsubmit="return false;">


        <table class="password table-bordered table-responsive table table-striped">


            <tr class="network">

                <th colspan="3"><label for="ipaddress" class="sr-only" ><b>IP address alias to assign </b></label></th>

                <th colspan="3"><label for="netmask"><b>Netmask </b></label></th>

            </tr>
            <tr class="network">

                <td colspan="3">
                    <input name="ipaddress" id="ipaddress" class="form-control ipaddress" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" />   
                </td>
                <td colspan="3">
                    <input name="netmask" id="netmask" class="form-control netmask" type="text" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" value="255.255.255.0" />
                </td>

            </tr>

            <tr id="header">
                <th colspan="2">
                    <label for="root" class="sr-only"><b>root</b>
                        <input type="button" class="btn btn-info btn-mini pull-right" value="Regenerate" id="rootpass"/>
                    </label>
                </th>
                <th colspan="2">
                    <label for="operator" class="sr-only"><b>operator</b>
                        <input type="button" class="btn btn-info btn-mini pull-right" value="Regenerate" id="operatorpass"/>
                    </label>
                </th>
                <th colspan="2">
                    <label for="oracle" class="sr-only"><b>oracle</b>
                        <input type="button" class="btn btn-info btn-mini pull-right" value="Regenerate" id="oraclepass"/>
                    </label>

                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <input name="root" id="root" required class="root form-control" type="text" />

                </td>
                <td colspan="2">
                    <input name="operator" id="operator" required class="operator form-control" type="text" />
                </td>
                <td colspan="2">
                    <input name="oracle" id="oracle" required class="oracle form-control" type="text" />
                </td>
            </tr>

            <tr>

                <th colspan="2">
                    <label for="oldroot" class="sr-only"><b>root to use</b></label>
                </th>
                <th colspan="2">
                    <label for="oldoperator" class="sr-only"><b>operator to use</b></label>
                </th>
                <th colspan="2">
                    <label for="salesorder" class="sr-only"><b>SO N.|customer ACR </b></label>


                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <input name="oldroot" id="oldroot" required class="oldroot form-control" type="text" value="***REMOVED***" />
                </td>
                <td colspan="2">
                    <input name="oldoperator" id="oldoperator" required class="oldoperator form-control" type="text" value="Customer" />
                </td>
                <td colspan="2">
                    <input name="salesorder" id="salesorder" required class="salesorder form-control" placeholder="xxxxxxxx|ZZZ" type="text"  />
                </td>

            </tr>

            <tr>
                <td colspan="6">

                    <input type="hidden" class="scriptID" />
                    <input type="submit" class="save btn btn-primary pull-right" value="Save" />

                </td>
            </tr>
        </table>


    </form>

    <table class="table table-bordered table-striped table-responsive results">
        <tr>
            <th>
                <label for="results"> <strong> Results </strong></label>
            </th>
        </tr>
        <tr>
            <td id="results">
                the results here
            </td>
        <tr>
    </table>
</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>