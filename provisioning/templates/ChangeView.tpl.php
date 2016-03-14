<?php
/**
 * Template for changing user information (username and password)
 * 
 */
$this->assign('title', 'SPOT | Manage you account');
$this->assign('nav', 'Account');
$this->display('_Header.tpl.php');

$DBHOST = GlobalConfig::$CONNECTION_SETTING->ConnectionStringDRBL;
$DBUSER = GlobalConfig::$CONNECTION_SETTING->UsernameDRBL;
$DBPASS = GlobalConfig::$CONNECTION_SETTING->PasswordDRBL;
$DBBASE = GlobalConfig::$CONNECTION_SETTING->DBNameDRBL;

function clear($message) {
    if (!get_magic_quotes_gpc()) {
        $message = addslashes($message);
    }
    $message = strip_tags($message);
    $message = htmlentities($message);
    return trim($message);
}

if (isset($_GET['log']))
    $_SESSION['log'] = clear($_GET['log']);
if ($_SESSION['log'] == 'user' && !isset($_POST['submit'])) {
    $title = "Change username";
    $form = '<label for="New User Name">   <input id="username" name="username" required type="text" placeholder="New User Name" size="15" /> </label>
      <input type="hidden" id="operation" name="operation" value="Username" />
		<input type="hidden" name="password" id="password" value="' . $_SESSION['pass'] . '" />';
}
if ($_SESSION['log'] == 'pass' && !isset($_POST['submit'])) {
    $title = 'change password';
    $form = '<label for="New Password">   <input id="password" name="password" required type="password" placeholder="New Password" size="15" /> </label>
	 <label for="Confirm New Password"> <input id="password-check" name="password-check" required type="password" placeholder="Confirm New Password" size="15" /> </label>
         <input type="hidden" id="operation" name="operation" value="Password" />
<input type="hidden" name="username" id="username" value="' . $_SESSION['login'] . '" />';
}
if (isset($_POST['submit'])) {
    try {
        $link = ($GLOBALS["___mysqli_ston"] = mysqli_connect($DBHOST, $DBUSER, $DBPASS));
        ((bool) mysqli_query($GLOBALS["___mysqli_ston"], "USE $DBBASE"));
        $username = clear($_POST['username']);
        $password = clear($_POST['password']);
        $password = md5($password);
        $old_username = $_SESSION['login'];
        $sql = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT U_id FROM users WHERE U_login = '$old_username'");
        $row = mysqli_fetch_array($sql);
        $id = $row['U_id'];

        if ($_SESSION['log'] == 'user') {
            $result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET U_login = '$username' WHERE U_id = '$id'");
            $status = '<div class="alert alert-success">New user name set!</div>';
        }
        if ($_SESSION['log'] == 'pass') {
            $result = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET U_password = '$password' WHERE U_login = '$username'");
            $status = '<div class="alert alert-success">New password set!</div>';
        }

        session_regenerate_id(true);
        //  ob_end_clean();

        unset($_SESSION['log']);
        $_SESSION['login'] = $username;
        $_SESSION['pass'] = $password;
    } catch (Exception $e) {
        $status = '<div class="alert alert-danger">An error occured changing ' . $_POST['operation'] . ': ' . $e->getMessage() . '</div>';
    }
    unset($_SESSION['log']);
}
?>

<div class="container">
    <h2 class="img-rounded"><i class="icon-th-list"></i> <?php if(isset($title))  echo $title ?></h2>
    <?php if (!isset($_POST['submit'])) { ?>
        <form method="post"   class="form-horizontal" role="form">

            <?php echo $form ?>
            <input type="submit" id="submit" class="btn btn-success" name="submit" value="Change" />
            <a id="link" class="btn btn-primary" name="link" href="index.php">Cancel</a>
        </form>

        <p>&nbsp; </p>

    <?php } ?>
    <script>
        jQuery(function() {
            $("#submit").click(function() {
                var operation = $('#operation').val();
                if (operation === 'Password') {
                    $(".error").hide();
                    var hasError = false;
                    var passwordVal = $("#password").val();
                    var checkVal = $("#password-check").val();
                    if (passwordVal == '') {
                        var error = 'Please enter a password.';
                        hasError = true;
                    } else if (checkVal == '') {
                        var error = 'Please re-enter your password.';
                        hasError = true;
                    } else if (passwordVal != checkVal) {
                        var error = 'Passwords do not match.';
                        hasError = true;
                    } else if (passwordVal.length < 8) {
                        var error = 'Password should consists of a minimum of 8 chars';
                        hasError = true;
                    }

                    if (hasError == true) {
                        $('form').after('<div class="alert alert-danger">' + error + '</div>');
                        return false;
                    }
                }
            });
        });
    </script>
    <?php if (isset($_POST['submit'])) echo $status; ?>
</div>
<?php
$this->display('_Footer.tpl.php');

