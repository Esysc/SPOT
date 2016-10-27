{section name=s loop=$selected_switchs}
	<div class="comparative-view-column">
		<table class="table" style="width:98%">
			<thead><tr><th>{$selected_switchs[s]->getName()}</th><th></th></tr></thead>
			{section name=i loop=$vlans[s]}
				<tr><td>{$vlans[s][i]}</td>
					<td>
						{section name=j loop=$ports[s][$vlans[s][i]->getId()]}
								{$ports[s][$vlans[s][i]->getId()][j]}
						{/section}
					</td>
				</tr>
			{/section}
		</table>
	</div>
{/section}