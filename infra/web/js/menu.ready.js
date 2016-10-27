$(document).ready(function() {
	var groupDetails = false;
	$(".groupDetails-link").hover(function(){
		if (hideGroupDetails) clearTimeout(hideGroupDetails);
		$("#groupDetails_1").slideDown("slow");
	}, function() {
		hideGroupDetails = setTimeout(function() {$("#groupDetails_1").slideUp("normal");}, 250);
	});
	$("#groupDetails_1").hover(function(){
		if (hideGroupDetails) clearTimeout(hideGroupDetails);
	}, function() {
	hideMailing = setTimeout(function() {$("#groupDetails_1").slideUp("normal");}, 250);
	});
});
