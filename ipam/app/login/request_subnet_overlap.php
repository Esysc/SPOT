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
$overlap = $Subnets->verify_subnet_overlapping($sectionId, $new_subnet, $vrfId = 0);
if ($overlap !== false) {
    $errors[] = $overlap;
}

/* If no errors are present execute request */
if (sizeof(@$errors) > 0) {
    print '<div class="alert alert-danger"><strong>' . _('Please fix following problems') . '</strong>:';
    foreach ($errors as $error) {
        print "<br>" . $error;
    }

    print '</div>';
    $subnet = $Tools->transform_to_decimal($_POST['subnet']);
    if ($subnet != 0)
        print "<button class='btn btn-primary' type='button'  data-toggle='modal' rel='tooltip' title='Subnet details' data-target='#$subnet'><i class='fa fa-database'></i> $new_subnet details</button>";
    die();
}

//print $overlap;
?>
