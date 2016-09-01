<?php //if ( is_active_sidebar() ) : ?>
    <?php dynamic_sidebar();?>
<?php //endif; ?>

<?php

	$post_id = $post->ID;

  /*$taxonomy = 'blogs';//used for page slug
  //$page_slug = the_slug();
  $tax_term = $wp_query->query_vars[$taxonomy];
  $current_term = get_term_by('slug', $tax_term, $taxonomy);
  //print_r($current_term);
  $page_slug = $current_term->slug;*/

	/*$year = $_GET['y'] ? $_GET['y'] : date("Y");*/

	$post_type = 'blog';
	//$post_type = get_post_type(get_the_ID());


	//$taxonomy = 'news-blogs';//for categories
	$taxonomy = $post_type . 's';//just s added in post-type slug

  $args = array(
		'order'=> 'DESC', 
		//‘orderby’ => ‘title’,//for alphabetically order, rand for random
		'numberposts'=> '5', //-1 for all, 1 for listing only one, 2 for listing 2
		'post_type' => $post_type,
		/*'date_query' => array(
								      array(
								      'year'      => $year,
								      'compare'   => '>=',
								    ),
								    array(
								      'year'      => $year+1,
								      'compare'   => '<',
								        )
								    ),*/
	);
  $postslist = get_posts( $args );
  if(count($postslist) != 0)
  {
  	echo '<h2>Recent Posts</h2>';
  	
    echo '<ul>';
    ?>
    <?php foreach ($postslist as $post) :  setup_postdata($post); ?>
		
			<li><a href="<?php echo the_permalink();?>">
			<?php the_title();?>
			</a></li>

    <?php endforeach;?>
    <?php
    echo '</ul>';
  }
?>

<?php
//just for dates
			echo '<h2>Archives</h2>';
			$args_for_date = array(
          'post_status' => 'publish',
          'posts_per_page'=> '-1', 
          'post_type' => $post_type,
      );
      $month_years = getMonthYearSplitsOfPosts($args_for_date);

      //$link = get_permalink();
      $link = site_url() . '/' . $post_type . '/';
      
      //print dates
      echo '<div class="clear"></div>';

      //listing
      /*echo '<ul class="">';  
      foreach ( $month_years as $month_year ) {
        echo '<li ';               
          if($month_yearSel==$month_year[0]['publish'] ) echo ' class="current_page_item" ';
        echo '>';

      echo  '<a href="'. $link. '?y=' . $month_year[0]['publish'] . '">'.  $month_year[0]['publish'] .'</a></li>';
  		}
   		echo '</ul>';*/

      //dropdown
      /*echo '<select onchange="document.location.href=this.options[this.selectedIndex].value;">
              <option value="">Select Month</option>';

      foreach ( $month_years as $month_year ):
        echo '<option value="'.$link. '?y=' . $month_year[0]['publish'].'">';
        echo $month_year[0]['publish'];
        echo '</option>';
      endforeach;
      echo '</select>';*/



        
        //listing all the months that works


        echo '<div class="customSel">
          <label>
          <select onchange="document.location.href=this.options[this.selectedIndex].value;">';
        echo '<option>Select Month</option>';
        echo cpt_wp_get_archives('blog');
        echo '</select></label></div>';
        
?>

<?php
	echo '<h2>Categories</h2>';

	$args = array(
  'orderby'           => 'name', //alphabetically
//‘orderby’ => ‘id’, //for latest first
  'order'             => 'ASC',
  'hide_empty'        => true,//hide empty or not  
  //true for hiding empty - because clicking on empty makes error
		); 

  $tax_terms = get_terms($taxonomy, $args);
  //print_r($tax_terms);    
    
    /*echo '<ul>';    
    foreach ($tax_terms as $tax_term): 
      $term_name = (strtolower($tax_term->name)); 

	    $tax_link = site_url() . '/blog/' . $taxonomy . '/' . $tax_term->slug . '/';// /blog appearing
		?>
		<li><a href="<?php echo $tax_link;?>" class="<?php if($page_slug == $term_name) echo 'active';?>"><?php echo $tax_term->name;?></a></li>
    <?php endforeach;    
    echo '</ul>';*/

    //dropdown
    echo '<div class="customSel">
          <label><select onchange="document.location.href=this.options[this.selectedIndex].value;">
            <option value="">Select Categories</option>';

    foreach ($tax_terms as $tax_term): 
      $tax_slug = $tax_term->slug;
      $tax_link = site_url() . '/' . $taxonomy . '/' . $tax_slug . '/';
      echo '<option value="'.$tax_link.'"';
      //if($page_slug == $tax_slug) echo 'selected="selected"'; //can not say it can be done in case of date archive
      echo '>';
      //print_r($page_slug . $tax_slug);
      echo $tax_term->name;
      echo '</option>';
    endforeach;
    echo '</select></label></div>';

?>