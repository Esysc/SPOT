<?php
	$this->assign('title','SPOT | Userses');
	$this->assign('nav','userses');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/userses.js").wait(function(){
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
	<i class="icon-th-list"></i> Userses
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="usersCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_UId">U Id<% if (page.orderBy == 'UId') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Username">Username<% if (page.orderBy == 'Username') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Password">Password<% if (page.orderBy == 'Password') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_URight">U Right<% if (page.orderBy == 'URight') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_UAdUser">U Ad User<% if (page.orderBy == 'UAdUser') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_UAdPassword">U Ad Password<% if (page.orderBy == 'UAdPassword') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_UPhone">U Phone<% if (page.orderBy == 'UPhone') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_UFullName">U Full Name<% if (page.orderBy == 'UFullName') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_UAdEmail">U Ad Email<% if (page.orderBy == 'UAdEmail') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Token">Token<% if (page.orderBy == 'Token') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('uId')) %>">
				<td><%= _.escape(item.get('uId') || '') %></td>
				<td><%= _.escape(item.get('username') || '') %></td>
				<td><%= _.escape(item.get('password') || '') %></td>
				<td><%= _.escape(item.get('uRight') || '') %></td>
				<td><%= _.escape(item.get('uAdUser') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('uAdPassword') || '') %></td>
				<td><%= _.escape(item.get('uPhone') || '') %></td>
				<td><%= _.escape(item.get('uFullName') || '') %></td>
				<td><%= _.escape(item.get('uAdEmail') || '') %></td>
				<td><%= _.escape(item.get('token') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="usersModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="uIdInputContainer" class="control-group">
					<label class="control-label" for="uId">U Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="uId"><%= _.escape(item.get('uId') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="usernameInputContainer" class="control-group">
					<label class="control-label" for="username">Username</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="username" placeholder="Username" value="<%= _.escape(item.get('username') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="passwordInputContainer" class="control-group">
					<label class="control-label" for="password">Password</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="password" placeholder="Password" value="<%= _.escape(item.get('password') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uRightInputContainer" class="control-group">
					<label class="control-label" for="uRight">U Right</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uRight" placeholder="U Right" value="<%= _.escape(item.get('uRight') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uAdUserInputContainer" class="control-group">
					<label class="control-label" for="uAdUser">U Ad User</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uAdUser" placeholder="U Ad User" value="<%= _.escape(item.get('uAdUser') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uAdPasswordInputContainer" class="control-group">
					<label class="control-label" for="uAdPassword">U Ad Password</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uAdPassword" placeholder="U Ad Password" value="<%= _.escape(item.get('uAdPassword') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uPhoneInputContainer" class="control-group">
					<label class="control-label" for="uPhone">U Phone</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uPhone" placeholder="U Phone" value="<%= _.escape(item.get('uPhone') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uFullNameInputContainer" class="control-group">
					<label class="control-label" for="uFullName">U Full Name</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uFullName" placeholder="U Full Name" value="<%= _.escape(item.get('uFullName') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="uAdEmailInputContainer" class="control-group">
					<label class="control-label" for="uAdEmail">U Ad Email</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="uAdEmail" placeholder="U Ad Email" value="<%= _.escape(item.get('uAdEmail') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="tokenInputContainer" class="control-group">
					<label class="control-label" for="token">Token</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="token" placeholder="Token" value="<%= _.escape(item.get('token') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteUsersButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteUsersButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Users</button>
						<span id="confirmDeleteUsersContainer" class="hide">
							<button id="cancelDeleteUsersButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteUsersButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="usersDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Users
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="usersModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveUsersButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="usersCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newUsersButton" class="btn btn-primary">Add Users</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
