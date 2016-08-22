<?php

/**
 * Script to confirm / reject subnet request
 * ********************************************* */
/* functions */
require( dirname(__FILE__) . '/../../../functions/functions.php');

# initialize user object
$Database = new Database_PDO;
$User = new User($Database);
$Admin = new Admin($Database, false);
$Addresses = new Addresses($Database);
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Result = new Result ();

# verify that user is logged in
$User->check_user_session();

# strip input tags
$_POST = $Admin->strip_input_tags($_POST);

# validate csrf cookie
$User->csrf_cookie("validate", "subnetRequests", $_POST['csrf_cookie']) === false ? $Result->show("danger", _("Invalid CSRF cookie"), true) : "";

# verify permissions
if ($Subnets->subnet_check_permission($User->user) != 3) {
    $Result->show("danger", _('You do not have permissions to process this request') . "!", true);
}



# fetch subnet
$subnet['subnet'] = $_POST['subnet'];
$subnet['mask'] = $_POST['mask'];

/* if action is reject set processed and accepted to 1 and 0 */
if ($_POST['action'] == "reject") {
    //set reject values
    $values = array("id" => $_POST['requestId'],
        "processed" => 1,
        "accepted" => 0,
        "adminComment" => @$_POST['adminComment']
    );
    if (!$Admin->object_modify("subnetRequests", "edit", "id", $values)) {
        $Result->show("danger", _("Failed to reject subnet request"), true);
    } else {
        $Result->show("success", _("Request has beed rejected"), false);
    }

    # send mail
    $Tools->subnet_request_send_mail("reject", $_POST);
}
/* accept */ else {



    //insert to Subnet table
    $values = array(
        "subnet" => $Addresses->transform_address($_POST['subnet'], "decimal"),
        // ipv4 section hardcoded
        "sectionId" => 1,
        "vlanId" => @$_POST['vlanId'],
        "mask" => @$_POST['mask'],
        "description" => sanitize(@$_POST['System_Name']),
        "Account" => sanitize(@$_POST['owner']),
        "permissions" => '{"2":"2","3":"1","4":"3"}',
        "Comments" => sanitize(@$_POST['comment']),
        "Site" => sanitize(@$_POST['Location']),
        "location" => sanitize(@$_POST['Location']),
        "System Name" => sanitize(@$_POST['System_Name'])
    );
    # append custom fields
    $custom = $Tools->fetch_custom_fields('subnets');
    if (sizeof($custom) > 0) {
        foreach ($custom as $myField) {

            //replace possible ___ back to spaces
            $myField['nameTest'] = str_replace(" ", "___", $myField['name']);
            if (isset($_POST[$myField['nameTest']])) {
                $_POST[$myField['name']] = $_POST[$myField['nameTest']];
            }

            //booleans can be only 0 and 1!
            if ($myField['type'] == "tinyint(1)") {
                if ($_POST[$myField['name']] > 1) {
                    $_POST[$myField['name']] = 0;
                }
            }
            //not null!
            if ($myField['Null'] == "NO" && strlen($_POST[$myField['name']]) == 0) {
                $Result->show("danger", $myField['name'] . '" can not be empty!', true);
            }

            # save to update array, but check if value is not NULL
            if (isset($_POST[$myField['name']]))
                $values[$myField['name']] = $_POST[$myField['name']];
        }
    }

    if (!$Subnets->modify_subnet("add", $values)) {
        $Result->show("danger", _("Failed to add subnet address"), true);
    }

    //accept message
    $values2 = array("id" => $_POST['requestId'],
        "processed" => 1,
        "accepted" => 1,
        "adminComment" => sanitize(@$_POST['adminComment'])
    );
    if (!$Admin->object_modify("subnetRequests", "edit", "id", $values2)) {
        $Result->show("danger", _("Cannot confirm subnet address"), true);
    } else {
        $Result->show("success", _("Subnet request accepted/rejected"), false);
    }


    # send mail


    $Tools->subnet_request_send_mail("accept", $_POST);
}
?>
