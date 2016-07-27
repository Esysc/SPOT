<?php
# initialize user object
$Database = new Database_PDO;
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Admin = new Admin($Database, false);
$Result = new Result ();

$vlans = $Tools->fetch_all_objects('vlans', 'vlanId');

$vlans = (array) $vlans;
$locations = $Tools->fetch_all_objects("locations");

?>
<div id="login" class="request">
    <form name="requestSUBNET" id="requestSUBNET">
        <div class="REQUESTsubnet">

            <!-- title -->

            <legend><?php print _('SUBNET request form'); ?></legend>

        </div>  



        <!-- back to login page -->
        <div class="iprequest" style="text-align:left">
            <a href="<?php print create_link("login"); ?>" class="backToLogin">
                <i class="fa fa-angle-left fa-pad-right"></i> <?php print _('Back to login'); ?>
            </a>
        </div>

        <!-- select subnet dropdown -->
        <table class="REQUESTsubnet table table-responsive">
            <tr>
                <th> * <?php print _('Enter a subnet'); ?></th>
                <td>
                    <input  name="subnet" id="subnet" class="form-control" type="text" lenght="15"/><strong>/24</strong>

                </td>
            </tr>
            <!-- Vlan name -->
            <tr>
                <th> * <?php print _('Vlan Name'); ?></th>
                <td>
                    <select name="vlan">
                        <?php
                        foreach ($vlans as $vlan => $val) {
                            $option = $val->name;
                            print "<option value='" . $option . "'>" . $option . "</option>";
                        }
                        ?>
                    </select>


                </td>
            </tr>

            <!-- description -->
            <tr>
                <th> * <?php print _('Description'); ?></th>
                <td>
                    <input type="text" name="description" class="form-control" size="30" placeholder="<?php print _('Subnet description'); ?>"></td>
            </tr>

            <!-- Location -->

            <tr>
                <th> * <?php print _('Location'); ?></th>
                <td>
                   <!-- <input type="text" name="location" class="form-control" size="30" placeholder="<?php print _('Subnet location'); ?>">-->
                    <label>If you cannot find the right location, please specify in the "Comment" field</label>
                    <select name="location" class="form-control input-sm input-w-auto">
                        <option value="0"><?php print _("None"); ?></option>
                        <?php
                        if ($locations !== false) {
                            foreach ($locations as $l) {
                                if ($subnet_old_details['location'] == $l->id) {
                                    print "<option value='$l->name' selected='selected'>$l->name</option>";
                                } else {
                                    print "<option value='$l->name'>$l->name</option>";
                                }
                            }
                        } else {
                            ?>
                            <input type="text" name="location" class="form-control" size="30" placeholder="<?php print _('Subnet location'); ?>">
                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>

            <!-- owner --> 

            <tr >
                <th> * <?php print _('Customer'); ?></th>


                <td><input type="text" name="owner" class="form-control" id="owner" size="30" placeholder="<?php print _('Customer'); ?>"></td>
            </tr>




            <!-- requester -->
            <tr>
                <th> * <?php print _('Requester'); ?> *</th>
                <td>
                    <input type="text" name="requester" class="form-control" size="30" placeholder="<?php print _('Your email address'); ?>"></textarea>
                </td>
            </tr>

            <!-- comment -->
            <tr>
                <th><?php print _('Comment'); ?></th>
                <td class="comment">
                    <textarea name="comment" rows="3" class="form-control" style="width:100%" placeholder="<?php print _('If there is anything else you want to say about request write it in this box'); ?>!"></textarea>
                </td>
            </tr>

            <!-- submit -->
            <tr>
                <td class="submit"></td>
                <td class="submit text-right">
                    <div class="btn-group text-right">
                        <input type="button" class="btn btn-sm btn-default clearSUBNETrequest" value="<?php print _('Reset'); ?>">
                        <input type="submit" class="btn btn-sm btn-default" value="<?php print _('Submit request'); ?>">
                    </div>
                </td>
                <td class="submit"></td>
            </tr>

        </table>



        <div id="REQUESTsubnetresult"></div>


        <!-- back to login page -->
        <div class="iprequest" style="text-align:left">
            <a href="<?php print create_link("login"); ?>">
                <i class="fa fa-angle-left fa-pad-right"></i> <?php print _('Back to login'); ?>
            </a>
        </div>

    </form>
</div>



<?php
# check for requests guide
/* $instructions = $Database->getObject("instructions", 2);

  if (is_object($instructions)) {
  if (strlen($instructions->instructions) > 0) {
  print "<div id='login' class='request'>";
  print "<div class='REQUESTsubnet'>";
  print $instructions->instructions;
  print "</div>";
  print "</div>";
  }
  } */
?>