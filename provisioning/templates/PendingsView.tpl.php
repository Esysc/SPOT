<?php
$this->assign('title', 'SPOT | Pending Orders');
$this->assign('nav', 'pendings');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/pendings.js").wait(function() {
        $(document).ready(function() {
            page.init();
        });
        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function() {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->

    </h1>
    <!-- underscore template for the collection -->
    <h2 class="alert alert-danger" role="alert"><i class="icon-cloud"></i> New orders</h2>
    <!-- underscore template for the collection -->
    <script type="text/template" id="tblstoredordersCollectionTemplate">
        <div class="alert alert-success" id="success" role="alert" style="display:none"></div>
        <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr >
        <th id="header_Id">Id</th>
        <th id="header_Salesorder">Salesorder</th>
        <th id="header_CustomerACR">Customer</th>
        <th id="header_releasename">Release</th>
        <th id="header_SysprodActor">User</th>
        <!--<th id="header_Object">Object</th>-->
        <th id="header_Creationdate">Creationdate</th>
        <th id="header_Origin">Origin</th>
        <th id="header_pstartDate">Production Start</th>
        <th id="header_pendDate">Production End</th>
        <!--<th id="header_Message">Message</th>-->

        </tr>
        </thead>
        <tbody>
        <% var salesorder = '' %>
        <% items.each(function(item) { %>
        <input type="hidden" id="username" value="<?php if (isset($_SESSION['login'])) echo $_SESSION['login']; ?>" />
        <% if (_.escape(item.get('salesorder') || '') !== salesorder ) { %>
        <tr id="<%= _.escape(item.get('id')) %>" data-toggle="tooltip"  title="Click on row to select.">

        <td ><%= _.escape(item.get('id') || '') %></td>
        <td><%= _.escape(item.get('salesorder') || '') %></td>
        <td><%= _.escape(item.get('CustomerACR') || '') %></td>
        <td><%= _.escape(item.get('releasename') || '') %></td>
        <td><%= _.escape(item.get('SysprodActor') || '') %></td>
        <!--<td><%= _.escape(item.get('object') || '') %></td>-->



        <td><%= _.escape(item.get('creationdate') || '') %></td>

        <td><%= _.escape(item.get('origin') || '') %></td>
        <td><%= _.escape(item.get('pstartDate') || '') %></td>
        <td><%= _.escape(item.get('pendDate') || '') %></td>
        <!--<td><%= _.escape(item.get('message') || '') %></td>-->

        </tr>
        <% salesorder =  _.escape(item.get('salesorder') || '') %>
        <% } %>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="tblstoredordersModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="idInputContainer" class="control-group">
        <label class="control-label" for="id">Id</label>
        <div class="controls inline-inputs">
        <span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="userInputContainer" class="control-group">
        <label class="control-label" for="user">User</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="user" placeholder="User" value="<%= _.escape(item.get('user') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="objectInputContainer" class="control-group">
        <label class="control-label" for="object">Object</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="object" placeholder="Object" value="<%= _.escape(item.get('object') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="salesorderInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Salesorder</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="creationdateInputContainer" class="control-group">
        <label class="control-label" for="creationdate">Creationdate</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="creationdate" placeholder="Creationdate" value="<%= _.escape(item.get('creationdate') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="originInputContainer" class="control-group">
        <label class="control-label" for="origin">Origin</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="origin" placeholder="Origin" value="<%= _.escape(item.get('origin') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="versionInputContainer" class="control-group">
        <label class="control-label" for="version">Version</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="version" placeholder="Version" value="<%= _.escape(item.get('version') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="statusInputContainer" class="control-group">
        <label class="control-label" for="status">Status</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="status" placeholder="Status" value="<%= _.escape(item.get('status') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="messageInputContainer" class="control-group">
        <label class="control-label" for="message">Message</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="message" rows="3"><%= _.escape(item.get('message') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteTblstoredordersButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteTblstoredordersButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Tblstoredorders</button>
        <span id="confirmDeleteTblstoredordersContainer" class="hide">
        <button id="cancelDeleteTblstoredordersButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteTblstoredordersButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

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
