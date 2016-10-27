<?php
/* Smarty version 3.1.28, created on 2016-10-27 16:37:21
  from "/var/www/infra/views/dashboard.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58121121cb5131_61113917',
  'file_dependency' => 
  array (
    'f4e54173727899048b8f61fdf501b2651ef1a9f4' => 
    array (
      0 => '/var/www/infra/views/dashboard.tpl',
      1 => 1477554038,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_errors.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_dashboard-switches_tables_container.tpl' => 1,
  ),
),false)) {
function content_58121121cb5131_61113917 ($_smarty_tpl) {
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_dashboard-switches_tables_container.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php }
}
