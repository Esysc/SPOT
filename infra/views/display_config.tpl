


{if $ENABLE_SWITH_CONFIGURATION_VIEW == 1}
        {if $ENABLE_SWITH_CONFIGURATION_EDITION == 1}
            <p><a href="edit_config_form.php?switch_id={$mySwitch->getId()}">{$LBL_0_modify}</a></p>
        {/if}
	{$conf}
{/if}


