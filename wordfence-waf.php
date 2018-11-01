<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

// This file was the current value of auto_prepend_file during the Wordfence WAF installation (Thu, 01 Nov 2018 17:15:30 +0000)
if (file_exists('/srv/bindings/83a2cb62528f468e85fc2f306951e64f/includes/prepend.php')) {
	include_once '/srv/bindings/83a2cb62528f468e85fc2f306951e64f/includes/prepend.php';
}
if (file_exists('/srv/bindings/83a2cb62528f468e85fc2f306951e64f/code/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/srv/bindings/83a2cb62528f468e85fc2f306951e64f/code/wp-content/wflogs/');
	include_once '/srv/bindings/83a2cb62528f468e85fc2f306951e64f/code/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>