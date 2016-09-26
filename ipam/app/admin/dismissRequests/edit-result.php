<?php

/**
 * Script to confirm / reject subnet didmiddion request
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
$User->csrf_cookie("validate", "subnetDismiss", $_POST['csrf_cookie']) === false ? $Result->show("danger", _("Invalid CSRF cookie"), true) : "";

# verify permissions
if ($Subnets->subnet_check_permission($User->user) != 3) {
    $Result->show("danger", _('You do not have permissions to process this request') . "!", true);
}



# fetch subnet
$subnet['subnet'] = $_POST['subnet'];
$subnet['subnetid'] = $_POST['subnetid'];

/* if action is reject set processed and accepted to 1 and 0 */
if ($_POST['action'] == "reject") {
    //set reject values
    $values = array("id" => $_POST['requestId'],
        "processed" => 1,
        "accepted" => 0
        
    );
    if (!$Admin->object_modify("subnetDismiss", "edit", "id", $values)) {
        $Result->show("danger", _("Failed to reject subnet dismisssion"), true);
    } else {
        $Result->show("success", _("Dismission has beed rejected"), false);
    }

    # send mail
    $Tools->subnet_dismiss_send_mail("reject", $_POST);
}
/* accept */ else {



    //insert to Subnet table
    $values = array(
        "subnet" => $Addresses->transform_address($_POST['subnet'], "decimal"),
        "id" => $_POST['subnetid'],
        
        // ipv4 obsolete section hardcoded
        "sectionId" => 3,
        "Comments" => @$_POST['comment']
        
        
    );
   
    if (!$Subnets->modify_subnet("edit", $values)) {
        $Result->show("danger", _("Failed to modify subnet"), true);
    }
   

    
    //accept message
    $values2 = array("id" => $_POST['requestId'],
        "processed" => 1,
        "accepted" => 1,
        
    );
    if (!$Admin->object_modify("subnetDismiss", "edit", "id", $values2)) {
        $Result->show("danger", _("Cannot confirm subnet dismission"), true);
    } else {
        $Result->show("success", _("Subnet dismission accepted/rejected"), false);
    }


    # send mail


    $Tools->subnet_dismiss_send_mail("accept", $_POST);
}
?>
