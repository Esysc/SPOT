<?php
	$this->assign('title','SPOT | Tempdatas');
	$this->assign('nav','tempdatas');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/tempdatas.js").wait(function(){
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
	<i class="icon-th-list"></i> Tempdatas
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>
    <div class="breadcrumb">This is a temporary table to add anything you wish.<strong>Please do not remove an existing value, even if it's possible. </strong> Don't mind to existing placeholder, keep in mind that <i>salesorder</i> is the primary key. <i>MESSAGE</i> key is for important notes on provisioning page.</div>
	<!-- underscore template for the collection -->
	<script type="text/template" id="tempdataCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Data">Data<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Status">Status<% if (page.orderBy == 'Status') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Timestamps">Timestamps<% if (page.orderBy == 'Timestamps') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Message">Message<% if (page.orderBy == 'Message') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Creator">Creator<% if (page.orderBy == 'Creator') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Dwprocessed">Dwprocessed<% if (page.orderBy == 'Dwprocessed') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('salesorder')) %>">
				<td><%= _.escape(item.get('salesorder') || '') %></td>
				<td><%= _.escape(item.get('data') || '') %></td>
				<td><%= _.escape(item.get('status') || '') %></td>
				<td><%if (item.get('timestamps')) { %><%= _date(app.parseDate(item.get('timestamps'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('message') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('creator') || '') %></td>
				<td><%= _.escape(item.get('dwprocessed') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="tempdataModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="salesorderInputContainer" class="control-group">
					<label class="control-label" for="salesorder">Salesorder</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dataInputContainer" class="control-group">
					<label class="control-label" for="data">Data</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="data" placeholder="Data" value="<%= _.escape(item.get('data') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="statusInputContainer" class="control-group">
					<label class="control-label" for="status">Status</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="status" rows="3"><%= _.escape(item.get('status') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="timestampsInputContainer" class="control-group">
					<label class="control-label" for="timestamps">Timestamps</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="timestamps" type="text" value="<%= _date(app.parseDate(item.get('timestamps'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="timestamps-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('timestamps'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="messageInputContainer" class="control-group">
					<label class="control-label" for="message">Message</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="message" rows="3"><%= _.escape(item.get('message') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="creatorInputContainer" class="control-group">
					<label class="control-label" for="creator">Creator</label>
					<div class="controls inline-inputs">
						<select id="creator" name="creator"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dwprocessedInputContainer" class="control-group">
					<label class="control-label" for="dwprocessed">Dwprocessed</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="dwprocessed" placeholder="Dwprocessed" value="<%= _.escape(item.get('dwprocessed') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteTempdataButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteTempdataButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Tempdata</button>
						<span id="confirmDeleteTempdataContainer" class="hide">
							<button id="cancelDeleteTempdataButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteTempdataButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="tempdataDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Tempdata
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="tempdataModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveTempdataButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="tempdataCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newTempdataButton" class="btn btn-primary">Add Tempdata</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
