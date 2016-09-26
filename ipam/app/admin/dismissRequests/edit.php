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
$csrf = $User->csrf_cookie("create", "subnetDismiss");


# fetch request
$request = $Admin->fetch_object("subnetDismiss", "id", $_POST['requestId']);

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






?>

<!-- header -->
<div class="pHeader"><?php print _('Manage dismiss request'); ?></div>

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


       
        $subnet = $request['subnet'];
        $id = $request['subnetid'];
        $cx = strpos($subnet, '/');
        if ($cx) {
           $mask = (int) (substr($subnet, $cx + 1));
            $subnet = substr($subnet, 0, $cx);
        }
        ?>

        <!-- subnet request form -->
        <form class="manageDismissEdit" name="manageDismissEdit">
            <!-- edit subnet table -->
            <table id="manageDismissEdit" class="table table-striped table-condensed">

                <!-- divider -->
                <tr>
                    <td colspan="2"><h4>Dismission details</h4><hr></td>
                </tr>

                <!-- Subnet -->

                <tr>
                    <th><?php print _('Subnet'); ?></th>
                    <td>
                        <input type="text" name="subnet" class="form-control input-sm" value="<?php print $subnet; ?>" size="30" disabled>
                        <input type="hidden" name="requestId" value="<?php print $request['id']; ?>">
                        <input type="hidden" name="subnetid" value="<?php print $request['subnetid']; ?>">
                        <input type="hidden" name="requester" value="<?php print sanitize($request['requester']); ?>">
                        <input type="hidden" name="csrf_cookie" value="<?php print $csrf; ?>">
                        
                    </td>
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
                

            </table>
        </form>
<?php } ?>
</div>

<!-- footer -->
<div class="pFooter">
    <div class="btn-group">
        <button class="btn btn-sm btn-default hidePopups"><?php print _('Cancel'); ?></button>
<?php if (@$errmsg_class != "danger") { ?>
            <button class="btn btn-sm btn-default btn-danger manageDismiss" data-action='reject'><i class="fa fa-times"></i> <?php print _('Reject'); ?></button>
            <button class="btn btn-sm btn-default btn-success manageDismiss" data-action='accept'><i class="fa fa-check"></i> <?php print _('Accept'); ?></button>
<?php } ?>
    </div>

    <!-- result -->
    <div class="manageRequestResult"></div>
</div>