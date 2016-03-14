<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->assign('title', 'SPOT | Infra backup');
$this->assign('nav', 'infra');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {
        $('#infra').on('click', function () {
            var scriptID = 100;
            var clientaddress = '<?php echo GlobalConfig::$SYSPROD_SERVER->MGT; ?>';
            var rack = 25;
            var shelf = "Z";
            var salesorder = 11111111;
            var exesequence = 1;
            var executionFlag = 0;
            var args = '{"0": "-u", "1" : "rancid", "2" : "-c", "3" : "rancid-run"}';
            var commandSet = {
                salesorder: salesorder,
                rack: rack,
                shelf: shelf,
                clientaddress: clientaddress,
                exesequence: exesequence,
                arguments: args,
                executionflag: executionFlag,
                scriptid: scriptID

            }
            var msg = "A new backup job has been launched trough rancid-run command for all network devices configured in routerdb file. It can take up to three minutes to complete";
            var Jcommand = JSON.stringify(commandSet);
            $.ajax({
                url: "/SPOT/provisioning/api/remotecommands",
                type: "POST",
                data: Jcommand,
                async: true,
                success: function () {

                    $('#servermsg').html(msg);
                    $('#basicModal').modal();
                }
            });
        });
    });
</script>
<div class="container">
    <h1>
        <i class="icon-th-list"></i>  <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->
        <button id="infra" class="btn btn-mini btn-info pull-right">New Backup</button>
    </h1>
    
    <div class="embed-responsive">
        <iframe src="/cgi-bin/cvsweb.cgi" class="embed-responsive-item" style=" border-width:0 " width="100%" height="800" frameborder="0" scrolling="yes"></iframe>
    </div>
</div>
<?php
$this->display('_Footer.tpl.php');
?>
