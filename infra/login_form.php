<?php
/*
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

session_start();
$error = false;
if (isset($_GET["connection"]) && $_GET["connection"] == "error") {
    $error = true;
}
if (isset($_POST['logout']) && $_POST['logout'] == true) {
    $logout = true;
    session_destroy();
}
if (isset($_GET['logout'])) {
    $message = "Succefully logged out";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>R28 switch configuration access</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/style.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/toolTip.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/errors.css" />

    </head>

    <body>
        <div class="page-container">
            <div class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">R28 switch configuration access</a>
                    </div>

                </div>
            </div>

            <div class="container">

                <div class="col-xs-12 col-sm-9">
                    <?php if ($error) { ?>
                        <p class="alert alert-danger">Bad login or password value !</p>
                    <?php } ?>
                        <?php if (!empty($message)) { ?>
                        <p class="alert alert-success"><?php echo $message; ?> !</p>
                    <?php } ?>
                    <form action="login_check.php" method="post" id="login_check">
                        <fieldset class="form-group">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" id="login" name="login"/>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"/>
                        </fieldset>
                        <input type="submit" class="btn btn-sm btn-info" value="OK"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
