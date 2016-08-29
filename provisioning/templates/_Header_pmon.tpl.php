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
        <link rel="stylesheet" href="scripts/chosen/chosen.css">
        <!-- Latest compiled and minified CSS -->

        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" /> 
        <link href="styles/style.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link href="bootstrap/css/font-awesome.min.css" rel="stylesheet" />
        <!--[if IE 7]>
        <link rel="stylesheet" href="bootstrap/css/font-awesome-ie7.min.css">
        <![endif]-->
        <link href="bootstrap/css/datepicker.css" rel="stylesheet" />
        <link href="bootstrap/css/timepicker.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap-combobox.css" rel="stylesheet" />

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
                <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

        <script src="scripts/jquery-1.8.2.min.js" ></script>


        <script src="bootstrap/js/codemirror.js" type="text/javascript"></script>
        <script type="text/javascript" src="scripts/libs/LAB.min.js"></script>

        <script type="text/javascript">
            $LAB.script("scripts/jquery-1.8.2.min.js").wait()
                    .script("bootstrap/js/bootstrap.min.js").wait()
                    .script("bootstrap/js/bootstrap-datepicker.js").wait()
                    .script("bootstrap/js/bootstrap-timepicker.js").wait()
                    .script("bootstrap/js/bootstrap-combobox.js").wait()
                    .script("bootstrap/js/codemirror.js").wait()
                    .script("scripts/libs/underscore-min.js").wait()
                    .script("scripts/libs/underscore.date.min.js").wait()

                    .script("scripts/libs/backbone-min.js").wait()
                    .script("scripts/app.js").wait()
                    .script("scripts/model.js").wait()
                    .script("scripts/view.js").wait()
                    .script("scripts/javascript_functions.js").wait()
                    .script("scripts/chosen/chosen.jquery.js").wait()
                    .script("scripts/chosen/docsupport/prism.js").wait()
                    .script("scripts/summernote.min.js").wait()
                    .script("scripts/libs/backbone-relational.js").wait()
                    .script("scripts/jquery.webui-popover.js").wait()
                    .script("scripts/placeholder.js").wait()
                    .script("scripts/socket.io.js").wait()

        </script>

<!--  <script src="//code.jquery.com/jquery-1.9.1.min.js"></script> -->
        <link href="bootstrap/css/summernote.css" rel="stylesheet">
        <link href="bootstrap/css/codemirror.css" rel="stylesheet" />
        <script src="scripts/summernote.min.js"></script>  
        <script src="scripts/jquery-ui.js"></script>  

        <script src="scripts/ajaxrequest.js"></script>

        
            <script src="scripts/javascript_functions.js"></script>
       
        <script src="scripts/chosen/chosen.jquery.js" type="text/javascript" ></script>
        <script src="scripts/chosen/docsupport/prism.js" type="text/javascript" charset="utf-8" ></script>
        <script src="scripts/jquery.webui-popover.js"></script>
        <script src="scripts/excanvas.js"></script>
        <script src="scripts/Chart.js"></script>
        <!-- <link rel="stylesheet" href="libs/Controller/razorflow_php/static/rf/css/razorflow.min.css"/>
     <script src="libs/Controller/razorflow_php/static/rf/js/razorflow.wrapper.min.js" type="text/javascript"></script>
      <script src="libs/Controller/razorflow_php/static/rf/js/razorflow.devtools.min.js" type="text/javascript"></script> -->
        <script src="scripts/placeholder.js"></script>
        <script src="scripts/chartphp.js"></script>
        <script src="scripts/socket.io.js"></script>
       <!--  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script> -->
        <link rel="stylesheet" href="styles/chartphp.css">
        <link rel="stylesheet" href="bootstrap/css/jquery-ui.css">

        <style>
            body #basicModal {
                /* new custom width */
                width: 900px;
                /* must be half of the width, minus scrollbar on the left (30px) */
                margin-left: -450px;
            }
            #DataTable
            {

                height:400px;

                overflow-y: auto;


            }

        </style>
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


        
        <!-- Modal for Server message -->
        <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><center><span class="icon-phone"></span> Server Message</center></h4>
                    </div>
                    <div class="modal-body">

                        <p id="servermsg"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        
        
        