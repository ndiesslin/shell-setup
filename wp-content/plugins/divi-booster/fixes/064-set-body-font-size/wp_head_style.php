<?php list($name, $option) = $this->get_setting_bases(__FILE__); ?>

body { font-size:<?php echo htmlentities(@$option['fontsize']); ?>% !important; }