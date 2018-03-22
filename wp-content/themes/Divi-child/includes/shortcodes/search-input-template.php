<form role="search" method="get" action="/" class="wpcf7-form">
  <input type="search" placeholder="Enter a Keyword, topic, name, etc..." value="" name="s" title="Search for:" class="wpcf7-form-control form-input wpcf7-text">
  <select name="post-type">
    <option>Search All</option>
    <?php
      $args = array(
        'public'              => true,
        'exclude_from_search' => false
      );
      $output = 'objects';
      $post_types = get_post_types( $args, $output );
      foreach ( $post_types  as $post_type ) {
        echo '<option value="' . $post_type->name . '" name="post-type">' . $post_type->label . '</option>';
      }
    ?>
  </select>
  <input type="submit" value="Search" class="wpcf7-form-control wpcf7-submit">
</form>
