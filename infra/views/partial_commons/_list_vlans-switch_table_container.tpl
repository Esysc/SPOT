


<div>
    <input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
</div>

{section name=i loop=$vlans}
    <ul class="vlan">
        <li>
            <fieldset class="form-group">
                {if $ALLOW_VLAN_DELETION}
                    <div class="checkbox">
                        <label><input type="checkbox" name="selected_vlans[]" value="{$vlans[i]->getId()}"/>{$vlans[i]}</label>
                    </div>
                {else}
                    <p>{$vlans[i]}</p>
                {/if}
                <div class="vlan-list-ports"> 
                    {if $ports[$vlans[i]->getId()]|@count gt 0}
                        {section name=j loop=$ports[$vlans[i]->getId()]}
                            {$ports[$vlans[i]->getId()][j] }

                        {/section}
                    {/if}
                </div>
            </fieldset>
        </li>

    </ul>

{/section}

