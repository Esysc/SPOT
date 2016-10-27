<?php
/* Smarty version 3.1.28, created on 2016-10-19 08:37:50
  from "/var/www/SPOT/infra/views/list_vlans.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580714beb3e455_78511449',
  'file_dependency' => 
  array (
    '679d120dfbdeb169a536c799fb862bdff6279a25' => 
    array (
      0 => '/var/www/SPOT/infra/views/list_vlans.tpl',
      1 => 1461594244,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_header.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_menu.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_errors.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_list_vlans-switch_table_container.tpl' => 1,
    'file:partial_commons".((string)$_smarty_tpl->tpl_vars[\'SYSTEM_PATH_SEPARATOR\']->value)."_footer.tpl' => 1,
  ),
),false)) {
function content_580714beb3e455_78511449 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('switch_id'=>$_smarty_tpl->tpl_vars['mySwitch']->value->getId()), 0, true);
?>


<?php if ($_smarty_tpl->tpl_vars['HIDE_DETAILS_BOX']->value && !$_smarty_tpl->tpl_vars['DISABLE_DETAILS_BOX']->value) {?>
	<?php echo '<script'; ?>
 type="text/javascript" src="web/js/list_vlans.ready.js"><?php echo '</script'; ?>
>
<?php }?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value;?>


<form action="delete_selected_vlans_form.php" method="post" id="delete_selected_vlans">

	<?php if ($_smarty_tpl->tpl_vars['ALLOW_VLAN_CREATION']->value) {?>
		<a href="create_vlan_form.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['LBL_14_add_vlan']->value;?>
</a>
	<?php }?>
	
	<p><?php echo $_smarty_tpl->tpl_vars['LBL_14_select_a_port']->value;?>
</p>
	
	<div class="switch_table_container">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_list_vlans-switch_table_container.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<br />
		<?php if ($_smarty_tpl->tpl_vars['ALLOW_VLAN_DELETION']->value) {?>
			<div>
				<input type="submit" class="btn btn-sm btn-warning" value="<?php echo $_smarty_tpl->tpl_vars['LBL_14_delete_selected_vlans']->value;?>
"/>
			</div>
		<?php }?>
	</div>

</form>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php }
}
