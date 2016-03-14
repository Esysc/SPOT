<?php

$this->assign('title', 'SPOT | SysprodDB wrapper');
$this->assign('nav', 'sysproddb');

$this->display('_Header.tpl.php');
?>
<script>
    $(document).ready(function() {
       function Base64( str ) {
  return window.btoa(unescape(encodeURIComponent( str )));
}

        var auth = 'Basic ' + Base64('sysprod:***REMOVED***');
        $.ajax({
            url: "https://sysproddb.my.compnay.com/api/1.0/",
            type: "GET",
            // data: session,
            dataType: 'json',
            headers: {Authorization: auth},
            success: function(data) {
                // All OK , pass to phase 2
                console.log(data);
            },
            error: function(data) {
                console.log('error');
            }
        });
    });
</script>
<div class="container">
    <h1>
        <i class="icon-th-list"></i> SysprodDB wrapper  
    </h1>
</div>
<?php

$this->display('_Footer.tpl.php');
?>