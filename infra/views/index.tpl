{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_header.tpl"}
{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_menu.tpl"}


<h1 class="breadcrumb">Welcome to Procurve quick management</h1>
<div class="well">
    This GUI allow quick configuration management for the Sysprod Procurve switches.
    {if ! $ALLOW_PORT_TAGGING}
        <p>
            Please note that only untag (access port in cisco terms) is allowed.
        </p>
    {/if}
    The ports are mapped as follow:
    <ul>
        <li class="menu-title">esw03<-->esw24</li>
        <li> shelfA 1 11</li>
        <li>shelfB 2 12</li>
        <li>shelfC 3 13</li>
        <li>shelfD 4 14</li>
        <li>shelfE 5 15</li>
        <li>shelfF 6 16</li>
        <li>shelfG 7 17</li>
    </ul>
    <ul>
        <li class="menu-title">esw02</li>
        <li>shelf2A 8</li>
        <li>shelf2B 9</li>
        <li>shelf2C 10</li>
        <li>shelf2E 12</li>
        <li>shelf2F 13</li>
        <li>shelf2G 14</li>
    </ul>
</div>

{include file = "partial_commons{$SYSTEM_PATH_SEPARATOR}_footer.tpl"}