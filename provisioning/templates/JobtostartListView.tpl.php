<?php
	$this->assign('title','SPOT | Jobtostarts');
	$this->assign('nav','jobtostarts');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/jobtostarts.js").wait(function(){
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
	<i class="icon-th-list"></i> Jobtostarts
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="jobtostartCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Scriptid">Scriptid<% if (page.orderBy == 'Scriptid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Rack">Rack<% if (page.orderBy == 'Rack') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Shelf">Shelf<% if (page.orderBy == 'Shelf') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Clientaddress">Clientaddress<% if (page.orderBy == 'Clientaddress') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Arguments">Arguments<% if (page.orderBy == 'Arguments') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Exesequence">Exesequence<% if (page.orderBy == 'Exesequence') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Scripttarget">Scripttarget<% if (page.orderBy == 'Scripttarget') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Scriptname">Scriptname<% if (page.orderBy == 'Scriptname') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Scriptcontent">Scriptcontent<% if (page.orderBy == 'Scriptcontent') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Interpreter">Interpreter<% if (page.orderBy == 'Interpreter') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Version">Version<% if (page.orderBy == 'Version') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Returncode">Returncode<% if (page.orderBy == 'Returncode') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Returnstdout">Returnstdout<% if (page.orderBy == 'Returnstdout') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Returnstderr">Returnstderr<% if (page.orderBy == 'Returnstderr') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Executionflag">Executionflag<% if (page.orderBy == 'Executionflag') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Exectime">Exectime<% if (page.orderBy == 'Exectime') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('scriptid')) %>">
				<td><%= _.escape(item.get('scriptid') || '') %></td>
				<td><%= _.escape(item.get('salesorder') || '') %></td>
				<td><%= _.escape(item.get('rack') || '') %></td>
				<td><%= _.escape(item.get('shelf') || '') %></td>
				<td><%= _.escape(item.get('clientaddress') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('arguments') || '') %></td>
				<td><%= _.escape(item.get('exesequence') || '') %></td>
				<td><%= _.escape(item.get('scripttarget') || '') %></td>
				<td><%= _.escape(item.get('scriptname') || '') %></td>
				<td><%= _.escape(item.get('scriptcontent') || '') %></td>
				<td><%= _.escape(item.get('interpreter') || '') %></td>
				<td><%= _.escape(item.get('version') || '') %></td>
				<td><%= _.escape(item.get('returncode') || '') %></td>
				<td><%= _.escape(item.get('returnstdout') || '') %></td>
				<td><%= _.escape(item.get('returnstderr') || '') %></td>
				<td><%= _.escape(item.get('executionflag') || '') %></td>
				<td><%= _.escape(item.get('exectime') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="jobtostartModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="scriptidInputContainer" class="control-group">
					<label class="control-label" for="scriptid">Scriptid</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="scriptid" placeholder="Scriptid" value="<%= _.escape(item.get('scriptid') || '') %>">
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
				<div id="rackInputContainer" class="control-group">
					<label class="control-label" for="rack">Rack</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="rack" placeholder="Rack" value="<%= _.escape(item.get('rack') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="shelfInputContainer" class="control-group">
					<label class="control-label" for="shelf">Shelf</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="shelf" placeholder="Shelf" value="<%= _.escape(item.get('shelf') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="clientaddressInputContainer" class="control-group">
					<label class="control-label" for="clientaddress">Clientaddress</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="clientaddress" placeholder="Clientaddress" value="<%= _.escape(item.get('clientaddress') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="argumentsInputContainer" class="control-group">
					<label class="control-label" for="arguments">Arguments</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="arguments" placeholder="Arguments" value="<%= _.escape(item.get('arguments') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="exesequenceInputContainer" class="control-group">
					<label class="control-label" for="exesequence">Exesequence</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="exesequence" placeholder="Exesequence" value="<%= _.escape(item.get('exesequence') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="scripttargetInputContainer" class="control-group">
					<label class="control-label" for="scripttarget">Scripttarget</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="scripttarget" placeholder="Scripttarget" value="<%= _.escape(item.get('scripttarget') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="scriptnameInputContainer" class="control-group">
					<label class="control-label" for="scriptname">Scriptname</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="scriptname" placeholder="Scriptname" value="<%= _.escape(item.get('scriptname') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="scriptcontentInputContainer" class="control-group">
					<label class="control-label" for="scriptcontent">Scriptcontent</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="scriptcontent" placeholder="Scriptcontent" value="<%= _.escape(item.get('scriptcontent') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="interpreterInputContainer" class="control-group">
					<label class="control-label" for="interpreter">Interpreter</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="interpreter" placeholder="Interpreter" value="<%= _.escape(item.get('interpreter') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="versionInputContainer" class="control-group">
					<label class="control-label" for="version">Version</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="version" placeholder="Version" value="<%= _.escape(item.get('version') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="returncodeInputContainer" class="control-group">
					<label class="control-label" for="returncode">Returncode</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="returncode" placeholder="Returncode" value="<%= _.escape(item.get('returncode') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="returnstdoutInputContainer" class="control-group">
					<label class="control-label" for="returnstdout">Returnstdout</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="returnstdout" placeholder="Returnstdout" value="<%= _.escape(item.get('returnstdout') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="returnstderrInputContainer" class="control-group">
					<label class="control-label" for="returnstderr">Returnstderr</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="returnstderr" placeholder="Returnstderr" value="<%= _.escape(item.get('returnstderr') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="executionflagInputContainer" class="control-group">
					<label class="control-label" for="executionflag">Executionflag</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="executionflag" placeholder="Executionflag" value="<%= _.escape(item.get('executionflag') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="exectimeInputContainer" class="control-group">
					<label class="control-label" for="exectime">Exectime</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="exectime" placeholder="Exectime" value="<%= _.escape(item.get('exectime') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteJobtostartButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteJobtostartButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Jobtostart</button>
						<span id="confirmDeleteJobtostartContainer" class="hide">
							<button id="cancelDeleteJobtostartButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteJobtostartButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="jobtostartDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Jobtostart
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="jobtostartModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveJobtostartButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="jobtostartCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newJobtostartButton" class="btn btn-primary">Add Jobtostart</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
