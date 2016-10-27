<?php
/* Smarty version 3.1.28, created on 2016-10-27 16:50:20
  from "/var/www/infra/views/partial_commons/_footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5812142c8737d0_50717602',
  'file_dependency' => 
  array (
    '05a38ee5e61b9d79e8f3b21b3036e24e17fd4fbd' => 
    array (
      0 => '/var/www/infra/views/partial_commons/_footer.tpl',
      1 => 1477554115,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5812142c8737d0_50717602 ($_smarty_tpl) {
?>
		</div><!--/.row-->
	</div><!--/.container-->
</div><!--/.page-container-->
</div>
<div id="footer">
	<p>
		<?php echo $_smarty_tpl->tpl_vars['FOOTER']->value;?>

		<?php if ($_smarty_tpl->tpl_vars['ENABLE_FOOTER_SUPPORT_LINK']->value) {?>
				<br />
					<?php echo $_smarty_tpl->tpl_vars['MSG_SUPPORT']->value;?>
<a href="<?php echo $_smarty_tpl->tpl_vars['SUPPORT_ADDRESS']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['MSG_SUPPORT_LINK']->value;?>
</a>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['ENABLE_COPYRIGHT_BOX']->value) {?>
				<br />HP VLAN ADMIN v<?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>

				
		<?php }?>
	</p>
</div>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<?php echo '<script'; ?>
 type="javascript" src="/SPOT/infra/web/css/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>

</body>
</html>
<?php }
}
