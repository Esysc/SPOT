<?php
/**
 * Script to get all active subnet requests
 * ************************************** */
# verify that user is logged in
$User->check_user_session();

# fetch all Active requests
$active_requests = $Admin->fetch_multiple_objects("subnetRequests", "processed", 0, "id", false);
$inactive_requests = $Admin->fetch_multiple_objects("subnetRequests", "processed", 1, "id", false);
?>


<h4><?php print _('List of  subnet requests'); ?></h4>
<hr><br>

<?php
if ($active_requests === false) {
    print "<div class='alert alert-info'>" . _('No subnet requests available') . "!</div>";
} else {
    ?>
    <table id="requestedsubnet" class="table sorted table-striped table-condensed table-hover table-top">

        <!-- headers -->
        <thead>
            <tr>
                <th style="width:50px;"></th>
                <th><?php print _('Subnet'); ?></th>
                <th><?php print _('Mask'); ?></th>
                <th><?php print _('Vlan'); ?></th>

                <th><?php print _('System Name'); ?></th>
                <th><?php print _('Location'); ?></th>
                <th><?php print _('Customer'); ?></th>
                <th><?php print _('Requested by'); ?></th>
                <th><?php print _('Comment'); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php
            # print requests

            foreach ($active_requests as $k => $request) {
                //cast
                $request = (array) $request;
                // Get address from id
                if (is_numeric($request['Location']))
                    $request['Location'] = $Tools->fetch_location_by_id($request['Location']);
                print '<tr>' . "\n";
                print "	<td><button class='btn btn-sm btn-default' data-requestid='" . $request['id'] . "'><i class='fa fa-pencil'></i> " . _('Process') . "</button></td>";
                print '	<td>' . $request['subnet'] . '</td>' . "\n";
                print '	<td>' . $request['mask'] . '</td>' . "\n";
                print '	<td>' . $request['Vlan'] . '</td>' . "\n";
                print '	<td>' . $request['System Name'] . '</td>' . "\n";
                print '	<td>' . $request['Location'] . '</td>' . "\n";
                print '	<td>' . $request['owner'] . '</td>' . "\n";
                print '	<td>' . $request['requester'] . '</td>' . "\n";
                print '	<td>' . $request['comment'] . '</td>' . "\n";
                print '</tr>' . "\n";
            }
            ?>
        </tbody>
    </table>
    <?php
}
# print resolved if present

if ($inactive_requests !== false) {
    ?>

    <h4 style="margin-top:50px;"><?php print _('List of all processes Subnet requests'); ?></h4>
    <hr><br>

    <table  id="processedSubnets" class="table sorted table-striped table-condensed table-hover table-top table-auto1">

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
            </tr>
        </thead>

        <tbody>
            <?php
            # print requests
            foreach ($inactive_requests as $k => $request) {
                //cast
                $request = (array) $request;

// Get address from id
                if (is_numeric($request['Location']))
                    $request['Location'] = $Tools->fetch_location_by_id($request['Location']);

                print '<tr>' . "\n";

                print '	<td>' . $request['subnet'] . '</td>' . "\n";
                print '	<td>' . $request['mask'] . '</td>' . "\n";
                print '	<td>' . $request['Vlan'] . '</td>' . "\n";
                print '	<td>' . $request['System Name'] . '</td>' . "\n";
                print '	<td>' . $request['Location'] . '</td>' . "\n";
                print '	<td>' . $request['owner'] . '</td>' . "\n";
                print '	<td>' . $request['requester'] . '</td>' . "\n";
                print '	<td>' . $request['comment'] . '</td>' . "\n";
                print ' <td>';
                print $request['accepted'] == 1 ? "Yes" : "No";
                print '</td>' . "\n";
                print '</tr>' . "\n";
            }
            ?>
        </tbody>
    </table>

<?php } ?>