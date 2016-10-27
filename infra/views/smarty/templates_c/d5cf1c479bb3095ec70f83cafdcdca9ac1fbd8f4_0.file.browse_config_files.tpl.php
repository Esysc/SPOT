<?php
/* Smarty version 3.1.28, created on 2016-10-19 17:35:45
  from "/var/www/infra/views/browse_config_files.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580792d19f26b2_18973932',
  'file_dependency' => 
  array (
    'd5cf1c479bb3095ec70f83cafdcdca9ac1fbd8f4' => 
    array (
      0 => '/var/www/infra/views/browse_config_files.tpl',
      1 => 1472021144,
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
function content_580792d19f26b2_18973932 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<ul>
	<?php
$_from = $_smarty_tpl->tpl_vars['conf_files_links']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_file_props_0_saved_item = isset($_smarty_tpl->tpl_vars['file_props']) ? $_smarty_tpl->tpl_vars['file_props'] : false;
$_smarty_tpl->tpl_vars['file_props'] = new Smarty_Variable();
$__foreach_file_props_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_file_props_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['file_props']->value) {
$__foreach_file_props_0_saved_local_item = $_smarty_tpl->tpl_vars['file_props'];
?>
			 <?php if ($_smarty_tpl->tpl_vars['file_props']->value['group']) {?><li><a href="<?php echo $_smarty_tpl->tpl_vars['file_props']->value['link'];?>
"><img src="web/images/group.png" alt="group"/>&nbsp;<?php echo $_smarty_tpl->tpl_vars['file_props']->value['name'];?>
</a></li><?php }?>
	<?php
$_smarty_tpl->tpl_vars['file_props'] = $__foreach_file_props_0_saved_local_item;
}
}
if ($__foreach_file_props_0_saved_item) {
$_smarty_tpl->tpl_vars['file_props'] = $__foreach_file_props_0_saved_item;
}
?>

	<?php
$_from = $_smarty_tpl->tpl_vars['conf_files_links']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_file_props_1_saved_item = isset($_smarty_tpl->tpl_vars['file_props']) ? $_smarty_tpl->tpl_vars['file_props'] : false;
$_smarty_tpl->tpl_vars['file_props'] = new Smarty_Variable();
$__foreach_file_props_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_file_props_1_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['file_props']->value) {
$__foreach_file_props_1_saved_local_item = $_smarty_tpl->tpl_vars['file_props'];
?>
			  <?php if (!$_smarty_tpl->tpl_vars['file_props']->value['group']) {?><li><a href="<?php echo $_smarty_tpl->tpl_vars['file_props']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['file_props']->value['name'];?>
</a></li><?php }?>
	<?php
$_smarty_tpl->tpl_vars['file_props'] = $__foreach_file_props_1_saved_local_item;
}
}
if ($__foreach_file_props_1_saved_item) {
$_smarty_tpl->tpl_vars['file_props'] = $__foreach_file_props_1_saved_item;
}
?>
</ul>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
