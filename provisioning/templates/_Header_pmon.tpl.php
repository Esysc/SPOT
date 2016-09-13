<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta http-equiv="X-Frame-Options" content="deny">
        <base href="<?php $this->eprint($this->ROOT_URL); ?>" />
        <title><?php $this->eprint($this->title); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="SPOT" />
        <meta name="author" content="andrea.cristalli@mycompany.com" />


        <style>
            .control-label {
                font-weight: bold;
            }
        </style>
        <!-- Le styles -->
        
        <!-- Latest compiled and minified CSS -->

        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" /> 
        <link href="styles/style.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link href="bootstrap/css/font-awesome.min.css" rel="stylesheet" />
        <!--[if IE 7]>
        <link rel="stylesheet" href="bootstrap/css/font-awesome-ie7.min.css">
        <![endif]-->
       

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        

    


       
<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
       
        <script src="scripts/jquery-ui.js"></script>
        <link rel="stylesheet" href="bootstrap/css/jquery-ui.css">

        <script>
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0) {
                window.onerror = function myErrorHandler(errorMsg, url, lineNumber) {
                    // deal with error
                    window.location.reload();
                    return false;
                }
            }
        </script>


    </head>

    <body>
