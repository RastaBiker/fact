$(document).ready(function() {
	$("#add").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});
	$(".delete").click(function() {
		var add_delete;
		$(this).parent().submit();
	})

});