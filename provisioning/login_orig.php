<?php
ob_start();
session_start();
$right = '';

/* GlobalConfig object contains all configuration information for the app */
include_once("_global_config.php");
include_once("_app_config.php");
@include_once("_machine_config.php");

$DBHOST = GlobalConfig::$CONNECTION_SETTING->ConnectionStringDRBL;
$DBUSER = GlobalConfig::$CONNECTION_SETTING->UsernameDRBL;
$DBPASS = GlobalConfig::$CONNECTION_SETTING->PasswordDRBL;
$DBBASE = GlobalConfig::$CONNECTION_SETTING->DBNameDRBL;
$SERVERROOT = GlobalConfig::$ROOT_URL;
$status = '';

function clear($message) {
    if (!get_magic_quotes_gpc()) {
        $message = addslashes($message);
    }
    $message = strip_tags($message);
    $message = htmlentities($message);
    return trim($message);
}

if (isset($_GET['log'])) {
    $log = $_GET['log'];
    if ($log == 'off' && !isset($_POST['submit'])) {
        unset($_SESSION['login']);
        setcookie('login', '', time() - 86400);
        session_destroy();
        session_regenerate_id(true);
        ob_end_clean();
        $status = '<div class="alert alert-success">Logged out </div>';
        ?>
        <script>
            setTimeout(function () {

                $('.alert-success').slideUp('slow').fadeOut(function () {
                    window.open('<?php echo $SERVERROOT; ?>', '_self');
                    /* or window.location = window.location.href; */
                });
            }, 2000);
        </script>
        <?php
    }
} else if (isset($_POST['submit'])) {



    ($GLOBALS["___mysqli_ston"] = mysqli_connect($DBHOST, $DBUSER, $DBPASS));
    ((bool) mysqli_query($GLOBALS["___mysqli_ston"], "USE $DBBASE"));
    $username = clear($_POST['login']);
    $password = clear($_POST['pass']);
    $password = md5($_POST['pass']);

    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE U_login = '$username' AND U_password = '$password'");

    $output = mysqli_fetch_array($result);


    if (isset($output)) {
        session_regenerate_id(true);
        ob_end_clean();
        $_SESSION['login'] = $username;
        $_SESSION['pass'] = $password;
        $_SESSION['ad_user'] = $output["U_AD_User"];
        $_SESSION['ad_pass'] = $output["U_AD_Password"];
        $_SESSION['phone'] = $output["U_Phone"];
        $_SESSION['full_name'] = $output["U_Full_Name"];
        $_SESSION['right'] = $output["U_right"];

        $_SESSION['U_id'] = $output["U_id"];
        $right = $_SESSION['right'];
        $right == 10 ? $url = 'index.php' : $url = './adresses';
        $status = '<div class="alert alert-success">Automatically redirecting ..... I this not the case, you can click on one of the  above buttons </div>';
        ?>
        <script>

            setTimeout(function () {

                $('#login').slideUp('slow').fadeOut(function () {

                    window.open('<?php echo $SERVERROOT . $url; ?>', '_self');
                    /* or window.location = window.location.href; */
                });
            }, 2000);
        </script>
        <?php
    } else {
        ?>
        <script>
            setTimeout(function () {

                $('.alert').fadeOut()


            }, 2000);
        </script>
        <?php
        // session_destroy();
        $status = '		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" >×</button>
			 Login failed! unknown username/password. 
		
		</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <title>SPOT System Production Overall Tool</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;

            }
            
        </style>

        <link href="bootstrap3/css/bootstrap.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-2.1.3.js"></script>
        <script src="bootstrap3/js/bootstrap.min.js"></script>
         <script src="scripts/placeholder.js"></script>
     

    </head>
    <div class='container'>
        <div class="container-fluid"> 

            <div class="jumbotron">
                <h2>User Access </h2>
                <p class="alert alert-info">System Production Overall Tool</p>
               <!-- <p>
                    <a href="./adresses" class="btn btn-mini btn-info">Visit Public Page</a>


                    <?php
                    if ($right == 10)
                        echo '<a href="index.php" class="btn btn-primary btn-large">Enter...</a>';
                    if ($right == 2)
                        echo '<a href="./adresses" class="btn btn-primary btn-large">Enter customer IP inventory</a>';
                    ?>


                </p>-->
                <p class="text-info">This app is compatible with Chrome (all versions), Firefox (all versions) </p>
            </div>
            <?php
            if (!isset($_GET['log'])) {
                ?>

                <form method="post" action="" id="login">
                    <fieldset>
                        <legend class="well well-sm form-control">Enter your credentials</legend>
                        <label for="login" >  User: <input id="login" name="login" type="text"  placeholder="User Name" size="15" /> </label>
                        <label for="pass">  Password: <input id="pass" name="pass" type="password" placeholder="Password" size="15" /> </label>

                        <input type="submit" id="submit" class="btn btn-mini btn-info" name="submit" value="Log In" />
                    </fieldset>
                </form>
                <?php
            }
            echo $status;
            ?>


        </div>
    </div>



    <?php
    include("templates/_Footer.tpl.php");


    