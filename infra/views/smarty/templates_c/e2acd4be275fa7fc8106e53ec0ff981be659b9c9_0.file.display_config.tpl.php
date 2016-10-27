<?php
/* Smarty version 3.1.28, created on 2016-10-27 12:23:56
  from "/var/www/infra/views/display_config.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5811d5bc0e47e2_46143074',
  'file_dependency' => 
  array (
    'e2acd4be275fa7fc8106e53ec0ff981be659b9c9' => 
    array (
      0 => '/var/www/infra/views/display_config.tpl',
      1 => 1477559841,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5811d5bc0e47e2_46143074 ($_smarty_tpl) {
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
