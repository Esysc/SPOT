
<style>
    .divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}

.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}
.divTableHeading {
	background-color: #FFF;
	display: table-header-group;
	font-weight: bold;
        color: #A3F
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}
</style>
<?php
/**
 * Script to get all active IP requests
 * ************************************** */
# verify that user is logged in
$User->check_user_session();
if ($User->user->groups === "{\"3\":\"3\"}") {
    print '<h4 class="danger alert-danger">You are not allow to see the request</h4>';

    return;
}
# fetch all Active subnet requests
$subnetRequests = $Tools->fetch_multiple_objects("subnetRequests", "processed", 0, "id", false);

# validate permissions
if ($subnetRequests !== false) {
    foreach ($subnetRequests as $k => $r) {
        // check permissions
        if ($Subnets->subnet_check_permission($User->user) != 3) {
            unset($subnetRequests[$k]);
        }
    }
    # null
    if (sizeof($subnetRequests) == 0) {
        $subnetRequests = false;
    }
}
?>

<h4><?php print _('List of  subnet requests'); ?></h4>
<hr><br>

<?php
if ($subnetRequests != false) {
    print "<div class='alert alert-info'>" . _('List of unprocessed subnet requests') . "</div>\n";
    ?>
<div  id="requestedSubnet"  class="divTable">
  
        <!-- headers -->
       <div class="divTableHeading">
<div class="divTableRow">
<div class="divTableHead">&nbsp;</div>
<div class="divTableHead">Subnet</div>
<div class="divTableHead">Mask</div>
<div class="divTableHead">Vlan</div>
<div class="divTableHead">System Name</div>
<div class="divTableHead">Location</div>
<div class="divTableHead">Customer</div>
<div class="divTableHead">Requested by</div>
<div class="divTableHead">Comment</div>
</div>
</div>
<div class="divTableBody">

            <?php
            # print requests
            foreach ($subnetRequests as $k => $request) {
                //cast
                $request = (array) $request;
// Get address from id
                if (is_numeric($request['Location']))
                    $request['Location'] = $Tools->fetch_location_by_id($request['Location']);


                print '<div class="divTableRow">';
                print "	<div class='divTableCell'><button class='btn btn-sm btn-default' data-requestid='" . $request['id'] . "'><i class='fa fa-pencil'></i> " . _('Process') . "</button></div>";
                print '<div class="divTableCell">' . $request['subnet'] . '</div>' . "\n";
                print '	<div class="divTableCell">' . $request['mask'] . '</div>' . "\n";
                print '<div class="divTableCell">' . $request['Vlan'] . '</div>' . "\n";
                print '<div class="divTableCell">' . $request['System Name'] . '</div>' . "\n";
                print '<div class="divTableCell">' . $request['Location'] . '</div>' . "\n";
                print '	<div class="divTableCell">' . $request['owner'] . '</div>' . "\n";
                print '	<div class="divTableCell">' . $request['requester'] . '</div>' . "\n";
                print '	<div class="divTableCell">' . $request['comment'] . '</div>' . "\n";
                print '</div>' . "\n";
            }
            ?>
</div>
    </div>
<hr><br>
    <?php
} else {
    print "<div class='alert alert-success'>" . _('No active subnet requests available') . '!</div>';
}
$subnetRequests = $Tools->fetch_multiple_objects("subnetRequests", "processed", 1, "id", false);
if ($subnetRequests != false) {

    print "<div class='alert alert-info'>" . _('List of processed subnet requests') . '</div>';
    ?>

    <table class="table sorted table-striped table-condensed table-hover table-top">

        <!-- headers -->
        <thead>
            <tr>

                <th><?php print _('Subnet'); ?></th>
                <th><?php print _('Mask'); ?></th>
                <th><?php print _('Vlan'); ?></th>

                <th><?php print _('System Name'); ?></th>
                <th><?php print _('Location'); ?></th>
                <th><?php print _('Customer'); ?></th>
                <th><?php print _('Requested by'); ?></th>
                <th><?php print _('Comment'); ?></th>
                <th><?php print _('Accepted'); ?></th>
                <th><?php print _('Admin Comment'); ?></th>
                <th><?php print _('Date'); ?></th>
            </tr>
        </thead>

        <tbody id="processedSubnets">
            <?php
            # print requests
            foreach ($subnetRequests as $k => $request) {
                //cast
                $request = (array) $request;
                $accepted = "Yes";
                $class = "class='alert alert-success'";
                if ($request['accepted'] == 0) {
                    $accepted = "No";
                    $class = "class='alert alert-danger'";
                }

// Get address from id
                if (is_numeric($request['Location']))
                    $request['Location'] = $Tools->fetch_location_by_id($request['Location']);

                print '<tr '.$class.'>' . "\n";

                print '	<td>' . $request['subnet'] . '</td>' . "\n";
                print '	<td>' . $request['mask'] . '</td>' . "\n";
                print '	<td>' . $request['Vlan'] . '</td>' . "\n";
                print '	<td>' . $request['System Name'] . '</td>' . "\n";
                print '	<td>' . $request['Location'] . '</td>' . "\n";
                print '	<td>' . $request['owner'] . '</td>' . "\n";
                print '	<td>' . $request['requester'] . '</td>' . "\n";
                print '	<td>' . $request['comment'] . '</td>' . "\n";
                print '	<td>' . $accepted . '</td>' . "\n";
                print '	<td>' . $request['adminComment'] . '</td>' . "\n";
                print '	<td>' . $request['occured'] . '</td>' . "\n";
                print '</tr>' . "\n";
            }
            ?>
        </tbody>
    </table>
    
    <?php
} else {

    print "<div class='alert alert-success'>" . _('No processed subnet requests available') . "!</div>";
}

