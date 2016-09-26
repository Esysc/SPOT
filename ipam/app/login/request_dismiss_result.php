<?php

/* functions */
require( dirname(__FILE__) . '/../../functions/functions.php');

# initialize user object
$Database = new Database_PDO;
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Admin = new Admin($Database, false);
$Result = new Result ();

# fetch settings, user is not authenticated !
$Tools->get_settings();

# requests must be enabled!
# 
$valueCheck = "";
foreach ($_POST as $name => $value) {
    if ($value === '') {
        $valueCheck .= "$name ";
    }
}
if ($valueCheck !== "")
    $Result->show("danger", _('Please Fill the mandatory fields') . '! (<strong>' . $valueCheck . '</strong>)', true);
# verify email
if (!$Result->validate_email($_POST['requester'])) {
    $Result->show("danger", _('Please provide valid email address') . '! (' . _('requester') . ': ' . $_POST['requester'] . ')', true);
}


# formulate insert values
$values = array("subnet" => $_POST['subnet'],
    "subnetid" => $_POST['subnetid'],
    "requester" => $_POST['requester'],
    "comment" => @$_POST['comment'],
    "processed" => 0
);
if (!$Admin->object_modify("subnetDismiss", "add", "id", $values)) {
    $Result->show("danger", _('Error submitting SUBNET  dismiss'), true);
} else { {
        $Result->show("success", _('Request submitted successfully'));
    }
    # send mail
      $Tools->subnet_dismiss_send_mail("new", $values);
}
?>