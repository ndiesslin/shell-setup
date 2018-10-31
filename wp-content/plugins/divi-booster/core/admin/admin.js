jQuery(function($){

	/* Define useful functions */
	$.fn.expand = function() { this.each(function(){ $(this).slideDown(200); }); return this; }
	$.fn.collapse = function() { this.each(function(){ $(this).slideUp(200); }); return this; }
	$.fn.section = function() { return this.next().next(); }
	$.fn.subheadings = function() { return this.nextUntil(".wtf-topheading", "h3"); }
	$.fn.isopen = function() { return this.filter(function(){return ($(this).next().val()==1);}); }
	$.fn.opened = function() { return this.isopen().section(); }
	
	/* Show currently expanded sections */
	$('.wtf-topheading').opened().show().subheadings().show().opened().show();
	
	/* Handle clicks on section headings */
	$(".wtf-section-head").click(function(){
		var section = $(this).section();
		if (section.is(":visible")) { // block is open, so close it
			section.collapse(); // close block
			if ($(this).hasClass('wtf-topheading')) { // hide subsections
				$(this).subheadings().collapse().opened().collapse(); 
			}
		} else {
			section.expand(); // open block
			if ($(this).hasClass('wtf-topheading')) { // show subsections
				$(this).subheadings().expand().opened().expand();
			}
		}
		
		// record state in hidden input
		var hiddenInput = $(this).next();
		var newState = (hiddenInput.val()=='1')?0:1;
		hiddenInput.val(newState); 
		
		// rotate "expanded" icon
		var expandedIcon = $(this).children(':first'); 
		expandedIcon.toggleClass('rotated');
	});
	
	// initialize the colorpickers
	$('.wtf-colorpicker').wpColorPicker();
	
});

// Image picker 
var formfield, thumbnail;

jQuery(document).ready(function($) {
	
	// handle image picker thumbnails
	$('.wtf-imagepicker').change(function() { 
		$(this).next().next('.wtf-imagepicker-thumb').attr('src', $(this).val()); 
	});
	
	$('.wtf-imagepicker-btn').click(function() {
		formfield = $(this).prev('.wtf-imagepicker');
		thumbnail = $(this).next('.wtf-imagepicker-thumb');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		imgurl = $('img',html).attr('src');
		formfield.val(imgurl);
		thumbnail.attr('src', imgurl);
		tb_remove();
	}
	
});
