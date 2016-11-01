
{if $HIDE_DETAILS_BOX && !$DISABLE_DETAILS_BOX}
    <script type="text/javascript" src="web/js/list_vlans.ready.js"></script>
{/if}

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_errors.tpl"}

{$mySwitch}

<form action="delete_selected_vlans_form.php" method="post" id="delete_selected_vlans">

    {if $ALLOW_VLAN_CREATION}
        <a href="create_vlan_form.php?switch_id={$mySwitch->getId()}">{$LBL_14_add_vlan}</a>
    {/if}

    <p>{$LBL_14_select_a_port}</p>

    <div class="switch_table_container">
        {include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_list_vlans-switch_table_container.tpl"}
        <br />
        {if $ALLOW_VLAN_DELETION}
            <div>
                <input type="submit" class="btn btn-sm btn-warning" value="{$LBL_14_delete_selected_vlans}"/>
            </div>
        {/if}
    </div>

</form>

<button class="btn btn-primary" id="default" data-switch="{$mySwitch->getIp()}">Reload Default</button>


<div id="results" class="well" style="display:none"></div>

<div class="modal fade" id="list_vlans_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><center>Port modification</center></h4>
            </div>
            <div class="modal-body" id="list_vlans">
            </div>
            <div class="modal-footer">
                <div id="message" class="bg-info"></div>
                <button id="getMacTable" class="btn btn-warning  pull-left">Get Mac addresses..</button> 	
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



    <div id="rack">
        
            <img src="{$myRack}" />

      
    </div>


