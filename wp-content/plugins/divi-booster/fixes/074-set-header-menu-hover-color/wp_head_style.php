<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-menu a:hover { color: <?php echo htmlentities(@$option['col']); ?> !important; }
