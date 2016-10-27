{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}

<p style="font-size:1.5em;">Port <b>{$port_id}</b> -- <b>{$mySwitch->getName()}</b> ({$LBL_10_actual_vlan} : {$source_vlan})</p>
<div class="infoBox">
		<u>{$LBL_10_port_details} : </u><br />
		<ul>
			<li><strong>Nom : </strong>{$port_name}</li>
			<li><strong>Alias : </strong>{$port_alias}</li>
			<li><strong>Description : </strong>{$port_description}</li>
			<li><strong>{$LBL_0_speed} : </strong>{$port_speed} Mbits/sec.</li>
		</ul>
</div>

<div class="untag_port">
    <p><u><b>{$LBL_0_untag_in_vlan}</b></u> : </p>

    <form class="formulaire" action="untag_port.php" method="post" id="edit_port">
            <div>
                    <input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
                    <input type="hidden" id="source_vlan" name="source_vlan" value="{$source_vlan}"/>
                    <input type="hidden" id="port_id" name="port_id" value="{$port_id}"/>
            </div>

            <select class="form-control" id="dest_vlan" name="dest_vlan">
                    {section name=i loop=$vlans_list}
                            <option label="{$vlans_list[i]->getName()}" value="{$vlans_list[i]->getId()}" {if ($source_vlan == $vlans_list[i]->getId())} selected="selected" {/if}>{$vlans_list[i]->getName()}</option>
                    {/section}
            </select>

            <br /><br />

            <input type="submit" class="btn btn-sm btn-info" value="{$LBL_0_untag_in_selected_vlan}"/>
    </form>
</div>
            	                        
                        
{if $ALLOW_PORT_TAGGING}
	
	<div class="tag_port">
	
		<p><u><b>{$LBL_0_tag_in_vlans}</b></u> : </p>
		
		<script type="text/javascript" src="web/js/edit_port_form.js"></script>
		
		<form action="tag_port_in_selected_vlans.php" method="post" id="edit_port">
			<div>
				<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
				<input type="hidden" id="source_vlan" name="source_vlan" value="{$source_vlan}"/>
				<input type="hidden" id="port_id" name="port_id" value="{$port_id}"/>

				<ul>
					{section name=i loop=$vlans_where_the_port_is_not_tagged}
						<li><input type="checkbox" name="dest_vlans[]" id="dest_vlans" value="{$vlans_where_the_port_is_not_tagged[i]->getId()}"/> {if ($source_vlan == $vlans_where_the_port_is_not_tagged[i]->getId())}<strong>{/if} {$vlans_where_the_port_is_not_tagged[i]->getName()} {if ($source_vlan == $vlans_where_the_port_is_not_tagged[i]->getId())}</strong>{/if}</li>
					{/section}
					
					<li>---</li>
					<li><input type="checkbox" id="check_all"/> {$LBL_0_select_all}</li>
                                        
				</ul>
				
				<br />
				<input type="submit" class="btn btn-sm btn-info" value="{$LBL_0_tag_in_vlans}"/>
			</div>
		</form>
		
	</div>
		
	{if !empty($no_untaggable_vlans)}
	
		<div class="no_untag_port">

			<p><u><b>{$LBL_0_no_untag_in_vlans}</b></u> : </p>
			
			<form action="set_to_no_untagged_port_in_selected_vlans.php" method="post" id="edit_port_set_to_no_untagged">
				<div>
					<input type="hidden" id="switch_id" name="switch_id" value="{$mySwitch->getId()}"/>
					<input type="hidden" id="source_vlan" name="source_vlan" value="{$source_vlan}"/>
					<input type="hidden" id="port_id" name="port_id" value="{$port_id}"/>

					<ul>
						{section name=i loop=$no_untaggable_vlans}
								<li><input type="checkbox" id="dest_vlans_no_untagged" name="dest_vlans[]" value="{$no_untaggable_vlans[i]->getId()}" {if (!$source_vlan == $no_untaggable_vlans[i]->getId())} checked="checked" {/if}/> {$no_untaggable_vlans[i]->getName()}</li>
						{/section}
						
						<li>---</li>
						<li><input type="checkbox" id="check_all_no_untagged"/> {$LBL_0_select_all}</li>
					</ul>
					
					<br />
					
					<input type="submit" class="btn btn-sm btn-info" value="{$LBL_0_no_untag_in_vlans}"/>
				</div>
			</form>
		</div>
	{/if}
{/if}

<div class="back-link"><p>
	<a href="list_vlans.php?switch_id={$mySwitch->getId()}">{$LBL_0_back}</a>
</p></div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}