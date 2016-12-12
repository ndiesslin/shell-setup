<?php
  /**
  * Template Name: Study Template
  */

  get_header();

  // Get title section
  get_template_part('template-title');

?>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">STUDY IDENTIFIER:</h3>
    <p class="display-inline">
      <?php
        // Get Identifier
        echo(get_post_meta( get_the_ID(), 'wpcf-study-identifier', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">STATUS:</h3>
    <p class="display-inline">
      <?php
        // Get Status
        echo(get_post_meta( get_the_ID(), 'wpcf-status', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">CONDITION:</h3>
    <p class="display-inline">
      <?php
        // Get Condition
        echo(types_render_field( "condition", array( "separator" => ", " ) ));
        print_r(get_post_meta( get_the_ID(), 'wpcf-condition', true ));
        $arr = get_post_meta( get_the_ID(), 'wpcf-condition', true );
        foreach ($arr as $value) {
          echo $value[0];
        }
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">DESCRIPTION:</h3>
    <p class="display-inline">
      <?php
      // Get Description
      echo(get_post_meta( get_the_ID(), 'wpcf-description', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">CRITERIA LIST/ QUALIFICATIONS:</h3>
    <p class="display-inline">
      <?php
        // Get Qualifications
        echo(get_post_meta( get_the_ID(), 'wpcf-criteria-list-qualifications', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">INVESTIGATORS:</h3>
    <p class="display-inline">
      <?php
        // Get Investigators
        echo(get_post_meta( get_the_ID(), 'wpcf-investigators', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">SPONSORS:</h3>
    <p class="display-inline">
      <?php
        // Get Sponsors
        echo(get_post_meta( get_the_ID(), 'wpcf-sponsors', true ));
      ?>
    </p>
  </div>
  <div class=" et_pb_row et_pb_row_0">
    <h3 class="text--purple display-inline text-bold">CONTACT INFO:</h3>
    <p class="display-inline">
      <a href="tel:<?php echo(get_post_meta( get_the_ID(), 'wpcf-contact-number', true )); ?>"><?php echo(get_post_meta( get_the_ID(), 'wpcf-contact-name', true )); ?></a>
      &nbsp;
      |
      &nbsp;
      <a href="mailto:<?php echo(get_post_meta( get_the_ID(), 'wpcf-contact-email', true )); ?>">
        <?php echo(get_post_meta( get_the_ID(), 'wpcf-contact-email', true )); ?>
      </a>
    </p>
  </div>
<?php get_footer(); ?>
