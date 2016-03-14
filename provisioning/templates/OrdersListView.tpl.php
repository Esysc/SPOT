<?php
	$this->assign('title','SPOT | Orderses');
	$this->assign('nav','orderses');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/orderses.js").wait(function(){
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
	<i class="icon-th-list"></i> Orderses
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="ordersCollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
				<th id="header_Salesorder">Salesorder<% if (page.orderBy == 'Salesorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Crmuid">Crmuid<% if (page.orderBy == 'Crmuid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Pgm">Pgm<% if (page.orderBy == 'Pgm') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Ordertitle">Ordertitle<% if (page.orderBy == 'Ordertitle') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Heacronym">Heacronym<% if (page.orderBy == 'Heacronym') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<th id="header_Systemtype">Systemtype<% if (page.orderBy == 'Systemtype') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Snapavail">Snapavail<% if (page.orderBy == 'Snapavail') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Pstartdate">Pstartdate<% if (page.orderBy == 'Pstartdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Penddate">Penddate<% if (page.orderBy == 'Penddate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Rstartdate">Rstartdate<% if (page.orderBy == 'Rstartdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Renddate">Renddate<% if (page.orderBy == 'Renddate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Shippmentdate">Shippmentdate<% if (page.orderBy == 'Shippmentdate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Status">Status<% if (page.orderBy == 'Status') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Polaroidexport">Polaroidexport<% if (page.orderBy == 'Polaroidexport') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Userid">Userid<% if (page.orderBy == 'Userid') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Commiteddate">Commiteddate<% if (page.orderBy == 'Commiteddate') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Moveorder">Moveorder<% if (page.orderBy == 'Moveorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Oracleorder">Oracleorder<% if (page.orderBy == 'Oracleorder') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
				<th id="header_Comments">Comments<% if (page.orderBy == 'Comments') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
-->
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) { %>
			<tr id="<%= _.escape(item.get('salesorder')) %>">
				<td><%= _.escape(item.get('salesorder') || '') %></td>
				<td><%= _.escape(item.get('crmuid') || '') %></td>
				<td><%= _.escape(item.get('pgm') || '') %></td>
				<td><%= _.escape(item.get('ordertitle') || '') %></td>
				<td><%= _.escape(item.get('heacronym') || '') %></td>
<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
				<td><%= _.escape(item.get('systemtype') || '') %></td>
				<td><%= _.escape(item.get('snapavail') || '') %></td>
				<td><%if (item.get('pstartdate')) { %><%= _date(app.parseDate(item.get('pstartdate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('penddate')) { %><%= _date(app.parseDate(item.get('penddate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('rstartdate')) { %><%= _date(app.parseDate(item.get('rstartdate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('renddate')) { %><%= _date(app.parseDate(item.get('renddate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%if (item.get('shippmentdate')) { %><%= _date(app.parseDate(item.get('shippmentdate'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('status') || '') %></td>
				<td><%= _.escape(item.get('polaroidexport') || '') %></td>
				<td><%= _.escape(item.get('userid') || '') %></td>
				<td><%if (item.get('commiteddate')) { %><%= _date(app.parseDate(item.get('commiteddate'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
				<td><%= _.escape(item.get('moveorder') || '') %></td>
				<td><%= _.escape(item.get('oracleorder') || '') %></td>
				<td><%= _.escape(item.get('comments') || '') %></td>
-->
			</tr>
		<% }); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="ordersModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div id="salesorderInputContainer" class="control-group">
					<label class="control-label" for="salesorder">Salesorder</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="salesorder" placeholder="Salesorder" value="<%= _.escape(item.get('salesorder') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="crmuidInputContainer" class="control-group">
					<label class="control-label" for="crmuid">Crmuid</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="crmuid" placeholder="Crmuid" value="<%= _.escape(item.get('crmuid') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="pgmInputContainer" class="control-group">
					<label class="control-label" for="pgm">Pgm</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="pgm" rows="3"><%= _.escape(item.get('pgm') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="ordertitleInputContainer" class="control-group">
					<label class="control-label" for="ordertitle">Ordertitle</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="ordertitle" rows="3"><%= _.escape(item.get('ordertitle') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="heacronymInputContainer" class="control-group">
					<label class="control-label" for="heacronym">Heacronym</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="heacronym" placeholder="Heacronym" value="<%= _.escape(item.get('heacronym') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="systemtypeInputContainer" class="control-group">
					<label class="control-label" for="systemtype">Systemtype</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="systemtype" placeholder="Systemtype" value="<%= _.escape(item.get('systemtype') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="snapavailInputContainer" class="control-group">
					<label class="control-label" for="snapavail">Snapavail</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="snapavail" placeholder="Snapavail" value="<%= _.escape(item.get('snapavail') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="pstartdateInputContainer" class="control-group">
					<label class="control-label" for="pstartdate">Pstartdate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="pstartdate" type="text" value="<%= _date(app.parseDate(item.get('pstartdate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="penddateInputContainer" class="control-group">
					<label class="control-label" for="penddate">Penddate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="penddate" type="text" value="<%= _date(app.parseDate(item.get('penddate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="rstartdateInputContainer" class="control-group">
					<label class="control-label" for="rstartdate">Rstartdate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="rstartdate" type="text" value="<%= _date(app.parseDate(item.get('rstartdate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="renddateInputContainer" class="control-group">
					<label class="control-label" for="renddate">Renddate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="renddate" type="text" value="<%= _date(app.parseDate(item.get('renddate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="shippmentdateInputContainer" class="control-group">
					<label class="control-label" for="shippmentdate">Shippmentdate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="shippmentdate" type="text" value="<%= _date(app.parseDate(item.get('shippmentdate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="statusInputContainer" class="control-group">
					<label class="control-label" for="status">Status</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="status" placeholder="Status" value="<%= _.escape(item.get('status') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="polaroidexportInputContainer" class="control-group">
					<label class="control-label" for="polaroidexport">Polaroidexport</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="polaroidexport" placeholder="Polaroidexport" value="<%= _.escape(item.get('polaroidexport') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="useridInputContainer" class="control-group">
					<label class="control-label" for="userid">Userid</label>
					<div class="controls inline-inputs">
						<select id="userid" name="userid"></select>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="commiteddateInputContainer" class="control-group">
					<label class="control-label" for="commiteddate">Commiteddate</label>
					<div class="controls inline-inputs">
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="commiteddate" type="text" value="<%= _date(app.parseDate(item.get('commiteddate'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append bootstrap-timepicker-component">
							<input id="commiteddate-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('commiteddate'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="moveorderInputContainer" class="control-group">
					<label class="control-label" for="moveorder">Moveorder</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="moveorder" placeholder="Moveorder" value="<%= _.escape(item.get('moveorder') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="oracleorderInputContainer" class="control-group">
					<label class="control-label" for="oracleorder">Oracleorder</label>
					<div class="controls inline-inputs">
						<input type="text" class="input-xlarge" id="oracleorder" placeholder="Oracleorder" value="<%= _.escape(item.get('oracleorder') || '') %>">
						<span class="help-inline"></span>
					</div>
				</div>
				<div id="commentsInputContainer" class="control-group">
					<label class="control-label" for="comments">Comments</label>
					<div class="controls inline-inputs">
						<textarea class="input-xlarge" id="comments" rows="3"><%= _.escape(item.get('comments') || '') %></textarea>
						<span class="help-inline"></span>
					</div>
				</div>
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="deleteOrdersButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="deleteOrdersButton" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete Orders</button>
						<span id="confirmDeleteOrdersContainer" class="hide">
							<button id="cancelDeleteOrdersButton" class="btn btn-mini">Cancel</button>
							<button id="confirmDeleteOrdersButton" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="ordersDetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit Orders
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="ordersModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="saveOrdersButton" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>
	
	<div id="ordersCollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="newOrdersButton" class="btn btn-primary">Add Orders</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
