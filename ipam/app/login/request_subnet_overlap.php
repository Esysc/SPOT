<?php

/* 	return overlaps
 * ************************************************* */

require( dirname(__FILE__) . '/../../functions/functions.php' );
# classes
$Database = new Database_PDO;
$Addresses = new Addresses($Database);
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$new_subnet = $_POST['subnet'] . '/24';

// Omly IPV4 address in request form for now IPv4 -> id 1
//$sectionId = $_POST['sectionId'];
$sectionId = 1;
//get first free IP address
//verify cidr
$cidr_check = $Subnets->verify_cidr_address($new_subnet);
if (strlen($cidr_check) > 5) {
    $errors[] = $cidr_check;
}
// check Overlap
if (! sizeof(@$errors) > 0) {
    $overlap = $Subnets->verify_subnet_overlapping($sectionId, $new_subnet, $vrfId = 0);
    if ($overlap !== false) {
        $errors[] = $overlap;
        $subnet = $Tools->transform_to_decimal($_POST['subnet']);
        $show = $Tools->fetch_object('subnets', 'subnet', $subnet);
    }
}

/* If no errors are present execute request */
if (sizeof(@$errors) > 0) {
    print '<div class="modal fade" id="overlap_details" tabindex="-1" role="dialog" aria-labelledby="overlap_details">';
    print '<div class="modal-dialog" role="document">';
    print '<div class="modal-content">';
    print '<div class="modal-header  modal-header-info">';
    print '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    print '<h4 class="modal-title" id="myModalLabel">Errors details</h4>';
    print '</div>';
    print '<div class="modal-body">';
    print '<div class="alert alert-danger"><strong>' . _('Please fix following problems') . '</strong>:';
    foreach ($errors as $error) {
        print "<br>" . $error;
    }
    print '</div>';
    if ($subnet != 0) {
        print '<table class="table table-responsive table-auto-wide">';
        print '<tr>';
        print '<th>Subnet</th>';
        print '<td>' . $Tools->transform_to_dotted($show->subnet) . '/' . $show->mask . '</td>';
        print '</tr>';
        print '<tr>';
        print '<th>System Name</th>';
        print '<td>' . $show->{"System Name"} . '</td>';
        print '</tr>';
        print '<tr>';
        print '<th>Description</th>';
        print '<td>' . $show->description . '</td>';
        print '</tr>';
        print '<tr>';
        print '<th>Customer</th>';
        print '<td>' . $show->Account . '</td>';
        print '</tr>';
        print '<tr>';
        print '<th>Site</th>';
        print '<td>' . $show->Site . '</td>';
        print '</tr>';
        print '<tr>';
        print '<th>Comments</th>';
        print '<td>' . $show->Comments . '</td>';
        print '</tr>';
        print '</table>';
    }
    print '</div>';
    print '<div class="modal-footer">';
    print '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    print ' </div>';
    print '</div>';
    print '</div>';
    print '</div>';
    die();
}

//print $overlap;
?>
