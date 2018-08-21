<?php
// Shortcode for search input
function getSearch() {
  ob_start(); // Turn on output buffering
  include('search-input-template.php'); // Include 
  return ob_get_clean();  // Return output buffer
}
add_shortcode("getSearch", "getSearch");
