<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

.mobile_nav::before { 
	content:'<?php echo htmlentities(addslashes(@$option['menubuttontext'])); ?>'; vertical-align:top; line-height:2.2em; 
}
.et-fixed-header .mobile_nav::before { line-height:1.3em; }
.mobile_menu_bar { display:inline-block !important; }