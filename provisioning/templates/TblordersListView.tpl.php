<?php
$this->assign('title', 'SPOT | Production DB Orders');
$this->assign('nav', 'tblorderses');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/tblorderses.js").wait(function () {
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
<script src="scripts/jquery-ui.js"></script>

<script>

    $(function () {

        $("#serial").autocomplete({
            source: "includes/json_populate.php",
            delay: 500,
            minLength: 3,
            autoFill: true,
            select: function (event, ui) {
                var invoice = ui.item.value;
                $.ajax({
                    url: "includes/load_visio.php?serial=" + invoice,
                    type: 'GET',
                    success: function (data) {
                        var obj = JSON.parse(data);
                        $.each(obj, function (key, value) {
                            $('#find_serial').html('<p class="label label-info"> Component Type: ' + key + '</p>');
                            console.log(key);
                            $('#filter').val(value[0]).trigger('change');

                        });
                    }
                });
            }
        });
        $('#export').on('click', function (e) {
            var table = $('#tblordersModelContainer').html();
            var arr = table.split('Components');
            table = arr[1];
            console.log(table);
            e.preventDefault();
            $.ajax({
                url: "includes/loadSession.php",
                type: "POST",
                data: {datatoPdf: table},
                success: function (data) {
                    var title = $('#filter').val();
                    var url = 'libs/App/excelexport.php?var=datatoPdf&debug=false&title=' + title + 'order_details.xlsx';
                    $('#tblordersDetailDialog').modal('hide');
                    window.location.href = url;


                }
            });
        });
    });
    $('.select').select(function (event, ui) {
        alert("|" + $("#serial").val() + "|1stAlert");
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
    #tblordersDetailDialog {

        /* new custom width */
        width: 1200px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -600px;


    }
    .ui-autocomplete-loading {
        background: url('images/loader.gif') bottom center no-repeat;
    }

    .ui-autocomplete
    {
        opacity:0.8;
        filter:alpha(opacity=80); /* For IE8 and earlier */
        width:25px; /* This fixes the dimension of result div */
    }

</style>
<div class="container">

    <h1>
        <i class="icon-th-list"></i> Stored Orders (Old DB)

        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>

        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search any value..." />
            <button class='btn add-on'><i class="icon-search"></i></button>

            <input type="text" class="serial" size="25" name="serial" id="serial" value=""  placeholder="Serial number" />
            <button class='btn add-on'><i class="icon-search"></i></button>








        </span>
    </h1>



    <!-- underscore template for the collection -->
    <script type="text/template" id="tblordersCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Salesorder">Sales Order<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Programmanager">Program Manager<% if (page.orderBy == 'Programmanager') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Siteengineer">Site Engineer<% if (page.orderBy == 'Siteengineer') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Sysprodactor">Sysprod Actor<% if (page.orderBy == 'Sysprodactor') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Release">Release<% if (page.orderBy == 'Release') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_Comment">Comment<% if (page.orderBy == 'Comment') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Startdate">Start Date<% if (page.orderBy == 'Startdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Enddate">End Date<% if (page.orderBy == 'Enddate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Prodstartdate">Prod Start<% if (page.orderBy == 'Prodstartdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Prodenddate">Prod End<% if (page.orderBy == 'Prodenddate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Customer">Customer<% if (page.orderBy == 'Customer') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Timezone">Timezone<% if (page.orderBy == 'Timezone') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Customersigle">Customer ACR<% if (page.orderBy == 'Customersigle') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('salesorder')) %>">
        <td><%= _.escape(item.get('salesorder') || '') %></td>
        <td><%= _.escape(item.get('programmanager') || '') %></td>
        <td><%= _.escape(item.get('siteengineer') || '') %></td>
        <td><%= _.escape(item.get('sysprodactor') || '') %></td>
        <td><%= _.escape(item.get('release') || '') %></td>
        <td><%= _.escape(item.get('comment') || '') %></td>
        <td><%if (item.get('startdate')) { %><%= _date(app.parseDate(item.get('startdate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
        <td><%if (item.get('enddate')) { %><%= _date(app.parseDate(item.get('enddate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
        <td><%if (item.get('prodstartdate')) { %><%= _date(app.parseDate(item.get('prodstartdate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
        <td><%if (item.get('prodenddate')) { %><%= _date(app.parseDate(item.get('prodenddate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
        <td><%= _.escape(item.get('customer') || '') %></td>
        <td><%= _.escape(item.get('timezone') || '') %></td>
        <td><%= _.escape(item.get('customersigle') || '') %></td>
        </tr>
        <% }); %>
        </tbody>
        </table>

        <%=  view.getPaginationHtml(page) %>
    </script>

    <!-- underscore template for the model -->
    <script type="text/template" id="tblordersModelTemplate">
        <form class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div id="salesorderInputContainer" class="control-group">
        <label class="control-label" for="salesorder">Salesorder</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="programmanagerInputContainer" class="control-group">
        <label class="control-label" for="programmanager">Programmanager</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="programmanager" placeholder="Programmanager" value="<%= _.escape(item.get('programmanager') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="siteengineerInputContainer" class="control-group">
        <label class="control-label" for="siteengineer">Siteengineer</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="siteengineer" placeholder="Siteengineer" value="<%= _.escape(item.get('siteengineer') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="sysprodactorInputContainer" class="control-group">
        <label class="control-label" for="sysprodactor">Sysprodactor</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="sysprodactor" placeholder="Sysprodactor" value="<%= _.escape(item.get('sysprodactor') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="releaseInputContainer" class="control-group">
        <label class="control-label" for="release">Release</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="release" placeholder="Release" value="<%= _.escape(item.get('release') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="commentInputContainer" class="control-group">
        <label class="control-label" for="comment">Comment</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="comment" placeholder="Comment" value="<%= _.escape(item.get('comment') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="startdateInputContainer" class="control-group">
        <label class="control-label" for="startdate">Startdate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="startdate" type="text" value="<%= _date(app.parseDate(item.get('startdate'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="enddateInputContainer" class="control-group">
        <label class="control-label" for="enddate">Enddate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="enddate" type="text" value="<%= _date(app.parseDate(item.get('enddate'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="prodstartdateInputContainer" class="control-group">
        <label class="control-label" for="prodstartdate">Prodstartdate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="prodstartdate" type="text" value="<%= _date(app.parseDate(item.get('prodstartdate'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="prodenddateInputContainer" class="control-group">
        <label class="control-label" for="prodenddate">Prodenddate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
        <input id="prodenddate" type="text" value="<%= _date(app.parseDate(item.get('prodenddate'))).format('YYYY-MM-DD') %>" />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="customerInputContainer" class="control-group">
        <label class="control-label" for="customer">Customer</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="customer" placeholder="Customer" value="<%= _.escape(item.get('customer') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="timezoneInputContainer" class="control-group">
        <label class="control-label" for="timezone">Timezone</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="timezone" placeholder="Timezone" value="<%= _.escape(item.get('timezone') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="cctsnapshotpathInputContainer" class="control-group">
        <label class="control-label" for="cctsnapshotpath">Cctsnapshotpath</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="cctsnapshotpath" placeholder="Cctsnapshotpath" value="<%= _.escape(item.get('cctsnapshotpath') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="sidInputContainer" class="control-group">
        <label class="control-label" for="sid">Sid</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="sid" placeholder="Sid" value="<%= _.escape(item.get('sid') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="customersigleInputContainer" class="control-group">
        <label class="control-label" for="customersigle">Customersigle</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="customersigle" placeholder="Customersigle" value="<%= _.escape(item.get('customersigle') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="exportedInputContainer" class="control-group">
        <label class="control-label" for="exported">Exported</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="exported" placeholder="Exported" value="<%= _.escape(item.get('exported') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteTblordersButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <button id="deleteTblordersButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Tblorders</button>
        <span id="confirmDeleteTblordersContainer" class="hide">
        <button id="cancelDeleteTblordersButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteTblordersButton" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <div class="modal hide fade" id="tblordersDetailDialog">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h3>
                <i class="icon-edit"></i> SalesOrder Detail
                <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
            </h3>
        </div>
        <div class="modal-body">
            <div id="modelAlert"></div>
            <div id="tblordersModelContainer"></div>
        </div>

        <div class="modal-footer">
            <?php
            if ($_SESSION['right'] == 1000) {
                ?> 
                <button class="btn" data-dismiss="modal" >Cancel</button>
                <button id="saveTblordersButton" class="btn btn-primary">Save Changes</button>
            <?php } ?>
            <button id="export" class="btn btn-success btn-mini">Export to Excel</button>
        </div>

    </div>

    <div id="collectionAlert"></div>

    <div id="tblordersCollectionContainer" class="collectionContainer">
    </div>
    <?php
    if ($_SESSION['right'] == 1000) {
        ?>
        <p id="newButtonContainer" class="buttonContainer">
            <button id="newTblordersButton" class="btn btn-primary">Add a order</button>
        </p>

    <?php } ?>
    <div id="find_serial"></div>
</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
