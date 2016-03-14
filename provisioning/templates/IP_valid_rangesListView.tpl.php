<?php

	$this->assign('title','Customer IP inventory | Ranges');
	$this->assign('nav','ranges');
        $json_table_content = apiWrapper('http://'.$_SERVER['SERVER_ADDR'].'/'.$_SERVER['REQUEST_URI'].'/../api/ranges');
	$this->display('_Header.tpl.php');
        $table_content = json_decode($json_table_content, true);
       $records_count = $table_content['totalResults'];
?>

<script type="text/javascript">
	$LAB.script("scripts/app/ranges.js").wait(function(){
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
    <?php
     if ($_SESSION['right'] == 10 ) {
         ?>

<h1>
	<i class="icon-th-list"></i> Ranges |  <div class="btn">Records in database: <?php echo $records_count; ?></div>
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span><span id="progress"></span>    /span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="iP_valid_rangesCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Start">Start<% if (page.orderBy == 'Start') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_End">End<% if (page.orderBy == 'End') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>">
				<td><%= _.escape(item.get('start') || '') %></td>
				<td><%= _.escape(item.get('end') || '') %></td>
				<td><%= _.escape(item.get('id') || '') %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="iP_valid_rangesModelTemplate">
         
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="startInputContainer" class="control-group">
					<span class='icon-star' style='color:red'></span><label class="control-label" for="start">Start</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge required ipaddress" required id="start" placeholder="Start" onkeypress="return IPAddressKeyOnly(event)" onblur="validIPranges();" value="<%= _.escape(item.get('start') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="endInputContainer" class="control-group">
					<span class='icon-star' style='color:red'></span><label class="control-label" for="end">End</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge required ipaddress" required id="end" placeholder="End" onkeypress="return IPAddressKeyOnly(event)" onblur="validIPranges();" value="<%= _.escape(item.get('end') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteIP_valid_rangesButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteIP_valid_rangesButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete IP_valid_ranges</button>
						<span id="confirmDeleteIP_valid_rangesContainer" class="hide">
							<button id="cancelDeleteIP_valid_rangesButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteIP_valid_rangesButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="iP_valid_rangesDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit range
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="iP_valid_rangesModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveIP_valid_rangesButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="iP_valid_rangesCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newIP_valid_rangesButton" class="btn btn-primary">Add Range</button>
	</p>

</div> <!-- /container -->

<?php
}
else
{
    echo '<h1>Not allow</h1>';
}	$this->display('_Footer.tpl.php');

?>
