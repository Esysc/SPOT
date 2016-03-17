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
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
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

        </script>

<!--  <script src="//code.jquery.com/jquery-1.9.1.min.js"></script> -->
        <link href="bootstrap/css/summernote.css" rel="stylesheet">
        <link href="bootstrap/css/codemirror.css" rel="stylesheet" />
        <script src="scripts/summernote.min.js"></script>  
        <script src="scripts/jquery-ui.js"></script>  

        <script src="scripts/ajaxrequest.js"></script>

        <?php if ($_SESSION['right'] != 99) { ?>
            <script src="scripts/javascript_functions.js"></script>
        <?php } ?>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>




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


        <div class="navbar navbar-inverse navbar-fixed-top">





            <div class="navbar-inner">



                <?php if ($_SESSION['right'] == 10) { ?>
                    <ul class="nav pull-right bgop">
                        <li>


                            <div class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse" id="pendings"></div>

                        </li>
                    </ul>
                <?php } ?>
                <ul class="nav pull-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock"></i> <?php if (isset($_SESSION['login'])) echo $_SESSION['login']; ?><i class="caret"></i></a>

                        <ul class="dropdown-menu">
                            <?php if (isset($_SESSION['login'])) { ?>
                                <li><a href="login.php?log=off"><img src="images/logout.gif" alt="logut" title="logout" class="img-circle"/> Logout </a></li>
                                <?php
                            }
                            if ($_SESSION['right'] !== 99) {
                                ?>
                                <li class="divider"></li>
                                <li><a href="./change?log=user"><i class="icon-lock"></i> Change Username</a></li>
                                <li><a href="./change?log=pass"><i class="icon-lock"></i> Change Password </a></li>
        <!--									<li><a href="./">Example Admin Page <i class="icon-lock"></i></a></li> -->
                            <?php } ?>
                        </ul>
                    </li>

                </ul>

                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">

                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <?php if ($_SESSION['right'] == 10) { ?>
                    <a class="brand" href="./">
                        <?php
                        if (isset($_SESSION['salesorder'])) {

                            echo "<span class='badge'> SO " . $_SESSION['salesorder'] . "</span>  ";
                        } if (isset($_SESSION['CustomerACR'])) {
                            echo "<span class='badge'> Customer " . $_SESSION['CustomerACR'] . "</span>  ";
                        }
                        ?> SPOT</a>
                    <div class="nav-collapse collapse">

                        <ul class="nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Provisioning<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li <?php
                                    if ($this->nav == 'scheduled') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./scheduled">Sharepoint "Scheduled"</a></li>
                                    <li <?php
                                    if ($this->nav == 'pendings') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./pendings">Sharepoint "In Progress"</a></li>
                                    <li <?php
                                    if ($this->nav == 'tblprogresses') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./tblprogresses">Stored orders</a></li>  
                                    <li <?php
                                    if ($this->nav == 'tblcompleted') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./tblcompleted">Full provisioned systems</a></li>
                                    <li <?php
                                    if ($this->nav == 'provisioningphase2') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./provisioning2">Provisioning Wizard</a></li>
                                    <li <?php
                                    if ($this->nav == 'customconfigsbuilder') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./customconfigsbuilder">Network Provisioning Wizard</a></li>

                                    <li <?php
                                    if ($this->nav == 'customconfigs') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./customconfigs">Customer network provisioning table</a></li>




                                </ul>
                            </li>





                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Monitoring<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li <?php
                                    if ($this->nav == 'provisioningnotificationses') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./provisioningnotificationses">Dashboard</a></li>
                                    <li <?php
                                    if ($this->nav == 'pmon') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./pmon" class="pmon" >Production Scheduling</a></li>
                                    <li <?php
                                    if ($this->nav == 'eventses') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./eventses">Events</a></li>
                                    <li <?php
                                    if ($this->nav == 'remotecommandses') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./remotecommandses">Remote commands</a></li>
                                    <li <?php
                                    if ($this->nav == 'provisioningactions') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./provisioningactions">Provisioning Jobs Sent</a></li>
                                    <li <?php
                                    if ($this->nav == 'dhcpmap') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./dhcpmap">Dhcp/Bootp Table Mapping</a></li>
                                    <li <?php
                                    if ($this->nav == 'pxeinv') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./pxeinv">Pxe clients inventory</a></li>
                                    <li <?php
                                    if ($this->nav == 'sysprodracksmappings') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./sysprodracksmappings">Racks Mappings</a></li>

                                </ul>
                            </li>





                        </ul>
                        <?php
                        if ($_SESSION['login'] === 'acs' || $_SESSION['login'] === 'admin') {
                            ?>

                            <ul class="nav">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administration<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="menu-item dropdown dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">DevOps</a>
                                            <ul class="dropdown-menu">

                                                <li <?php
                                                if ($this->nav == 'provisionningoss') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./provisionningoss">Operating systems</a></li>
                                                <!--  <li <?php
                                                if ($this->nav == 'eventcategories') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./eventcategories">Event categories</a></li> -->
                                                <li <?php
                                                if ($this->nav == 'executionflagcodeses') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./executionflagcodeses">Execution flag codes</a></li>
                                                <!-- <li <?php
                                                if ($this->nav == 'jobtostarts') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./jobtostarts">Jobs pending</a></li> -->
                                                <li <?php
                                                if ($this->nav == 'mediatypes') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./mediatypes">Media types</a></li>
                                                <li <?php
                                                if ($this->nav == 'provisioningimageses') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./provisioningimageses">Provisioning images</a></li>
                                                <li <?php
                                                if ($this->nav == 'provisioningscriptses') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./provisioningscriptses">Scripts</a></li>

                                                <li <?php
                                                if ($this->nav == 'sysprodracksmappings') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./sysprodracksmappings">Racks Mappings</a></li>
                                                <li <?php
                                                if ($this->nav == 'tempdatas') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./tempdatas">Temporary data table</a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item dropdown dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">NetOps</a>
                                            <ul class="dropdown-menu">

                                                <li <?php
                                                if ($this->nav == 'configtemplates') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./configtemplates">Templates configuration</a></li>
                                                <li <?php
                                                if ($this->nav == 'customconfigs') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./customconfigs">Customer Configurations</a></li>
                                                <li <?php
                                                if ($this->nav == 'networkequipments') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./networkequipments">Network Equipment</a></li>
                                                <li <?php
                                                if ($this->nav == 'cvsweb') {
                                                    echo 'class="active"';
                                                }
                                                ?>><a href="./cvsweb">Backup Infra</a></li>
                                            </ul>
                                        </li>
                                    </ul>



                                </li>
                            </ul>
                            <?php
                        }
                        ?>

                        <ul class="nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pre/Post Install<b class="caret"></b></a>
                                <ul class="dropdown-menu">

                                    <li class="menu-item dropdown dropdown-submenu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pre</a>
                                        <ul class="dropdown-menu">

                                            <li <?php
                                            if ($this->nav == 'maintdiag') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./maintdiag">Boot Maint/Diag mode</a></li>

                                        </ul>
                                    </li>

                                    <li class="menu-item dropdown dropdown-submenu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Post</a>
                                        <ul class="dropdown-menu">

                                            <li <?php
                                            if ($this->nav == 'memos') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./memos">Manage Memos</a></li>

                                            <li <?php
                                            if ($this->nav == 'password') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./setpasswords">Customize passwords</a></li>
                                            <li <?php
                                            if ($this->nav == 'tblpasswords') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./tblpasswords">Passwords archive</a></li>
                                            <li <?php
                                            if ($this->nav == 'randompass') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./randompass">Random Password Generator</a></li>
                                            <li <?php
                                            if ($this->nav == 'setipalias') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./setipalias">Set ip alias on MGT</a></li>
                                            <li <?php
                                            if ($this->nav == 'instmod') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./instmod">PRE CCT preparation</a></li>
                                            


                                        </ul>
                                    </li>




                                </ul>
                            </li>
                        </ul>
                    <?php } ?>
                    <ul class="nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">IP address INV<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li <?php
                                if ($this->nav == 'adresses') {
                                    echo 'class="active"';
                                }
                                ?>><a href="./adresses">Customer IP Inventory</a></li>
                                    <?php
                                    if ($_SESSION['right'] == 10) {
                                        ?>
                                    <li <?php
                                    if ($this->nav == 'ranges') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./ranges">Available IP ranges</a></li>

                                    <li <?php
                                    if ($this->nav == 'subnetcalculator') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./subnetcalculator">Subnet Calculator</a></li>

                                    <?php
                                }
                                if ($_SESSION['right'] == 2) {
                                    ?>

                                    <li <?php
                                    if ($this->nav == 'hotline') {
                                        echo 'class="active"';
                                    }
                                    ?>><a href="./hotline">CS Synchronisation</a></li>
                                        <?php
                                    }
                                    ?>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">External Link <b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a href="http://chx-sysprod-01.my.compnay.com" target="_blank">(OLD) Sysprod DB</a>
                                </li>
                                <li>
                                    <a href="http://sharepoint.my.compnay.com/sites/salesandops/sysprod/SitePages/Home.aspx" target="_blank">Sharepoint</a>
                                </li>
                                <li>
                                    <a href="http://ist.my.compnay.com" target="_blank">IST</a>
                                </li>


                            </ul>
                        </li>
                    </ul>
                    <ul class="nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">SysLog DB <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li <?php
                                if ($this->nav == 'tblorderses') {
                                    echo 'class="active"';
                                }
                                ?>><a href="./tblorderses">Query DB (Old Version)</a></li>
                                <li>
                                    <a href="http://sysproddb.my.compnay.com/sales_order.php" target="_blank">(Query DB (NEW)</a>
                                </li>
                                <?php if ($_SESSION['right'] == 10) { ?>
                                    <li class="menu-item dropdown dropdown-submenu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">DB Wrappers (rest API calls)</a>
                                        <ul class="dropdown-menu">
                                            <li <?php
                                            if ($this->nav == 'treebuilder') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./treebuilder">Build Tree in a order</a></li>
                                           <!-- <li <?php
                                            if ($this->nav == 'setattr') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./setattr">Set hostname ip attributes new DB</a></li> -->

                                            <li <?php
                                            if ($this->nav == 'setspecs') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./setspecs">Set Specifications</a></li>
                                            <li <?php
                                            if ($this->nav == 'setassgly') {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="./setassgly">Assemble Globally a given sales order</a></li>

                                        </ul>
                                    </li>
                                <?php } ?>


                            </ul>
                        </li>
                    </ul>
                    <!-- <ul class="nav">
                         <li class="dropdown">
                             <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
                             <ul class="dropdown-menu">


                                  <li <?php
                    if ($this->nav == 'networkses') {
                        echo 'class="active"';
                    }
                    ?>><a href="./networkses">Networks</a></li> 
                                    <li <?php
                    if ($this->nav == 'orderses') {
                        echo 'class="active"';
                    }
                    ?>><a href="./orderses">Orders</a></li>
                                <li <?php
                    if ($this->nav == 'orderslogs') {
                        echo 'class="active"';
                    }
                    ?>><a href="./orderslogs">Orders logs</a></li>
                                 <li <?php
                    if ($this->nav == 'provisionings') {
                        echo 'class="active"';
                    }
                    ?>><a href="./provisionings">Provisioning</a></li>
                                 
                                 
                                 
                                 

                                 <li <?php
                    if ($this->nav == 'sysprodrackses') {
                        echo 'class="active"';
                    }
                    ?>><a href="./sysprodrackses">Sysprod racks</a></li> 


                                  <li <?php
                    if ($this->nav == 'userses') {
                        echo 'class="active"';
                    }
                    ?>><a href="./userses">Users</a></li>
                             </ul>

                         </li> 
                     </ul> -->


                </div><!--/.nav-collapse -->


            </div>


        </div>
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

        <?php
        // Remember to put here the public view sites
        $publicSites = array('adresses', 'tblorderses', 'pmon');
        $requestSite = '';
        if (isset($_GET['_REWRITE_COMMAND']))
            $requestSite = $_GET['_REWRITE_COMMAND'];
        if (!in_array($requestSite, $publicSites) && $_SESSION['right'] != 10) {
            header('HTTP/1.1 401 Unauthorized');
            echo '<p class="alert alert-error"><b>You need to be logged in as <a href="login.php">sysprod user</a> to access this page</b></p>';
            $this->display('_Footer.tpl.php');
            exit;
        }
        ?>