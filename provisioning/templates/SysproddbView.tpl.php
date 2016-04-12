<?php

$this->assign('title', 'SPOT | SysprodDB wrapper');
$this->assign('nav', 'sysproddb');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function () {
        function Base64(str) {
            return window.btoa(unescape(encodeURIComponent(str)));
        }
        $(window).on('beforeunload', function () {
            socket.close();
        });

        var socket = io.connect('http://chx-sysprod-01:8000');
        console.log(socket);
        socket.on('notification', function (data) {
            var recordList = "<p>";

            $.each(data.idracks, function (key, value) {
                $.each(value, function (subkey, subvalue) {
                    recordList += subkey + " : " + subvalue + " "
                });
                recordList += "</p>"
            });
            $('#container').html(recordList);

            $('time').html(data.time);
            /*		$('#footer').html('Sysprod &copy; ' + new Date().getFullYear() + ' - Dev by ACS'); */
        });

    });
</script>
<div class="container">
    <h1>
        <i class="icon-th-list"></i> SysprodDB wrapper  
    </h1>
    <div id="container" class="highlighted"></div>
</div>
<?php

$this->display('_Footer.tpl.php');
?>