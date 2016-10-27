$(document).ready(function()
{
	$("#check_all").click(function()				
	{
		var checked_status = this.checked;
		$("input[id=dest_switches]").each(function()
		{
			this.checked = checked_status;
		});
	});					
});