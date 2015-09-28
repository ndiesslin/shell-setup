<?php $this->techlink('http://www.wpthemefaqs.com/change-the-height-of-the-divi-header/'); ?>
<?php $this->checkbox(__FILE__); ?> Header minimum height:
<table style="margin-left:50px">
<tr><td>Normal:</td><td><?php $this->numberpicker(__FILE__, 'normal', 43, 0); ?>px</td></tr>
<tr><td>Shrunk:</td><td><?php $this->numberpicker(__FILE__, 'shrunk', 30, 0); ?>px</td></tr>
</table>