<div class="container">
    <center>
        <div id="listdash-pagination" style="display:none">
            <button id="listdash-previous" href="#" class="disabled btn btn-success">&laquo; Previous</button> 
            <button id="listdash-next" href="#" class="btn btn-success">Next &raquo;</button> 
        </div>
    </center>
    <ul id="listdash">
        {section name=i loop=$mySwitchs}
            {if $mySwitchs[i]->hasToBeDiplayedInDashboard()== true}
                <li>
                    <div class="switch_table_container_small">
                        <table class="table table-striped small">
                            <thead><tr><th><h3><span class="label label-default">{$mySwitchs[i]->getName()}</span></h3></th></tr></thead>
                                        {section name=j loop=$vlans[i]}
                                <tr>
                                    <td>{$vlans[i][j]->toString_small()}</td>
                                    <td>
                                        {section name=k loop=$ports[i][$vlans[i][j]->getId()]}
                                            {$ports[i][$vlans[i][j]->getId()][k]}
                                        {/section}
                                    </td>
                                </tr>
                            {/section}
                        </table>
                    </div>
                </li>
            {/if}
        {/section}
    </ul>
</div>
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

