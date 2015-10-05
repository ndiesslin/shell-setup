<?php
	$post_id = $post->ID;

	/*$year = $_GET['y'] ? $_GET['y'] : date("Y");*/

  $args = array(
		'order'=> 'DESC', 
		//‘orderby’ => ‘title’,//for alphabetically order, rand for random
		'numberposts'=> '5', //-1 for all, 1 for listing only one, 2 for listing 2
		'post_type' => 'blog',
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
          'post_type' => 'blog',
      );
      $month_years = getMonthYearSplitsOfPosts($args_for_date);

      //$link = get_permalink();
      $link = site_url() . '/blog/';
      
      //print dates
      echo '<div class="clear"></div>';
      echo '<ul class="">';  
      foreach ( $month_years as $month_year ) {
        echo '<li ';               
          if($month_yearSel==$month_year[0]['publish'] ) echo ' class="current_page_item" ';
        echo '>';

      echo  '<a href="'. $link. '?y=' . $month_year[0]['publish'] . '">'.  $month_year[0]['publish'] .'</a></li>';
  		}
   		echo '</ul>';
?>

<?php
	echo '<h2>Categories</h2>';
	$taxonomy = 'blogs';

	$args = array(
  'orderby'           => 'name', //alphabetically
//‘orderby’ => ‘id’, //for latest first
  'order'             => 'ASC',
  'hide_empty'        => true,//hide empty or not  
  //true for hiding empty - because clicking on empty makes error
		); 

  $tax_terms = get_terms($taxonomy, $args);
  //print_r($tax_terms);    
  echo '<ul>';
    ?>
    <?php foreach ($tax_terms as $tax_term): ?>

    <?php $term_name = (strtolower($tax_term->name)); 

	    $tax_link = site_url() . '/' . $taxonomy . '/' . $tax_term->slug . '/';
	    $doctor_name = $tax_term->name; 

      //$doctor_details = get_term_by('slug', $tax_term, $taxonomy );
      $doctor_img = get_metadata("taxonomy", $tax_term->term_taxonomy_id, 'image', TRUE);

		?>

		<li><a href="<?php echo $tax_link;?>" class="<?php if($page_slug == $term_name) echo 'active';?>"><?php echo $tax_term->name;?></a></li>

    <?php endforeach;?>
    <?php
    echo '</ul>';
?>