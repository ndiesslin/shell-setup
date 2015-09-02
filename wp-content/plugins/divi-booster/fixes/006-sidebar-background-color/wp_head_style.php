<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#sidebar { background-color: <?php echo htmlentities(@$option['bgcol']); ?>; }

/* Increase the container width by 60px to provide space for outer margins */
@media only screen and (min-width: 1100px) {
	.et_right_sidebar #main-content .container, 
	.et_left_sidebar #main-content .container { 
        width:1140px;
    }
}
@media only screen and (min-width: 981px) and (max-width:1099px) {	
	.et_right_sidebar #main-content .container, 
	.et_left_sidebar #main-content .container { 
        width:1020px;
    }
}

/* Add outer padding */
@media only screen and (min-width: 981px) {
	
	/* Add 30px margin to outside edge of sidebar */
    .et_right_sidebar #sidebar .et_pb_widget { margin-right:30px }
	.et_left_sidebar #sidebar .et_pb_widget { margin-left:30px }    
	
	/* Add 30px margin to outside edge of main area (for balance) */
	.et_right_sidebar #left-area { margin-left:30px }
	.et_left_sidebar #left-area { margin-right:30px }
}

/* Declare the sidebar width */
@media only screen and ( min-width: 1100px ) {
	.et_left_sidebar #sidebar,
	.et_right_sidebar #sidebar {
		width:254px;
	}
}
@media only screen and (min-width: 981px) and (max-width:1099px) {
	.et_left_sidebar #sidebar,
	.et_right_sidebar #sidebar {
		width:224px;
	}
}

/* Fix the inside padding / margin on medium screens */
@media only screen and (min-width: 981px) and (max-width:1099px) {
	/* Reduce inside margin on main content */
	.et_right_sidebar #left-area { 
        margin-right:30px 
    }
	.et_left_sidebar #left-area { 
        margin-left:30px 
    }
	
	/* Add as inside padding to sidebar instead */
	.et_right_sidebar #sidebar { 
        padding-left:30px 
    }
	.et_left_sidebar #sidebar { 
        padding-right:30px 
    }
}

/* Fix the dividing line position */
@media only screen and (min-width: 1100px) {
	.et_right_sidebar #main-content .container:before { 
        right:254px !important; 
    }
    .et_left_sidebar #main-content .container:before { 
        left:254px !important; 
    }
}
@media only screen and (min-width: 981px) and (max-width: 1099px) {
	.et_right_sidebar #main-content .container:before { 
        right:224px !important; 
    }
    .et_left_sidebar #main-content .container:before { 
        left:224px !important; 
    }
}

/* Extend sidebar vertically */
@media only screen and (min-width: 981px) { 
    .et_right_sidebar #sidebar, 
	.et_left_sidebar #sidebar { 
        margin-top:-100px; 
        padding-top:100px; 
		padding-bottom:10px;
    }
}

/* Add padding on small screens (mobiles, etc) */
@media only screen and (max-width: 981px) { 
	#sidebar { padding:30px !important;margin:0 0 30px 0 !important; }
}
