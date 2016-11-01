<?php
/* Smarty version 3.1.28, created on 2016-11-01 10:11:48
  from "/var/www/SPOT/infra/views/comparative_view_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58185c541b0428_32093525',
  'file_dependency' => 
  array (
    'b6c37fc0e1727f413058f2ecee0d93fda4e3f59e' => 
    array (
      0 => '/var/www/SPOT/infra/views/comparative_view_form.tpl',
      1 => 1477640516,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_comparative_view_form.tpl' => 1,
  ),
),false)) {
function content_58185c541b0428_32093525 ($_smarty_tpl) {
?>


<p><u><?php echo $_smarty_tpl->tpl_vars['LBL_3_select_two_switches']->value;?>
</u> : </p>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_comparative_view_form.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php }
}
