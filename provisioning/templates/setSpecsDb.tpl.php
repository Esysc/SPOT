<?php

$this->assign('title', 'SPOT | Set generic specification salesorder components');
$this->assign('nav', 'setspecs');

$this->display('_Header.tpl.php');
?>
<style>
    .right {

        position: absolute;
        top: 120px;
        right: -10px;
        height: 100%;
        width: 300px;
        margin-left: 1em;
        margin-right: 2em;

    }

    .specs, .results {
        visibility: hidden;
    }
</style>
<script>
    $(document).ready(function () {
        var salesorder;
        function highlight(element) {
            var isValid = true;
            var id = element.attr('id');
            $('p#error_' + id).remove();
            if ($.trim($(element).val()) === '') {
                $('#' + id).after('<p class="alert alert-error" id="error_' + id + '">' + element.attr('ui') + ' is required!</p>');
                isValid = false;
            } else {
                $('p#error_' + id).remove();
                isValid = true;
            }
            return isValid;
        }


        var loader = '<center><img src="/SPOT/provisioning/images/loader.gif" /> ....... Please Wait.....</center>';
        var error = '<p class="alert alert-error">An error occured (see console.log in developer tool to debug)</p>';
        $('#msg').html(loader + "<span class='help-inline'>Loading salesorders from SysLog....</span>");
        $('#salesel').chosen({
            width: "50%"
        });
        $('#items').chosen({
            width: "100%"
        });
        $.ajax({
            url: "/SPOT/provisioning/includes/getOrdersSysproddb.php",
            type: "GET",
            async: false,
            cache: false,
            wait: true,
            success: function (jsonResult) {

                $('#msg').html(loader + "<span class='help-inline'>Loading specifications definition from SysLog....</span>");
                var jdata = JSON.parse(jsonResult);
                $.each(jdata, function (i, o) {

                    $('#salesel').append('<option>' + o + '</option>').trigger("chosen:updated");
                });
                $('#specification').chosen();
                //Get all specification names from Sysprod DB
                $.ajax({
                    url: "/SPOT/provisioning/includes/getSpecs.php",
                    type: "GET",
                    async: false,
                    cache: false,
                    wait: true,
                    success: function (jsonResult) {

                        $('#msg').html('');
                        console.log(jsonResult);
                        jsonResult = $.parseJSON(jsonResult);
                        $.each(jsonResult, function (a, b) {
                            var specif = b.name;
                            $('#specification').append(
                                    '<option>' + specif + '</option>'
                                    ).trigger("chosen:updated");
                        });
                    },
                    error: function (e) {
                        $('#msg').html(error);
                        console.log(e);
                        return;
                    }
                });
            },
            error: function (e) {
                $('#msg').html(error);
                console.log(e);
                return;
            }
        });
        $('#salesel').on('change', function (e) {

            salesorder = $("#salesel option:selected").val();

            if (salesorder === '') {

                return;
            }
            $('#msg').html(loader + "<span class='help-inline'>Loading all components items  from SysLog....</span>");
            e.preventDefault();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            $('#salesorder').val(SO);
            var tdEle = $('#report');
            //SO = '';
            var btn = $('<a href="includes/getInstallationReport.php?sales_order_ref=' + SO + '" id="generate_report" class="btn btn-info pull-right">' + SO + ' Installation Report</a>');
            tdEle.append(btn);
            $(this).remove();
            // Get all data from DB x salesorder
            $.ajax({
                url: 'includes/treeBuilder.php?salesorder=' + SO,
                type: "GET",
                async: false,
                cache: false,
                wait: true,
                success: function (jsonResult) {

                    $('.specs').css('visibility', 'visible');
                    $('#msg').html('');
                    var Obj = $.parseJSON(jsonResult);
                    $.each(Obj, function (key, value) {
                        var optVal = value.order_item_id;
                        var optText = optVal + " - " + value.model_name + " - " + value.product_ref;
                        $('#items')
                                .append($("<option></option>")
                                        .attr("value", optVal)
                                        .text(optText)).trigger("chosen:updated");
                    });
                },
                error: function (e) {
                    $('#msg').html(error);
                    console.log(e);
                    return;
                }
            });
        });
        var postdata = {};
        $('#send').on('click', function (e) {
            $("#DataTable").html('');
            var isValid = true;
            $('[required=required]').each(function () {

                isValid = highlight($(this));
                if (!isValid)
                    return isValid;
            });
            if (!isValid) {
                return isValid;
            }

            var url = $(this).attr("value");
            $("#DataTable").html('');
            e.preventDefault();
            var items = $("#items").val();
            var specification = $("#specification option:selected").text();
            var specification_value = $('#specification_value').val();
            var itemToPost = [];
            $('#msg').html(loader + "<span class='help-inline'>Starting to update items specification in SysLog....</span>");
            $('.results').css('visibility', 'visible');
            $.each(items, function (key, value) {
                var itemArr = value.split(' - ');
                var item = itemArr[0].trim();
                postdata = {
                    serial: item,
                    specification: specification,
                    specification_value: specification_value
                };
//Ready to set attributes to  Sysprod DB
                $.ajax({
                    url: url,
                    type: "POST",
                    data: postdata,
                    wait: true,
                    success: function (data) {
                        $("#DataTable").append('<p>S/N: ' + item + ', Specification: ' + specification + ', specification value: ' + specification_value + ' Message from server:</p> ' + data);
                    }
                });
            });
            $('#msg').html('');
        });
        $('#sendStored').on('click', function (e) {
            var url = '/SPOT/provisioning/includes/setAttr.php';
            $("#DataTable").html('');
            e.preventDefault();
            salesorder = $("#salesorder").val();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            $('.results').css('visibility', 'visible');
            // Get all data from DB x salesorder
            var jsonArr = [];
            var dataArr = [];
            $.get("/SPOT/provisioning/api/tblprogresses?Salesorder_IsLike=" + SO, function (data) {
                if (data['totalResults'] == 0) {
                    $("#DataTable").html('<p class="alert alert-error">Sorry, but this sales order has not been provisioned by <strong>SPOT</strong></p>');
                    return false;
                }
                var Jdata = data.rows;
                $.each(Jdata, function (i, o) {

                    var Jfield = JSON.parse(o.data);
                    var release = $.trim(Jfield.releasename);
                    var ip = $.trim(Jfield.network);
                    var vlan = 10;
                    var network_name = "CTRL";
                    var network_mask = "255.255.255.0"
                    var updateSalesOrder = {
                        ip: ip,
                        action3: "Add",
                        sales_order_ref: SO,
                        page: "salesOrderDetails",
                        network_name: network_name,
                        network_mask: network_mask,
                        vlan: vlan,
                        release_installed: release
                        
                    };
                    
                    //Ready to update the general sales order informations
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: updateSalesOrder,
                        wait: true,
                        success: function (data) {

                            $('#msg').html('');
                            $('#results').html('');
                            $("#DataTable").append('<p>Network name: ' + network_name + ', Network: ' + ip + '/' + network_mask + ', Vlan: ' + vlan + ', Release: ' + release + ' Message from server:</p> ' + data);
                        }
                    });
                   
                });
            });

            $.get("/SPOT/provisioning/api/provisioningnotificationses?Notifid_IsLike=" + SO, function (jsonResult) {
                if (jsonResult['totalResults'] == 0) {
                    $("#DataTable").html('<p class="alert alert-error">Sorry, but this sales order has not been provisioned by <strong>SPOT</strong></p>');
                    return false;
                }

                jsonArr = jsonResult['rows'];
                $.each(jsonArr, function (index, linesObj) {
                    var serial = $.trim(linesObj.serial);
                    var hostname = $.trim(linesObj.hostname);
                    var ipaddress = $.trim(linesObj.configuredip);
                    var ram = $.trim(linesObj.ram);
                    var cpu = $.trim(linesObj.cpu);
                    var os = $.trim(linesObj.image);
                    os = os.replace('DEPLOY', '');
                    os = $.trim(linesObj.os) + ' - ' + os;
                    postdata = {
                        serial: serial,
                        hostname: hostname,
                        ipaddress: ipaddress,
                        ram: ram,
                        cpu: cpu,
                        os: os

                    };
                    //Ready to set attributes to  Sysprod DB

                    $('#msg').html(loader + "<span class='help-inline'>Starting to update items specification in SysLog....</span>");
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: postdata,
                        wait: true,
                        success: function (data) {

                            $('#msg').html('');
                            $('#results').html('');
                            $("#DataTable").append('<p>S/N: ' + serial + ', IP: ' + ipaddress + ', Hostname: ' + hostname + ' Message from server:</p> ' + data);
                        }
                    });
                });
            });
        });
        $(document).on('click', '#assemble', function (e) {
            var url = $(this).attr("value");
            // $("#DataTable").html('');
            e.preventDefault();
            var stringToParse = salesorder;
            var arr = stringToParse.split('|');
            var sales_order_ref = arr[0];
            var r = confirm("Are you sure to assemble all items in Sales order " + sales_order_ref + "? \n This action cannot undone!");
            if (r == false)
                return;
            $('.results').show();
            postdata = {
                sales_order_ref: sales_order_ref

            };
            //Ready to set attributes to  Sysprod DB

            $('#results').html('<center><img src="/SPOT/provisioning/images/loader.gif" /></center>');
            $.ajax({
                url: url,
                type: "POST",
                data: postdata,
                wait: true,
                success: function (data) {


                    $('#results').html('').show();

                    $("#DataTable").append('<p>Sales Order ' + sales_order_ref + ' Message from server:</p> ' + data);



                },
                error: function (data) {


                }
            });
        });
        $(document).on('click', '#generate_report', function (e) {
            $(this).prop('disabled', true);
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "GET",
                wait: true,
                success: function () {
                    window.location.assign(url);
                },
                error: function (data) {
                    // console.log(data);
                    var Jdata = JSON.parse(data.responseText);
                    var message = Jdata.message;
                    var code = Jdata.code;
                    $('#msg').html(message);
                    $(this).removeClass('disabled');
                    setTimeout(function () {
                        $('#msg').html('');
                    }, 4000)

                },
            }).done($(this).prop('disabled', false));
        });
    });
