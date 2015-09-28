/* Change header to float correctly wherever it is in the page */
@media only screen and ( min-width:981px ) {
	#main-header { position:relative !important; top:0px !important; } /* inline */
	#main-header.et-fixed-header { position:fixed !important; margin-bottom:0px; top:0px !important; } /* floating */
	body.admin-bar #main-header.et-fixed-header { top:32px !important; } /* adjust for WP admin bar */
	#page-container { overflow:hidden; } /* prevent sub-menus from breaking scrolling */
	
	/* Handle top header */
	#top-header { position:relative !important; top:0px !important; } /* inline header */
}

/* Style the image for full screen layouts */
@media only screen and ( min-width:981px ) {

	#wtfdivi004-page-start-img { margin-bottom:0px; width:100%; }
	
	/* Override Divi JS padding adjustment */
	div#page-container[style] { padding-top:0 !important; } 
	
	/* Remove gap between heading and menu caused by line height */
	body { line-height:0 !important; }
	body * { line-height:1.7em }
}

/* Style the image for box layout */
@media only screen and (min-width: 1200px) {
	.et_boxed_layout #wtfdivi004-page-start-img {
		width:1200px;
		-moz-box-shadow: 0 0 10px 0 rgba(0,0,0,0.2);
		-webkit-box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
		box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
		display:block !important;
		margin-left:auto;
		margin-right:auto;
	}
}

/* Hide the image on smaller screens */
@media only screen and ( max-width:980px ) {
	#wtfdivi004-page-start-img { display:none !important; }
}