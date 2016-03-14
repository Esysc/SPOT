<?php
$this->assign('title', 'SPOT | OS Images');
$this->assign('nav', 'provisioningimageses');

$this->display('_Header.tpl.php');
?>


<script type="text/javascript">
    $LAB.script("scripts/app/provisioningimageses.js").wait(function () {
        $(document).ready(function () {
            page.init();
        });

        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function () {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });
</script>


<div class="container">

    <h1>
        <i class="icon-th-list"></i> Images and Task Sequences Available
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>
    <p class="label label-info">All available images definition in database. You can update data cliking on the righrt-bootm button.
    <div class="alert alert-success" id="success" role="alert" style="display:none"></div>
    <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
    <!-- underscore template for the collection -->
    <script type="text/template" id="provisioningimagesCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Targetname">Target Name<% if (page.orderBy == 'Targetname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Imagename">Image or Task ID name<% if (page.orderBy == 'Imagename') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Ostarget">Target OS<% if (page.orderBy == 'Ostarget') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('imagename')) %>">
        <td><%= _.escape(item.get('targetName') || '') %></td>
        <td><%= _.escape(item.get('imagename') || '') %></td>
        <td><%= _.escape(item.get('ostarget') || '') %></td>
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>
    <div class="breadcrumb"><span class="icon-tint"></span> In MDT when define a new task sequence, please provide the key name "DEPLOY" in task name to avoid unwanted task sequence to be listed</div>
    <!-- underscore template for the model -->
    <script type="text/template" id="provisioningimagesModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="imagetargetInputContainer" class="control-group">
        <label class="control-label" for="imagetarget">Target</label>
        <div class="controls inline-inputs">
        <select id="imagetarget" name="imagetarget"></select>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="imagenameInputContainer" class="control-group">
        <label class="control-label" for="imagename">Image</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="imagename" placeholder="Imagename" value="<%= _.escape(item.get('imagename') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="ostargetInputContainer" class="control-group">
        <label class="control-label" for="ostarget">Os target</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="ostarget" placeholder="Ostarget" value="<%= _.escape(item.get('ostarget') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteProvisioningimagesButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteProvisioningimagesButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete This Image Reference </button>
        <span id="confirmDeleteProvisioningimagesContainer" class="hide">
        <button id="cancelDeleteProvisioningimagesButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteProvisioningimagesButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="provisioningimagesDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Edit Images
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="provisioningimagesModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveProvisioningimagesButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="provisioningimagesCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <button id="newProvisioningimagesButton" class="btn btn-primary">Add Image definition Manually</button>

        <button id="uploadLysis" style="position:relative;left:20%" class="btn btn-primary center" />
        Import Linux iso in <?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>:/lysis directory
        </button>
        <button id="getProvisioningimagesButton" class="btn btn-primary pull-right">Update Images definition</button>
    </p>
    <span id="getImageInfo" class="pull-right loader progress progress-striped active" style="display:none"><span class="bar"></span></span>

    <div class="modal hide fade" id="Upload">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Import ISO
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <span class="success">The file must be in <?php echo GlobalConfig::$IMAGES->LYSIS; ?> directory</span>
            <input type="file" class="btn btn-warning" id="lysisISO" name="lysisISO" />
            <div id="modelAlert"></div>
            <div id="isoAlert"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="isoUpload" class="btn btn-primary">Start import</button>
        </div>
    </div>

</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>

<script>
    $('#uploadLysis').click(function () {
        $('#Upload').modal({show: true});
        $('#isoUpload').click(function () {
            $('#Upload').modal('hide');
            var isoNAME = $("#lysisISO").val();

            var isoPATH = "/lysis/";
            var path = '<?php echo GlobalConfig::$IMAGES->LYSIS; ?>';
            var file = path + "/" + isoNAME;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>';
            var scriptid = "6"; //The id for uploadISO script
            //Sales order rack and shelf neede only to send some fake data
            var salesorder = "10000000"; //internal use
            var rack = "25";
            var shelf = "Z";
            var args = '{"0" : "' + file + '", "1" : "' + isoPATH + '"}';
            //arguments = JSON.stringify(args);
            //alert(arguments);
            var exesequence = 1;
            var executionFlag = 0;
            var toSend = {salesorder: salesorder,
                rack: rack,
                shelf: shelf,
                scriptid: scriptid,
                arguments: args,
                clientaddress: clientaddress,
                exesequence: exesequence,
                executionFlag: executionFlag

            };
            var JSONdata = JSON.stringify(toSend);
            $.ajax({
                url: "api/remotecommands",
                type: "POST",
                data: JSONdata,
                wait: true,
                success: function () {

                    $('#success').html('Successfully sent jobs to client:<- ' + clientaddress + ' ->, You can monitor the process on dashboard ');
                    $('#failed').hide();

                    $('#success').show();


                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#success').html('');
                    $('#success').hide();
                    $('#failed').html(xhr.responseText);
                   
                    $('#failed').show();
                }
            }); //End of AJAX call
            setTimeout(function () {
                $('#success').hide();
            }, 6000);
        });


    });
</script>





<script>
    $('#getProvisioningimagesButton').click(function () {
        $('#getProvisioningimagesButton').prop('disabled', true);
        $('#getImageInfo').show();

        var server = ['<?php echo GlobalConfig::$SYSPROD_SERVER->DRBL; ?>', '<?php echo GlobalConfig::$SYSPROD_SERVER->NIM; ?>'];
        var scriptid = "1"; //The id for getImage.pl script
        //Sales order rack and shelf neede only to send some fake data
        var salesorder = "10000000"; //internal use
        var rack = "25";
        var shelf = "Z";
        var exesequence = "1";
        var executionFlag = "0";
        $.each(server, function (index, value) {
            var clientaddress = value;
            var toSend = {salesorder: salesorder,
                rack: rack,
                shelf: shelf,
                scriptid: scriptid,
                clientaddress: clientaddress,
                exesequence: exesequence,
                executionFlag: executionFlag

            };

            var JSONdata = JSON.stringify(toSend);


            $.ajax({
                url: "api/remotecommands",
                type: "POST",
                data: JSONdata,
                wait: true,
                success: function () {

                    $('#success').append('Successfully sent jobs to client: ' + value + ' <br />');
                    $('#failed').hide();
                    $('#success').show();


                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#success').html('');
                    $('#success').hide();
                    $('#failed').html(xhr.responseText);
                   
                    $('#failed').show();
                }
            }); //End of AJAX call

        }); // END of loop
        setTimeout(function () {
                  $('#getProvisioningimagesButton').prop('disabled', false);
            $('#getImageInfo').hide();
            $('#success').html('');
            $('#success').hide();
        }, 20000);


    });

</script>