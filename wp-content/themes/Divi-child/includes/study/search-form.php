<div class="et_pb_section page et_pb_section_0 et_section_regular group-toggle-target" id="filter-form">
  <div class="et_pb_row et_pb_row_0">
    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
      <div class="et_pb_posts et_pb_module et_pb_bg_layout_light et_pb_blog_0">
        <div role="form" lang="en-US" dir="ltr">
          <div class="screen-reader-response"></div>
          <form action="" method="get">
            <div class="wpcf7-form">
              <h3 class="text--purple display-inline text-bold">Search By Keyword</h3><br>
              <div class="clearfix">
                <span class="wpcf7-form-control-wrap your-subject et_pb_column et_pb_column_1_2">
                  <input type="text" class="wpcf7-form-control form-input form-input--full-width wpcf7-text et_pb_column_4_4" name="cs" id="cs" value="<?php echo isset($_GET['cs']) ? $_GET['cs'] : '' ?>">
                </span>
                <div class="et_pb_column_1_2  et_pb_column_4 et_pb_column_empty"></div>
              </div>
            </div>
            <br>
            <div class="clearfix">
              <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                <div>
                  <h3 class="text--purple display-inline text-bold">I AM A PHYSICIAN:</h3><br>
                  <input type="checkbox" name="wpcf-physician-check" value="Yes" <?php echo in_array_r('Yes', getParameters(), false, 'wpcf-physician-check') ? 'checked' : ''; ?>>Yes<br>
                  <input type="checkbox" name="wpcf-physician-check" value="No" <?php echo in_array_r('No', getParameters(), false, 'wpcf-physician-check') ? 'checked' : ''; ?>>No
                </div>
                <br>
                <div>
                  <h3 class="text--purple display-inline text-bold">CONDITIONS</h3><br>
                  <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                    <input type="checkbox" name="wpcf-condition" value="Arrhythmia"<?php echo in_array_r('Arrhythmia', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Arrhythmia<br>
                    <input type="checkbox" name="wpcf-condition" value="Cardiomyopathy"<?php echo in_array_r('Cardiomyopathy', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Cardiomyopathy<br>
                    <input type="checkbox" name="wpcf-condition" value="Heart Attack"<?php echo in_array_r('Heart Attack', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Heart Attack<br>
                    <input type="checkbox" name="wpcf-condition" value="Heart Failure"<?php echo in_array_r('Heart Failure', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Heart Failure<br>
                    <input type="checkbox" name="wpcf-condition" value="Heart Valve Disease"<?php echo in_array_r('Heart Valve Disease', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Heart Valve Disease
                  </div>
                  <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                    <input type="checkbox" name="wpcf-condition" value="High Blood Pressure"<?php echo in_array_r('High Blood Pressure', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>High Blood Pressure<br>
                    <input type="checkbox" name="wpcf-condition" value="High Cholesterol"<?php echo in_array_r('High Cholesterol', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>High Cholesterol<br>
                    <input type="checkbox" name="wpcf-condition" value="Peripheral Arterial Disease"<?php echo in_array_r('Peripheral Arterial Disease', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Peripheral Arterial Disease<br>
                    <input type="checkbox" name="wpcf-condition" value="Sudden Cardiac Arrest"<?php echo in_array_r('Sudden Cardiac Arrest', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Sudden Cardiac Arrest<br>
                    <input type="checkbox" name="wpcf-condition" value="Stroke"<?php echo in_array_r('Stroke', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>>Stroke<br>
                  </div>
                </div>
              </div>
              <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                <div>
                  <h3 class="text--purple display-inline text-bold">GENDER</h3><br>
                  <input type="checkbox" name="wpcf-gender" value="Male" <?php echo in_array_r('Male', getParameters(), false, 'wpcf-gender') ? 'checked' : ''; ?>>Male
                  <input type="checkbox" name="wpcf-gender" value="Female" <?php echo in_array_r('Female', getParameters(), false, 'wpcf-gender') ? 'checked' : ''; ?>>Female
                </div>
                <br>
                <div>
                  <h3 class="text--purple display-inline text-bold">AGE</h3><br>
                  <input type="checkbox" name="wpcf-age-range" value="18-20" <?php echo in_array_r('18-20', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>>18-20<br>
                  <input type="checkbox" name="wpcf-age-range" value="21-74"<?php echo in_array_r('21-74', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>>21-74<br>
                  <input type="checkbox" name="wpcf-age-range" value="75-79"<?php echo in_array_r('75-79', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>>75-79<br>
                  <input type="checkbox" name="wpcf-age-range" value="80+"<?php echo in_array_r('80+', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>>80+
                </div>
              </div>
              <!-- <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                <h3 class="text--purple display-inline text-bold">STUDY STATUS</h3><br>
                <input type="checkbox" name="wpcf-status" value="Open/Enrolling"<?php// echo in_array_r('Open/Enrolling', getParameters(), false, 'wpcf-status') ? 'checked' : ''; ?>>Open/Enrolling<br>
                <input type="checkbox" name="wpcf-status" value="Closed"<?php// echo in_array_r('Closed', getParameters(), false, 'wpcf-status') ? 'checked' : ''; ?>>Closed<br>
                <input type="checkbox" name="wpcf-status" value="Hasn’t Started"<?php// echo in_array_r('Hasn’t Started', getParameters(), false, 'wpcf-status') ? 'checked' : ''; ?>>Hasn’t Started
              </div> -->
            </div>
            <p class="wpcf7-form">
              <input type="submit" value="Search" class="wpcf7-form-control wpcf7-submit">
            </p>
            <div class="wpcf7-response-output wpcf7-display-none"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="et_pb_fullwidth_section et_pb_section_1 et_pb_with_background et_section_regular group-toggle">
  <span class="group-toggle__tab" data-toggle-id="filter-form"></span>
</div>
