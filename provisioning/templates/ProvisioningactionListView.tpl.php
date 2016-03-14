<?php

$this->assign('title', 'SPOT | Provisioning Job Sent');
$this->assign('nav', 'provisioningactions');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/provisioningactions.js").wait(function() {
        $(document).ready(function() {
            page.init();
        });

        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function() {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });
</script>
<style>
    .showprogress {
       white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: -o-pre-wrap;
        word-wrap: break-word;
        
        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        width: 30%;
    }
    
   table {
        font-size: smaller;
    }
</style>
<div class="container">


    <h1>
        <i class="icon-th-list"></i> Provisioning jobs sent
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>

    <!-- underscore template for the collection -->
    <script type="text/template" id="provisioningactionCollectionTemplate">
        <table class="collection table table-bordered table-hover" style="width:50px">
        <thead>
        <tr>

        <th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Codeapc">Codeapc<% if (page.orderBy == 'Codeapc') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
            <th id="header_Creationdate">Creation date<% if (page.orderBy == 'Creationdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Rack">Rack<% if (page.orderBy == 'Rack') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Shelf">Shelf<% if (page.orderBy == 'Shelf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_Hostname">Hostname<% if (page.orderBy == 'Hostname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Timezone">Timezone<% if (page.orderBy == 'Timezone') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Posixtz">Posixtz<% if (page.orderBy == 'Posixtz') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Wintz">Wintz<% if (page.orderBy == 'Wintz') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!-- <th id="header_Dststartday">Dststartday<% if (page.orderBy == 'Dststartday') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Dststopday">Dststopday<% if (page.orderBy == 'Dststopday') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Dststarth">Dststarth<% if (page.orderBy == 'Dststarth') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Dststoph">Dststoph<% if (page.orderBy == 'Dststoph') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th> -->
        <th id="header_Os">Os<% if (page.orderBy == 'Os') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Image">Image<% if (page.orderBy == 'Image') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!-- <th id="header_Boot">Boot<% if (page.orderBy == 'Boot') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th> -->
        <th id="header_Ip">Ip<% if (page.orderBy == 'Ip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Netmask">Netmask<% if (page.orderBy == 'Netmask') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Gateway">Gateway<% if (page.orderBy == 'Gateway') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!-- <th id="header_Iloip">Iloip<% if (page.orderBy == 'Iloip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Ilonm">Ilonm<% if (page.orderBy == 'Ilonm') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Ilogw">Ilogw<% if (page.orderBy == 'Ilogw') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>-->
        <th id="header_Workgroup">Workgroup<% if (page.orderBy == 'Workgroup') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th> 
        <th id="header_Productkey">Productkey<% if (page.orderBy == 'Productkey') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('actionid')) %>">

        <td><%= _.escape(item.get('salesorder') || '') %></td>
        <td><%= _.escape(item.get('codeapc') || '') %></td>
            <td><%= _.escape(item.get('creationdate') || '') %></td>
        <td><%= _.escape(item.get('rack') || '') %></td>
        <td><%= _.escape(item.get('shelf') || '') %></td>

        <td><%= _.escape(item.get('hostname') || '') %></td>
        <td><%= _.escape(item.get('timezone') || '') %></td>
        <td><%= _.escape(item.get('posixtz') || '') %></td>
        <td><%= _.escape(item.get('wintz') || '') %></td>
        <!--	<td><%= _.escape(item.get('dststartday') || '') %></td>
        <td><%= _.escape(item.get('dststopday') || '') %></td>
        <td><%= _.escape(item.get('dststarth') || '') %></td>
        <td><%= _.escape(item.get('dststoph') || '') %></td> -->
        <td><%= _.escape(item.get('os') || '') %></td>
        <td><%= _.escape(item.get('image') || '') %></td>
        <!-- <td><%= _.escape(item.get('boot') || '') %></td> -->
        <td><%= _.escape(item.get('ip') || '') %></td>
        <td><%= _.escape(item.get('netmask') || '') %></td>
        <td><%= _.escape(item.get('gateway') || '') %></td>
        <!--	<td><%= _.escape(item.get('iloip') || '') %></td>
        <td><%= _.escape(item.get('ilonm') || '') %></td>
        <td><%= _.escape(item.get('ilogw') || '') %></td> -->
        <td><%= _.escape(item.get('workgroup') || '') %></td> 
        <td><%= _.escape(item.get('productkey') || '') %></td>

        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>
    <script type="text/template"  id="provisioningactionsModelTemplate">
         <form id="deleteProvisioningactionsButtonContainer" class="form-horizontal" onsubmit="return false;">
            <fieldset>
            <div class="control-group">
            <label class="control-label"></label>
            <div class="controls">
            <button id="deleteProvisioningactionsButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete <%= _.escape(item.get('notifid') || '') %></button>
            <span id="confirmDeleteProvisioningactionsContainer" class="hide">
            <button id="cancelDeleteProvisioningactionsButton" class="btn btn-mini">Cancel</button>
            <button id="confirmDeleteProvisioningactionsButton" class="btn btn-mini btn-danger">Confirm</button>
            </span>
            </div>
            </div>
            </fieldset>
            </form>
    </script>
    <!-- modal edit dialog -->
    <div class="modal hide fade" id="provisioningactionDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Remove Record
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="provisioningactionModelContainer"></div>
        </div>
        <!-- <div class="modal-footer">
                <button class="btn" data-dismiss="modal" >Cancel</button>
                <button id="saveProvisioningactionsButton" class="btn btn-primary">Save Changes</button>
        </div> -->
    </div>

    <div id="collectionAlert"></div>

    <div id="provisioningactionCollectionContainer" class="collectionContainer">
    </div>

<!--	<p id="newButtonContainer" class="buttonContainer">
                <button id="newProvisioningactionsButton" class="btn btn-primary">Add Provisioningactions</button>
        </p> -->







</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
