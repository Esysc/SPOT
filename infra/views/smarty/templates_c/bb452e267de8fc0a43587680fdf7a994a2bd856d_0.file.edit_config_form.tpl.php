<?php
/* Smarty version 3.1.28, created on 2016-10-18 17:04:30
  from "/var/www/SPOT/infra/views/edit_config_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580639fef357c0_32134355',
  'file_dependency' => 
  array (
    'bb452e267de8fc0a43587680fdf7a994a2bd856d' => 
    array (
      0 => '/var/www/SPOT/infra/views/edit_config_form.tpl',
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
function content_580639fef357c0_32134355 ($_smarty_tpl) {
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
