<?php $this->techlink('http://www.wpthemefaqs.com/add-semi-transparent-background-to-divi-slider-text/'); ?> 
<?php $this->checkbox(__FILE__); ?> Add background to slider text:
<table style="margin-left:50px">
<tr><td>Background color:</td><td><?php $this->colorpicker(__FILE__, 'bgcol', '#000'); ?></td></tr>
<tr><td>Opacity:</td><td><?php $this->numberpicker(__FILE__, 'opacity', 50); ?>%</td></tr>
</table>