<?php

$this->assign('title', 'SPOT | Assemble a sales order globally');
$this->assign('nav', 'setassgly');

$this->display('_Header.tpl.php');
?>

<script>
    $(document).ready(function () {
         $('#msg').html('<center><img src="/SPOT/provisioning/images/loader.gif" />Loading , please wait..........</center>');
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        $('.sales_order_ref').chosen({
            width: "80%"
        });
        //Get all specification names from Sysprod DB
        $.ajax({
            url: "/SPOT/provisioning/includes/getOrdersSysproddb.php",
            type: "GET",
            async: false,
            cache: false,
            wait: true,
            success: function (jsonResult) {
                console.log(jsonResult);
                jsonResult = $.parseJSON(jsonResult);
                $.each(jsonResult, function (a, b) {
                    console.log(b);
                    var so = b;

                    $('.sales_order_ref').append(
                            '<option>' + so + '</option>'
                            ).trigger("chosen:updated");
                });

            }
        });

        $('#sales_order_ref').keyup(function () {
            var n = this.value.replace(/{.*?}/g, '').length;
            if (n > 6) {
                $('#assemble').removeAttr('disabled');
            } else {
                $('#assemble').attr("disabled", true);
            }
        });

        $('.sales_order_ref').on('change', function () {
            var text = $(".sales_order_ref option:selected").text();
            var SOarr = text.split('|');
            var so = SOarr[0].trim();
            if (isNumber(so)) {
                $('#sales_order_ref').val(so);
                $('#assemble').removeAttr('disabled');
            } else
            {
                $('#sales_order_ref').val('');
                $('#assemble').attr("disabled", true);
            }
        });

        var postdata = {};




        $('#assemble').on('click', function (e) {
            var url = $(this).attr("value");
            $("#DataTable").html('');
            e.preventDefault();
            
            var sales_order_ref = $("#sales_order_ref").val();
            var r = confirm("Are you sure to assemble all items in Sales order " + sales_order_ref + "? \n This action cannot undone!");
            if (r == false) return;
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



                    $('#results').html('');
                   
                        $("#DataTable").append('<p>Sales Order ' + sales_order_ref + ' Message from server:</p> ' + data);

                 

                }
            });
        });
        $('.results').hide();
        $('#msg').hide();



    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Assemble a sales order globally


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="table-bordered table-responsive table table-striped">

        <tr>
            <th colspan="2">

        <center> Sales Order number </center>


        </th>
        </tr>
        <tr >
            <th>
                Select a sales order (filtered from sysproddb) .....
            </th>
            <th>
                ... or enter manually.
            </th>
        </tr>
        <tr>
            <td>
        <center>   <select class="sales_order_ref" class="form-control" required autofocus="autofocus">
                <option>Select from the menu</option>

            </select>.
        </center>
        </td>
        <td>
        <center>
            <input type="text" class="form-control"  id="sales_order_ref"  />
        </center>
        </td>


        </tr>



    </table>

    <button class="btn btn-success btn-mini create pull-left" disabled="disabled" id="assemble" value="/SPOT/provisioning/includes/setAssembled.php">
        Start to assemble....
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
                <div id="DataTable">
                    
                </div>
            </td>
        </tr>
    </table>
</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>