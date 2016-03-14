<?php
	$this->assign('title','ODSDB | CS Synchronisation date');
	$this->assign('nav','CS Synchronisation date');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/hotlinesyncdates.js").wait(function(){
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
	<i class="icon-th-list"></i> HotlineSyncDates
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="hotlineSyncDateCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				
				<th id="header_LastSyncDate">Last Sync Date<% if (page.orderBy == 'LastSyncDate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				
				<td><h1><%if (item.get('lastSyncDate')) { %><%= _date(app.parseDate(item.get('lastSyncDate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></h1></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="hotlineSyncDateModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				
				<div id="lastSyncDateInputContainer" class="control-group">
					<label class="control-label" for="lastSyncDate">Update Date</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="lastSyncDate" type="text" value="<%= _date(app.parseDate(item.get('lastSyncDate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="hotlineSyncDateDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Synchronisation date
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="hotlineSyncDateModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveHotlineSyncDateButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="hotlineSyncDateCollectionContainer" class="collectionContainer">
	</div>

	

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
