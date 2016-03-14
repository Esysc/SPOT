<?php

$this->assign('title', 'SPOT | Create csv to import in DB');
$this->assign('nav', 'createcsv');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {

        function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
            //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
            var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

            var CSV = '';
            //Set Report title in first row or line

            //CSV += ReportTitle + '\r\n\n';

            //This condition will generate the Label/Header
            if (ShowLabel) {
                var row = "";

                //This loop will extract the label from 1st index of on array
                for (var index in arrData[0]) {

                    //Now convert each value to string and comma-seprated
                    row += index + ',';
                }

                row = row.slice(0, -1);

                //append Label row with line break
                CSV += row + '\r\n';
            }
            var u = ShowLabel == true ? 1 : 0;

            //1st loop is to extract each row
            for (var i = u; i < arrData.length; i++) {
                var row = "";

                //2nd loop will extract each column and convert it in string comma-seprated
                for (var index in arrData[i]) {
                    row += '"' + arrData[i][index] + '",';
                }

                //row.slice(0, row.length - 1);
                row = row.slice(0, -1);
                //add a line break after each row
                CSV += row + '\r\n';
            }

            if (CSV == '') {
                alert("Invalid data");
                return;
            }

            //Generate a file name

            var fileName = ReportTitle.replace(/ /g, "_");
            //Initialize file format you want csv or xls
            var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

            // Now the little tricky part.
            // you can use either>> window.open(uri);
            // but this will not work in some browsers
            // or you will not get the correct file extension    

            //this trick will generate a temp <a /> tag
            var link = document.createElement("a");
            link.href = uri;

            //set the visibility hidden so it will not effect on your web-layout
            link.style = "visibility:hidden";
            link.download = fileName + ".csv";

            //this part will append the anchor tag and remove it after automatic click
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }


        $('#details').on('click', function () {
            $('#basicModal').show();
        });
        $('#salesel').chosen();
        // Get all the sales order in the tblprogress table
        $.get("/SPOT/provisioning/api/tblprogresses", function (jsonResult) {
            var Jdata = jsonResult.rows;
            // $('#salesel').attr('enabled', 'true');
            $.each(Jdata, function (i, o) {

                var Jfield = JSON.parse(o.data);
                var customerACR = Jfield.CustomerACR;
                if (Jfield.completed == true) {
                    $('#salesel').append(
                            '<option>' + o.salesorder + '|' + customerACR + '</option>'
                            ).trigger("chosen:updated");
                }
            });
        });
        $('#salesel').on('change', function () {
            $('#create').removeAttr('disabled');
        });
        var jsonArr = [];
        var sorttmp = {};
        var sortedArr = [];
        $('#create').on('click', function (e) {
            e.preventDefault();
            var salesorder = $("#salesel option:selected").text();
            var SOarr = salesorder.split('|');
            var SO = SOarr[0].trim();
            // Get all data from DB x salesorder
            $.get("/SPOT/provisioning/api/provisioningnotificationses?Notifid_IsLike=" + SO, function (jsonResult) {
                jsonArr = jsonResult['rows'];
                console.log(jsonArr);
                // Sort in right order each obj
                sorttmp = {serial: 'serial',
                    hostname: 'hostname',
                    brand: 'brand',
                    model: 'model',
                    modeltype: 'modeltype',
                    diskscount: 'diskscount',
                    cpu: 'cpu',
                    ram: 'ram',
                    ipaddress: 'ipaddress'
                };
                sortedArr[0] = sorttmp;
                $.each(jsonArr, function (index, linesObj) {

                    var value = linesObj.diskscount;
                    if (!$.isNumeric(value)) {
                        value = value.replace(/ /g, '');
                        var splitted = value.split("<br/>");
                        console.log(splitted);
                        var sub1 = splitted[0].split(":");
                        var sub2 = splitted[1].split(":");
                        var num1 = sub1[1];
                        var num2 = sub2[1];
                        if (num1 > num2) {
                            value = num1;
                        } else {
                            value = num2;
                        }
                    }

                    sorttmp = {serial: $.trim(linesObj.serial),
                        hostname: $.trim(linesObj.hostname),
                        brand: '',
                        model: $.trim(linesObj.model),
                        modeltype: '',
                        diskscount: $.trim(value),
                        cpu: $.trim(linesObj.cpu),
                        ram: $.trim(linesObj.ram),
                        ipaddress: $.trim(linesObj.configuredip)
                    };
                    sortedArr.push(sorttmp);
                });
//newArr now contains all values we need to create csv structure

// Convert JSON to CSV & Display CSV
                console.log(sortedArr);
                JSONToCSVConvertor(sortedArr, SO, true);
                //  $('.results').show();
            });
        });
        $('.results').hide();
        $('#msg').hide();
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Create Csv


    </h1>
    <p id="msg" class="alert alert-error"></p>
    <!-- underscore template for the collection -->

    <table class="stselection table-bordered table-responsive table table-striped">

        <tr class="salesel">
            <th colspan="2">
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
            <td>
                <button class="btn btn-success btn-mini" disabled="disabled" id="create">
                    Create Csv
                </button>
            </td>

        </tr>



    </table>


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