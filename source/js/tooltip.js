$(document).ready(function(){
	$("a, img, table, div, font").tooltip({
		track: true,
		delay: 30,
		showBody: "::",
		opacity: 0.85,
		showURL: false
	});
});
