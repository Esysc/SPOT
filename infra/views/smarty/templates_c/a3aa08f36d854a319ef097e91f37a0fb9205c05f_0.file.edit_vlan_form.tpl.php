<?php
/* Smarty version 3.1.28, created on 2016-10-18 17:37:09
  from "/var/www/SPOT/infra/views/edit_vlan_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580641a5db4808_70750152',
  'file_dependency' => 
  array (
    'a3aa08f36d854a319ef097e91f37a0fb9205c05f' => 
    array (
      0 => '/var/www/SPOT/infra/views/edit_vlan_form.tpl',
      1 => 1466672972,
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
function content_580641a5db4808_70750152 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<?php echo '<script'; ?>
 type="text/javascript" src="web/js/edit_vlan_form.js"><?php echo '</script'; ?>
>

<p>Vlan id <b><?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
</b> -- <?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getName();?>
 (<?php echo $_smarty_tpl->tpl_vars['LBL_11_actual_name']->value;?>
 : <?php echo $_smarty_tpl->tpl_vars['vlan_name']->value;?>
)</p>

<p><u><?php echo $_smarty_tpl->tpl_vars['LBL_11_rename_vlan']->value;?>
</u> : </p>

<form action="edit_vlan_name.php" method="post" id="edit_vlan_name">
	<div>
		<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
		<input type="hidden" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
	</div>

	<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['vlan_name']->value;?>
" id="vlan_name" name="vlan_name"/>

	<input type="submit" class="btn btn-sm btn-info" value="OK"/>
</form>

<?php if ($_smarty_tpl->tpl_vars['ALLOW_PORT_TAGGING']->value) {?>
	<div class="tag_port">
	
		<p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_tag_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
</b></u> : </p>
		
		
		<form action="tag_selected_ports_in_vlan.php" method="post" id="tag_selected_ports_in_vlan">
			<div>
				<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
				<input type="hidden" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>

				<ul>
					<?php
$__section_i_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['taggable_ports']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total != 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<li><input type="checkbox" name="dest_ports_tagged[]" id="dest_ports_tagged" value="<?php echo $_smarty_tpl->tpl_vars['taggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
"/><?php echo $_smarty_tpl->tpl_vars['taggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</li>
					<?php
}
}
if ($__section_i_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_0_saved;
}
?>
					
					<li>---</li>
					<li><input type="checkbox" id="check_all_tagged"/><?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>
</li>
				</ul>
				
				<br />
				
				<input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_tag_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
			</div>
		</form>
		
	</div>
	
	<div class="no_untag_port">

			<p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_no_untag_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
</b></u> : </p>
			
			<form action="set_to_no_untagged_selected_ports_in_vlan.php" method="post" id="set_to_no_untagged_selected_ports_in_vlan">
				<div>
					<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
					<input type="hidden" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
				
					<ul>
						<?php
$__section_i_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['no_untaggable_ports']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total != 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
								<li><input type="checkbox" id="dest_ports_no_untagged" name="dest_ports_no_untagged[]" value="<?php echo $_smarty_tpl->tpl_vars['no_untaggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
"/><?php echo $_smarty_tpl->tpl_vars['no_untaggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</li>
						<?php
}
}
if ($__section_i_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_1_saved;
}
?>
						
						<li>---</li>
						<li><input type="checkbox" id="check_all_no_untagged"/><?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>
</li>
					</ul>
					
					<br />
					
				<input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_no_untag_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
				</div>
			</form>
		</div>
<?php }?>

<div class="untag_port">

	<p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_ports_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
</b></u> : </p>
	
	<form action="untag_selected_ports_in_vlan.php" method="post" id="untag_selected_ports_in_vlan">
		<div>
			<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
			<input type="hidden" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
		
			<ul>
				<?php
$__section_i_2_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['untaggable_ports']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = $__section_i_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total != 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<li><input type="checkbox" id="dest_ports_untagged" name="dest_ports_untagged[]" value="<?php echo $_smarty_tpl->tpl_vars['untaggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
"/><?php echo $_smarty_tpl->tpl_vars['untaggable_ports']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</li>
				<?php
}
}
if ($__section_i_2_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_2_saved;
}
?>
				
				<li>---</li>
				<li><input type="checkbox" id="check_all_untagged"/><?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>
</li>
			</ul>
			
			<br />
			
		<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_selected_ports_in_vlan']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
		</div>
	</form>
</div>

<?php if ($_smarty_tpl->tpl_vars['ALLOW_VLAN_DELETION']->value) {?>
<hr />
	<form action="delete_vlan.php" method="post" id="edit_vlan_name">
		<div>
			<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
			<input type="hidden" id="vlan_id" name="vlan_id" value="<?php echo $_smarty_tpl->tpl_vars['vlan_id']->value;?>
"/>
			<input type="hidden" id="vlan_name" name="vlan_name" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getName();?>
"/>
		</div>
	
		<input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_11_delete_vlan']->value;?>
"/>
	</form>
<?php }?>

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['LBL_0_back']->value;?>
</a>
</p></div>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
