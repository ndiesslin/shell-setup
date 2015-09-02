<?php $this->techlink('https://www.wpthemefaqs.com/adding-custom-icons-to-divi/'); ?>
<?php $this->checkbox(__FILE__); ?> Add custom icons (recommended size 96x96px):<br>
<?php 
list($name, $option) = $this->get_setting_bases(__FILE__);
if (!isset($option['urlmax'])) { $option['urlmax']=0; }

for($i=0; $i<=$option['urlmax']; $i++) {
	if (!empty($option["url$i"])) {
		$this->imagepicker(__FILE__, "url$i"); 
		echo '<a href="javascript:;" onclick="jQuery(this).prev().find(\'input[type=text]\').val(\'\');jQuery(this).prev().hide();jQuery(this).hide();jQuery(this).next().hide();" style="text-decoration:none" title="Delete">X</a><br>';
	}
}
$option["urlmax"]+=(empty($option["url".$option["urlmax"]])?0:1);
$this->imagepicker(__FILE__, "url".$option['urlmax']); 
?> 
<input type="hidden" name="<?php echo $name; ?>[urlmax]" value="<?php echo $option["urlmax"]; ?>"/>  