<?php

$this->assign('title', 'SPOT | Completed orders');
$this->assign('nav', 'tblcompleted');
$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/tblcompleted.js").wait(function() {
        $(document).ready(function() {
            page.init();
        });

        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function() {
            if (!page.isInitialized)
                page.init();
        }, 1000);
        
         $('#top').click( function(e) {
             e.preventDefault();
             console.log('clic');
               $(".modal-body").animate({ scrollTop: 0 }, 'slow'); 
              
             
            });
            
            
         
    });
</script>
<style>
    body #tblprogressDetailDialog {
        /* new custom width */
        width: 900px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -450px;
    }
</style>
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
    <h2 class="alert alert-success" role="alert"><i class="icon-magic"></i> Full provisioned systems</h2>
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
        <% if ( Jdatas.completed == true ) { %>
        <% ACR = Jdatas.CustomerACR %>

        <tr id="<%= _.escape(item.get('id')) %>" data-toggle="tooltip"  title="Click on row to select <%= _.escape(item.get('salesorder') || '') %>.">

        <td><%= _.escape(item.get('id') || '') %></td>
        <td><%= _.escape(item.get('user') || '') %></td>

        <!-- <td><%= item.get('data') %></td> -->

        <td><%= _.escape(item.get('salesorder') || '') %> | <%= ACR %></td>
        <td><%= _.escape(item.get('creationdate') || '') %></td>



        <td><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACfElEQVR4XnWTXUiUTRTH//Ps+om04mZBrNAHRS257OaaBdnqjRj4seGN+vqiENGt0H1C4EWEdBV055V4uRbmV+EHWqBurum2kRXYromQkrXi7ro7c2pGnoEV+sE8/+FwzpznzDkDIspaF8MtLteH1oAn3B7zhjvIs9JGzuWW2LmlpoAjWO866s/kx8QdaQ9YmOGPprbwk8fBwSGxwEARCmCnY0iLzFD06uhtmMis92NP8r2R/2OnVxoJwQq9ikM1cmXZ7PM15HhbF6t61ZovYw0AeP17YXA7s+tYP9iExF9cozTkHEDo0gAgtA07ljj2WMLxNX9rEBJvpKPySuQ/naF/+wVJMOshEwyXK+3feq79js/4qGSqutJIioNeWbOZucveCMVOGpoMKek62QS/tRqSvdwk0oz3GgUszykvTNJpb1DavfpIqSxpfX/z0BbrO/Q50QgkBFJWDpYmJ2SrzN8ysb28Rph2ky3kI9vYdVmC2ptg3KX8i8aqyMoFx1EYGFBowS++B5QAQK6yaRJCCScBIyP4d9lnyXT8ndLOsmZoDLV0edPbi0oZAfxvLM4sNUzYgjdVCb5Pd8mk+8tjPQfd0T5t9811EUZdZMxWkGXcPYELwWbv2cUG3Z6e6DP6Fz0fnxKGLhPeeMg64iY2XO5Vk1g2Xx+QE2Ye4lu6Q1M/FshE7mVmFTzpJmPSQ2zEFZCxVgA4nzrVtmbd+FwsCh27OfuYEcuoXbsHBDmQElDkGUBpDowUQPt8g74l2gBkP6bSudoABPnlkMg+EyN9YSwJGHEODjEkbr03H5M+QGObvVEOTg+Jk5cywiFbxcE3OBNBweiBqFtehQb4A4O0y3i73WxoAAAAAElFTkSuQmCC"/></td>

        </tr>
        <% } %>

        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="tblprogressModelTemplate">
        
        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteTblprogressButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>

        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">

        <button  id="reloadOrder" class="btn btn-mini btn-info"><i class="icon-laptop icon-white"></i> Reload This order </button>
        <button id="deleteTblprogressButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete this record </button>
        <span id="confirmDeleteTblprogressContainer" class="hide">
        <button id="cancelDeleteTblprogressButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteTblprogressButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>


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
        <input type="text" class="input-xlarge uneditable-input" disabled id="user" placeholder="User" value="<%= _.escape(item.get('user') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>

        <div id="dataInputContainer" class="control-group">
        <label class="control-label" for="data">Data</label>
        <div class="controls inline-inputs">
        <textarea style="display:none;" class="input-xlarge" id="data" rows="3"><%= _.escape(item.get('data') || '') %></textarea>
        <% var content = JSON.parse(item.get('data')) %>
        <%=  htmlDiv = '<table class="table table-striped table-bordered table-condensed table-responsive table-hover">' %>
        <% $.each(content, function(key,val) { %>
        <%  if (! $.isPlainObject(val)) {%> 
        <% htmlDiv = htmlDiv + '<tr><td><strong>' + key + '</strong></td><td>' + val + '</td></tr>' %>
        <% } else { %>
        <% $.each(val, function(subkey,subval) { %>
        <% $.each(subval, function(iterkey,iterval) { %> 
        <% htmlDiv = htmlDiv + '<tr><td><strong>'+key+'_' + subkey + ':'+ iterkey + '</strong></td><td>' + iterval + '</td></tr>' %> 
        <% }) %>
        <% }) %>
        <% } %>  
        <% }) %> 
        <% htmlDiv = htmlDiv + '</table>' %>


        <p><%= htmlDiv %></p>



        <span class="help-inline"></span>
        </div>
        </div>
        <div id="salesorderInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Salesorder</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge uneditable-input"  disabled id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="creationdateInputContainer" class="control-group">
        <label class="control-label" for="creationdate">Creationdate</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge uneditable-input"  disabled id="creationdate" placeholder="Creationdate" value="<%= _.escape(item.get('creationdate') || '') %>">
        <span class="help-inline"></span>

         
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
                <i class="icon-edit"></i> System details

                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="tblprogressModelContainer"></div>
        </div>
        <div class="modal-footer">
       <button class="btn btn-success btn-mini" id="excel" >Excel Export</button>
       <button class="btn btn-warning btn-mini" id="pdf" >PDF Export</button>
       <button  id="top" class="btn btn-mini btn-info"><i class="icon-laptop icon-white"></i> Scroll To Top </button>
            <button class="btn" data-dismiss="modal" >Close</button>
            <!--   <button id="saveTblprogressButton" class="btn btn-primary">Save Changes</button> -->
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="tblprogressCollectionContainer" class="collectionContainer">
    </div>



</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
