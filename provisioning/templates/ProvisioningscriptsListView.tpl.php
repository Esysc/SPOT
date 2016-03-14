<?php

$this->assign('title', 'SPOT |  Scripts');
$this->assign('nav', ' Scripts');

$this->display('_Header.tpl.php');
?>
<style>

    #provisioningscriptsDetailDialog {
        width: 1300px;
        margin-left: -650px;

    }
</style>
<script type="text/javascript">
    $LAB.script("scripts/app/provisioningscriptses.js").wait(function () {
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
        <i class="icon-th-list"></i>  Scripts
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>

    <!-- underscore template for the collection -->
    <script type="text/template" id="provisioningscriptsCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Scriptid">Scriptid<% if (page.orderBy == 'Scriptid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Scripttargetname">Script Target<% if (page.orderBy == 'Scripttargetname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Scriptname">Script Name<% if (page.orderBy == 'Scriptname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Scriptdescription">Script Description<% if (page.orderBy == 'Scriptdescription') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!--<th id="header_Scriptcontent">Script Content<% if (page.orderBy == 'Scriptcontent') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        -->
        <th id="header_Interpreter">Interpreter<% if (page.orderBy == 'Interpreter') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Version">Version<% if (page.orderBy == 'Version') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('scriptid')) %>">
        <td><%= _.escape(item.get('scriptid') || '') %></td>
        <td><%= _.escape(item.get('scripttargetname') || '') %></td>
        <td><%= _.escape(item.get('scriptname') || '') %></td>
        <td><%= item.get('scriptdescription')%></td>
        <!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
        <td><%= _.escape(item.get('scriptcontent') || '') %></td>
        -->
        <td><%= _.escape(item.get('interpreter') || '') %></td>
        <td><%= _.escape(item.get('version') || '') %></td>
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="provisioningscriptsModelTemplate">
            <form class="form-horizontal" onsubmit="return false;">
                    <fieldset>
                            <div id="scriptidInputContainer" class="control-group">
                                    <label class="control-label" for="scriptid">Script Id</label>
                                    <div class="controls inline-inputs">
                                            <input type="text" class="input-xlarge" id="scriptid" placeholder="Scriptid" value="<%= _.escape(item.get('scriptid') || '') %>">
                                            <span class="help-inline"></span>
                                    </div>
                            </div>
                            <div id="scripttargetInputContainer" class="control-group">
                                    <label class="control-label" for="scripttarget">Script Target</label>
                                    <div class="controls inline-inputs">
                                            <select id="scripttarget" name="scripttarget"></select>
                                            <span class="help-inline"></span>
                                    </div>
                            </div>
                            <div id="scriptnameInputContainer" class="control-group">
                                    <label class="control-label" for="scriptname">Script Name</label>
                                    <div class="controls inline-inputs">
                                            <input type="text" class="input-xlarge" id="scriptname" placeholder="Scriptname" value="<%= _.escape(item.get('scriptname') || '') %>">
                                            <span class="help-inline"></span>
                                    </div>
                            </div>
                            <div id="scriptdescriptionInputContainer" class="control-group">
                                    <label class="control-label" for="scriptdescription">Script Description</label>
                                    <div class="controls inline-inputs">
                                            <textarea class="input-xlarge" id="scriptdescription"><%= _.escape(item.get('scriptdescription') || '') %></textarea>
                                            <span class="help-inline"></span>
                                    </div>
                            </div>
                            <div id="scriptcontentInputContainer" class="control-group">
                                    <label class="control-label" for="scriptcontent">Script Content
                            <% if (item.get('scriptcontent') !== '' ) { %>
                                    <p>
                                                            <button class="btn-mini btn-success" id="scriptdownload"><i class="icon-download"></i> Download File</button>
                            </p>
                    <% }  %>
                               <p>
                                            
                                              <button class="btn-mini btn-info" id="scriptupload" onclick="$('#fileupload').click()"><i class="icon-upload" /></i> Upload File</button>
                                            <input type="file" id="fileupload" class="hidden" />
                                              
                                   
           
    </p>
                                             
                                           
                                                        </label>
                            
                                    <div class="controls inline-inputs">
                                            <textarea class="input-xlarge" id="scriptcontent"><%= item.get('scriptcontent') %></textarea>
                                            <span class="help-inline"></span>
                                                
                                                            </div>
                                                    
                                
                            </div>
                            <div id="interpreterInputContainer" class="control-group">
                                    <label class="control-label" for="interpreter">Interpreter</label>
                                    <div class="controls inline-inputs">
                                            <input type="text" class="input-xlarge" id="interpreter" placeholder="Interpreter" value="<%= _.escape(item.get('interpreter') || '') %>">
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
                    </fieldset>
            </form>

            <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
            <form id="deleteProvisioningscriptsButtonContainer" class="form-horizontal" onsubmit="return false;">
                    <fieldset>
                            <div class="control-group">
                                    <label class="control-label"></label>
                                    <div class="controls">
                                            <button id="deleteProvisioningscriptsButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Script</button>
                                            <span id="confirmDeleteProvisioningscriptsContainer" class="hide">
                                                    <button id="cancelDeleteProvisioningscriptsButton" class="btn btn-mini">Cancel</button>
                                                    <button id="confirmDeleteProvisioningscriptsButton" class="btn btn-mini btn-danger">Confirm</button>
                                            </span>
                                    </div>
                            </div>
                    </fieldset>
            </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="provisioningscriptsDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Edit the script
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="provisioningscriptsModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveProvisioningscriptsButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="provisioningscriptsCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <button id="newProvisioningscriptsButton" class="btn btn-primary">Add a script</button>
    </p>


</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
