jQuery(function($){
	
	var wtfdivi054_featured = $('body.single article.has-post-thumbnail img:nth-of-type(1)');
	var wtfdivi054_contentarea = $('#content-area');
		
	if (wtfdivi054_featured.length) { 
		wtfdivi054_adjust_margin();
		$(window).resize(function(){ wtfdivi054_adjust_margin(); });	
	}
	
	function wtfdivi054_adjust_margin() { 
		wtfdivi054_contentarea.css('margin-top', wtfdivi054_featured.height()); 
	}
});
