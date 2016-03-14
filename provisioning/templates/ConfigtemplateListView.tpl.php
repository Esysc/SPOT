<?php

$this->assign('title', 'SPOT | Template configuration');
$this->assign('nav', 'configtemplates');

$this->display('_Header.tpl.php');
?>
<style>
    #configtemplateDetailDialog {

        /* new custom width */
        width: 1200px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -600px;


    }
</style>
<script type="text/javascript">
    $LAB.script("scripts/app/configtemplates.js").wait(function () {
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
        <i class="icon-th-list"></i> Templates configuration
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>

    <!-- underscore template for the collection -->
    <script type="text/template" id="configtemplateCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_VersionId">Name and Version<% if (page.orderBy == 'VersionId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_ConfigTarget">Config Target<% if (page.orderBy == 'ConfigTarget') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_ConfigTemplate">Config Template<% if (page.orderBy == 'ConfigTemplate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Timestamp">Timestamp<% if (page.orderBy == 'TimeStamp') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('versionId')) %>">
        <td><%= _.escape(item.get('versionId') || '') %></td>
        <!--<td><%= _.escape(item.get('configTarget') || '') %></td>-->
        <td><%= _.escape(item.get('targetName') || '') %></td>
        <td>Click on row for details</td>
        <td><%= _date(app.parseDate(item.get('timeStamp'))).format('MMM D, YYYY h:mm A') %></td>

        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="configtemplateModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="versionIdInputContainer" class="control-group">
        <label class="control-label" for="versionId">Version Id</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="versionId" placeholder="Version Id" value="<%= _.escape(item.get('versionId') || '') %>" required="required">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="configTargetInputContainer" class="control-group">
        <label class="control-label" for="configTarget">Config Target</label>
        <div class="controls inline-inputs">
        <select  id="configTarget" name="configTarget"></select>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="configTemplateInputContainer" class="control-group">
        <label class="control-label" for="configTemplate">Config Template
        <% if (item.get('configTemplate') !== '' ) { %>
        <p>
        <button class="btn-mini btn-success" id="templatedownload"><i class="icon-download"></i> Download File</button>
        </p>
        <% }  %>
        <p>

        <button class="btn-mini btn-info" id="templateupload" onclick="$('#fileupload').click()"><i class="icon-upload" /></i> Upload File</button>
        <input type="file" id="fileupload" class="hidden" />



        </p>
        </label>
        <div class="controls inline-inputs">

        <textarea class="input-xlarge" id="configTemplate" required="required"><%= _.escape(item.get('configTemplate') || '') %> </textarea>
       

        <span class="help-inline"></span>
        </div>
        </div>
        <div id="timeStampInputContainer" class="control-group">
        <label class="control-label" for="timeStamp">Time Stamp</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="timeStamp" type="text" value="<%= _date(app.parseDate(item.get('timeStamp'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <div class="input-append bootstrap-timepicker-component">
        <input id="timeStamp-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('timeStamp'))).format('h:mm A') %>" />
        <span class="add-on"><i class="icon-time"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>

        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteConfigtemplateButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteConfigtemplateButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Configtemplate</button>
        <span id="confirmDeleteConfigtemplateContainer" class="hide">
        <button id="cancelDeleteConfigtemplateButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteConfigtemplateButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="configtemplateDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Edit template
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="configtemplateModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveConfigtemplateButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="configtemplateCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <button id="newConfigtemplateButton" class="btn btn-primary">Add template</button>
    </p>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
