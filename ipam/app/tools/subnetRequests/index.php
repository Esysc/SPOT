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
    print "<div class='alert alert-info'>" . _('List of unprocessed subnet requests') . "</div>";
    ?>
    <table id="requestedSubnet" class="table sorted table-striped table-condensed table-hover table-top">

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
            foreach ($subnetRequests as $k => $request) {
                //cast
                $request = (array) $request;



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
} else {
    print "<div class='alert alert-success'>" . _('No active subnet requests available') . "!</div>";
}
$subnetRequests = $Tools->fetch_multiple_objects("subnetRequests", "processed", 1, "id", false);
if ($subnetRequests != false) {

    print "<div class='alert alert-info'>" . _('List of processed subnet requests') . "</div>";
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

        <tbody>
            <?php
            # print requests
            foreach ($subnetRequests as $k => $request) {
                //cast
                $request = (array) $request;
                $accepted = "Yes";
                if ($request['accepted'] == 0) {
                    $accepted = "No";
                }



                print '<tr>' . "\n";

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
