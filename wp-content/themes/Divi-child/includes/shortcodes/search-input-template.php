<form role="search" method="get" action="/" class="display-flex flex-wrap-wrap">
  <div class="search-module display-flex flex-wrap-wrap">
    <input type="search" placeholder="Enter a Keyword, topic, name, etc..." value="" name="s" title="Search for:" class="search-module__input">
    <label class="search-module__select-label margin-bottom--20">
      <select name="post-type" class="search-module__select">
        <option value>Search All</option>
        <?php
          $args = array(
            'public'              => true,
            'exclude_from_search' => false
          );
          $output = 'objects';
          $post_types = get_post_types( $args, $output );
          foreach ( $post_types as $post_type ) {
            echo $post_type->name;
            $exclude = array( 'project', 'homepage-slider', 'attachment' ); // Excludes projects, homepage sliders, and attachments

            if( TRUE === in_array( $post_type->name, $exclude ) )
              continue;

            echo '<option value="' . $post_type->name . '" name="post-type">' . $post_type->label . '</option>';
          }
        ?>
      </select>
      <i class="fa fa-chevron-down search-module__select-arrow"></i>
    </label>
  </div>
  <button type="submit" class="search-button">
    <i class="fa fa-search"></i> Search
  </button>
</form>
