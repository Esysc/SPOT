<?php
$this->assign('title', 'SPOT | Sharepoint Connector');
$this->assign('nav', 'scheduled');

$this->display('_Header.tpl.php');
?>
<script>
    $('document').ready(function() {
    function chooseAndSetOrder(json) {
       // console.log(json);
        
        var link = json.SharepointLink;
       window.open(link);
     }
        //check if HQ servers  are all alive before to continue
        var sharepoint = 'sharepoint.my.compnay.com';
        var ist = 'ist.my.compnay.com';
        var servers = {
            0: sharepoint
           
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
                $('#success').append('Retreiving "Scheduled" orders from sharepoint. Please, be patient.&nbsp;  <img src="/SPOT/provisioning/images/loader.gif" class="pull-right" />').show();
                $.ajax({
                    url: '/SPOT/provisioning/includes/sharepointScheduled.php',
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
                                    //console.log(json.SharepointLink);
                                chooseAndSetOrder(json);
                                });



                            });
                        }
                        else
                        {
                            $('#servermsg').html('<strong>There aren\'t "Scheduled" order on sharepoint, so no New order.</strong>');
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
    <h2 class="alert alert-info" role="alert"><i class="icon-cloud"></i> Sharepoint "Scheduled" Orders</h2>
    <!-- underscore template for the collection -->


    <div class="alert alert-success" id="success" role="alert" style="display:none"></div>
    <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
    <input type="hidden" id="username" value="<?php if (isset($_SESSION['login'])) echo $_SESSION['login']; ?>" />
    <table class="collection table table-bordered table-hover" id="sharepoint">








    </table>

    <div class="breadcrumb"><span class="icon-certificate"></span>  This is a view of sharepoint "Scheduled" orders. Selecting a row , will open a window on the corresponding sharepoint page to start a workflow. </div>



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
