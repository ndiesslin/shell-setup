jQuery(function($){
	$(window).load(function(){
		$('#main-content').waypoint('destroy');
		$('#main-header').removeClass('et-fixed-header');
	});
});
