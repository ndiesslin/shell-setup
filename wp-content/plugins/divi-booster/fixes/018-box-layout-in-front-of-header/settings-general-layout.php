<?php $this->techlink('http://www.wpthemefaqs.com/making-the-divi-box-layout-overlap-the-header/'); ?>
<?php $this->checkbox(__FILE__); ?> Make main content overlap header in box layout:
<table style="margin-left:50px">
<tr><td>Header height:</td><td><?php $this->numberpicker(__FILE__, 'headerheight', 120, 0); ?>px</td></tr>
<tr><td>Header color:</td><td><?php $this->colorpicker(__FILE__, 'headercol'); ?></td></tr>
<tr><td>Page background color:</td><td><?php $this->colorpicker(__FILE__, 'bgcol'); ?></td></tr>
</table>