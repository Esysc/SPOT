<?php
$this->assign('title', 'SPOT | Random Password Generator');
$this->assign('nav', 'randompass');

$this->display('_Header.tpl.php');
if (isset($_POST['value'])) {
    $selected = $_POST['value'];
} else {
    $selected = '';
}
?>
<script type="text/javascript" src="scripts/clipboard.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        var elm = document.getElementById('value'),
                df = document.createDocumentFragment();
        for (var i = 8; i <= 42; i++) {
            var option = document.createElement('option');
            option.value = i;
            var ref = "<?php echo $selected; ?>";


            option.appendChild(document.createTextNode("Password lentgh #" + i));
            df.appendChild(option);
        }

        elm.appendChild(df);
        var ref = "<?php echo $selected; ?>";
        if (ref !== '')
            document.getElementById('value').value = ref;
        $('#value').chosen();
        $('body').on('click', 'button.copy', function (e) {

            e.preventDefault();

        });


    });
</script>

<div class="container">

    <h1>
        <i class="icon-th-list"></i>  <?php echo $this->title; ?>
<!--    <span id=loader class="loader progress progress-striped active"><span class="bar"></span></span> -->

    </h1>



    <?php

// Password Generator


    function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds') {
        $sets = array();
        if (strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if (strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if (strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if (strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if (!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    $checked = 'checked="checked"';

    isset($_POST['usenums']) ? $numcheck = $checked : $numcheck = '';
    isset($_POST['usecaps']) ? $capscheck = $checked : $capscheck = '';

    isset($_POST['uselower']) ? $lowercheck = $checked : $lowercheck = '';
    isset($_POST['usesymbols']) ? $symcheck = $checked : $symcheck = '';

    echo '
<h2>Password Generator</h2>
<div class="breadcrumb"><span class="icon-tint"></span> Want a random password? Just fill out this form and click &quot;Generate Password&quot;!<br /></div>
<form name="password" action="' . GlobalConfig::$ROOT_URL . $this->nav . '" method="post">
<table class=" table-bordered table-responsive table table-striped">
<tr><th>
<label> Include numbers?</label>
</th><td>
<input type="checkbox" name="usenums" ' . $numcheck . ' checked />
</td></tr>
<tr><th>
<label>Include capital letters?</label>
</th><td>
<input type="checkbox" name="usecaps" ' . $capscheck . ' checked /> 
</td></tr>
<tr><th>
<label>Include lowercase letters?</label>
</th><td>
<input type="checkbox" name="uselower"  ' . $lowercheck . '  checked /> 
</td></tr>
<tr><th>
<label>Include symbols/special characters?</label>
</th><td>
<input type="checkbox" name="usesymbols" ' . $symcheck . ' /> 
</td></tr>
<tr><th>
<label>Password length:</label>
</th><td>
<select name="value" id="value" >
</select>
</td></tr>
<tr><td>
<input name="go" value="Generate Password" type="submit" class="btn btn-success"/>
</td><th id="generated">
</th></tr>
</table>
</form>';
    if (isset($_POST['go'])) {
        $lenght = $_POST['value'];
        $add_dashes = false;
        $available_sets = '';

        if (isset($_POST['uselower']))
            $available_sets .= 'l';
        if (isset($_POST['usecaps']))
            $available_sets .= 'u';
        if (isset($_POST['usenums']))
            $available_sets .= 'd';
        if (isset($_POST['usesymbols']))
            $available_sets .= 's';
        $password = generateStrongPassword($lenght, $add_dashes, $available_sets);
        echo '<script>
            function feedback(clipboard) {
            var msg;
            clipboard.on("success", function(e) {
                msg = "  Copied!";
                $(".msg").html(msg);
                 $(".copy").attr("data-original-title", msg);
            });
            clipboard.on("error", function(e) {
                msg = "   Your browser doesn\'t allow clipboard access, copy and paste manually!";
            $(".msg").html(msg);
             $(".copy").attr("data-original-title", msg);
            });
         }
            var clipboard = new Clipboard(".copy");
            $("#generated").html(\'<div class="alert alert-success" role="alert">Your generated password is:  '
        . ' <input type="text" readonly id="toClipboard" value="' . $password . '"/>   '
        . '<button class="btn btn-mini copy" data-clipboard-action="copy" data-clipboard-target="#toClipboard" onclick="feedback(clipboard);"  title="Copy Value" data-toggle="tooltip">  Copy   Value    </button><span class="msg"></span></div>\'); </script>';
    }
    ?>



</div>
<?php
$this->display('_Footer.tpl.php');
?>

