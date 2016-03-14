<?php
$this->assign('title', 'SPOT | Sharepoint Connector');
$this->assign('nav', 'pendings');

$this->display('_Header.tpl.php');
?>
<script>
    $('document').ready(function() {
        var chooseAndSetOrder = function(attr) {
            $('#modelAlert').html('');
            $('.control-group').removeClass('error');
            $('.help-inline').html('');


            var user = $('#username').val();
            var currentdate = new Date();
            var datetime = currentdate.getDate() + "/"
                    + (currentdate.getMonth() + 1) + "/"
                    + currentdate.getFullYear() + " @ "
                    + currentdate.getHours() + ":"
                    + currentdate.getMinutes() + ":"
                    + currentdate.getSeconds();
            var inside = {
                CustomerACR: attr.Customer,
                ProgramManager: attr.ProgramManager,
                Machines: attr.Machines,
                pendDate: attr.PlannedEnd,
                pstartDate: attr.PlannedStart,
                orderdescription: attr.OrderDescription,
                releasename: attr.Release


            };
            var JSONinside = JSON.stringify(inside);
            var toSend = {user: user,
                salesorder: attr.SalesOrder,
                creationdate: datetime,
                data: JSONinside



            };
            var JSONdata = JSON.stringify(toSend);
            //   $.put("api/tblprogress", JSONdata, function( data ) {
            $.ajax({
                url: "api/tblprogress",
                type: "POST",
                data: JSONdata,
                success: function() {
                    //  $('#success').html('Successfully selected sales order ' + attr.SalesOrder + ' for provisioning. You can now proceed to provision the machines:  <a href=\"./provisioning1\" class=\"btn btn-large btn-primary\">Wizard');
                    //$('#failed').hide();
                    //  $('#success').show();
                    $('#servermsg').html('Successfully selected sales order ' + attr.SalesOrder + ' for provisioning. You can now proceed to provision the machines:  <a href=\"./provisioning1\" class=\"btn btn-mini btn-primary\">Wizard</a><br /><a href="'+attr.SharepointLink+'" target="_blank">Sharepoint link </a>');
                    $('#basicModal').modal();
                    $.ajax({
                        url: "includes/loadSession.php",
                        type: "POST",
                        data: {salesorder: attr.SalesOrder,
                            data: JSONinside}

                    });
                },
                error: function(model, response, scope) {

                    // $('#failed').html('An error occured, probally the order ' + attr.SalesOrder + ' is already in <a href="tblprogresses"> progress</a> or <a href="tblcompleted"> completed</a>. The error reported is: ' + scope);
                    //  $('#success').hide();
                    //$('#failed').show();
                    $('#servermsg').html('An error occured, probally the order ' + attr.SalesOrder + ' is already in <a href="tblprogresses"> progress</a> or <a href="tblcompleted"> completed</a>. The error reported is: ' + scope);
                    $('#basicModal').modal();
                }

            });

            //var toSend = JSON.stringify({"user" : user, "salesorder" : salesorder, "creationdate" : datetime, "data" : myObj});





        }
        //check if HQ servers  are all alive before to continue
        var sharepoint = 'sharepoint.my.compnay.com';
        var ist = 'ist.my.compnay.com';
        var servers = {
            0: sharepoint,
            1: ist
        };

        var serializedData = JSON.stringify(servers);
        $.ajax({
            url: "includes/ping.php",
            type: "post",
            data: 'hosts=' + serializedData,
            cache: false,
            async: true,
            beforeSend: function(data) {
                $('#alive').html('Checking Sysprod Servers recheability   <img src="/SPOT/provisioning/images/loader.gif" />');
            },
            success: function(data) {

                var alive = JSON.parse(data);
                var html = '';
                for (var key in alive) {
                    if (alive.hasOwnProperty(key)) {
                        html = html + ' ' + alive[key];
                    }
                }

                $('#alive').html(html);
                /*
                 * Take the sharepoint values
                 */
                $('#success').append('Retreiving "In Progress" orders from sharepoint. Please, be patient.<br />  <img src="/SPOT/provisioning/images/loader.gif" />').show();
                $.ajax({
                    url: '/SPOT/provisioning/includes/sharepointProgress.php',
                    dataType: 'json',
                    methos: 'GET',
                    cache: false,
                    async: true,
                    success: function(data) {
                        var json = JSON.stringify(data);
                        var jsonobj = JSON.parse(json);
                        $('#success').hide();
                        var tbl = $('#sharepoint');
                        var table = '';
                        var td = '';
                        $.each(jsonobj, function(index, item) {
                            var jcell = JSON.parse(item);
                            var th = '';
                            td += '<tr class="data" data-toggle="tooltip"  title="Click on row to select ' + index + '" id="' + index + '">';
                            for (var key in jcell) {
                                if (jcell.hasOwnProperty(key)) {
                                    var header = key.replace(/([a-z])([A-Z])/g, "$1 $2");
                                    th += '<th>' + header + '</th>';
                                    if (typeof jcell[key] === 'undefined' || jcell[key] == null) {
                                        jcell[key] = '';
                                    }
                                    td += '<td class="inputValue text-info" data-attr="' + key + '" id="' + index + '_' + key + '"><strong>' + jcell[key] + '</strong></td>';
                                }
                            }
                            table = '<tr>' + th + '</tr>';
                            td += '</tr>';
                        });

                        table += td;
                        if (table !== '') {
                            tbl.append(table);
                            $('.data').tooltip();
                            // Logic to add click action
                            $('.data').on('click', function() {
                                var trid = $(this).attr('id');
                                var data = $("#" + trid).map(function(index, elem) {
                                    var json = {};
                                    $('.inputValue', this).each(function() {
                                        var d = $(this).val() || $(this).text();
                                        var tdkey = $(this).attr('data-attr');
                                        json[tdkey] = d;
                                    });
                                    //Call the function to load
                                    chooseAndSetOrder(json);
                                });



                            });
                        }
                        else
                        {
                            $('#servermsg').html('<strong>There aren\'t "in progress" order on sharepoint, so no New order.</strong>');
                            $('#basicModal').modal();
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        $('#failed').html('An error occured checking HQ servers. The errors reported:' + err + ' - ' + error);
                        $('#success').hide();
                        $('#failed').show();
                    }
                });
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                $('#failed').html('An error occured checking HQ servers. The errors reported:' + err + ' - ' + error);
                $('#success').hide();
                $('#failed').show();
            }
        });
    });
</script>

<div class="container">
    <span  id="alive" class="text-info text-right pull-right" ></span>
    <h1>
        <i class="icon-th-list"></i> <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->

    </h1>

    <!-- underscore template for the collection -->
    <h2 class="alert alert-danger" role="alert"><i class="icon-cloud"></i> Sharepoint "In Progress" Orders</h2>
    <!-- underscore template for the collection -->


    <center><strong  id="success"  style="display:none"></strong></center>
    
    <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
    <input type="hidden" id="username" value="<?php if (isset($_SESSION['login'])) echo $_SESSION['login']; ?>" />
    <table class="collection table table-bordered table-hover" id="sharepoint">








    </table>
    
   
 <div class="breadcrumb"><span class="icon-certificate"></span>  This is a view of sharepoint "In Progress orders. Selecting a row , will import the order in application to provision machines. If the selected order was already imported, an error will throw. The order could be found in the application imported orders. 
     If for whatever reason you could not find the order you are looking for, you can create it manually going on <a href="tblprogresses">stored orders page</a>.
 </div>



    <!-- modal edit dialog -->
    <div class="modal hide fade" id="tblstoredordersDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Edit Tblstoredorders
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="tblstoredordersModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveTblstoredordersButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="tblstoredordersCollectionContainer" class="collectionContainer">
    </div>



</div> <!-- /container -->


<?php
$this->display('_Footer.tpl.php');
?>
