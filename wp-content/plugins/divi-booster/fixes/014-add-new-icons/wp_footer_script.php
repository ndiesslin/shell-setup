jQuery(function($){
<?php
list($name, $option) = $this->get_setting_bases(__FILE__);

if (!isset($option['urlmax'])) { $option['urlmax']=0; }
for($i=0; $i<=$option['urlmax']; $i++) {
	if (!empty($option["url$i"])) { ?>
$('.et-pb-icon').filter(function(){ return $(this).text() == 'wtfdivi014-url<?php echo $i; ?>'; }).html('<img src="<?php echo htmlentities(@$option["url$i"]); ?>"/>');
<?php
	} else { ?>
$('.et-pb-icon').filter(function(){ return $(this).text() == 'wtfdivi014-url<?php echo $i; ?>'; }).hide();
<?php
	}
} 
?>
});