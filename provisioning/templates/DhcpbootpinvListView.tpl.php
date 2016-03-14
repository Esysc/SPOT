<?php
$this->assign('title', 'SPOT | DHCPMAP');
$this->assign('nav', 'dhcpmap');

$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
    $LAB.script("scripts/app/dhcpbootpinvs.js").wait(function() {

        $(document).ready(function() {
            var salesid = 'DHCPMAP';
            var data = "Please wait, updating infos.......<p><img src='/SPOT/provisioning/images/loader.gif' /></p>";
            var sendtotable = {
                salesorder: salesid,
                data: data,
                message: data
            };
            var Jsend = JSON.stringify(sendtotable);
            $.ajax({
                url: "/SPOT/provisioning/api/tempdata/" + salesid,
                type: "PUT",
                data: Jsend,
                wait: true
            });
            salesid = 'BOOTPMAP';
            sendtotable = {
                salesorder: salesid,
                data: data,
                message: data
            };
            Jsend = JSON.stringify(sendtotable);
            $.ajax({
                url: "/SPOT/provisioning/api/tempdata/" + salesid,
                type: "PUT",
                data: Jsend,
                wait: true
            });
            var scriptID = 12;
            var salesorder = 10000000;
            var rack = 25;
            var shelf = 'Z';
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var exesequence = 0;
            var executionFlag = 0;

            var command = {
                salesorder: salesorder,
                rack: rack,
                shelf: shelf,
                clientaddress: clientaddress,
                exesequence: exesequence,
                executionflag: executionFlag,
                scriptid: scriptID
            }
            var Jcommand = JSON.stringify(command);

            $.ajax({
                url: "/SPOT/provisioning/api/remotecommands",
                type: "POST",
                data: Jcommand,
                wait: true
            });


            page.init();

        });

        // hack for IE9 which may respond inconsistently with document.ready
        setTimeout(function() {
            if (!page.isInitialized)
                page.init();
        }, 1000);
    });

</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Dhcp/Bootp Table Mapping
        <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>

    </h1>

    <!-- underscore template for the collection -->
    <script type="text/template" id="dhcpbootpinvCollectionTemplate">
        
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>
        <th><p class="badge badge-info">If the shelf is not found, it means that the client is not connected to a standard port.</p><p class="pull-right badge badge-success">SUBNET 10.0.129.1-100. The informations are updated automatically.</p> * DHCP Discover * </th>
        </tr>
        <td>
        <% items.each(function(item) { %>
        <%  if (_.escape(item.get('salesorder')) === 'DHCPMAP') {%>
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>

        <th id="header_Data">Clients => Rack Position<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Message">Clients (VM + Physical)<% if (page.orderBy == 'Message') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Timestamps">Timestamps<% if (page.orderBy == 'Timestamps') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_Creator">Creator<% if (page.orderBy == 'Creator') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        </tr>
        </thead>
        <tbody>
        <tr id="<%= _.escape(item.get('salesorder')) %>">

        <td><%= item.get('data') || '' %></td>
        <td><%= item.get('message') || '' %></td>
        <td><%if (item.get('timestamps')) { %><%= _date(app.parseDate(item.get('timestamps'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>


        <td><%= _.escape(item.get('creator') || '') %></td>

        </tr>
        </tbody>
        </table>
        <% } %>
        <% }); %>
                    </td>
            </tr>
    <tr>
                <th><p class="badge badge-info">If the shelf is not found, it means that the client is not connected to a standard port.</p><p class="pull-right badge badge-success">SUBNET 192.168.10.3-247. The informations are updated automatically.</p> * BOOTP discover * </th>
                    </tr>
            <tr>
            <td>
        <% items.each(function(item) { %>
        <%  if (_.escape(item.get('salesorder')) === 'BOOTPMAP') {%>
        <table class="collection table table-bordered table-hover">
        <thead>
        <tr>

        <th id="header_Data">Clients => Rack Position<% if (page.orderBy == 'Data') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Message">Clients (VM + Physical)<% if (page.orderBy == 'Message') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
        <th id="header_Timestamps">Timestamps<% if (page.orderBy == 'Timestamps') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        <th id="header_Creator">Creator<% if (page.orderBy == 'Creator') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>

        </tr>
        </thead>
        <tbody>
        <tr id="<%= _.escape(item.get('salesorder')) %>">

        <td><%= item.get('data') || '' %></td>
        <td><%= item.get('message') || '' %></td>
        <td><%if (item.get('timestamps')) { %><%= _date(app.parseDate(item.get('timestamps'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>


        <td><%= _.escape(item.get('creator') || '') %></td>

        </tr>
        </tbody>
        </table>
        <% } %>
        <% }); %>
        </td>
        </tr>
        </tbody>
        </table>


        <%=  view.getPaginationHtml(page) %>
    </script>

    

   
    <div id="collectionAlert"></div>

    <div id="dhcpbootpinvCollectionContainer" class="collectionContainer">
    </div>



</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
