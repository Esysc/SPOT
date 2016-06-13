<?php
$this->assign('title', 'SPOT | Customer Configurations Builder');
$this->assign('nav', 'customconfigsbuilder');

$this->display('_Header.tpl.php');

if (!isset($_SESSION['salesorder'])) {
    echo '<script type="text/javascript">
           window.location = "pendings"
      </script>';
}
?>
<style>

    .networkDetailDialog {
        width: 1300px;
        margin-left: -850px;

    }
</style>
<script>
    $(document).ready(function () {

        /*
         * Check the field class vlans for changes and remove corresponding unusefuil input fields
         */
        $(document.body).on('change', '.vlans', function (e) {
            var name, remove;
            remove = false;
            var vlans = $(this).val();
            var splitted = vlans.split(',');

            $('.modal-body *').filter(':input').each(function () {
                console.log($(this).attr('name'));
                name = $(this).attr('name');

                var number = name.replace(/[^0-9]/g, '');
                for (var s = 0; s < splitted.length; s++) {
                    var val = splitted[s];
                    if (number === val) {
                        console.log('values are: ' + number + ' and ' + val);
                        remove = false;
                        break;

                    }
                    else
                    {
                        if (number !== '')
                            remove = true;

                    }

                }
                var label = $('label[for="' + $(this).attr('id') + '"]');
                if (remove == true) {

                    $(this).hide();
                    label.hide();

                } else
                {
                    $(this).show();
                    label.show();
                }
            });
        });
        var c = 0;
        $('.networksave').hide();
        setTimeout(function () {
            $(".newitem").trigger('click');
        }, 10);
        $('.newitem').click(function (event) {
            $('.newitem').hide();
            c++;
            event.preventDefault();
            var newTable = $(' <table class="stselection table-bordered table-responsive table table-striped main">' + '<tr><th colspan="3"><div class="equip' + c + '" style="display:inline"></div> Configuration n. ' + c + '</th></tr>' +
                    '<tr class="salesel"><td ><label for="salesel' + c + '"><strong>Select the type</strong></label>' +
                    '<select class="chosen salesel" id="salesel' + c + '" name="salesel' + c + '" required autofocus="autofocus">' +
                    '<option value="">' +
                    'Select a value' +
                    '</option>' +
                    '</select>' +
                    '</td>' +
                    '<td class="hide 2' + c + '" id="2' + c + '" ><label for="template' + c + '"><strong>Select the template (newer recommended) </strong></label>' +
                    '<select class="chosen template" id="template' + c + '" name="template' + c + '" required autofocus="autofocus">' +
                    '<option value="">' +
                    'Select a value' +
                    '</option>' +
                    ' </select>' +
                    '</td>' +
                    '<td id="3' + c + '" ><label><strong>Click the button to change default configuration</strong><label>' +
                    '<button class="btn btn-primary details" id="details' + c + '">Details &nbsp;<span></span></button></td></tr></table>');
            $('.netcontainer').append(newTable);
            var newModal = '<div class="modal hide fade networkDetailDialog" id="networkdetails' + c + '">' +
                    '<div class="modal-header">' +
                    '<a class="close" data-dismiss="modal">&times;</a>' +
                    '<h3>' +
                    '<i class="icon-edit"></i> Edit default configuration line n.' + c + ', <div class="equip' + c + '" style="display:inline"></div>' +
                    '</h3>' +
                    '</div>' +
                    '<div class="modal-body" id="modal-body-networking' + c + '">' +
                    '<div id="networkContainer' + c + '"></div>' +
                    '</div>' +
                    ' <div class="modal-footer">' +
                    '<button class="btn" data-dismiss="modal" >Close</button>' +
                    '</div>' +
                    '</div>';
            $('div.main').append(newModal);
            $('#3' + c).hide();
            $('.details').on('click', function () {
                $('.newitem').show();
                $('.networksave').show();
                if (!$(this).find('span').hasClass('icon-ok')) {
                    $(this).find('span').addClass('icon-ok');
                }
                var ids = this.id
                console.log(ids);
                $('#network' + ids).modal({show: true});
                // $('#detailModal').show();

            });
            $('#template' + c).chosen({width: '50%'});
            console.log(c);
            $('#salesel' + c).before("<span class='icon-star' style='color:red'></span>");
            $('#template' + c).before("<span class='icon-star' style='color:red'></span>");
            // Get all the sales order in the tblprogress table
            $.get("/SPOT/provisioning/api/networkequipments", function (jsonResult) {
                var Jdata = jsonResult.rows;
                $.each(Jdata, function (i, o) {




                    $('#salesel' + c).append(
                            '<option value="' + o.equipId + '">' + o.equipModel + '</option>'
                            );
                });
                $('#salesel' + c).chosen();
            });
            $('.salesel').on('change', function () {

                var thisprefix = this.id.match(/\d+/);
                $('#2' + thisprefix).hide();
                $("#template" + thisprefix).empty();
                $('#template' + thisprefix).append('<option value="">Choose a value</option>');
                $("#template" + thisprefix).trigger('chosen:updated');
                var equipid = $(this).val();
                var equipmodel = $(this).find(":selected").text();
                if (equipid !== '') {
                    $('.equip' + thisprefix).html(equipmodel);
                    $('.equip' + thisprefix).after('<input type="hidden" value="' + equipid + '" id="configtarget' + thisprefix + '" />');
                    $.get("/SPOT/provisioning/api/configtemplates?configTarget_In=" + equipid, function (jsonResult) {
                        var Jdata = jsonResult.rows;
                        var totalResults = jsonResult.totalResults;
                        if (totalResults != 0) {

                            $.each(Jdata, function (i, o) {




                                $('#template' + thisprefix).append(
                                        '<option value="' + o.versionId + '">' + o.versionId + ' Creation date: ' + o.timeStamp + '</option>'
                                        );
                            });
                            $('#2' + thisprefix).show();
                            $("#template" + thisprefix).trigger('chosen:updated');
                            $("#template" + thisprefix).on('change', function () {
                                //Request the vars to build form fields...
                                var template = $(this).val();
                                if (template !== '') {
                                    var data = 'fileid=' + template;
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        url: 'includes/phpParser.php',
                                        data: data,
                                        success: function (reponse) {
                                            $('#3' + thisprefix).show();
                                            var table = '<table class="stselection table-bordered table-responsive table table-striped"><tr>';
                                            var tdcounter = 0;
                                            var td;
                                            var key, total = 0;
                                            for (key in reponse) {
                                                if (reponse.hasOwnProperty(key)) {
                                                    total++;
                                                }
                                            }
                                            var format = total % 4;
                                            var counter = 1
                                            $.each(reponse, function (i, o) {
                                                tdcounter++;
                                                if (tdcounter == 5) {
                                                    tdcounter = 1;
                                                    table = table + '</tr><tr>';
                                                }
                                                if (counter == total && format != 0) {
                                                    // adding colspan for  html table formatting

                                                    var colspan = 5 - format;
                                                    ;
                                                    td = '<td colspan="' + colspan + '">';
                                                }
                                                else
                                                {
                                                    td = '<td>';
                                                }
                                                table = table + td + '<label for="' + i + '_' + c + '"><strong>' + o.help + '</strong></label><input data-toggle="tooltip" class="' + o.class + '" type="text" id="' + i + '_' + c + '" name="' + i + '" placeholder="' + o.help + '" title="' + o.help + '" value="' + o.value + '" /></td>';
                                                counter++;
                                            });
                                            table = table + ' </tr></table>';
                                            $('#networkContainer' + thisprefix).html(table);
                                            $('input').tooltip();
                                        }



                                    });
                                }
                            });
                        }
                    });
                }
            });
        });
        $('.networksave').on('click', function (e) {

            console.log('cliking save');
            $('select').each(function () {
                if ($(this).val() === '') {
                    console.log('validation failed');
                    $('#servermsg').html('<strong>Please fill all the required fields</strong>');
                    $('#basicModal').modal();
                    return false;
                }
            });
            // Preparing the request for database insertion
            var salesOrder = "<?php echo $_SESSION['salesorder']; ?>";
            //Loop on c variable
            var data = {};
            var args = {};
            data.salesorder = salesOrder;
            var success = [];
            for (var i = 1; i <= c; i++) {

                var configtarget = $('#configtarget' + i).val();
                var configid = $('#salesel' + i).val();
                var configidname = $('#template' + i).val();
                var configname = $('#salesel' + i + ' option[value=' + configid + ']').text()

                data.fileid = configidname;
                data.configtarget = configtarget;
                data.configname = configname;
                console.log(configtarget);
                $('#networkContainer' + i + ' :input').each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    if (name.toLowerCase().indexOf("hostname") >= 0) {
                        data.hostname = value;
                        success.push('\n' + configname + " generated for hostname " + value);
                    }
                    args[name] = value

                    //data.args[name] = value;
                });
                data.args = args;
                //The object to send to php script is ready
                console.log(data);
                //Let post to php script

                var dataToSend = JSON.stringify(data);
                $.ajax({
                    type: "POST",
                    //dataType: 'json',
                    url: 'includes/templateCustomisation.php',
                    data: {'data': dataToSend},
                    error: function (xhr, err) {
                        $('#servermsg').html("<strong>An error occured: \nreadyState: " + xhr.readyState + "\nstatus: " + xhr.status + "\nresponseText: " + xhr.responseText + "</strong>");
                        $('#basicModal').modal();
                        return false;
                    },
                    success: function () {

                    }



                });
            }
            var html = "<strong>Successfully saved in database the configuration files</strong>";
            for (i = 0; i < success.length; i++) {
                html = html + '<br />' + success[i];
            }
            html = html + "<br />You can navigate to <a href='customconfigs?filter=" + salesOrder + "'>Custome configs</a> and download the files";
            $('#servermsg').html(html);
            $('#basicModal').modal();
        });
    });

</script>


<div class="container">

    <h1>
        <i class="icon-th-list"></i> Customer Configurations Builder
    </h1>
    <div class="breadcrumb"><span class="icon-tint"></span> Welcome to Network provisioning. 
        <p>Select the equipment and when all the steps are done, 
            you can add a new line or save more times the same equipment changing only few values. Any time you save, a new configuration with new ID number will be generated.
        </p>   
        
         <p class="badge-important">
             <strong style="color:white;">
            <span class="icon-exclamation-sign"></span>
            Attention: when configuring cisco 2901, you should read carefully the documentation. 
            Here you have two file to generate: the router config itself and the tcl script to load to the router.
            These files are only when a simple configuration is needed (most of the cases). If you need vlans, you should generate them manually
            following the official documentation, even if here you can generate a good starting point. Note that the document and tcl script should be
            attached to the NSE module of the customer release.
             </strong>
        </p>
       
    </div>
   
    <div class="netcontainer"></div>

    <button  class="btn btn-mini newitem btn-success"><span class="icon-building"></span> Add an equipment</button>



    <div class="main"></div>

    <button class="btn btn-primary networksave pull-right">Save</button>

    <!-- underscore template for the collection -->

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