</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Set specifications, ack and then assemble


    </h1>
    <span id="msg"></span>
    <!-- underscore template for the collection -->

    <table class=" table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th >

                Select a stored SO

            </th>
        </tr>
        <tr class="salesel">
            <td  id="report">
                <select class="chosen" id="salesel" name="salesel" autofocus="autofocus">
                    <option value="">
                        Select a sales order
                    </option>

                </select>
                <input type="hidden" id="salesorder" />
            </td>


        </tr>



    </table>



    <table class="table-bordered table-responsive table table-striped specs">



        <tr>
            <th colspan="3">

        <center>    Action center: </center>
        <div class="row-fluid" >
            <div class=" ui-state-highlight ">Select form the left select field all the items you need to add a specification that you can choose from the middle menu, then wirte down the value. 
                The specification will be directly set and acked. You can also click the button on other section (see explanation below).
            </div>
        </div>


        </th>
        </tr>
        <tr >
            <td>

                <label for="items" >Select the items</label>

                <select id="items" class="chosen" multiple required="required" ui="Items order" name="items" autofocus="autofocus" data-placeholder="Select the items..." >

                </select>



            </td>
            <td>

                <label for="specification" >Specifications name</label>


                <select id="specification" class="form-control" required="required" ui="Specification Type"name="specif" autofocus="autofocus">
                    <option value="">
                        Select a specification name
                    </option>
                </select>


            </td>
            <td>

                <label for="specification_value">Corresponding specification values</label>
                <input type="text" class="form-control"  required="required" ui="Specification Value" name="specifValue" id="specification_value" />

            </td>
        </tr>  

        <tr>
            <th colspan="3">
        <center>
            <button class="btn btn-success"  id="send" value="/SPOT/provisioning/includes/setSpecDb_item_order_id.php">
                Send to SysLog the attributes
            </button>
        </center>
        </th>


        </tr>


        <tr>
            <th colspan="3">
        <div class="row-fluid" >
            <div class=" ui-state-highlight ">Get all values like hostnames, IPs, number of CPUs, RAM qty that this application have stored in the internal 
                DB and try to set the corresponding specifications in syslog. 
                The crossing KEY is the serial number of the machine.
            </div>
        </div>
        </th>
        </tr>
        <tr>
            <td colspan="3">
        <center>
            <button class="btn btn-success btn"  id="sendStored">
                Send to SysLog  all the infos that I have
            </button>
        </center>
        </td>
        </tr>

        <tr>
            <th colspan="3">
        <div class="row-fluid" >
            <div class=" ui-state-highlight ">Assemble globally the sales order, to prepare the installation report and export polaroid.
            </div>
        </div>
        </th>
        </tr>
        <tr>
            <td colspan="3">
        <center>
            <button class="btn btn-success"  id="assemble" value="/SPOT/provisioning/includes/setAssembled.php">
                Start to assemble....
            </button>
        </center>
        </td>
        </tr>
    </table>



    <table class="table table-bordered table-striped table-responsive results">
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