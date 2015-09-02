/* Remove padding between post title and page builder content */
.single .et_pb_section:nth-of-type(1), 
.single .et_pb_section:nth-of-type(1) .et_pb_row:nth-of-type(1), 
.single .entry-content { padding-top:0; }

/* Remove content area top margin which appears when filtering blog modules from excerpts */
#content-area { margin-top:0 !important; }

/* Fix the unclickable links issue on sidebar with old version of pagebuilder for posts */
.db_pagebuilder_for_posts.et_right_sidebar #sidebar *, 
.db_pagebuilder_for_posts.et_left_sidebar #sidebar * { 
	position: relative; 
}

/* Fix empty specialty section layout issue */
.db_pagebuilder_for_posts.et_full_width_page .et_pb_column { min-height:1px; } 

/* Hide regular post content */
.db_pagebuilder_for_posts.et_full_width_page article > :not(.entry-content) { display: none; }
.db_pagebuilder_for_posts.et_full_width_page article.comment-body > * { display: block !important; }

/* Adjust the padding to match the standard blog post format */
.db_pagebuilder_for_posts.et_full_width_page .entry-content { padding-top: 0px !important; }
.db_pagebuilder_for_posts.et_full_width_page .et_pb_widget_area_right { margin-bottom: 30px !important; margin-left:29px !important; }
.db_pagebuilder_for_posts.et_full_width_page .et_pb_widget_area_left .et_pb_widget { margin-bottom: 30px !important; margin-left: 0px !important; margin-right: 30px !important; }

<?php if ($this->config['theme']['divi2.4+']) { ?>
.single .et_pb_row { width:90% !important; }
@media only screen and (min-width: 981px) {
	.single #sidebar.et_pb_widget_area {
	  width: 100% !important;
	}
}
<?php } ?>