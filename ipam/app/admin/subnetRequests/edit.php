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

# create csrf token
$csrf = $User->csrf_cookie("create", "subnetRequests");

# fetch request
$request = $Admin->fetch_object("subnetRequests", "id", $_POST['requestId']);

//fail
if ($request === false) {
    $Result->show("danger", _("Request does not exist"), true, true);
} else {
    $request = (array) $request;
}

# verify permissions
if ($Subnets->subnet_check_permission($User->user) != 3) {
    $Result->show("danger", _('You do not have permissions to process this request') . "!", true, true);
}


# set new subnet
# if provided (requested from logged in user) check if already in use, if it is warn and set next free
# else get next free
if (strlen($request['subnet']) > 0) {
    // check if it exists
    $new_subnet = $request['subnet'] . '/24';

// Omly IPV4 address in request form for now IPv4 -> id 1
//$sectionId = $_POST['sectionId'];
    $sectionId = 1;

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
        $errmsg_class = "danger";

        foreach ($errors as $error) {
            $errmsg .= "<br>" . $error;
        }
    }
}


# fetch custom fields
$custom_fields = $Tools->fetch_custom_fields('subnets');
?>

<!-- header -->
<div class="pHeader"><?php print _('Manage subnet request'); ?></div>

<!-- content -->
<div class="pContent">

    <?php
// if error / warning message provided
    if (isset($errmsg)) {
        $Result->show($errmsg_class, $errmsg, false, false);
        print "<hr>";
    }
