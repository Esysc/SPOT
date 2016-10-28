<?php
/* Smarty version 3.1.28, created on 2016-10-28 16:55:37
  from "/var/www/SPOT/infra/views/partial_commons/_errors.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_581366e928e864_75762021',
  'file_dependency' => 
  array (
    '17367a31d56289e06b2051c6c434f9fe8a1ac21e' => 
    array (
      0 => '/var/www/SPOT/infra/views/partial_commons/_errors.tpl',
      1 => 1477554038,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_581366e928e864_75762021 ($_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['errors']->value) {?>
	<div class="errors">
		<h3><?php echo $_smarty_tpl->tpl_vars['MSG_ERRORS_OCCURED']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['MSG_ERRORS_ADVICE']->value;?>
</h3>
			<ul>
				<?php
$__section_i_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['errors']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total != 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
					<li><?php echo $_smarty_tpl->tpl_vars['errors']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)];?>
</li>
				<?php
}
}
if ($__section_i_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_0_saved;
}
?>
			</ul>
	</div>
<?php }
}
}
