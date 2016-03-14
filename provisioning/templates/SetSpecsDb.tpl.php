<?php

$this->assign('title', 'SPOT | Set generic specification salesorder components');
$this->assign('nav', 'setspecs');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {

        $('#specification').chosen();
        //Get all specification names from Sysprod DB
        $.ajax({
            url: "/SPOT/provisioning/includes/getSpecs.php",
            type: "GET",
            async: false,
            cache: false,
            wait: true,
            success: function (jsonResult) {
                //console.log(jsonResult);
                jsonResult = $.parseJSON(jsonResult);
                $.each(jsonResult, function (a, b) {
                    var specif = b.name;
                    console.log(specif);
                    $('#specification').append(
                            '<option>' + specif + '</option>'
                            ).trigger("chosen:updated");
                });

            }
        });

        var postdata = {};
        $('#create_serial').removeAttr('disabled');

        $('#create_order_item_id').removeAttr('disabled');

        $('.create').on('click', function (e) {
            var url = $(this).attr("value");
            $("#DataTable").html('');
            e.preventDefault();
            var serial = $("#serial").val();
            var specification = $("#specification option:selected").text();
            var specification_value = $('#specification_value').val();
            $('.results').show();
            postdata = {
                serial: serial,
                specification: specification,
                specification_value: specification_value
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
                    $("#DataTable").append('<p>S/N: ' + serial + ', Specification: ' + specification + ', specification value: ' + specification_value + ' Message from server:</p> ' + data);
                }
            });
        });
        $('.results').hide();
        $('#msg').hide();
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Set specifications, ack and then assemble


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="table-bordered table-responsive table table-striped">

        <tr>
            <th colspan="3">

        <center>    Insert Data </center>


        </th>
        </tr>
        <tr >
            <td>
                <label for="serial">Serial number or order item number separated by comma</label>
                <textarea class="form-control" rows="10" id="serial"></textarea>
            </td>
            <td>
                <label for="specification" class="">Specifications name</label>
                <select id="specification" class="form-control" required autofocus="autofocus">
                    <option value="">
                        Select a specification name
                    </option>
                </select>

            </td>
            <td>
                <label for="specification_value">Corresponding specification values</label>
                <textarea class="form-control" rows="10" id="specification_value"></textarea>
            </td>



        </tr>



    </table>

    <button class="btn btn-success btn-mini create pull-left" disabled="disabled" id="create_serial" value="/SPOT/provisioning/includes/setSpecDb.php" id="create">
        Send to Sysprod DB the attributes(serial numbers)
    </button>
    <button class="btn btn-danger btn-mini create pull-right" disabled="disabled" id="create_order_item_id" value="/SPOT/provisioning/includes/setSpecDb_item_order_id.php" id="create">
        Send to Sysprod DB the attributes(item order ids)
    </button>



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