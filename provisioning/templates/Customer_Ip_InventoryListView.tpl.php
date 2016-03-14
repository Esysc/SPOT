<?php

$this->assign('title', 'Customer IP inventory | Adresses');
$this->assign('nav', 'adresses');

$this->display('_Header.tpl.php');
$subnet = "Proposed subnet: " . subpicker();

$date = date("Y-m-d");
$json_last_synch = apiWrapper('http://'.$_SERVER['SERVER_ADDR'].'/'.$_SERVER['REQUEST_URI'].'/../api/hotline');
$json_table_content = apiWrapper('http://'.$_SERVER['SERVER_ADDR'].'/'.$_SERVER['REQUEST_URI'].'/../api/adresses');

$obj = json_decode($json_last_synch, true);
$table_content = json_decode($json_table_content, true);
$table_rows = $table_content['rows'];

$records_count = $table_content['totalResults'];
//$_SESSION['datatoExcel'] = jsonToHtml($table_rows);
$this->assign('user', $_SESSION['login']);

?>
<style>
    body .modal {
    /* new custom width */
    width: 900px;
    /* must be half of the width, minus scrollbar on the left (30px) */
    margin-left: -450px;
}
</style>


<script type="text/javascript">
    $LAB.script("scripts/app/adresses.js").wait(function() {
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
        <i class="icon-th-list"></i> IP Inventory - Last Sync CS  <?php
echo $obj['rows'][0]['lastSyncDate'];

?>
        
 | <a href="/SPOT/provisioning/api/exportIP" class="btn btn-info btn-mini">Excel Export</a>  | <div class="btn">Records in database: <?php echo $records_count; ?></div>
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span><span id="progress"></span></span>
        <span class='input-append pull-right searchContainer'>
            <input id='filter' type="text" placeholder="Search..." />
            <button class='btn add-on'><i class="icon-search"></i></button>
            
        </span>
    </h1>

    <!-- underscore template for the collection -->
    <script type="text/template" id="customer_Ip_InventoryCollectionTemplate">
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th id="header_Custipid">Record n.<% if (page.orderBy == 'Custipid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Subnet">Subnet<% if (page.orderBy == 'Subnet') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Netmask">Netmask<% if (page.orderBy == 'Netmask') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Account">Customer<% if (page.orderBy == 'Account') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Location">Location<% if (page.orderBy == 'Location') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_SystemName">System Name<% if (page.orderBy == 'SystemName') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <!--		<th id="header_Entt">Entt<% if (page.orderBy == 'Entt') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th> -->
        <!--		<th id="header_RemoteAccess">Remote Access<% if (page.orderBy == 'RemoteAccess') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>-->
        <th id="header_Comments">Comments<% if (page.orderBy == 'Comments') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Valdate">Validation Date<% if (page.orderBy == 'Valdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_ValidatedBy">Validated By<% if (page.orderBy == 'ValidatedBy') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Lsmod">Last Modification Date<% if (page.orderBy == 'Lsmod') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Status">Status<% if (page.orderBy == 'Status') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
       
        </tr>
        </thead>
        <tbody>
        <% items.each(function(item) { %>
        <tr id="<%= _.escape(item.get('custipid')) %>" class="ips">
        <td><%= _.escape(item.get('custipid') || '') %></td>
        <td><%= _.escape(item.get('subnet') || '') %></td>
        <td><%= _.escape(item.get('netmask') || '') %></td>
        <td><%= _.escape(item.get('account') || '') %></td>
        <td><%= _.escape(item.get('location') || '') %></td>

        <td><%= item.get('systemName') %></td>
        <!--				<td><%= _.escape(item.get('entt') || '') %></td> -->
        <!--				<td><%= _.escape(item.get('remoteAccess') || '') %></td> -->
        <td><%= item.get('comments') %></td>
        <td><%= _.escape(item.get('valdate') || '') %></td>
        <td><%= _.escape(item.get('validatedBy') || '') %></td>
        <td><%if (item.get('lsmod')) { %><%= _date(app.parseDate(item.get('lsmod'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
        <td><% 
        // modify status field with an icon
        if ( item.get('status') === 'active'){
        %>


        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACfElEQVR4XnWTXUiUTRTH//Ps+om04mZBrNAHRS257OaaBdnqjRj4seGN+vqiENGt0H1C4EWEdBV055V4uRbmV+EHWqBurum2kRXYromQkrXi7ro7c2pGnoEV+sE8/+FwzpznzDkDIspaF8MtLteH1oAn3B7zhjvIs9JGzuWW2LmlpoAjWO866s/kx8QdaQ9YmOGPprbwk8fBwSGxwEARCmCnY0iLzFD06uhtmMis92NP8r2R/2OnVxoJwQq9ikM1cmXZ7PM15HhbF6t61ZovYw0AeP17YXA7s+tYP9iExF9cozTkHEDo0gAgtA07ljj2WMLxNX9rEBJvpKPySuQ/naF/+wVJMOshEwyXK+3feq79js/4qGSqutJIioNeWbOZucveCMVOGpoMKek62QS/tRqSvdwk0oz3GgUszykvTNJpb1DavfpIqSxpfX/z0BbrO/Q50QgkBFJWDpYmJ2SrzN8ysb28Rph2ky3kI9vYdVmC2ptg3KX8i8aqyMoFx1EYGFBowS++B5QAQK6yaRJCCScBIyP4d9lnyXT8ndLOsmZoDLV0edPbi0oZAfxvLM4sNUzYgjdVCb5Pd8mk+8tjPQfd0T5t9811EUZdZMxWkGXcPYELwWbv2cUG3Z6e6DP6Fz0fnxKGLhPeeMg64iY2XO5Vk1g2Xx+QE2Ye4lu6Q1M/FshE7mVmFTzpJmPSQ2zEFZCxVgA4nzrVtmbd+FwsCh27OfuYEcuoXbsHBDmQElDkGUBpDowUQPt8g74l2gBkP6bSudoABPnlkMg+EyN9YSwJGHEODjEkbr03H5M+QGObvVEOTg+Jk5cywiFbxcE3OBNBweiBqFtehQb4A4O0y3i73WxoAAAAAElFTkSuQmCC" alt="Active" title="Active"/>
        <%
        } else {

        %>                      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAACd0lEQVR4XnVSX0hTYRQ/986N9ZIV9pCYFa223PRpk4hohv1RetH+WBHLoQ891MMoMEjQiqy3iKheCgQhfKobFYXDKGykW47lislw6mpeEEKwVqzt3n2n7/tu+2hGP/jdczic33fOd78fIGIZk2cONcycP66kenyZmUtdmOzxYeJsWybu36d8ONbYsLK/TJy6cEKZvXgap1rsGKm3YNgBnBGXGaNNtRjz7cVox07lnwNy80nrXG93JnFqNxMITnrWMJbV3u+3YfhIY+Zd3zkr08pAod7pH/6VTtZkoyFgWNvcxqNLiXEWUdSAZFKAS4s1EBkd5oW53i4PXVtM+Pp4EBlGtgGW8GCTkauPBkVf6IAdx5ptHlnPLg/8mAqLyVXtfp6n8iCQRyNWH/aD5jU2kZcWAfO5Adm0el1dXp1nNSru5DF0JWAI1TRkF9I8/3LDqFXTnuUigJzLgi7LdUCfSqxVwn1nJT61sZ9YiUOuSnYFnpfwcIvRH9y1ASuIrsFKSJIEVSaAYvYb2Nj6Vl4VDXQDDqLpIJNCXpUqzLzwPfKGR/vRTtFskgyu/3M9dcLoQTqE6AUV4r6mIDUJXynh82IJn64FhA8+Xw+IutLhxSF6hTGnBUec1iDEu1vc1GHCKLO3+/F/iNzsx7u1gKPbAV+6VuEzh8XNncjsSR0mDnl70osL46+FkOZsMhcrWwFf0ekvHGZFWDl267KV2XPCuAoln8JWpSJGlvMaFz93mDP3Npu4lSX2KWG8dYdCCLZRk/B3lggRP6wgm+GnZALU9Set04V2MCAOEAjt2VhPQLpaROLWCalhTwVafgF0bZKy7+C09vFvwW/UvuzpbRuZjgAAAABJRU5ErkJggg==" alt="Removed" title="Removed"/>
        <%
        }
        %>




        </td>
        
        </tr>
        <% }); %>
        </tbody>
        </table>
        
        <%=  view.getPaginationHtml(page) %>
    </script>



    <!-- underscore template for the model -->
    <script type="text/template" id="customer_Ip_InventoryModelTemplate">
        <form class="form-horizontal" id="formIP" onsubmit="return false;">
        <fieldset>
        <!--	<div id="custipidInputContainer" class="control-group">
        <label class="control-label" for="custipid">Custipid</label>
        <div class="controls inline-inputs">
        <span class="input-xlarge uneditable-input"  id="custipid"><%= _.escape(item.get('custipid') || '') %></span>
        <span class="help-inline"></span>
        </div>
        </div> -->
        <div id="subnetInputContainer" class="control-group required">
        <label class="control-label" for="subnet">Subnet</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge subnet"  onkeypress="return IPAddressKeyOnly(event)" onblur=" checkUniq_all('subnet');"  id="subnet" placeholder="<?php echo $subnet; ?>" value="<%= _.escape(item.get('subnet') || '') %>" >
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="netmaskInputContainer" class="control-group required">
        <label class="control-label" for="netmask">Netmask</label>
        <div class="controls inline-inputs">
        <select id="netmask" onchange="checkUniq_all('subnet', this.name, this.value);">
        <option value="24">24</option>
        <option value="23">23</option>
        <option value="22">22</option>
        <option value="21">21</option>
        <option value="20">20</option>
        <option value="19">19</option>
        <option value="19">18</option>
        <option value="17">17</option>
        <option value="16">16</option>
        </select>
        <!--		<input type="text" class="input-xlarge" onchange="checkUniq_subnet(this.name, this.value);" id="netmask" placeholder="Netmask" value="<%= _.escape(item.get('netmask') || '') %>">-->
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="accountInputContainer" class="control-group required">
        <label class="control-label" for="account">Customer</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="account" placeholder="Customer" value="<%= _.escape(item.get('account') || '') %>">
	<div id="result"></div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="locationInputContainer" class="control-group required">
        <label class="control-label" for="location">Location</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="location" placeholder="Location" value="<%= _.escape(item.get('location') || '') %>">
	<div id="result2"></div>
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="systemNameInputContainer" class="control-group">
        <label class="control-label" for="systemName">System Name</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge"  id="systemName"  rows="5"><%= _.escape(item.get('systemName') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <!--		<div id="enttInputContainer" class="control-group">
        <label class="control-label" for="entt">Entt</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="entt" placeholder="Entt" value="<%= _.escape(item.get('entt') || '') %>">
        <span class="help-inline"></span>
        </div>
        </div>
        <div id="remoteAccessInputContainer" class="control-group">
        <label class="control-label" for="remoteAccess">Remote Access</label>
        <div class="controls inline-inputs">
        <input type="text" class="input-xlarge" id="remoteAccess" placeholder="Remote Access" value="<%= _.escape(item.get('remoteAccess') || '') %>">
        <span class="help-inline"></span>
        </div> 
        </div> -->
        <div id="commentsInputContainer" class="control-group">
        <label class="control-label" for="comments">Comments</label>
        <div class="controls inline-inputs">
        <textarea class="input-xlarge" id="comments" rows="5"><%= _.escape(item.get('comments') || '') %></textarea>
        <span class="help-inline"></span>
        </div>
        </div>
        <!--			<div id="valdateInputContainer" class="control-group">
        <label class="control-label" for="valdate">Valdate</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd"> -->
        <input type="hidden" class="input-xlarge" id="valdate" placeholder="Valdate" value="<%= _date(app.parseDate(item.get('lsmod'))).format('YYYY-MM-DD')  %>">
        <!--<span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div> -->
        <div id="validatedByInputContainer" class="control-group required">
        <label class="control-label" for="validatedBy">Validated By</label>
        <div class="controls inline-inputs">
        <select id="validatedBy"  placeholder="Validated By">
	<option value="<?php echo $this->user ?>"><?php echo $this->user ?></option>
        <option value="ACS">ACS</option>
        <option value="DCL">DCL</option>
        <option value="DDC">DDC</option>
        </select>
        <!--		<input type="text" class="input-xlarge" id="validatedBy" placeholder="Validated By" value="<%= _.escape(item.get('validatedBy') || '') %>"> -->
        <span class="help-inline"></span>
        </div>
        </div>
        <!-- <div id="lsmodInputContainer" class="control-group">
        <label class="control-label" for="lsmod">Lsmod</label>
        <div class="controls inline-inputs">
        <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">-->
        <input id="lsmod" type="hidden" value="<%= _date(app.parseDate(item.get('lsmod'))).format('YYYY-MM-DD') %>" />
        <!--	<span class="add-on"><i class="icon-calendar"></i></span>
        </div>
        <div class="input-append bootstrap-timepicker-component"> -->
        <input id="lsmod-time" type="hidden" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('lsmod'))).format('h:mm A') %>" />
        <!--	<span class="add-on"><i class="icon-time"></i></span>
        </div>
        <span class="help-inline"></span>
        </div>
        </div> -->
        <div id="statusInputContainer" class="control-group">
        <label class="control-label" for="status">Status</label>
        <div class="controls inline-inputs">
        <select  id="status" >
        <option value="active">On</option>
        <option value="inactive">Off</option>
        </select>
        </div>
        </div>
        </fieldset>
        </form>

        <!-- delete button is is a separate form to prevent enter key from triggering a delete -->
        <form id="deleteCustomer_Ip_InventoryButtonContainer" class="form-horizontal" onsubmit="return false;">
        <fieldset>
        <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
        <!--		<button id="deleteCustomer_Ip_InventoryButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Customer_Ip_Inventory</button> -->
        <span id="confirmDeleteCustomer_Ip_InventoryContainer" class="hide">
        <button id="cancelDeleteCustomer_Ip_InventoryButton" class="btn btn-mini">Cancel</button>
        <button id="confirmDeleteCustomer_Ip_InventoryButton" onClick="checkUniq_all('SUBNET');" class="btn btn-mini btn-danger">Confirm</button>
        </span>
        </div>
        </div>
        </fieldset>
        </form>
    </script>

    <!-- modal edit dialog -->
    <?php
    if ($_SESSION['right'] == 10) {
        ?>
        <div class="modal hide fade" id="customer_Ip_InventoryDetailDialog">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h3>
                    <i class="icon-edit"></i> Edit Record
                    <span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
                </h3>
            </div>
            <div class="modal-body">
                <div id="modelAlert"></div>
                <div id="customer_Ip_InventoryModelContainer"></div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" >Cancel</button>
                <button id="saveCustomer_Ip_InventoryButton" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
<?php } ?>
    <div id="collectionAlert"></div>

    <div id="customer_Ip_InventoryCollectionContainer" class="collectionContainer">
    </div>
<?php
if ($_SESSION['right'] == 10) {
    ?>
        <p id="newButtonContainer" class="buttonContainer">
            <button id="newCustomer_Ip_InventoryButton" class="btn btn-primary">Add a record</button>
        </p>
<?php }

?>
</div> <!-- /container -->
<?php
$this->display('_Footer.tpl.php');
?>
