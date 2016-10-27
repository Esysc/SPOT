<?php
/* Smarty version 3.1.28, created on 2016-10-26 18:21:28
  from "/var/www/infra/views/untag_port_popup.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5810d808efb6b7_06588057',
  'file_dependency' => 
  array (
    '50a4d76a077ac5efced6b631544e06bb824fa3d8' => 
    array (
      0 => '/var/www/infra/views/untag_port_popup.tpl',
      1 => 1477064739,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5810d808efb6b7_06588057 ($_smarty_tpl) {
?>

<p><?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_success_port_id']->value;?>
 <b><?php echo $_smarty_tpl->tpl_vars['port_id']->value;?>
</b> <?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_success_switch']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['mySwitch']->value->getName();?>
 <?php echo $_smarty_tpl->tpl_vars['LBL_0_untag_success']->value;?>
 <b><?php echo $_smarty_tpl->tpl_vars['dest_vlan']->value;?>
</b>.</p>

<?php }
}
