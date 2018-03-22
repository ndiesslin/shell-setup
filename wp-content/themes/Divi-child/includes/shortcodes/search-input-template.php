<form role="search" method="get" action="/" class="wpcf7-form">
  <input type="search" placeholder="Enter a Keyword, topic, name, etc..." value="" name="s" title="Search for:" class="wpcf7-form-control form-input wpcf7-text">
  <select>
    <?php
      foreach ( get_post_types( '', 'names' ) as $post_type ) {
        echo '<option value="' . $post_type . '">' . $post_type . '</option>';
      }
    ?>
  </select>
  <input type="submit" value="Search" class="wpcf7-form-control wpcf7-submit">
</form>
