<?php
# initialize user object
$Database = new Database_PDO;
$Subnets = new Subnets($Database);
$Tools = new Tools($Database);
$Admin = new Admin($Database, false);
$Result = new Result ();

//$subnets = $Tools->fetch_all_objects("subnets", "subnet");
/*
 * SectionId = 1 = IPv4
 */
$subnets = $Subnets->fetch_section_subnets(1);
$subnets = (array) $subnets;
//var_dump($subnets);
?>


<div class="container">
    <form name="requestDISMISS" id="requestDISMISS">
        <div class="REQUESTdismiss">

            <!-- title -->

            <legend><?php print _('DISMISS request form'); ?></legend>

        </div>  



        <!-- back to login page -->
        <div class="iprequest" style="text-align:left">
            <a href="<?php print create_link("login"); ?>" class="backToLogin">
                <i class="fa fa-angle-left fa-pad-right"></i> <?php print _('Back to login'); ?>
            </a>
        </div>

        <!-- select dismiss dropdown -->
        <table class="REQUESTdismiss table table-responsive">
            <tr>
                <th>  * <?php print _('Subnet to dismiss'); ?><code> (only block of /24)</code></th>
                <td>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalSubnet" type="button" id='get-dismiss' rel='tooltip'  data-sectionId="1" title='<?php print _('Choose the subnet'); ?>'>
                                <i class="fa fa-gear" ></i>
                            </button>
                        </span>
                        <div class="clearfix">
                            <input  name="subnet" id="dismiss" class="form-control" type="text" placeholder="xxx.xxx.xxx.0" />
                            <input  name="subnetid" id="subnetid" class="form-control" type="hidden"  />
                        </div>
                        




                    </div>







                </td>
            </tr>



           

            <!-- requester -->
            <tr>
                <th> * <?php print _('Requester'); ?> </th>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
                        <div class="clearfix">
                            <input type="text" name="requester" id="requester" class="form-control" size="30" placeholder="<?php print _('Your email address'); ?>"></textarea>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- comment -->
            <tr>
                <th> * <?php print _('Comment'); ?></th>
                <td class="comment">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-comment"></i></span>
                        <div class="clearfix">
                            <textarea name="comment" id="comment" rows="3" class="form-control" style="width:100%" placeholder="<?php print _('If there is anything else you want to say about request write it in this box'); ?>!"></textarea>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- submit -->
            <tr>
                <td class="submit"></td>
                <td class="submit text-right">
                    <div class="btn-group text-right">

                        <input type="button" class="btn btn-sm btn-default clearDISMISSrequest" value="<?php print _('Reset'); ?>">

                        <input type="submit" class="btn btn-sm btn-default" value="<?php print _('Submit request'); ?>">

                        </td>
                        <td class="submit"></td>
            </tr>

        </table>



        <div id="REQUESTdismissresult"></div>


        <!-- back to login page -->
        <div class="iprequest" style="text-align:left">
            <a href="<?php print create_link("login"); ?>">
                <i class="fa fa-angle-left fa-pad-right"></i> <?php print _('Back to login'); ?>
            </a>
        </div>

    </form>
</div>

<!-- Modal -->



<div class="modal fade" id="modalSubnet" tabindex="-1" role="dialog" aria-labelledby="get-dismiss">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header  modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Active subnets</h4>
            </div>
            <div class="modal-body">
                <select  id="get-dismiss-active" class="chosen-select">
                    <option value="">Choose a subnet...</option>
                    <?php
                    foreach ($subnets as $subnet => $val) {
                        $option = $val->ip;
                        $id = $val->id;
                        print "<option value='" . $id . "'>" . $option . "</option>";
                    }
                    ?>
                </select>
                <hr />
                <div id="details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>




