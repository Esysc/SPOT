<?php

$this->assign('title', 'SPOT | VAMT Status');
$this->assign('nav', 'vamt');

$this->display('_Header.tpl.php');
?>

<script>
    $(document).ready(function () {

        $.ajax({
            url: '/SPOT/provisioning/includes/vamt.php',
            success: function (data) {
                $('#iot').html(data);
            }

        });
        $(document).on('mouseover', '#export', function () {
            $(this).css('cursor', 'pointer');
            $(this).attr('title', 'Click to export in excel');
        });
        $(document).on('click', '#export', function (e) {
            var htmlDiv = $(this).html();
            e.preventDefault();
            $.ajax({
                url: "includes/loadSession.php",
                type: "POST",
                //htmlDiv is take from template, no need to define here
                //it contains the table var
                data: {datatoPdf: htmlDiv},
                success: function (data) {
                    var title = "Vamt_activation_status";
                    var url = 'libs/App/excelexport.php?var=datatoPdf&debug=false&title=' + title + ".xls";
                    // $('#tblprogressDetailDialog').modal('hide');
                    window.location.assign(url);

                }
            });
        });


    });
</script>
<div class="container">

    <h1>
        <i class="icon-th-list"></i> Vamt report

    </h1>
    <table   class="table table-auto-wide table-responsive table-condensed">
        <tr>
            <th><center>Windows IOT activation status (Read only from chx-sysprod-01)</center></th>
        </tr>
        <tr>
            <td id="iot"></td>
        </tr>
    </table>

</div> <!-- /container -->

<?php

$this->display('_Footer.tpl.php');
?>
