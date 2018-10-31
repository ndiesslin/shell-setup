<?php
add_action('admin_head', 'wtfdivi094_admin_css');

function wtfdivi094_admin_css() {
  echo '<style>.et_pb_modal_settings_container{top:32px;bottom:0;width:100%;left:0;margin:0;}.et-pb-options-tabs-links{width:100%;}</style>';
}
?>