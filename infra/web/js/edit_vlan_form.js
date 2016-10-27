$(document).ready(function()
{
	$("#check_all_tagged").click(function()				
	{
		var checked_status = this.checked;
		$("input[id=dest_ports_tagged]").each(function()
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
		$("input[id=dest_ports_no_untagged]").each(function()
		{
			this.checked = checked_status;
		});
	});					
});

$(document).ready(function()
{
	$("#check_all_untagged").click(function()				
	{
		var checked_status = this.checked;
		$("input[id=dest_ports_untagged]").each(function()
		{
			this.checked = checked_status;
		});
	});					
});