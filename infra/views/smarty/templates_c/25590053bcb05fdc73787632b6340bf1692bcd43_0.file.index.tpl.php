<?php
/* Smarty version 3.1.28, created on 2016-11-08 17:33:14
  from "/var/www/SPOT/infra/views/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5821fe4aacaf14_45557760',
  'file_dependency' => 
  array (
    '25590053bcb05fdc73787632b6340bf1692bcd43' => 
    array (
      0 => '/var/www/SPOT/infra/views/index.tpl',
      1 => 1477554038,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_header.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_menu.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_footer.tpl' => 1,
  ),
),false)) {
function content_5821fe4aacaf14_45557760 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>



<h1 class="breadcrumb">Welcome to Procurve quick management</h1>
<div class="well">
    This GUI allow quick configuration management for the Sysprod Procurve switches.
    <?php if (!$_smarty_tpl->tpl_vars['ALLOW_PORT_TAGGING']->value) {?>
        <p>
            Please note that only untag (access port in cisco terms) is allowed.
        </p>
    <?php }?>
    The ports are mapped as follow:
    <ul>
        <li class="menu-title">esw03<-->esw24</li>
        <li> shelfA 1 11</li>
        <li>shelfB 2 12</li>
        <li>shelfC 3 13</li>
        <li>shelfD 4 14</li>
        <li>shelfE 5 15</li>
        <li>shelfF 6 16</li>
        <li>shelfG 7 17</li>
    </ul>
    <ul>
        <li class="menu-title">esw02</li>
        <li>shelf2A 8</li>
        <li>shelf2B 9</li>
        <li>shelf2C 10</li>
        <li>shelf2E 12</li>
        <li>shelf2F 13</li>
        <li>shelf2G 14</li>
    </ul>
</div>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
