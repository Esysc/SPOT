<?php

$this->assign('title', 'SPOT | Pending Orders');
$this->assign('nav', 'tblprogresses');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/tblprogresses.js").wait(function() {
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
        <i class="icon-th-list"></i> <?php echo $this->title; ?>
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>

    </h1>

    <!-- underscore template for the collection -->
    <h2 class="alert alert-info" role="alert"><i class="icon-cloud"></i> Stored orders</h2>
    <script type="text/template" id="tblprogressCollectionTemplate">
        <div class="alert alert-success" id="success" role="alert" style="display:none"></div>
        <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_User">User<% if (page.orderBy == 'User') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!--<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        -->			<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Creationdate">Creationdate<% if (page.orderBy == 'Creationdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th>Completed</th>
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>

        <% var Jdatas = JSON.parse(item.get('data')) %>
        <% console.log(Jdatas) %>
        <% if (! Jdatas.completed == true ) { %>
        <% ACR = Jdatas.CustomerACR %>
        <tr id="<%= _.escape(item.get('id')) %>" data-toggle="tooltip"  title="Click on row to select <%= _.escape(item.get('salesorder') || '') %>.">
        <td><%= _.escape(item.get('id') || '') %></td>
        <td><%= _.escape(item.get('user') || '') %></td>

        <!-- <td><%= item.get('data') %></td> -->

        <td><%= _.escape(item.get('salesorder') || '') %>| <%= ACR %></td>
        <td><%= _.escape(item.get('creationdate') || '') %></td>

        <td>
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACd0lEQVR4XnVSX0hTYRQ/986N9ZIV9pCYFa223PRpk4hohv1RetH+WBHLoQ891MMoMEjQiqy3iKheCgQhfKobFYXDKGykW47lislw6mpeEEKwVqzt3n2n7/tu+2hGP/jdczic33fOd78fIGIZk2cONcycP66kenyZmUtdmOzxYeJsWybu36d8ONbYsLK/TJy6cEKZvXgap1rsGKm3YNgBnBGXGaNNtRjz7cVox07lnwNy80nrXG93JnFqNxMITnrWMJbV3u+3YfhIY+Zd3zkr08pAod7pH/6VTtZkoyFgWNvcxqNLiXEWUdSAZFKAS4s1EBkd5oW53i4PXVtM+Pp4EBlGtgGW8GCTkauPBkVf6IAdx5ptHlnPLg/8mAqLyVXtfp6n8iCQRyNWH/aD5jU2kZcWAfO5Adm0el1dXp1nNSru5DF0JWAI1TRkF9I8/3LDqFXTnuUigJzLgi7LdUCfSqxVwn1nJT61sZ9YiUOuSnYFnpfwcIvRH9y1ASuIrsFKSJIEVSaAYvYb2Nj6Vl4VDXQDDqLpIJNCXpUqzLzwPfKGR/vRTtFskgyu/3M9dcLoQTqE6AUV4r6mIDUJXynh82IJn64FhA8+Xw+IutLhxSF6hTGnBUec1iDEu1vc1GHCKLO3+/F/iNzsx7u1gKPbAV+6VuEzh8XNncjsSR0mDnl70osL46+FkOZsMhcrWwFf0ekvHGZFWDl267KV2XPCuAoln8JWpSJGlvMaFz93mDP3Npu4lSX2KWG8dYdCCLZRk/B3lggRP6wgm+GnZALU9Set04V2MCAOEAjt2VhPQLpaROLWCalhTwVafgF0bZKy7+C09vFvwW/UvuzpbRuZjgAAAABJRU5ErkJggg=="/></td>


        </tr>
        <% } %>

        <% }); %>
        </tbody>
        </table>

       <!--  <%=  view.getPaginationHtml(page) %> -->
       
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="tblprogressModelTemplate">
        
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
        <input type="text"  disabled class="input-xlarge" id="user" placeholder="User" value="<?php echo $_SESSION['login']; ?> ">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="dataInputContainer" class="control-group hide">
        <label class="control-label" for="data">Data</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge " disabled id="data" rows="3"><%= _.escape(item.get('data') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="customeracrInputContainer" class="control-group">
        <label class="control-label" for="customeracr">Customer ACR</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="customeracr" placeholder="Customer Acr" value="<%= _.escape(item.get('customeracr') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="releaseInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Relese</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="release" placeholder="Customer release" value="<%= _.escape(item.get('release') || '') %>">
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
        
        <!--<input type="text" class="input-xlarge" id="creationdate" placeholder="Creationdate" value="<%= _date(app.parseDate(item.get('creationdate'))).format('YYYY-MM-DD') %>" />-->
        <input type="text" disabled class="input-xlarge " id="creationdate" placeholder="Creationdate" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteTblprogressButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteTblprogressButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Tblprogress</button>
        <span id="confirmDeleteTblprogressContainer" class="hide">
        <button id="cancelDeleteTblprogressButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteTblprogressButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="tblprogressDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Add an order
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="tblprogressModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>
            <button id="saveTblprogressButton" class="btn btn-primary">Save Changes</button>
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="tblprogressCollectionContainer" class="collectionContainer">
    </div>

<p id="newButtonContainer" class="buttonContainer">
            <button id="newTblprogressButton" class="btn btn-primary">Add an order manually</button>
        </p>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
