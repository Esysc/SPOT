<div class="container">
    <div class="row row-left">
        <div class="col-md-3 sidebar" id="sidebar">
            <ul class="nav nav-sidebar">

    <!--<li class="menu-home"><a href="index.php">{$LBL_1_home}</a></li>-->
                {if $DISPLAY_DASHBOARD}
                    <li ><a  href="dashboard.php" class="btn btn-default" id="dashboard">{$LBL_1_dashboard} <span><img src="web/images/next.png" style="display:inline"/></span></a></li>

                {/if}
                <li class="menu-title">{$LBL_1_switches}
                    <div id="listitems-pagination" style="display:none" class="pull-right">
                        <button id="listitems-previous" href="#" class="disabled btn btn-success">&laquo; Previous</button> 
                        <button id="listitems-next" href="#" class="btn btn-success">Next &raquo;</button> 
                    </div>
                </li>
                {if count($allGroups) > 0}
                    {assign var=gid value=0}
                    {assign var=switch_not_member_of_any_group value=1}
                    {section name=i loop=$allGroups}
                        <li class="switch-group"><a id="groupDetails-link_{$gid}">{$allGroups[i]->getName()}<b class="caret"></b></a></li>
                                {if $LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS}
                                    {assign var=display value="display: none;"}
                                {else}
                                    {assign var=display value=""}
                                {/if}
                                {assign var=selected_switch_in_group value=0}
                                {section name=j loop=$groups_of_switches[$allGroups[i]->getId()]}
                                    {if $groups_of_switches[$allGroups[i]->getId()][j]->getId() == $switch_id}
                                        {assign var=selected_switch_in_group value=1}
                                        {assign var=switch_not_member_of_any_group value=0}
                                    {/if}
                                {/section}
                        <li>
                            {if $selected_switch_in_group}
                                <ul class="nav nav-sidebar">
                                {else}
                                    <ul class="nav nav-sidebar" id="groupDetails_{$gid}" style="{$display}">
                                    {/if}
                                    {section name=j loop=$groups_of_switches[$allGroups[i]->getId()]}
                                        {if $allGroups[i]->getColor() != ""}
                                            {assign var="color" value="background:{$allGroups[i]->getColor()};"}
                                        {/if}
                                        {if $groups_of_switches[$allGroups[i]->getId()][j]->getId() == $switch_id}
                                            <li style="{$color}" class="active switch-group-member">
                                            {else}
                                            <li style="{$color}" class="switch-group-member">
                                            {/if}
                                            <a href="list_vlans.php?switch_id={$groups_of_switches[$allGroups[i]->getId()][j]->getId()}" {if $SHOW_SWITCH_IP_MAIN_MENU} title="{$groups_of_switches[$allGroups[i]->getId()][j]->getIP()} {/if}">{$groups_of_switches[$allGroups[i]->getId()][j]->getName()}</a></li>
                                        {/section}
                                </ul>
                        </li>
                        {assign var=gid value=$gid+1}
                    {/section}
                {/if}
                {if $gid gt 1}
                    <li class="switch-group"><a id="groupDetails-link_{$gid+1}">{$LBL_1_other_switches}<b class="caret"></b></a></li>
                    <li><ul class="nav nav-sidebar" id="groupDetails_{$gid+1}" style="{$display}">
                        {else}
                            <li><ul class="nav nav-sidebar switch-carousel listitems" id="listitems">
                                {/if}
                                {section name=i loop=$mySwitchs}
                                    {if $mySwitchs[i]->getGroupId() == 0}
                                        {if $mySwitchs[i]->getId() == $switch_id}
                                            <li class="active switch-group-member">
                                            {else}
                                            <li class="switch-group-member">
                                            {/if} 
                                            <a  href="list_vlans.php?switch_id={$mySwitchs[i]->getId()}" {if $SHOW_SWITCH_IP_MAIN_MENU} title="{$mySwitchs[i]->getIp()} {/if}">
                                                <img src="web/images/procurve.jpg" rel="tooltip" title="{$mySwitchs[i]->getIp()} - {$mySwitchs[i]->getName()}" />

                                                <div class="textoverlay"><span class="label label-default">{$mySwitchs[i]->getName()}</span></div></a>
                                            <p class="diff" ipattr='{$mySwitchs[i]->getIp()}'></p>
                                           
                                        </li>

                                    {/if}






                                {/section}
                            </ul>
                        </li>

                        <li ><a href="comparative_view_form.php" class="btn btn-default" id="compare">{$LBL_1_compare}
                                <span> <img src="web/images/next.png" style="display:inline"/></span></a></li>

                        {if $ENABLE_CONFIGURATION_BACKUP_MANAGEMENT}
                            <li class="menu-title">{$LBL_1_backup}</li>
                            <li>
                                <a href="backup_all_configs.php">{$LBL_1_backup_exec}<img class="menu-image" src="web/images/warning.png" height="16px" width="16px" title="{$LBL_1_backup_warning}" alt="backup_warning"></img></a>
                            </li>
                            <li><a href="browse_config_files.php">{$LBL_1_browse_backups}</a></li>
                            <li><a href="show_log.php">{$LBL_1_show_log}</a></li>
                            {/if}
                    </ul>
                    </div>
                    {if $LEFT_MENU_HIDE_SWITCHES_GROUP_MEMBERS}
                        {if $gid gt 1}
                            <script type="text/javascript">
                                {assign var=i value=0}
                                {while $i <= $gid+1}
                                    {literal}
                                        $("#groupDetails-link_{/literal}{$i}{literal}").click(function () {
                                            $("#groupDetails_{/literal}{$i}{literal}").toggle();
                                        });
                                    {/literal}
                                    {assign var=i value=$i+1}
                                {/while}{literal}
                                {/literal}
                            </script>
                        {/if}
                    {/if}

                    <!-- main area -->
                    <div class="col-md-9">
