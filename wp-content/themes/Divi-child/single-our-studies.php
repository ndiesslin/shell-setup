<?php
  /**
  * Template Name: Study Template
  */

  get_header();

  // Get title section
  get_template_part('template-title');

  //Check if list template
  $study_list_check = types_render_field( "study-list-template-options" );
  if (!empty( $study_list_check )) :
    if (isset($_GET['search-form']) && !empty($_GET['search-form'])) :
      include('includes/study/search-form.php'); // Get form for study search
      ?>
      <div class=" et_pb_row et_pb_row_0">
        <p>  
          <a href="/our-studies/all-studies">View All Studies</a>
        </p>
      </div>
      <?php    
    else :
      ?>
      <div class=" et_pb_row et_pb_row_0">
        <p>  
          <a href="/our-studies/all-studies?search-form=true">Search Studies</a>
        </p>
      </div>
      <?php 
    endif;
    include('includes/study-list.php'); // Get study list code
  else :
  ?>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">STUDY IDENTIFIER:</h3>
      <p class="display-inline">
        <?php
          // Get Identifier
          echo get_post_meta( get_the_ID(), 'wpcf-study-identifier', true );
        ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">CONDITION:</h3>
      <p class="display-inline">
        <?php
          // Get Condition
          $specific_condition = get_post_meta( get_the_ID(), 'wpcf-specific-condition', true );
          // Check if specific condition is specified
          if ( $specific_condition ) {
            echo $specific_condition;
          } else {
            echo types_render_field( "condition", array( "separator" => ", " ) ); // This is the simpler way.
          }
          // This is the more complicated way, but may be more useful in the future
          // $arr = get_post_meta( get_the_ID(), 'wpcf-condition', true );
          // foreach ($arr as $value) {
          //   echo $value[0];
          //   echo ($value === end($arr)) ? '' : ', ';
          // }
        ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">CONTACT INFO:</h3>
      <p class="display-inline">
        <?php echo get_post_meta( get_the_ID(), 'wpcf-contact-name', true ); ?>
        &nbsp;
        |
        &nbsp;
        <a href="mailto:<?php echo get_post_meta( get_the_ID(), 'wpcf-contact-email', true ); ?>">
          <?php echo get_post_meta( get_the_ID(), 'wpcf-contact-email', true ); ?>
        </a>
        <?php
          $tel = get_post_meta( get_the_ID(), 'wpcf-contact-number', true );
          if ( $tel ) :
        ?>
          &nbsp;
          |
          &nbsp;
          <a href="tel:<?php echo $tel; ?>"><?php echo $tel; ?></a>
        <?php endif ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">DESCRIPTION:</h3>
      <p class="display-inline">
        <?php
        // Get Description
        echo do_shortcode( get_post_meta( get_the_ID(), 'wpcf-description', true ) );
        ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">CRITERIA LIST/ QUALIFICATIONS:</h3>
      <?php
        // Get Qualifications
        echo do_shortcode( get_post_meta( get_the_ID(), 'wpcf-criteria-list-qualifications', true ) );
      ?>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">INVESTIGATORS:</h3>
      <p class="display-inline">
        <?php
          // Get Investigators
          echo get_post_meta( get_the_ID(), 'wpcf-investigators', true );
        ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">SPONSORS:</h3>
      <p class="display-inline">
        <?php
          // Get Sponsors
          echo get_post_meta( get_the_ID(), 'wpcf-sponsors', true );
        ?>
      </p>
    </div>
    <div class=" et_pb_row et_pb_row_0">
      <h3 class="text--purple margin-right--8 display-inline text-bold">STATUS:</h3>
      <p class="display-inline">
        <?php
          // Get Status
          echo get_post_meta( get_the_ID(), 'wpcf-status', true );
        ?>
      </p>
    </div>
    <?php if (get_post_meta( get_the_ID(), 'wpcf-study-results', true )) : ?>
      <div class=" et_pb_row et_pb_row_0">
        <h3 class="text--purple margin-right--8 display-inline text-bold">Results:</h3>
        <?php
          // Get Status
          echo do_shortcode( get_post_meta( get_the_ID(), 'wpcf-study-results', true ) );
        ?>
      </div>
    <?php endif ?>
    <?php if (get_post_meta( get_the_ID(), 'wpcf-study-url', true )) : ?>
      <div class=" et_pb_row et_pb_row_0">
        <p class="wpcf7-form">
          <a href=
          "<?php echo get_post_meta( get_the_ID(), 'wpcf-study-url', true ); ?>" class="btn">Get More Information</a>
        </p>
      </div>
    <?php endif ?>
<?php
  endif;
get_footer(); ?>
