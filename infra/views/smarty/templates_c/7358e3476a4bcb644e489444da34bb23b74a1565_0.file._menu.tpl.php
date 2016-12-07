<?php
/* Smarty version 3.1.28, created on 2016-12-07 16:48:07
  from "/var/www/SPOT/infra/views/partial_commons/_menu.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58482f373fbd63_15743800',
  'file_dependency' => 
  array (
    '7358e3476a4bcb644e489444da34bb23b74a1565' => 
    array (
      0 => '/var/www/SPOT/infra/views/partial_commons/_menu.tpl',
      1 => 1479398808,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58482f373fbd63_15743800 ($_smarty_tpl) {
?>
<div class="container">
    <div class="row row-left">
        <div class="col-md-3 sidebar" id="sidebar">
            <ul class="nav nav-sidebar">

    <!--<li class="menu-home"><a href="index.php"><?php echo $_smarty_tpl->tpl_vars['LBL_1_home']->value;?>
</a></li>-->
                <?php if ($_smarty_tpl->tpl_vars['DISPLAY_DASHBOARD']->value) {?>
                    <li ><a  href="dashboard.php" class="btn btn-default" id="dashboard"><?php echo $_smarty_tpl->tpl_vars['LBL_1_dashboard']->value;?>
 <span><img src="web/images/next.png" style="display:inline"/></span></a></li>

                <?php }?>
                <li class="menu-title"><?php echo $_smarty_tpl->tpl_vars['LBL_1_switches']->value;?>

                    <div id="listitems-pagination" style="display:none" class="pull-right">
                        <button id="listitems-previous" href="#" class="disabled btn btn-success">&laquo; Previous</button> 
                        <button id="listitems-next" href="#" class="btn btn-success">Next &raquo;</button> 
                    </div>
                </li>
                <?php if (count($_smarty_tpl->tpl_vars['allGroups']->value) > 0) {?>
                    <?php $_smarty_tpl->tpl_vars['gid'] = new Smarty_Variable(0, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'gid', 0);?>
                    <?php $_smarty_tpl->tpl_vars['switch_not_member_of_any_group'] = new Smarty_Variable(1, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'switch_not_member_of_any_group', 0);?>
                    <?php
$__section_i_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allGroups']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total != 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                        <li class="switch-group"><a id="groupDetails-link_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
<b class="caret"></b></a></li>
                                <?php if ($_smarty_tpl->tpl_vars['LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS']->value) {?>
                                    <?php $_smarty_tpl->tpl_vars['display'] = new Smarty_Variable("display: none;", null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'display', 0);?>
                                <?php } else { ?>
                                    <?php $_smarty_tpl->tpl_vars['display'] = new Smarty_Variable('', null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'display', 0);?>
                                <?php }?>
                                <?php $_smarty_tpl->tpl_vars['selected_switch_in_group'] = new Smarty_Variable(0, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'selected_switch_in_group', 0);?>
                                <?php
$__section_j_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_j']) ? $_smarty_tpl->tpl_vars['__section_j'] : false;
$__section_j_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()]) ? count($_loop) : max(0, (int) $_loop));
$__section_j_1_total = $__section_j_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_1_total != 0) {
for ($__section_j_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $__section_j_1_iteration <= $__section_j_1_total; $__section_j_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
                                    <?php if ($_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()][(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]->getId() == $_smarty_tpl->tpl_vars['switch_id']->value) {?>
                                        <?php $_smarty_tpl->tpl_vars['selected_switch_in_group'] = new Smarty_Variable(1, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'selected_switch_in_group', 0);?>
                                        <?php $_smarty_tpl->tpl_vars['switch_not_member_of_any_group'] = new Smarty_Variable(0, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'switch_not_member_of_any_group', 0);?>
                                    <?php }?>
                                <?php
}
}
if ($__section_j_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_j'] = $__section_j_1_saved;
}
?>
                        <li>
                            <?php if ($_smarty_tpl->tpl_vars['selected_switch_in_group']->value) {?>
                                <ul class="nav nav-sidebar">
                                <?php } else { ?>
                                    <ul class="nav nav-sidebar" id="groupDetails_<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" style="<?php echo $_smarty_tpl->tpl_vars['display']->value;?>
">
                                    <?php }?>
                                    <?php
$__section_j_2_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_j']) ? $_smarty_tpl->tpl_vars['__section_j'] : false;
$__section_j_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()]) ? count($_loop) : max(0, (int) $_loop));
$__section_j_2_total = $__section_j_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_2_total != 0) {
for ($__section_j_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $__section_j_2_iteration <= $__section_j_2_total; $__section_j_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
                                        <?php if ($_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getColor() != '') {?>
                                            <?php $_smarty_tpl->tpl_vars["color"] = new Smarty_Variable("background:".((string)$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getColor()).";", null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, "color", 0);?>
                                        <?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()][(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]->getId() == $_smarty_tpl->tpl_vars['switch_id']->value) {?>
                                            <li style="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" class="active switch-group-member">
                                            <?php } else { ?>
                                            <li style="<?php echo $_smarty_tpl->tpl_vars['color']->value;?>
" class="switch-group-member">
                                            <?php }?>
                                            <a href="list_vlans.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()][(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]->getId();?>
" <?php if ($_smarty_tpl->tpl_vars['SHOW_SWITCH_IP_MAIN_MENU']->value) {?> title="<?php echo $_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()][(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]->getIP();?>
 <?php }?>"><?php echo $_smarty_tpl->tpl_vars['groups_of_switches']->value[$_smarty_tpl->tpl_vars['allGroups']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId()][(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]->getName();?>
</a></li>
                                        <?php
}
}
if ($__section_j_2_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_j'] = $__section_j_2_saved;
}
?>
                                </ul>
                        </li>
                        <?php $_smarty_tpl->tpl_vars['gid'] = new Smarty_Variable($_smarty_tpl->tpl_vars['gid']->value+1, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'gid', 0);?>
                    <?php
}
}
if ($__section_i_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_0_saved;
}
?>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['gid']->value > 1) {?>
                    <li class="switch-group"><a id="groupDetails-link_<?php echo $_smarty_tpl->tpl_vars['gid']->value+1;?>
"><?php echo $_smarty_tpl->tpl_vars['LBL_1_other_switches']->value;?>
<b class="caret"></b></a></li>
                    <li><ul class="nav nav-sidebar" id="groupDetails_<?php echo $_smarty_tpl->tpl_vars['gid']->value+1;?>
" style="<?php echo $_smarty_tpl->tpl_vars['display']->value;?>
">
                        <?php } else { ?>
                            <li><ul class="nav nav-sidebar switch-carousel listitems" id="listitems">
                                <?php }?>
                                <?php
$__section_i_3_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_i']) ? $_smarty_tpl->tpl_vars['__section_i'] : false;
$__section_i_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['mySwitchs']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_3_total = $__section_i_3_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_3_total != 0) {
for ($__section_i_3_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_3_iteration <= $__section_i_3_total; $__section_i_3_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                                    <?php if ($_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getGroupId() == 0) {?>
                                        <?php if ($_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId() == $_smarty_tpl->tpl_vars['switch_id']->value) {?>
                                            <li class="active switch-group-member">
                                            <?php } else { ?>
                                            <li class="switch-group-member">
                                            <?php }?> 
                                            <a  href="list_vlans.php?switch_id=<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getId();?>
" <?php if ($_smarty_tpl->tpl_vars['SHOW_SWITCH_IP_MAIN_MENU']->value) {?> title="<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getIp();?>
 <?php }?>">
                                                <img src="web/images/procurve.jpg" rel="tooltip" title="<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getIp();?>
 - <?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
" />

                                                <div class="textoverlay"><span class="label label-default"><?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
</span></div></a>  
                                            <button class="buttonoverlay diff label label-primary" ipattr='<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getIp();?>
' id='<?php echo $_smarty_tpl->tpl_vars['mySwitchs']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]->getName();?>
'>Check config</button>
                                            
                                        </li>
                                        
                                        <?php }?>






                        <?php
}
}
if ($__section_i_3_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_i'] = $__section_i_3_saved;
}
?>
                    </ul>
                </li>

                <li ><a href="comparative_view_form.php" class="btn btn-default" id="compare"><?php echo $_smarty_tpl->tpl_vars['LBL_1_compare']->value;?>

                        <span> <img src="web/images/next.png" style="display:inline"/></span></a></li>

                <?php if ($_smarty_tpl->tpl_vars['ENABLE_CONFIGURATION_BACKUP_MANAGEMENT']->value) {?>
                    <li class="menu-title"><?php echo $_smarty_tpl->tpl_vars['LBL_1_backup']->value;?>
</li>
                    <li>
                        <a href="backup_all_configs.php"><?php echo $_smarty_tpl->tpl_vars['LBL_1_backup_exec']->value;?>
<img class="menu-image" src="web/images/warning.png" height="16px" width="16px" title="<?php echo $_smarty_tpl->tpl_vars['LBL_1_backup_warning']->value;?>
" alt="backup_warning"></img></a>
                    </li>
                    <li><a href="browse_config_files.php"><?php echo $_smarty_tpl->tpl_vars['LBL_1_browse_backups']->value;?>
</a></li>
                    <li><a href="show_log.php"><?php echo $_smarty_tpl->tpl_vars['LBL_1_show_log']->value;?>
</a></li>
                    <?php }?>
            </ul>
        </div>
        <?php if ($_smarty_tpl->tpl_vars['LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS']->value) {?>
            <?php if ($_smarty_tpl->tpl_vars['gid']->value > 1) {?>
                <?php echo '<script'; ?>
 type="text/javascript">
                    <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(0, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'i', 0);?>
                    <?php
while ($_smarty_tpl->tpl_vars['i']->value <= $_smarty_tpl->tpl_vars['gid']->value+1) {?>
                        
                                        $("#groupDetails-link_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
").click(function () {
                                            $("#groupDetails_<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
").toggle();
                                        });
                        
                        <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable($_smarty_tpl->tpl_vars['i']->value+1, null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'i', 0);?>
                    <?php }?>

                    
                <?php echo '</script'; ?>
>
            <?php }?>
        <?php }?>

        <!-- main area -->
        <div class="col-md-9">
<?php }
}
