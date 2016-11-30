<?php
/* Smarty version 3.1.28, created on 2016-11-29 18:44:20
  from "/var/www/SPOT/infra/views/partial_commons/_footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_583dbe742526b1_23039856',
  'file_dependency' => 
  array (
    '4255755cbe59c11fbf60e171620bc70f686de8c0' => 
    array (
      0 => '/var/www/SPOT/infra/views/partial_commons/_footer.tpl',
      1 => 1478279878,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583dbe742526b1_23039856 ($_smarty_tpl) {
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
        <center>  <div class="diffres well-large"></div> </center>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<?php echo '<script'; ?>
 type="javascript" src="/SPOT/infra/web/css/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>

</body>
</html>
<?php }
}
