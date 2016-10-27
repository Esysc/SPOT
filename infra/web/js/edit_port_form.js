$(document).ready(function()
{
	$("#check_all").click(function()				
	{
		var checked_status = this.checked;
		$("input[id=dest_vlans]").each(function()
		{
			this.checked = checked_status;
		});
	});					
});

$(document).ready(function()
{
	$("#check_all_no_untagged").click(function()				
	{
		var checked_status = this.checked;
		$("input[id=dest_vlans_no_untagged]").each(function()
		{
			this.checked = checked_status;
		});
	});					
});