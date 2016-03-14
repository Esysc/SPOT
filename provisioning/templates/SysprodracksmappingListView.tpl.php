<?php

$this->assign('title', 'SPOT | Sysprodracksmappings');
$this->assign('nav', 'sysprodracksmappings');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/sysprodracksmappings.js").wait(function() {
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

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Rack Mapping
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>
    <div class="breadcrumb">Modify an existing mapping or add a new one. It's not possible adding twice same rack and shelf (keys costraint), so if you get an error on DB side, check you didn't enter not consistent values</div>
    <!-- underscore template for the collection -->
    <script type="text/template" id="sysprodracksmappingCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Rack">Rack<% if (page.orderBy == 'Rack') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Shelf">Shelf<% if (page.orderBy == 'Shelf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Cycladesip">Cyclades ip<% if (page.orderBy == 'Cycladesip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Cycladesport">Cyclades port<% if (page.orderBy == 'Cycladesport') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Switchip">Switch ip<% if (page.orderBy == 'Switchip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
       
        <th id="header_Switchport">Switch port<% if (page.orderBy == 'Switchport') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Bootpip">Bootp ip<% if (page.orderBy == 'Bootpip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
   <!--     <th id="header_Clientid">Clientid<% if (page.orderBy == 'Clientid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        -->
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('clientid')) %>">
        <td><%= _.escape(item.get('rack') || '') %></td>
        <td><%= _.escape(item.get('shelf') || '') %></td>
        <td><%= _.escape(item.get('cycladesip') || '') %></td>
        <td><%= _.escape(item.get('cycladesport') || '') %></td>
        <td><%= _.escape(item.get('switchip') || '') %></td>
       
        <td><%= _.escape(item.get('switchport') || '') %></td>
        <td><%= _.escape(item.get('bootpip') || '') %></td>
             <!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
        <td><%= _.escape(item.get('clientid') || '') %></td>
        -->
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="sysprodracksmappingModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="rackInputContainer" class="control-group">
        <label class="control-label" for="rack">Rack</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="rack" placeholder="Rack" value="<%= _.escape(item.get('rack') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="shelfInputContainer" class="control-group">
        <label class="control-label" for="shelf">Shelf</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="shelf" placeholder="Shelf" value="<%= _.escape(item.get('shelf') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="cycladesipInputContainer" class="control-group">
        <label class="control-label" for="cycladesip">Cyclades ip</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="cycladesip" placeholder="Cycladesip" value="<%= _.escape(item.get('cycladesip') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="cycladesportInputContainer" class="control-group">
        <label class="control-label" for="cycladesport">Cyclades port</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="cycladesport" placeholder="Cycladesport" value="<%= _.escape(item.get('cycladesport') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="switchipInputContainer" class="control-group">
        <label class="control-label" for="switchip">Switch ip</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="switchip" placeholder="Switchip" value="<%= _.escape(item.get('switchip') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="switchportInputContainer" class="control-group">
        <label class="control-label" for="switchport">Switchport</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="switchport" placeholder="Switchport" value="<%= _.escape(item.get('switchport') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="bootpipInputContainer" class="control-group">
        <label class="control-label" for="bootpip">Bootp ip</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="bootpip" placeholder="Bootpip" value="<%= _.escape(item.get('bootpip') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
       <div id="clientidInputContainer" class="control-group">
        <label class="control-label" for="clientid">Clientid</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="clientid" placeholder="Clientid" disabled value="<%= _.escape(item.get('clientid') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteSysprodracksmappingButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteSysprodracksmappingButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Sysprodracksmapping</button>
        <span id="confirmDeleteSysprodracksmappingContainer" class="hide">
        <button id="cancelDeleteSysprodracksmappingButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteSysprodracksmappingButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="sysprodracksmappingDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Edit Mapping
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="sysprodracksmappingModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveSysprodracksmappingButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="sysprodracksmappingCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <button id="newSysprodracksmappingButton" class="btn btn-primary">Add Mapping</button>
    </p>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
