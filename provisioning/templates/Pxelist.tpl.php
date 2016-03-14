<?php
	$this->assign('title','SPOT | Client Pxe Inventory');
	$this->assign('nav','pxeinv');

	$this->display('_Header.tpl.php');
        
        
?>

<script type="text/javascript">
	$LAB.script("scripts/app/tempdatas.js").wait(function(){
		$(document).ready(function(){
			page.init();
                        $('#filter').trigger('change');
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">

<h1>
	<i class="icon-th-list"></i> PXE clients inventory
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
        <input type="hidden" id="filter" value="IPXE" />
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="tempdataCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Timestamps">Timestamps<% if (page.orderBy == 'Timestamps') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Message">Message<% if (page.orderBy == 'Message') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody> 
		<% items.each(function(item) { %>
                            <% if ( item.get('salesorder').indexOf('IPXE') >=0 ) { %>
			<tr id="<%= _.escape(item.get('salesorder')) %>">
				
				
				
				<td><%if (item.get('timestamps')) { %><%= _date(app.parseDate(item.get('timestamps'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= item.get('message') || '' %></td>

			</tr>
                <% } %>
		<% }); %>
		</tbody> 
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="tempdataModelTemplate">
		

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
				<i class="icon-edit"></i> Delete Record
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="tempdataModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="tempdataCollectionContainer" class="collectionContainer">
	</div>

	

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
