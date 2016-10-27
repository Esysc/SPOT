{if $errors}
	<div class="errors">
		<h3>{$MSG_ERRORS_OCCURED} {$MSG_ERRORS_ADVICE}</h3>
			<ul>
				{section name=i loop=$errors}
					<li>{$errors[i]}</li>
				{/section}
			</ul>
	</div>
{/if}
