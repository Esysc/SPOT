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
                <p>



                    <?php
                    if ($right == 10)
                        echo '<a href="index.php" class="btn btn-primary btn-large">Enter...</a>';
                    ?>


                </p>
                <p class="text-info">This app is compatible with Chrome (all versions), Firefox (all versions) </p>
            </div>
            <?php
            
            if (!isset($_GET['log'])) {
                ?>

                <form method="post" action="" id="login">
                    <fieldset>
                        <legend class="well well-sm form-control"><strong>Enter your AD credentials</strong></legend>
                        <label for="login" >AD  User: <input id="login" name="login" type="text"  placeholder="User Name" size="15" /> </label>
                        <label for="pass">AD  Password: <input id="pass" name="pass" type="password" placeholder="Password" size="15" /> </label>

                        <input type="submit" id="submit" class="btn btn-mini btn-info" name="submit" value="Log In" />
                    </fieldset>
                </form>
                <?php
            }
            echo $status;
            ?>


        </div>




        <?php
        ob_start();
        session_start();
        $right = '';

        /* GlobalConfig object contains all configuration information for the app */
        include_once("_global_config.php");
        include_once("_app_config.php");
        include_once("_machine_config.php");





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
                $status = '<div class="alert alert-success">Successfully logged out </div>';
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
                echo $status;
            }
        } else if (isset($_POST['submit'])) {
# initialize user object from IPAM DB
# The authentication system is now manage by IPAM administration
            require_once( dirname(__FILE__) . '/../ipam/functions/functions.php');
            $Database = new Database_PDO;
            $User = new User($Database);
            $Result = new Result ();
            $Log = new Logging($Database);

            // try to authenticate on local and AD, users added through ipam administration
            $username = $_POST['login'];
            $password = $_POST['pass'];
            $_POST['ipamusername'] = $User->strip_input_tags($username);
            $_POST['ipampassword'] = $password;

            if (!empty($_POST['ipamusername']) && !empty($_POST['ipampassword'])) {

                # initialize array
                $ipampassword = array();

                # check failed table
                $cnt = $User->block_check_ip();

                # check for failed logins and captcha
                if ($User->blocklimit > $cnt) {
                    // all good
                }
                # count set, captcha required
                elseif (!isset($_POST['captcha'])) {
                    $Log->write("Login IP blocked", "Login from IP address $_SERVER[REMOTE_ADDR] was blocked because of 5 minute block after 5 failed attempts", 1);
                    $status = $Result->show("danger", _('You have been blocked for 5 minutes due to authentication failures'), true);
                }
                # captcha check
                else {
                    # check captcha
                    if (strtolower($_POST['captcha']) != strtolower($_SESSION['securimage_code_value'])) {
                        $status = $Result->show("danger", _("Invalid security code"), true);
                    }
                }

                # all good, try to authentucate user
                # fetch
                try {

                    $status = $User->authenticate($_POST['ipamusername'], $_POST['ipampassword']);
                } catch (Exception $e) {
                    $status = $this->Result->show("danger", _("Error: ") . $e->getMessage());
                    return false;
                }




                $_SESSION['login'] = "$username";
                if ($_SESSION['login'] === "mycompanyuser") {

                    $_SESSION['right'] = 99;
                } else {
                    $_SESSION['right'] = 10;
                }
                $url = '';
                if (isset($_GET['location'])) {
                    $link = $_GET['location'];
                    $link_hash = explode('/', $link);
                    $url = end($link_hash);
                }
               
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
            }
# Username / pass not provided
            else {
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

        include("templates/_Footer.tpl.php");
        
        ?>
    </div>