// error check
    if (@$errmsg_class != "danger") {


        $vlans = $Tools->fetch_object("vlans", "name", @$request['Vlan']);
        $vlanNum = $vlans->number;
        $vlanId = $vlans->vlanId
        ?>

        <!-- subnet request form -->
        <form class="manageRequestEdit" name="manageRequestEdit">
            <!-- edit subnet table -->
            <table id="manageRequestEdit" class="table table-striped table-condensed">

                <!-- divider -->
                <tr>
                    <td colspan="2"><h4>Request details</h4><hr></td>
                </tr>

                <!-- Subnet -->

                <tr>
                    <th><?php print _('Subnet'); ?></th>
                    <td>
                        <input type="text" name="subnet" class="form-control input-sm" value="<?php print $request['subnet']; ?>" size="30">
                        <input type="hidden" name="requestId" value="<?php print $request['id']; ?>">
                        <input type="hidden" name="requester" value="<?php print sanitize($request['requester']); ?>">
                        <input type="hidden" name="csrf_cookie" value="<?php print $csrf; ?>">
                    </td>
                </tr>
                <!-- Mask -->

                <tr>
                    <th><?php print _('Mask'); ?></th>
                    <td>
                        <input type="text" name="mask" class="form-control input-sm" value="<?php print $request['mask']; ?>" size="2">

                    </td>
                </tr>
                <!-- Vlan -->
                <tr>
                    <th><?php print _('Vlan') . ' - ' . @$request['Vlan']; ?></th>
                    <td>
                        <input type="text" disabled name="VlanNum" class="form-control input-sm" value="<?php print $vlanNum; ?>" size="30" >
                        <input type="hidden" name="vlanId" class="form-control input-sm" value="<?php print $vlanId; ?>" size="30" >
                    </td>
                </tr>
                <!-- System Name -->
                <tr>
                    <th><?php print _('System Name'); ?></th>
                    <td>
                        <input type="text" name="System_Name" class="form-control input-sm" value="<?php print sanitize(@$request['System Name']); ?>" size="30" placeholder="<?php print _('System Name'); ?>">
                    </td>
                </tr>
                <!-- Location -->
                <tr>
                    <th><?php print _('Location'); ?></th>
                    <td>
                        <input type="text" name="Location" class="form-control input-sm" value="<?php print sanitize(@$request['Location']); ?>" size="30" placeholder="<?php print _('Location'); ?>">
                    </td>
                </tr>


                <!-- owner -->
                <tr>
                    <th><?php print _('Customer'); ?></th>
                    <td>
                        <input type="text" name="owner" class="form-control input-sm" id="owner" value="<?php print sanitize(@$request['owner']); ?>" size="30" placeholder="<?php print _('Enter Subnet Customer'); ?>">
                    </td>
                </tr>

                <!-- Custom fields -->
                <?php
                if (sizeof(@$custom_fields) > 0) {
                    # count datepickers
                    $timeP = 0;

                    # all my fields
                    foreach ($custom_fields as $myField) {
                        # replace spaces with |
                        $myField['nameNew'] = str_replace(" ", "___", $myField['name']);
                        if ($myField['nameNew'] === "Comments") {

                            $details[$myField['name']] = sanitize(@$request['comment']);

                            $disabled = "disabled";
                        } else {
                            $disabled = "";
                        }
                        if ($myField['nameNew'] === "Site") {
                            $details[$myField['name']] = sanitize(@$request['Location']);
                        }
                        if ($myField['name'] === "System Name") {
                            continue;
                        }
                        if ($myField['name'] === "Account") {
                            $details[$myField['name']] = sanitize(@$request['owner']);
                        }
                        # required
                        if ($myField['Null'] == "NO") {
                            $required = "*";
                        } else {
                            $required = "";
                        }

                        print '<tr>' . "\n";
                        print ' <th>' . $myField['name'] . ' ' . $required . '</th>' . "\n";
                        print ' <td>' . "\n";

                        //set type
                        if (substr($myField['type'], 0, 3) == "set" || substr($myField['type'], 0, 4) == "enum") {
                            //parse values
                            $tmp = substr($myField['type'], 0, 3) == "set" ? explode(",", str_replace(array("set(", ")", "'"), "", $myField['type'])) : explode(",", str_replace(array("enum(", ")", "'"), "", $myField['type']));
                            //null
                            if ($myField['Null'] != "NO") {
                                array_unshift($tmp, "");
                            }

                            print "<select name='$myField[nameNew]' class='form-control input-sm input-w-auto' rel='tooltip' data-placement='right' title='$myField[Comment]'>";
                            foreach ($tmp as $v) {
                                if ($v == @$details[$myField['name']]) {
                                    print "<option value='$v' selected='selected'>$v</option>";
                                } else {
                                    print "<option value='$v'>$v</option>";
                                }
                            }
                            print "</select>";
                        }
                        //date and time picker
                        elseif ($myField['type'] == "date" || $myField['type'] == "datetime") {
                            // just for first
                            if ($timeP == 0) {
                                print '<link rel="stylesheet" type="text/css" href="css/1.2/bootstrap/bootstrap-datetimepicker.min.css">';
                                print '<script type="text/javascript" src="js/1.2/bootstrap-datetimepicker.min.js"></script>';
                                print '<script type="text/javascript">';
                                print '$(document).ready(function() {';
                                //date only
                                print ' $(".datepicker").datetimepicker( {pickDate: true, pickTime: false, pickSeconds: false });';
                                //date + time
                                print ' $(".datetimepicker").datetimepicker( { pickDate: true, pickTime: true } );';

                                print '})';
                                print '</script>';
                            }
                            $timeP++;

                            //set size
                            if ($myField['type'] == "date") {
                                $size = 10;
                                $class = 'datepicker';
                                $format = "yyyy-MM-dd";
                            } else {
                                $size = 19;
                                $class = 'datetimepicker';
                                $format = "yyyy-MM-dd";
                            }

                            //field
                            if (!isset($details[$myField['name']])) {
                                print ' <input type="text" class="' . $class . ' form-control input-sm input-w-auto" data-format="' . $format . '" name="' . $myField['nameNew'] . '" maxlength="' . $size . '" rel="tooltip" data-placement="right" title="' . $myField['Comment'] . '">' . "\n";
                            } else {
                                print ' <input type="text" class="' . $class . ' form-control input-sm input-w-auto" data-format="' . $format . '" name="' . $myField['nameNew'] . '" maxlength="' . $size . '" value="' . @$details[$myField['name']] . '" rel="tooltip" data-placement="right" title="' . $myField['Comment'] . '">' . "\n";
                            }
                        }
                        //boolean
                        elseif ($myField['type'] == "tinyint(1)") {
                            print "<select name='$myField[nameNew]' class='form-control input-sm input-w-auto' rel='tooltip' data-placement='right' title='$myField[Comment]'>";
                            $tmp = array(0 => "No", 1 => "Yes");
                            //null
                            if ($myField['Null'] != "NO") {
                                $tmp[2] = "";
                            }

                            foreach ($tmp as $k => $v) {
                                if (strlen(@$details[$myField['name']]) == 0 && $k == 2) {
                                    print "<option value='$k' selected='selected'>" . _($v) . "</option>";
                                } elseif ($k == @$details[$myField['name']]) {
                                    print "<option value='$k' selected='selected'>" . _($v) . "</option>";
                                } else {
                                    print "<option value='$k'>" . _($v) . "</option>";
                                }
                            }
                            print "</select>";
                        }
                        //text
                        elseif ($myField['type'] == "text") {

                            print ' <textarea ' . $disabled . ' class="form-control input-sm" name="' . $myField['nameNew'] . '" placeholder="' . $myField['name'] . '" rowspan=3 rel="tooltip" data-placement="right" title="' . $myField['Comment'] . '">' . $details[$myField['name']] . '</textarea>' . "\n";
                        }
                        //default - input field
                        else {
                            
;                            if ($myField['name'] === "User") {
                                print ' <input type="text" class="' . $class . ' form-control input-sm input-w-auto" data-format="' . $format . '" name="' . $myField['name'] . '" maxlength="' . $size . '" value="' . $_SESSION['ipamusername'] . '" rel="tooltip" data-placement="right" title="' . $myField['Comment'] . '" readonly>' . "\n";
                            } else {
                                print ' <input type="text" ' . $disabled . ' class="ip_addr form-control input-sm" name="' . $myField['nameNew'] . '" placeholder="' . $myField['name'] . '" value="' . @$details[$myField['name']] . '" size="30" rel="tooltip" data-placement="right" title="' . $myField['Comment'] . '">' . "\n";
                            }
                        }
                        print ' </td>' . "\n";
                        print '</tr>' . "\n";
                    }
                }
                ?>

                <!-- divider -->
                <tr>
                    <td colspan="2"><h4>Additional information</h4><hr></td>
                </tr>

                <!-- requested by -->
                <tr>
                    <th><?php print _('Requester email'); ?></th>
                    <td>
                        <input type="text" disabled="disabled" class="form-control" value="<?php print @$request['requester']; ?>">
                    </td>
                </tr>
                <!-- comment -->
                <tr>
                    <th><?php print _('Requester comment'); ?></th>
                    <td>
                        <input type="text" disabled="disabled" class="form-control" value="<?php print sanitize(@$request['comment']); ?>">
    <?php print "<input type='hidden' name='comment' value='" . @$request['comment'] . "'>"; ?></i></td>
                </tr>
                <!-- Admin comment -->
                <tr>
                    <th><?php print _('Comment approval/reject'); ?>:</th>
                    <td>
                        <textarea name="adminComment" rows="3" cols="30" class="form-control input-sm" placeholder="<?php print _('Enter reason for reject/approval to be sent to requester'); ?>"><?php print 'Edited by ' . $_SESSION['ipamusername'] . '.'; ?></textarea>
                    </td>
                </tr>

            </table>
        </form>
<?php } ?>
</div>

<!-- footer -->
<div class="pFooter">
    <div class="btn-group">
        <button class="btn btn-sm btn-default hidePopups"><?php print _('Cancel'); ?></button>
<?php if (@$errmsg_class != "danger") { ?>
            <button class="btn btn-sm btn-default btn-danger manageRequest" data-action='reject'><i class="fa fa-times"></i> <?php print _('Reject'); ?></button>
            <button class="btn btn-sm btn-default btn-success manageRequest" data-action='accept'><i class="fa fa-check"></i> <?php print _('Accept'); ?></button>
<?php } ?>
    </div>

    <!-- result -->
    <div class="manageRequestResult"></div>
</div>