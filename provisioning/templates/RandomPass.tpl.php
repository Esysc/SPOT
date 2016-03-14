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
<script>
    $(document).ready(function () {

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
        $('body').on('click', 'button.copy', function(e) {
            e.stopPropagation();
             e.preventDefault();
             CopyToClipboard();
        });
        
         function CopyToClipboard () {
             
            var input = document.getElementById ("toClipboard");
            console.log(input);
            var textToClipboard = input.value;
            
            var success = true;
            if (window.clipboardData) { // Internet Explorer
                window.clipboardData.setData ("Text", textToClipboard);
            }
            else {
                    // create a temporary element for the execCommand method
                var forExecElement = CreateElementForExecCommand (textToClipboard);

                        /* Select the contents of the element 
                            (the execCommand for 'copy' method works on the selection) */
                SelectContent (forExecElement);

                var supported = true;

                    // UniversalXPConnect privilege is required for clipboard access in Firefox
                try {
                    if (window.netscape && netscape.security) {
                        netscape.security.PrivilegeManager.enablePrivilege ("UniversalXPConnect");
                    }

                        // Copy the selected content to the clipboard
                        // Works in Firefox and in Safari before version 5
                    success = document.execCommand ("copy", false, null);
                }
                catch (e) {
                    success = false;
                }
                
                    // remove the temporary element
                document.body.removeChild (forExecElement);
            }

            if (success) {
                var msg = "  The text is on the clipboard, try to paste it!";
                $('.msg').html(msg);
            }
            else {
                var msg = "   Your browser doesn't allow clipboard access!";
                $('.msg').html(msg);
                $('#toClipboard').removeAttr('disabled');
            }
        }

        function CreateElementForExecCommand (textToClipboard) {
            var forExecElement = document.createElement ("div");
                // place outside the visible area
            forExecElement.style.position = "absolute";
            forExecElement.style.left = "-10000px";
            forExecElement.style.top = "-10000px";
                // write the necessary text into the element and append to the document
            forExecElement.textContent = textToClipboard;
            document.body.appendChild (forExecElement);
                // the contentEditable mode is necessary for the  execCommand method in Firefox
            forExecElement.contentEditable = true;

            return forExecElement;
        }

        function SelectContent (element) {
                // first create a range
            var rangeToSelect = document.createRange ();
            rangeToSelect.selectNodeContents (element);

                // select the contents
            var selection = window.getSelection ();
            selection.removeAllRanges ();
            selection.addRange (rangeToSelect);
        }
   
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
        echo '<script>$("#generated").html(\'<div class="alert alert-success" role="alert">Your generated password is:  '
        . ' <input type="text" disabled id="toClipboard" value="' . $password . '"/>   '
    .'<button class="btn btn-mini copy" >  Copy   Value    </button><span class="msg"></span></div>\'); </script>';
    }
    ?>



</div>
<?php
$this->display('_Footer.tpl.php');
?>

