<?php
	$this->assign('title','SPOT | Sysprodrackses');
	$this->assign('nav','sysprodrackses');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/sysprodrackses.js").wait(function(){
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
	<i class="icon-th-list"></i> Sysprodrackses
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="sysprodracksCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Idracks">Idracks<% if (page.orderBy == 'Idracks') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Reponse">Reponse<% if (page.orderBy == 'Reponse') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Machinetype">Machinetype<% if (page.orderBy == 'Machinetype') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Ipaddress">Ipaddress<% if (page.orderBy == 'Ipaddress') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Timestamp">Timestamp<% if (page.orderBy == 'Timestamp') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('idracks')) %>">
				<td><%= _.escape(item.get('idracks') || '') %></td>
				<td><%= _.escape(item.get('reponse') || '') %></td>
				<td><%= _.escape(item.get('machinetype') || '') %></td>
				<td><%= _.escape(item.get('ipaddress') || '') %></td>
				<td><%if (item.get('timestamp')) { %><%= _date(app.parseDate(item.get('timestamp'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="sysprodracksModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idracksInputContainer" class="control-group">
					<label class="control-label" for="idracks">Idracks</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="idracks" placeholder="Idracks" value="<%= _.escape(item.get('idracks') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="reponseInputContainer" class="control-group">
					<label class="control-label" for="reponse">Reponse</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="reponse" placeholder="Reponse" value="<%= _.escape(item.get('reponse') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="machinetypeInputContainer" class="control-group">
					<label class="control-label" for="machinetype">Machinetype</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="machinetype" placeholder="Machinetype" value="<%= _.escape(item.get('machinetype') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="ipaddressInputContainer" class="control-group">
					<label class="control-label" for="ipaddress">Ipaddress</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="ipaddress" placeholder="Ipaddress" value="<%= _.escape(item.get('ipaddress') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="timestampInputContainer" class="control-group">
					<label class="control-label" for="timestamp">Timestamp</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="timestamp" type="text" value="<%= _date(app.parseDate(item.get('timestamp'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="timestamp-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('timestamp'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteSysprodracksButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteSysprodracksButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Sysprodracks</button>
						<span id="confirmDeleteSysprodracksContainer" class="hide">
							<button id="cancelDeleteSysprodracksButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteSysprodracksButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="sysprodracksDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Sysprodracks
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="sysprodracksModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveSysprodracksButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="sysprodracksCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newSysprodracksButton" class="btn btn-primary">Add Sysprodracks</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
