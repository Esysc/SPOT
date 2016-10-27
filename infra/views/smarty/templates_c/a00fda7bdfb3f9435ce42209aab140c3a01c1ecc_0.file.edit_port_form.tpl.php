<?php
/* Smarty version 3.1.28, created on 2016-10-21 16:53:39
  from "/var/www/infra/views/edit_port_form.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_580a2bf3656ea4_42041279',
  'file_dependency' => 
  array (
    'a00fda7bdfb3f9435ce42209aab140c3a01c1ecc' => 
    array (
      0 => '/var/www/infra/views/edit_port_form.tpl',
      1 => 1477059120,
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
function content_580a2bf3656ea4_42041279 ($_smarty_tpl) {
$_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>


<p style="font-size:1.5em;">Port <b><?php echo $_smarty_tpl->tpl_vars['port_id']->value;?>
</b> -- <b><?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getName();?>
</b> (<?php echo $_smarty_tpl->tpl_vars['LBL_10_actual_vlan']->value;?>
 : <?php echo $_smarty_tpl->tpl_vars['source_vlan']->value;?>
)</p>
<div class="infoBox">
		<u><?php echo $_smarty_tpl->tpl_vars['LBL_10_port_details']->value;?>
 : </u><br />
		<ul>
			<li><strong>Nom : </strong><?php echo $_smarty_tpl->tpl_vars['port_name']->value;?>
</li>
			<li><strong>Alias : </strong><?php echo $_smarty_tpl->tpl_vars['port_alias']->value;?>
</li>
			<li><strong>Description : </strong><?php echo $_smarty_tpl->tpl_vars['port_description']->value;?>
</li>
			<li><strong><?php echo $_smarty_tpl->tpl_vars['LBL_0_speed']->value;?>
 : </strong><?php echo $_smarty_tpl->tpl_vars['port_speed']->value;?>
 Mbits/sec.</li>
		</ul>
</div>

<div class="untag_port">
    <p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_in_vlan']->value;?>
</b></u> : </p>

    <form class="formulaire" action="untag_port.php" method="post" id="edit_port">
            <div>
                    <input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
                    <input type="hidden" id="source_vlan" name="source_vlan" value="<?php echo $_smarty_tpl->tpl_vars['source_vlan']->value;?>
"/>
                    <input type="hidden" id="port_id" name="port_id" value="<?php echo $_smarty_tpl->tpl_vars['port_id']->value;?>
"/>
            </div>

            <select class="form-control" id="dest_vlan" name="dest_vlan">
                    <?php
$__section_i_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['vlans_list']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total != 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                            <option label="<?php echo $_smarty_tpl->tpl_vars['vlans_list']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
" value="<?php echo $_smarty_tpl->tpl_vars['vlans_list']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
" <?php if (($_smarty_tpl->tpl_vars['source_vlan']->value == $_smarty_tpl->tpl_vars['vlans_list']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId())) {?> selected="selected" <?php }?>><?php echo $_smarty_tpl->tpl_vars['vlans_list']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</option>
                    <?php
}
}
if ($__section_i_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_0_saved;
}
?>
            </select>

            <br /><br />

            <input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_in_selected_vlan']->value;?>
"/>
    </form>
</div>
            	                        
                        
<?php if ($_smarty_tpl->tpl_vars['ALLOW_PORT_TAGGING']->value) {?>
	
	<div class="tag_port">
	
		<p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_tag_in_vlans']->value;?>
</b></u> : </p>
		
		<?php echo '<script'; ?>
 type="text/javascript" src="web/js/edit_port_form.js"><?php echo '</script'; ?>
>
		
		<form action="tag_port_in_selected_vlans.php" method="post" id="edit_port">
			<div>
				<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
				<input type="hidden" id="source_vlan" name="source_vlan" value="<?php echo $_smarty_tpl->tpl_vars['source_vlan']->value;?>
"/>
				<input type="hidden" id="port_id" name="port_id" value="<?php echo $_smarty_tpl->tpl_vars['port_id']->value;?>
"/>

				<ul>
					<?php
$__section_i_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['vlans_where_the_port_is_not_tagged']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total != 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<li><input type="checkbox" name="dest_vlans[]" id="dest_vlans" value="<?php echo $_smarty_tpl->tpl_vars['vlans_where_the_port_is_not_tagged']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
"/> <?php if (($_smarty_tpl->tpl_vars['source_vlan']->value == $_smarty_tpl->tpl_vars['vlans_where_the_port_is_not_tagged']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId())) {?><strong><?php }?> <?php echo $_smarty_tpl->tpl_vars['vlans_where_the_port_is_not_tagged']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
 <?php if (($_smarty_tpl->tpl_vars['source_vlan']->value == $_smarty_tpl->tpl_vars['vlans_where_the_port_is_not_tagged']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId())) {?></strong><?php }?></li>
					<?php
}
}
if ($__section_i_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_1_saved;
}
?>
					
					<li>---</li>
					<li><input type="checkbox" id="check_all"/> <?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>
</li>
                                        
				</ul>
				
				<br />
				<input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_tag_in_vlans']->value;?>
"/>
			</div>
		</form>
		
	</div>
		
	<?php if (!empty($_smarty_tpl->tpl_vars['no_untaggable_vlans']->value)) {?>
	
		<div class="no_untag_port">

			<p><u><b><?php echo $_smarty_tpl->tpl_vars['LBL_0_no_untag_in_vlans']->value;?>
</b></u> : </p>
			
			<form action="set_to_no_untagged_port_in_selected_vlans.php" method="post" id="edit_port_set_to_no_untagged">
				<div>
					<input type="hidden" id="switch_id" name="switch_id" value="<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"/>
					<input type="hidden" id="source_vlan" name="source_vlan" value="<?php echo $_smarty_tpl->tpl_vars['source_vlan']->value;?>
"/>
					<input type="hidden" id="port_id" name="port_id" value="<?php echo $_smarty_tpl->tpl_vars['port_id']->value;?>
"/>

					<ul>
						<?php
$__section_i_2_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['no_untaggable_vlans']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = $__section_i_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total != 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
								<li><input type="checkbox" id="dest_vlans_no_untagged" name="dest_vlans[]" value="<?php echo $_smarty_tpl->tpl_vars['no_untaggable_vlans']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
" <?php if ((!$_smarty_tpl->tpl_vars['source_vlan']->value == $_smarty_tpl->tpl_vars['no_untaggable_vlans']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId())) {?> checked="checked" <?php }?>/> <?php echo $_smarty_tpl->tpl_vars['no_untaggable_vlans']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</li>
						<?php
}
}
if ($__section_i_2_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_2_saved;
}
?>
						
						<li>---</li>
						<li><input type="checkbox" id="check_all_no_untagged"/> <?php echo $_smarty_tpl->tpl_vars['LBL_0_select_all']->value;?>
</li>
					</ul>
					
					<br />
					
					<input type="submit" class="btn btn-sm btn-info" value="<?php echo $_smarty_tpl->tpl_vars['LBL_0_no_untag_in_vlans']->value;?>
"/>
				</div>
			</form>
		</div>
	<?php }
}?>

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['LBL_0_back']->value;?>
</a>
</p></div>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:partial_commons".((string)$_smarty_tpl->tpl_vars['SYSTEM_PATH_SEPARATOR']->value)."_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
