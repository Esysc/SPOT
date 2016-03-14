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

