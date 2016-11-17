<?php
/* Smarty version 3.1.28, created on 2016-11-17 17:56:50
  from "/var/www/SPOT/infra/views/partial_commons/_header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_582de15264cb36_81365728',
  'file_dependency' => 
  array (
    'ffe109ca0018544ea362d6128231985a4fccfa49' => 
    array (
      0 => '/var/www/SPOT/infra/views/partial_commons/_header.tpl',
      1 => 1479398795,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_582de15264cb36_81365728 ($_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <title><?php echo $_smarty_tpl->tpl_vars['TITLE']->value;?>
</title>
        <link rel="shortcut icon" href="/favicon.ico" />

        <link rel="stylesheet" type="text/css" media="screen" href="web/css/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/style.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/styles.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/chosen.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/font-awesome/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="web/css/fontAwesomeAnimated.css" />

        <?php echo '<script'; ?>
 type="text/javascript" src="web/js/jquery-1.11.3.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="web/js/chosen.jquery.js" type="text/javascript" ><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="web/css/bootstrap/js/bootstrap.min.js" type="text/javascript" ><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="web/js/magic.js" type="text/javascript" ><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="web/js/jqueryPaginate.js" type="text/javascript" ><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>
            $(document).ready(function () {
                $('select').chosen().trigger('chosen:updated');
                $('a').addClass('');
            });
        <?php echo '</script'; ?>
>
    </head>

    <body>

        <div class="page-container">

            <!-- top navbar -->
            <div class="navbar navbar-default ">
                <div class="container">
                    <div class="loading" style="display:none;position:absolute"><img src="web/images/loader.gif" /></div>

                    <div class="navbar-header">

                        <table class="table table-striped table-responsive">
                            <tr>

                                <th class="btn btn-info"    >

                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Welcome <?php echo $_smarty_tpl->tpl_vars['USER']->value;?>

                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="index.php"><strong>HOME - <?php echo $_smarty_tpl->tpl_vars['TITLE']->value;?>
</strong></a></li>
                                            <li><a href="#" id="logout">Logout</a></li>

                                        </ul>
                                    </div>
                                </th>
                              
                                <td class="btn btn-primary btn-sm">
                                    UP tagged port
                                </td>
                                <td class="btn btn-success btn-sm">
                                    UP untagged port
                                </td>
                                <td class="btn btn-warning btn-sm">
                                    DOWN tagged port
                                </td>
                                <td class="btn btn-danger btn-sm">
                                    DOWN untagged port
                                </td>

                            </tr>
                        </table>

                    </div>


                </div>



            </div>

<?php }
}
