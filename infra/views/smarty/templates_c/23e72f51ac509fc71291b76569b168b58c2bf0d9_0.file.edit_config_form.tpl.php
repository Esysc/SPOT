<?php
/* Smarty version 3.1.28, created on 2016-10-25 18:36:39
  from "/var/www/infra/views/edit_config_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580f8a176faeb1_62958688',
  'file_dependency' => 
  array (
    '23e72f51ac509fc71291b76569b168b58c2bf0d9' => 
    array (
      0 => '/var/www/infra/views/edit_config_form.tpl',
      1 => 1461594238,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_header.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_menu.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_errors.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_footer.tpl' => 1,
  ),
),false)) {
function content_580f8a176faeb1_62958688 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php if ($_smarty_tpl->tpl_vars['ENABLE_SWITH_CONFIGURATION_EDITION']->value == 1) {?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<h2><?php echo $_smarty_tpl->tpl_vars['LBL_9_switch_configuration_file_modification']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getName();?>
</h2>

<?php if ($_smarty_tpl->tpl_vars['message']->value != '') {?>
	<p style="color: green"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</p>
<?php } else { ?>
	<p style="color: red"><?php echo $_smarty_tpl->tpl_vars['LBL_9_switch_configuration_modification_warning']->value;?>
</p>
<?php }?>

<form action="edit_config.php" method="post" id="edit_config">
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
		
	</div>

	<textarea cols='100%' rows='50' id="conf" name="conf"><?php echo $_smarty_tpl->tpl_vars['conf']->value;?>
</textarea>
	<br /><br />
	<input type="submit" class="btn btn-sm btn-info" value="OK"/>
</form>

<?php }?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php }
}
