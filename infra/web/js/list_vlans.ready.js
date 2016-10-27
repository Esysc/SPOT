$(document).ready(function() {
	var hideSwitchDetails = false;
	$(".switchDetails-link").hover(function(){
		if (hideSwitchDetails) clearTimeout(hideSwitchDetails);
		$("#toolTipsSwitchDetails").slideDown("slow");
	}, function() {
		hideSwitchDetails = setTimeout(function() {$("#toolTipsSwitchDetails").slideUp("normal");}, 250);
	});
	$("#toolTipsSwitchDetails").hover(function(){
		if (hideSwitchDetails) clearTimeout(hideSwitchDetails);
	}, function() {
	hideMailing = setTimeout(function() {$("#toolTipsSwitchDetails").slideUp("normal");}, 250);
	});
});

