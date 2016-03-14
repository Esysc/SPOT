<?php

$this->assign('title', 'SPOT | Customized Passwords');
$this->assign('nav', 'tblpasswords');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/tblpasswords.js").wait(function () {
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
<style>
    body #tblPasswordDetailDialog {
        /* new custom width */
        width: 100%;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -50%;
    }
</style>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Customized Passwords
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
        </span>
    </h1>
    <!-- underscore template for the collection -->
    <script type="text/template" id="tblPasswordCollectionTemplate">
        <%=  view.getPaginationHtml(page) %>
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Results">Table<% if (page.orderBy == 'Results') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Time">Time<% if (page.orderBy == 'Time') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('salesorder')) %>">
        <td><%= _.escape(item.get('salesorder') || '') %>

        </td>
        <td class="bodyCont"><%= item.get('results') || '' %></td>
        <td><%if (item.get('time')) { %><%= _date(app.parseDate(item.get('time'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="tblPasswordModelTemplate">

        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="salesorderInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Salesorder</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="resultsInputContainer" class="control-group">
        <label class="control-label" for="results">Contents</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge hidden" disabled id="results" rows="3"><%= item.get('results') || '' %></textarea>
        <span id="results"><%= item.get('results') || '' %></span>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="timeInputContainer" class="control-group">
        <label class="control-label" for="time">Time</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="time" type="text" value="<%= _date(app.parseDate(item.get('time'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <div class="input-append bootstrap-timepicker-component">
        <input id="time-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('time'))).format('h:mm A') %>" />
        <span class="add-on"><i class="icon-time"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>




        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteTblPasswordButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="email" class="btn btn-mini btn-primary hide"><i class="icon-mail-forward icon-white"></i> Email the template</button>
        <button id="download" class="btn btn-mini btn-primary"><i class="icon-download icon-white"></i> Create Excel File</button>
        <button id="deleteTblPasswordButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete the table</button>

        <span id="confirmDeleteTblPasswordContainer" class="hide">
        <button id="cancelDeleteTblPasswordButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteTblPasswordButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>

        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="tblPasswordDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> Options
                <span id="modelLoader" class="loader progress progress-striped active icon-download"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="tblPasswordModelContainer"></div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" >Cancel</button>

        </div>
    </div>

    <div id="collectionAlert"></div>

    <div id="tblPasswordCollectionContainer" class="collectionContainer">
    </div>



</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
