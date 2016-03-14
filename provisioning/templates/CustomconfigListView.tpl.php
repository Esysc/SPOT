<?php
	$this->assign('title','SPOT | Customer Configurations');
	$this->assign('nav','customconfigs');

	$this->display('_Header.tpl.php');
?>
<style>
    #customconfigDetailDialog {

        /* new custom width */
        width: 1200px;
        /* must be half of the width, minus scrollbar on the left (30px) */
        margin-left: -600px;


    }
</style>
<script type="text/javascript">
	$LAB.script("scripts/app/customconfigs.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> Customer Configurations
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="customconfigCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_ConfigId">Config Id<% if (page.orderBy == 'ConfigId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ConfigTarget">Config Target<% if (page.orderBy == 'ConfigTarget') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_ConfigContent">Config Content<% if (page.orderBy == 'ConfigContent') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
                                <th id="header_Timestamp">Timestamp<% if (page.orderBy == 'Timestamp') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
                                
                                            </tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('configId')) %>">
				<td><%= _.escape(item.get('configId') || '') %></td>
				<td><%= _.escape(item.get('salesorder') || '') %></td>
				<!--<td><%= _.escape(item.get('configTarget') || '') %></td>-->
                                 <td><%= _.escape(item.get('targetName') || '') %></td>
				<td>Click the row for details</td>
                                    <td><%= _date(app.parseDate(item.get('timeStamp'))).format('MMM D, YYYY h:mm A') %></td>
                                      
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="customconfigModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="configIdInputContainer" class="control-group">
					<label class="control-label" for="configId">Config Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="configId"><%= _.escape(item.get('configId') || '') %></span>
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
				<div id="configTargetInputContainer" class="control-group">
					<label class="control-label" for="configTarget">Config Target</label>
					<div class="controls inline-inputs">
						<select id="configTarget" name="configTarget"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="configContentInputContainer" class="control-group">
					<label class="control-label" for="configContent">Config Content
                                         <% if (item.get('configContent') !== '' ) { %>
                                    <p>
                                                            <button class="btn-mini btn-success" id="configdownload"><i class="icon-download"></i> Download File</button>
                            </p>
                    <% }  %>
                               <p>
                                            
                                              <button class="btn-mini btn-info" id="configupload" onclick="$('#fileupload').click()"><i class="icon-upload" /></i> Upload File</button>
                                            <input type="file" id="fileupload" class="hidden" />
                                              
                                   </p>
                                                            
                                                            </label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="configContent" required="required" ><%= _.escape(item.get('configContent') || '') %></textarea>
                                         
                                        
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteCustomconfigButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteCustomconfigButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Customconfig</button>
						<span id="confirmDeleteCustomconfigContainer" class="hide">
							<button id="cancelDeleteCustomconfigButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteCustomconfigButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="customconfigDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Customer Configurations
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="customconfigModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveCustomconfigButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="customconfigCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newCustomconfigButton" class="btn btn-primary">Add Customer Configurations</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
