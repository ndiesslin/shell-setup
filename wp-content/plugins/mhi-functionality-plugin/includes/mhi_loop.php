<?php
/*  MHI loop template
	
	This loop can be used with the template tag or shortcode to query posts in the Clinical Study and People custom post types, filtered by any of the custom taxonomies associated with those CPTs
*/

	// Make sure the loop has access to the $post variable
	global $post;
	
	// Set aside the current $post variable
	$mhi_posttemp = $post;


	$sorter = ($tab == 'past' ? 'DESC' : 'ASC');

	// Settings for MHI query
	$mhi_loop = new WP_Query( array(
		'post_type'      => $cpt,
		$tax             => $term,
		'posts_per_page' => $quantity,
		'meta_key'			=> 'time_and_date',
		'orderby' => 'meta_value_num',
		'order'				=> $sorter
		) );

	// Check for posts
		if ( $mhi_loop->have_posts() ) : ?>

		<ul class="mhi-posts">


			<?php // Loop which loads the posts
			
			if($term == 'featured' || $term == 'featured,patient-story' || $term == 'open-for-enrollment'){
				while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post(); ?>

				<li>
					            <?php // Check for featured image
					            if ( has_post_thumbnail()) { ?>
					            <a class="people-headshot" href="<?php echo the_permalink() ?>"> <?php the_post_thumbnail() ?></a> <?php } ?>
					            <h4><a href="<?php the_permalink() ?>"><?php echo the_title() ?></a></h4>
					            <?php echo the_content('. Read more...') ?>


					        </li>	

					    <?php endwhile; // End post loop ?>

			<?php // Loop which loads the posts
			
		}elseif(($cpt == 'grand-rounds-lecture' && $tax == 'author_name') ||  ($cpt == 'people' && $tax == 'author_name')){
			while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post(); ?>
			
			<li><h4><?php echo the_title() ?> | <a href="<?php echo the_permalink() ?>"> VIEW </a> | <a href="<?php echo site_url(); ?>/my-account/?editpost=<?php echo the_ID(); ?>&titlepost=<?php echo the_title(); ?>&ptype=<?php echo $cpt;?>">EDIT</a></h4></li>	

		<?php endwhile; // End post loop ?>

			<?php // Loop which loads the posts
			
		}
			//Grand Rounds Loop [mhi_loop cpt="grand-rounds-lecture" quantity="100"]
		elseif($cpt == 'grand-rounds-lecture'){
			$dateVal;
			$dateToday =  strtotime(date("F j, Y"));


			while ( $mhi_loop->have_posts() ) : $mhi_loop->the_post();

			$stat = get_field('status');
			//types_render_field("date-and-time")
			if($tab == 'upcoming'):
				if($dateToday <= strtotime(get_field('time_and_date')) && $stat == 'publish'): 

					if($dateVal != get_field('time_and_date')):?><h4><?php the_field('time_and_date'); ?></h4><?php  endif; ?>
				<ul>
					<?php if($stat == 'publish'):?><li><strong><a href="<?php the_field('flier'); ?>" target="_blank"><?php echo the_title().' '; ?></a> </strong> <?php if(get_field('flier')){?>(<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">MP4</a>)<?php }else{echo '';} ?> | <a href="https://attendee.gotowebinar.com/register/4460524431407545857" target="_blank">Register here for live webinar</a><br />

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
						<?php if($stat == 'publish'):?><li><strong><?php echo the_title() ?> </strong> <?php if(get_field('flier')){?>(<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?>  <?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">MP4</a>)<?php }else{echo '';} ?><br />

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
						<li><strong><a href="<?php the_field('flier'); ?>" target="_blank"><?php echo the_title() ?></a></strong> <?php if(get_field('flier')){?>(<a href="<?php the_field('flier'); ?>" target="_blank">Flier</a>)<?php }else{echo '';} ?>  <?php if(get_field('slides')){?>(<a href="<?php the_field('slides'); ?>" target="_blank">Slides</a>)<?php }else{echo '';} ?> 
							<?php if(get_field('video_mp4')){?>(<a href="<?php the_field('video_mp4'); ?>" target="_blank">MP4</a>)<?php }else{echo '';} ?> - <?php if($stat == 'publish'):echo 'Published'; else: echo 'Draft'; endif ?> | <a href="/wordpress/my-account/?editpost=<?php the_ID(); ?>&formshow=show&button=Edit">Edit</a><br />

							<?php //if(get_field('lecturer')):
							the_field('lecturer'); //endif;?>
						
					<?php //endif; ?></li>
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

				<?php endwhile; }// End post loop ?>

				
			</ul><!-- .mhi-posts -->

		<?php // Reset the previous $post variable
		$post = $mhi_posttemp;
		wp_reset_postdata();

		endif; // End post check ?>