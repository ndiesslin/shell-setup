<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-menu li { padding-right: <?php echo intval(@$option['menuitempadding']); ?>px !important; }
