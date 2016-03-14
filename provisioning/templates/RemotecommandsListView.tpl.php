<?php

$this->assign('title', 'SPOT | Remote Commands Dashboard');
$this->assign('nav', 'remotecommandses');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/remotecommandses.js").wait(function() {
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
    body .modal {
    /* new custom width */
    width: 900px;
    /* must be half of the width, minus scrollbar on the left (30px) */
    margin-left: -450px;
}
</style>
<style>
    #remotecommandsDetailDialog {

        /* new custom width */
        width: 1200px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -600px;


    }
</style>
<div class="container">

    <h1>
        <i class="icon-th-list"></i> Remote Commands Dashboard
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>
    <p class="badge badge-important">Commands executed more than 2 hours ago are automatically deleted</p>

    <!-- underscore template for the collection -->
    <script type="text/template" id="remotecommandsCollectionTemplate">
        <table class="collection table table-bordered">
        <thead>
        <tr>
        <th id="header_Remotecommandid">ID<% if (page.orderBy == 'Remotecommandid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Salesorder">Sales Order<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Rack">Rack<% if (page.orderBy == 'Rack') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Clientaddress">Target<% if (page.orderBy == 'Clientaddress') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_Arguments">Arguments<% if (page.orderBy == 'Arguments') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Exesequence">Exe Seq<% if (page.orderBy == 'Exesequence') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Scriptid">Script<% if (page.orderBy == 'Scriptid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Returncode">Ret Code<% if (page.orderBy == 'Returncode') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Returnstdout">OUT<% if (page.orderBy == 'Returnstdout') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Returnstderr">ERR<% if (page.orderBy == 'Returnstderr') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Executionflag">Exe flag<% if (page.orderBy == 'Executionflag') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Logtime">Ins Time<% if (page.orderBy == 'Logtime') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Exectime">Exe Time<% if (page.orderBy == 'Exectime') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
      

        <tr id="<%= _.escape(item.get('remotecommandid')) %>" class="<% if (( _.escape(item.get('returncode')) == 0 || _.escape(item.get('returncode')) == 145 ) && ( _.escape(item.get('executionflag')) != 0 && _.escape(item.get('executionflag')) != 100 )) 
        { %>success<% } 
        if (( _.escape(item.get('returncode')) == 1 || _.escape(item.get('returncode')) == 144 || _.escape(item.get('returncode')) == 2)  && ( _.escape(item.get('executionflag')) != 0 && _.escape(item.get('executionflag')) != 100 ))
        { %>error<% } 

        %>">
        <td><%= _.escape(item.get('remotecommandid') || '') %></td>
        <td><%= _.escape(item.get('salesorder') || '') %></td>
        <td>rack<%= _.escape(item.get('rack') || '') %>_shelf<%= _.escape(item.get('shelf') || '') %></td>
        <td><%= _.escape(item.get('clientaddress') || '') %></td>

      <td><%= _.escape(item.get('arguments') || '') %></td>
      
        <td><%= _.escape(item.get('exesequence') || '') %></td>
        <td><%= _.escape(item.get('scriptid') || '') %></td>
        <td><%= _.escape(item.get('returncode') || '') %></td>
     <!--   <td><%= _.escape(item.get('returnstdout') || '') %></td> -->
        <td><% if ( item.get('returnstdout') != '') { var msg = 'Click the row for details...' } else { var msg = 'No messages' } %><strong><%= msg %></strong></td>
     <!--    <td><%= _.escape(item.get('returnstderr') || '') %></td> --> 
        <td><% if ( item.get('returnstderr') != '') { var msg = 'Click the row for details...' } else { var msg = 'No messages' } %><strong><%= msg %></strong></td>
        <td><%= _.escape(item.get('executionflag') || '') %></td>
        <td><%if (item.get('logtime')) { %><%= _date(app.parseDate(item.get('logtime'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
        <td><%= _.escape(item.get('exectime') || '') %></td>

        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="remotecommandsModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="remotecommandidInputContainer" class="control-group">
        <label class="control-label" for="remotecommandid">Record ID</label>
        <div class="controls inline-inputs">
        <span class="input-xlarge uneditable-input" id="remotecommandid"><%= _.escape(item.get('remotecommandid') || '') %></span>
        <span class="help-inline"></span>
        </div>
        </div> 
        <!--	<div id="salesorderInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Sales Order</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
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
        <div id="clientaddressInputContainer" class="control-group">
        <label class="control-label" for="clientaddress">Target</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="clientaddress" placeholder="Clientaddress" value="<%= _.escape(item.get('clientaddress') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="argumentsInputContainer" class="control-group">
        <label class="control-label" for="arguments">Arguments</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="arguments" placeholder="Arguments" value="<%= _.escape(item.get('arguments') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>  
        <div id="exesequenceInputContainer" class="control-group">
        <label class="control-label" for="exesequence">Execution order</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="exesequence" placeholder="Exesequence" value="<%= _.escape(item.get('exesequence') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="scriptidInputContainer" class="control-group">
        <label class="control-label" for="scriptid">Script id</label>
        <div class="controls inline-inputs">
        <select id="scriptid" name="scriptid"></select>
        <span class="help-inline"></span>
        </div>
        </div> -->
        <div id="returncodeInputContainer" class="control-group">
        <label class="control-label" for="returncode">Return Code</label>
        <div class="controls inline-inputs">
        <input type="text" disabled class="input-xlarge" id="returncode" placeholder="Returncode" value="<%= _.escape(item.get('returncode') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="returnstdoutInputContainer" class="control-group">
        <label class="control-label" for="returnstdout">STDOUT</label>
        <div class="controls inline-inputs">
        <textarea  class="input-xlarge" disabled id="returnstdout"> <%= _.escape(item.get('returnstdout') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="returnstderrInputContainer" class="control-group">
        <label class="control-label" for="returnstderr">STDERR</label>
        <div class="controls inline-inputs">
        <textarea  class="input-xlarge" disabled id="returnstderr"><%= _.escape(item.get('returnstderr') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div> 
        <!--	<div id="executionflagInputContainer" class="control-group">
        <label class="control-label" for="executionflag">Execution Flag</label>
        <div class="controls inline-inputs">
        <select id="executionflag" name="executionflag"></select>
        <span class="help-inline"></span>
        </div>
        </div> -->
        <div id="logtimeInputContainer" class="control-group">
        <label class="control-label" for="logtime">Insertion Time</label>
        <div class="controls inline-inputs">
        <!--	<div class="input-append date date-picker" data-date-format="yyyy-mm-dd"> -->
        <!--		<input id="logtime" type="text" value="<%= _date(app.parseDate(item.get('logtime'))).format('YYYY-MM-DD') %>" /> -->
        <span id="logtime"><%= _date(app.parseDate(item.get('logtime'))).format('YYYY-MM-DD') %></span>
        <span id="logtime-time"><%= _date(app.parseDate(item.get('logtime'))).format('h:mm A') %></span>
        <!--		<span class="add-on"><i class="icon-calendar"></i></span> -->
        <!--	</div> -->
        <div class="input-append bootstrap-timepicker-component">
        <!--		<input id="logtime-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('logtime'))).format('h:mm A') %>" /> -->
        <!--		<span class="add-on"><i class="icon-time"></i></span> -->
        </div>
        <span class="help-inline"></span>
        </div> 
        </div>
        <div id="exectimeInputContainer" class="control-group">
        <label class="control-label" for="exectime">Execution Time</label>
        <div class="controls inline-inputs">
        <!--	<input type="text" class="input-xlarge" id="exectime" placeholder="Exectime" value="<%= _.escape(item.get('exectime') || '') %>"> -->
        <span id="exectime"><%= _.escape(item.get('exectime') || '') %></span>
        <span class="help-inline"></span>
        </div>
        </div>  
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteRemotecommandsButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteRemotecommandsButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Record</button>
        <span id="confirmDeleteRemotecommandsContainer" class="hide">
        <button id="cancelDeleteRemotecommandsButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteRemotecommandsButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="remotecommandsDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Execution details
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="remotecommandsModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Close</button>
            <!--   <button id="saveRemotecommandsButton" class="btn btn-primary">Save Changes</button> -->
        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="remotecommandsCollectionContainer" class="collectionContainer">
    </div>

    <p id="newButtonContainer" class="buttonContainer">
        <!--   <button id="newRemotecommandsButton" class="btn btn-primary">Add Remotecommands</button> -->
    </p>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
