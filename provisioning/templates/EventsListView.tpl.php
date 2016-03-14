<?php
	$this->assign('title','SPOT | Events');
	$this->assign('nav','eventses');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/eventses.js").wait(function(){
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
	<i class="icon-th-list"></i> Events
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>
    <p class="label label-info">Scripts events detailed information</p>
	<!-- underscore template for the collection -->
	<script type="text/template" id="eventsCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Id">Id<% if (page.orderBy == 'Id') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Title">Title<% if (page.orderBy == 'Title') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Content">Content<% if (page.orderBy == 'Content') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Userid">Userid<% if (page.orderBy == 'Userid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Date">Date<% if (page.orderBy == 'Date') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('id')) %>" class="<% if ( _.escape(item.get('content')).indexOf('INFO') > -1 )  
                                                                                    { %>success<% } 
                                                                          if ( _.escape(item.get('content')).indexOf('ERROR') > -1 )
                                                                                    { %>error<% } 
                                                                          if ( _.escape(item.get('content')).indexOf('WARN') > -1 )
                                                                                    { %>warning<% }          
                                                                                     %>">
				<td><%= _.escape(item.get('id') || '') %></td>
				<td><%= _.escape(item.get('title') || '') %></td>
				<td><strong><%= item.get('content') || '' %></strong></td>
				<td><%= _.escape(item.get('userid') || '') %></td>
				<td><%if (item.get('date')) { %><%= _date(app.parseDate(item.get('date'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="eventsModelTemplate">
	<!--	<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="idInputContainer" class="control-group">
					<label class="control-label" for="id">Id</label>
					<div class="controls inline-inputs">
						<span class="input-xlarge uneditable-input" id="id"><%= _.escape(item.get('id') || '') %></span>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="titleInputContainer" class="control-group">
					<label class="control-label" for="title">Title</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="title" placeholder="Title" value="<%= _.escape(item.get('title') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="contentInputContainer" class="control-group">
					<label class="control-label" for="content">Content</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="content" rows="3"><%= _.escape(item.get('content') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="useridInputContainer" class="control-group">
					<label class="control-label" for="userid">Userid</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="userid" placeholder="Userid" value="<%= _.escape(item.get('userid') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="dateInputContainer" class="control-group">
					<label class="control-label" for="date">Date</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="date" type="text" value="<%= _date(app.parseDate(item.get('date'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="date-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('date'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>  -->

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteEventsButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteEventsButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Events</button>
						<span id="confirmDeleteEventsContainer" class="hide">
							<button id="cancelDeleteEventsButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteEventsButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form> 
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="eventsDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Remove Event
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="eventsModelContainer"></div>
		</div>
		<!-- <div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveEventsButton" class="btn btn-primary">Save Changes</button>
		</div> -->
	</div>

	<div id="collectionAlert"></div>
	
	<div id="eventsCollectionContainer" class="collectionContainer">
	</div>

	<!--<p id="newButtonContainer" class="buttonContainer">
		<button id="newEventsButton" class="btn btn-primary">Add Events</button>
	</p> -->

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
