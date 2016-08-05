<?php
# initialize user object
$Database = new Database_PDO;
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Admin = new Admin($Database, false);
$Result = new Result ();

$vlans = $Tools->fetch_all_objects('vlans', 'vlanId');

$vlans = (array) $vlans;
$locations = $Tools->fetch_all_objects("locations", "name");
$customers = $Tools->fetch_all_objects("subnets", "Account");

?>


<div class="container">
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
                <th>  * <?php print _('Subnet'); ?></th>
                <td>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id='get-subnet' rel='tooltip'  data-sectionId="1" title='<?php print _('Suggest new subnet'); ?>'>
                                <i class="fa fa-gear" ></i>
                            </button>
                        </span>
                        <input  name="subnet" id="subnet" class="form-control" type="text" placeholder="xxx.xxx.xxx.0/24" />
                        <span class="input-group-btn" >
                            <button class="btn btn-primary" type="button"  data-toggle="modal" rel='tooltip' title='Choose vlan' id="button_vlan" data-target="#modalVlan"><i class="fa fa-share"></i></button>
                            <input type="hidden" name="vlan" id="vlan" />
                        </span>
                        

                            
                       
                    </div>







                </td>
            </tr>


            <!-- description -->
            <tr>
                <th> * <?php print _('Description'); ?></th>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                        <input type="text" name="description" class="form-control" size="30" placeholder="<?php print _('Subnet description'); ?>">
                    </div>
                </td>
            </tr>

            <!-- Location -->

            <tr>
                <th> * <?php print _('Location'); ?> </th>
                <td>
                    <div class="input-group">
                        <span class="input-group-btn" >
                            <button class="btn btn-primary" type="button"  data-toggle="modal" rel='tooltip' title='Help' data-target="#modalLocation"><i class="fa fa-map"></i></button>
                        </span>
                        <input type="text" name="location" class="form-control" id="location" size="40" placeholder="<?php print _('Subnet location'); ?>">

                    </div>
                    <span class='text-muted'>The format should be: "Address, [zip] City, Country". Ex: <code>Route de Genève 24, 1033 Cheseaux-sur-Lausanne, Switzerland</code></span>
                    <br />
                    <span id="verif"></span>
                </td>
            </tr>

            <!-- owner --> 

            <tr >
                <th> * <?php print _('Customer'); ?></th>


                <td>
                    <div class="input-group">
                        <span class="input-group-btn" >
                        <button class="btn btn-primary" type="button"  data-toggle="modal" rel='tooltip' title='Help' data-target="#modalCustomer"><i class="fa fa-database"></i></button>
                        </span>
                       
                        <input type="text" name="owner" class="form-control" id="owner" size="40" placeholder="<?php print _('Customer'); ?>">



                    </div>
                </td>
            </tr>




            <!-- requester -->
            <tr>
                <th> * <?php print _('Requester'); ?> </th>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="text" name="requester" class="form-control" size="30" placeholder="<?php print _('Your email address'); ?>"></textarea>
                    </div>
                </td>
            </tr>

            <!-- comment -->
            <tr>
                <th> * <?php print _('Comment'); ?></th>
                <td class="comment">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                        <textarea name="comment" rows="3" class="form-control" style="width:100%" placeholder="<?php print _('If there is anything else you want to say about request write it in this box'); ?>!"></textarea>
                    </div>
                </td>
            </tr>

            <!-- submit -->
            <tr>
                <td class="submit"></td>
                <td class="submit text-right">
                    <div class="btn-group text-right">

                        <input type="button" class="btn btn-sm btn-default clearSUBNETrequest" value="<?php print _('Reset'); ?>">

                        <input type="submit" class="btn btn-sm btn-default" value="<?php print _('Submit request'); ?>">

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

<!-- Modal -->
<div class="modal fade" id="modalLocation" tabindex="-1" role="dialog" aria-labelledby="Location">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header  modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">List of locations</h4>
            </div>
            <div class="modal-body">
                <select name="help_location" id="help_location" class="chosen-select">
                    <option>Find .....</option>
                    <?php
                    if ($locations !== false) {
                        foreach ($locations as $l) {
                            if ($subnet_old_details['location'] == $l->id) {
                                print "<option value='$l->name' selected='selected'>$l->address</option>";
                            } else {
                                print "<option value='$l->name'>$l->address</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="Owners">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header  modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">List of Customers</h4>
            </div>
            <div class="modal-body">
                <select name="help_customer" id="help_customer" class="chosen-select">
                    <option>Find .....</option>
                    <?php
                    $help = array();
                    foreach ($customers as $record) {
                        $help[] = $record->Account;
                    }
                    $customers = array_unique($help);

                    foreach ($customers as $customer => $cust) {
                        $option = $cust;
                        print "<option value='" . $option . "'>" . $option . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalVlan" tabindex="-1" role="dialog" aria-labelledby="Valns">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header  modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Vlans available</h4>
            </div>
            <div class="modal-body">
               <select  id="help_vlan" class="chosen-select">
                                <?php
                                foreach ($vlans as $vlan => $val) {
                                    $option = $val->name;
                                    print "<option value='" . $option . "'>" . $option . "</option>";
                                }
                                ?>
                            </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
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


