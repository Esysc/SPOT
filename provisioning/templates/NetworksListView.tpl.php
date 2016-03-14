<?php
	$this->assign('title','SPOT | Networkses');
	$this->assign('nav','networkses');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/networkses.js").wait(function(){
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
	<i class="icon-th-list"></i> Networkses
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="networksCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Name">Name<% if (page.orderBy == 'Name') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Ip">Ip<% if (page.orderBy == 'Ip') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Mask">Mask<% if (page.orderBy == 'Mask') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Vlanno">Vlanno<% if (page.orderBy == 'Vlanno') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('salesorder')) %>">
				<td><%= _.escape(item.get('salesorder') || '') %></td>
				<td><%= _.escape(item.get('name') || '') %></td>
				<td><%= _.escape(item.get('ip') || '') %></td>
				<td><%= _.escape(item.get('mask') || '') %></td>
				<td><%= _.escape(item.get('vlanno') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="networksModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="salesorderInputContainer" class="control-group">
					<label class="control-label" for="salesorder">Salesorder</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="nameInputContainer" class="control-group">
					<label class="control-label" for="name">Name</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="name" placeholder="Name" value="<%= _.escape(item.get('name') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="ipInputContainer" class="control-group">
					<label class="control-label" for="ip">Ip</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="ip" placeholder="Ip" value="<%= _.escape(item.get('ip') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="maskInputContainer" class="control-group">
					<label class="control-label" for="mask">Mask</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="mask" placeholder="Mask" value="<%= _.escape(item.get('mask') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="vlannoInputContainer" class="control-group">
					<label class="control-label" for="vlanno">Vlanno</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="vlanno" placeholder="Vlanno" value="<%= _.escape(item.get('vlanno') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteNetworksButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteNetworksButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Networks</button>
						<span id="confirmDeleteNetworksContainer" class="hide">
							<button id="cancelDeleteNetworksButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteNetworksButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="networksDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Networks
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="networksModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveNetworksButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="networksCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newNetworksButton" class="btn btn-primary">Add Networks</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
