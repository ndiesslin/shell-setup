<?php
/*  MHI loop template

	This loop can be used with the template tag or shortcode to query posts in the Clinical Study and People custom post types, filtered by any of the custom taxonomies associated with those CPTs
*/

	// Make sure the loop has access to the $post variable
	global $post;

	// Set aside the current $post variable
	$mhi_posttemp = $post;



	//echo  $tab;
	//$sorter = 'SSS';
	$grvar_cont = $grvar;
	//$ordering_cont = ($_SESSION["order"])?'ASC':'DESC';

  if ($tab == 'past' || $tab == 'past-edit') {
    $sorter = 'DESC';
  } else {
    $sorter = 'ASC';
  }

	if($grvar_cont == 'title'){
		$mhi_loop = new WP_Query( array(
		'post_type'      => 'grand-rounds-lecture' ,
		$tax             => $term,
		'posts_per_page' => $quantity,
		'orderby' => 'title',
		'order'				=>$sorter
		) );
	}else{
	// Settings for MHI query
	$mhi_loop = new WP_Query( array(
		'post_type'      => 'grand-rounds-lecture' ,
		$tax             => $term,
		'posts_per_page' => $quantity,
		'meta_key'			=> 'time_and_date',
		'orderby' => 'meta_value',
		'order'				=>$sorter
		) );
	}
	// Check for posts
		if ( $mhi_loop->have_posts() ) : ?>



			<?php // Loop which loads the posts
		if($grvar_cont){ ?>




<!-- Table Start -->


<div class=" et_pb_row et_pb_row_2">

				<div class="et_pb_column et_pb_column_1_3  et_pb_column_2">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">

<p><strong><a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') ?>?grvar=time_and_date">Date </a></strong><?php if($grvar_cont == 'time_and_date') { ?><span class="<?php if(($_SESSION["order"])=='DESC'){echo'fa fa-caret-up';}else{echo'fa fa-caret-down';} ?>"></span> <?php } ?></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_3">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_3">

<p><strong><a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') ?>?grvar=title">Presentation </a><?php if($grvar_cont == 'title') { ?></strong><span class="<?php if(($_SESSION["order"])=='DESC'){echo'fa fa-caret-up';}else{echo'fa fa-caret-down';} ?>"></span><?php } ?></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_4">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_4">

<p><strong><a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') ?>?grvar=lecturer">Lecturer </a></strong><?php if($grvar_cont == 'lecturer') { ?><span class="<?php if(($_SESSION["order"])=='DESC'){echo'fa fa-caret-up';}else{echo'fa fa-caret-down';} ?>"></span> <?php } ?></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->

			</div> <!-- .et_pb_row -->

<?php while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post();
			if(get_field('status') == 'publish'){
?>



			<div class=" et_pb_row et_pb_row_3"><hr />

				<div class="et_pb_column et_pb_column_1_3  et_pb_column_6">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_6">

<p><?php the_field('time_and_date'); ?></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_7">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_7">

<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column --><div class="et_pb_column et_pb_column_1_3  et_pb_column_8">

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_8">

<p><?php the_field('lecturer'); ?></p>

			</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->

			</div> <!-- .et_pb_row -->

			<?php
		}

			endwhile; ?>
<?php
	}
	//End Grand Rounds Sorting
	//Grand Rounds Loop [mhi_loop cpt="grand-rounds-lecture" quantity="100"]
		elseif($cpt == 'grand-rounds-lecture'){
			?><ul class="mhi-posts"><?php
			$dateVal;
			$dateToday =  strtotime(date("F j, Y"));


			while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post();

			$stat = get_field('status');


			//types_render_field("date-and-time")
			if($tab == 'upcoming'):
				if($dateToday <= strtotime(get_field('time_and_date')) && $stat == 'publish'):

					if($dateVal != get_field('time_and_date')):?><h4><?php the_field('time_and_date'); ?></h4><?php  endif; ?>
				<ul>
					<?php if($stat == 'publish'):?><li><strong><?php echo the_title(); ?></strong> <?php if(get_field('flier')){?> (<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">Recording</a>)<?php }else{echo '';} ?><br />

						<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>

				</li><?php endif; ?>
				<?php
				$dateVal = get_field("time_and_date");
				echo '</ul>';
				endif;

				elseif($tab == 'past'):

					if($dateToday > strtotime(get_field('time_and_date')) && $stat == 'publish'):

						if($dateVal != get_field('time_and_date')):?><h4><?php the_field('time_and_date'); ?></h4><?php  endif; ?>
					<ul>
						<?php if($stat == 'publish'):?><li><strong><?php echo the_title() ?></strong><?php if(get_field('flier')){?> (<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">Recording</a>)<?php }else{echo '';} ?><br />

							<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>

					<?php //endif; ?></li><?php endif; ?>
					<?php
					$dateVal = get_field("time_and_date");
					echo '</ul>';
					endif;

					else:


						if($dateVal != get_field('time_and_date')):?><h4><?php the_field('time_and_date'); ?></h4><?php  endif; ?>
					<ul>
						<li><strong><?php echo the_title() ?></strong> <?php if(get_field('flier')){?> (<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>
							<?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">Recording</a>)<?php }else{echo '';} ?> - <?php if($stat == 'publish'):echo 'Published'; else: echo 'Draft'; endif ?> | <a href="/wordpress/my-account/?editpost=<?php the_ID(); ?>&formshow=show&button=Edit">Edit</a><br />

							<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>

					<?php //endif; ?>2nd Conditional</li>
					<?php
					$dateVal = get_field("time_and_date");
					echo '</ul>';
					endif;
					endwhile;

		// End post loop
				}
				else{
					while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post(); ?>

					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

				<?php endwhile;

				}// End post loop ?>



			</ul><!-- .mhi-posts -->

		<?php // Reset the previous $post variable
		$post = $mhi_posttemp;
		wp_reset_postdata();

		endif; // End post check ?>
