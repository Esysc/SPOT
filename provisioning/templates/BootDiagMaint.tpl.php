<?php
$this->assign('title', 'SPOT | Maintenance or Diag Mode boot');
$this->assign('nav', 'maintdiag');

$this->display('_Header.tpl.php');

$letters = range('A', 'G');

for ($i = 1; $i <= 24; $i++) {
    $rack = "rack$i";
    foreach ($letters as $str) {
        $shelf = "shelf$str";
        $arr[$rack . '_' . $shelf] = $rack . '_' . $shelf;
        $display[$rack . '_' . $shelf] = "Rack $i Shelf $str";
        $selected = "";
    }
}

$racks = $arr;
$startshelf = '';
?>
<script>

    /*
     * 
     * @jquery logic
     * manage the updates
     * updates php session
     * updates DB
     * read DB
     * validate Form
     * 
     * 
     * 
     * 
     */
    $(document).ready(function() {
        $("#general").submit(function(e) {
            return false;
        });
        $('*[required="required"]').before("<span class='icon-star' style='color:red'></span>");
        $('#bootmode').chosen();
        $('#rackshelf').chosen();
        $('#spot').chosen();
        $('#send').click(function(event) {
            var valid = true;
            $('#failed').hide();
            $('.control-group').removeClass('error');
            $('.help-inline').html('');
            $('*[required="required"]').each(function() {
                if ($(this).val() === '') {

                    $('#failed').html('Please, fill all the required fields! ');
                    $('#failed').show();
                    valid = false;
                }
            });
            if (valid == false)
                return valid;

            var rackshelf = $('#rackshelf').val();
            var bootmode = $('#bootmode').val();
            var spot = $('#spot').val();
            var scriptID = 3; //Nim wrapper
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->NIM; ?>';
            var rack = 25;
            var shelf = 'Z';
            var salesorder = '99999999';
            var args = {
                "0": "-c",
                "1": rackshelf,
                "2": "-i",
                "3": bootmode,
                "4": "-spot",
                "5": spot
            };
            var datastring = JSON.stringify(args);
            var exesequence = 1;
            var executionFlag = 0;
            var command = {
                salesorder: salesorder,
                rack: rack,
                shelf: shelf,
                clientaddress: clientaddress,
                arguments: datastring,
                exesequence: exesequence,
                executionflag: executionFlag,
                returnstdout: "Waiting for command execution",
                scriptid: scriptID
            };
            var Jcommand = JSON.stringify(command);
            var url = "/SPOT/provisioning/api/remotecommands/";

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: Jcommand,
                wait: true,
                success: function(data) {

                    $('#message').html("The command has been succesfully sent. Waiting for command execution message...");

                    $('#message').show();
                    setTimeout(function() {
                        $('#message').fadeOut(10000);
                    }, 12000);
                    $('#save').hide();
                    $('#general').hide();
                    var commandID = data.remotecommandid
                    var url = "/SPOT/provisioning/api/remotecommands/" + commandID;
                    setInterval(function() {
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function(data) {
                                $('.monitor').show();
                                var out = data.returnstdout.replace('[', '<br />[');
                                var err = data.returnstderr.replace('[', '<br />[');
                                $('#stdout').html('<strong>' + out + ' ' + err + '</strong>');
                            }
                        });
                    }, 2000);
                },
                error: function(data) {
                    $('#failed').html("An error occured:  " + data.statusText + " " + data.responseText);
                    console.log(data);
                    $('#failed').show();
                    setTimeout(function() {
                        $('#failed').fadeOut(3000);
                    }, 4000);

                }
            });
        });
    });











</script>  

<div class="container">

    <h1>
        <i class="icon-th-list"></i> Boot Diag/Maintenance mode


    </h1>


    <div class="alert alert-danger" id="failed" role="alert" style="display:none"></div>
    <div id="message" class="alert alert-success" role="alert" style="display:none"></div>
    

    <form id="general">


        <div class="form-group">

            <div id="accordion">
                <div>
                    <h3 class="ui-widget-header">Boot Maint/Diag mode - <small class="icon-star" style="color:red"> mark a field as required</small></h3>

                    <div id="posixmsg" class="pull-right"></div>
                    <fieldset>

                        <div id="rackselection" title="Rack Selection">
                            <table  class="collection table table-bordered table-hover">
                                <tr>
                                    <th>
                                        <span class="icon-tasks"></span> Rack Shelf Position
                                    </th>
                                    <th>
                                        <span class="icon-beer"></span> Boot Mode
                                    </th>
                                    <th>
                                        <span class="icon-file"></span>Spot to use as initial ram disk
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="rackshelf" id="rackshelf" class="chosen" data-placeholder="Choose the client position"  required="required">
                                            <?php
                                            echo "<option value='$startshelf'>$startshelf</option>";
                                            foreach ($racks as $rack) {
                                                echo "<option value='$rack'>$rack</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="bootmode" id="bootmode" class="chosen" data-placeholder="Choose a boot mode" required="required">
                                            <option value=""></option>
                                            <option value="diag">
                                                Diag - (build raid)
                                            </option>
                                            <option value="maint_boot">
                                                Maintenance mode
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="spot" id="spot" class="chosen" data-placeholder="Choose a spot" required="required">
                                            <option value=""></option>
                                            <option value="AIX53_SPOT">
                                                AIX53_SPOT (Recommended)
                                            </option>
                                            <option value="AIX71_SPOT">
                                                AIX71_SPOT
                                            </option>
                                        </select>
                                    </td>

                                </tr>
                            </table>
                        </div>



                        <button id="send"  class="btn btn-primary pull-right" >Save  </button>
                    </fieldset>





                </div>
            </div>
        </div>



    </form>
    <table  class="monitor table table-bordered" style="display:none" >
        <tr>

            <th>
                <span class="icon-coffee"></span> Response
            </th>

        </tr>
        <tr>
            <td>

                <p id="stdout"></p>
            </td>

        </tr>
    </table>


</div> <!-- /container -->

<?php
$this->display('_Footer.tpl.php');
?>
