<?php
/* Smarty version 3.1.28, created on 2016-10-26 12:05:35
  from "/var/www/infra/views/create_vlan_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58107fef24bbe6_17497273',
  'file_dependency' => 
  array (
    'd55b5a6dcade408a00736d5c44f245f7434702c4' => 
    array (
      0 => '/var/www/infra/views/create_vlan_form.tpl',
      1 => 1461594238,
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
function content_58107fef24bbe6_17497273 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<form action="create_vlan.php" method="post" id="create_vlan_form">

	<?php echo '<script'; ?>
 type="text/javascript" src="web/js/create_vlan_form.js"><?php echo '</script'; ?>
>
	
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
	</div>
	
	<fieldset class="form-group">
						<label for="vlan_name"><?php echo $_smarty_tpl->tpl_vars['LBL_0_name']->value;?>
</label>
						<input type="text" class="form-control" id="vlan_name" name="vlan_name" value="<?php echo $_smarty_tpl->tpl_vars['vlan_name']->value;?>
" />
	</fieldset>
	
	<fieldset class="form-group">
						<label for="vlan_id">Id</label>
						<input type="text" class="form-control" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
" />
	</fieldset>
	
	<fieldset class="form-group">
		<input type="submit" class="btn btn-sm btn-info" value="OK"/>
	</fieldset>
	
	<fieldset class="form-group">
		<div>
			<h4><?php echo $_smarty_tpl->tpl_vars['LBL_5_deploy_vlan']->value;?>
 :</h4>
		</div>
		<ul>
			<?php
$__section_i_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['mySwitchs']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total != 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
				<?php if ($_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId() != $_smarty_tpl->tpl_vars['mySwitch']->value->getId()) {?>
					<li><input type="checkbox" name="dest_switches[]" id="dest_switches" value="<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
"/> <?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</li>
				<?php }?>
			<?php
}
}
if ($__section_i_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_0_saved;
}
?>
			
			<li>---</li>
			
			<li>
				<div class="checkbox">
					<input type="checkbox" id="check_all"/><?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>

				</div>
			</li>
		</ul>
	</fieldset>

</form>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
