<?php
	$this->assign('title','SPOT | Network equipments');
	$this->assign('nav','networkequipments');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/networkequipments.js").wait(function(){
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
	<i class="icon-th-list"></i> Network equipments
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="networkequipmentCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_EquipId">Equip Id<% if (page.orderBy == 'EquipId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_EquipModel">Equip Model<% if (page.orderBy == 'EquipModel') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Method">Method<% if (page.orderBy == 'Method') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('equipId')) %>">
				<td><%= _.escape(item.get('equipId') || '') %></td>
				<td><%= _.escape(item.get('equipModel') || '') %></td>
				<!--<td ><%= _.escape(item.get('method') || '') %></td>-->
                               <td> <%= _.escape(item.get('methodname') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="networkequipmentModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="equipIdInputContainer" class="control-group">
					<label class="control-label" for="equipId">Equip Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="equipId"><%= _.escape(item.get('equipId') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="equipModelInputContainer" class="control-group">
					<label class="control-label" for="equipModel">Equip Model</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="equipModel" placeholder="Equip Model" value="<%= _.escape(item.get('equipModel') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="methodInputContainer" class="control-group">
					<label class="control-label" for="method">Method</label>
					<div class="controls inline-inputs">
						<select id="method" name="method"></select>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteNetworkequipmentButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteNetworkequipmentButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Networkequipment</button>
						<span id="confirmDeleteNetworkequipmentContainer" class="hide">
							<button id="cancelDeleteNetworkequipmentButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteNetworkequipmentButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="networkequipmentDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Network equipments
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="networkequipmentModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveNetworkequipmentButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="networkequipmentCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newNetworkequipmentButton" class="btn btn-primary">Add Network equipments</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
