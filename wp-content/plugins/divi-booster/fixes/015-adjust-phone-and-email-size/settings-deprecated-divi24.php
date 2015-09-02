<?php $this->techlink('http://www.wpthemefaqs.com/changing-the-divi-header-phone-and-email-font-sizes/'); ?>
<?php $this->checkbox(__FILE__); ?> Contact info icon and text style:
<table style="margin-left:50px">
<tr><td>Text size:</td><td><?php $this->numberpicker(__FILE__, 'fontsize', 100, 0); ?>%</td></tr>
<tr><td>Text / icon color:</td><td><?php $this->colorpicker(__FILE__, 'col'); ?></td></tr>
<tr><td>Hover color:</td><td><?php $this->colorpicker(__FILE__, 'hovercol'); ?></td></tr>
<tr><td>Background color:</td><td><?php $this->colorpicker(__FILE__, 'bgcol'); ?></td></tr>
</table>