<?php
/* Smarty version 3.1.28, created on 2016-10-28 09:31:49
  from "/var/www/SPOT/infra/views/display_config.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5812fee51089c0_60867626',
  'file_dependency' => 
  array (
    '6dbb5bdf9e9510affadc483b66a072aae72de946' => 
    array (
      0 => '/var/www/SPOT/infra/views/display_config.tpl',
      1 => 1477559841,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5812fee51089c0_60867626 ($_smarty_tpl) {
?>



<?php if ($_smarty_tpl->tpl_vars['ENABLE_SWITH_CONFIGURATION_VIEW']->value == 1) {?>
        <?php if ($_smarty_tpl->tpl_vars['ENABLE_SWITH_CONFIGURATION_EDITION']->value == 1) {?>
            <p><a href="edit_config_form.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['LBL_0_modify']->value;?>
</a></p>
        <?php }?>
	<?php echo $_smarty_tpl->tpl_vars['conf']->value;?>

<?php }?>


<?php }
}
