<?php
/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */
 
session_start();

require_once( dirname(__FILE__) . '/../ipam/functions/functions.php');
            $Database = new Database_PDO;
            $User = new User($Database);
            $Result = new Result ();
            $Log = new Logging($Database);

            // try to authenticate on local and AD, users added through ipam administration
            $username = $_POST['login'];
            $password = $_POST['password'];
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
                echo "<script>window.location.replace('index.php')</script>";
               
            } 
 
?>

