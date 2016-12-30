<div class="page group-toggle-target" id="filter-form">
  <div class="et_pb_row et_pb_row_0">
    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
      <div role="form" lang="en-US" dir="ltr">
        <div class="screen-reader-response"></div>
        <?php //set form action to locatin of search page, this is needed for when a user searches on a paginated page ?>
        <form action="/our-studies/all-studies" method="get">
          <div class="wpcf7-form margin-bottom--25">
            <h3 class="text--purple display-inline text-bold">Search By Keyword</h3><br>
            <div class="clearfix">
              <span class="wpcf7-form-control-wrap your-subject et_pb_column et_pb_column_1_2">
                <input type="text" class="wpcf7-form-control form-input form-input--full-width wpcf7-text et_pb_column_4_4" name="cs" id="cs" value="<?php echo isset($_GET['cs']) ? $_GET['cs'] : '' ?>">
              </span>
              <div class="et_pb_column_1_2  et_pb_column_4 et_pb_column_empty"></div>
            </div>
          </div>
          <div class="clearfix margin-bottom--50">
            <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
              <div class="margin-bottom--25">
                <h3 class="text--purple display-inline text-bold">I AM A PHYSICIAN:</h3><br>
                <input type="radio" name="wpcf-physician-check" value="Yes" id="physician-yes" <?php echo in_array_r('Yes', getParameters(), false, 'wpcf-physician-check') ? 'checked' : ''; ?>><label for="physician-yes">Yes</label><br>
                <input type="radio" name="wpcf-physician-check" value="No" id="physician-no" <?php echo in_array_r('No', getParameters(), false, 'wpcf-physician-check') ? 'checked' : ''; ?>><label for="physician-no">No</label>
              </div>
              <div class="margin-bottom--25 clearfix">
                <h3 class="text--purple display-inline text-bold">CONDITIONS</h3><br>
                <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                  <input type="checkbox" name="wpcf-condition" value="Arrhythmia" id="Arrhythmia" <?php echo in_array_r('Arrhythmia', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="Arrhythmia">Arrhythmia</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Cardiomyopathy" id="Cardiomyopathy" <?php echo in_array_r('Cardiomyopathy', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="Cardiomyopathy">Cardiomyopathy</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Heart Attack" id="HeartAttack" <?php echo in_array_r('Heart Attack', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="HeartAttack">Heart Attack</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Heart Failure" id="HeartFailure" <?php echo in_array_r('Heart Failure', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="HeartFailure">Heart Failure</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Heart Valve Disease" id="HeartValveDisease" <?php echo in_array_r('Heart Valve Disease', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="HeartValveDisease">Heart Valve Disease</label>
                </div>
                <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
                  <input type="checkbox" name="wpcf-condition" value="High Blood Pressure" id="HighBloodPressure" <?php echo in_array_r('High Blood Pressure', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="HighBloodPressure">High Blood Pressure</label><br>
                  <input type="checkbox" name="wpcf-condition" value="High Cholesterol" id="HighCholesterol" <?php echo in_array_r('High Cholesterol', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="HighCholesterol">High Cholesterol</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Peripheral Arterial Disease" id="PeripheralArterialDisease" <?php echo in_array_r('Peripheral Arterial Disease', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="PeripheralArterialDisease">Peripheral Arterial Disease</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Sudden Cardiac Arrest" id="SuddenCardiacArrest" <?php echo in_array_r('Sudden Cardiac Arrest', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="SuddenCardiacArrest">Sudden Cardiac Arrest</label><br>
                  <input type="checkbox" name="wpcf-condition" value="Stroke" id="Stroke" <?php echo in_array_r('Stroke', getParameters(), false, 'wpcf-condition') ? 'checked' : ''; ?>><label for="Stroke">Stroke</label><br>
                </div>
              </div>
            </div>
            <div class="et_pb_column et_pb_column_1_2  et_pb_column_1">
              <div class="margin-bottom--25">
                <h3 class="text--purple display-inline text-bold">GENDER</h3><br>
                <input type="radio" name="wpcf-gender" value="Male" id="Male" <?php echo in_array_r('Male', getParameters(), false, 'wpcf-gender') ? 'checked' : ''; ?>><label for="Male">Male</label>
                <input type="radio" name="wpcf-gender" value="Female" id="Female" <?php echo in_array_r('Female', getParameters(), false, 'wpcf-gender') ? 'checked' : ''; ?>><label for="Female">Female</label>
              </div>
              <div class="margin-bottom--25">
                <h3 class="text--purple display-inline text-bold">AGE</h3><br>
                <input type="radio" name="wpcf-age-range" value="18-20" id="18-20" <?php echo in_array_r('18-20', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>><label for="18-20">18-20</label><br>
                <input type="radio" name="wpcf-age-range" value="21-74" id="21-74" <?php echo in_array_r('21-74', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>><label for="21-74">21-74</label><br>
                <input type="radio" name="wpcf-age-range" value="75-79" id="75-79" <?php echo in_array_r('75-79', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>><label for="75-79">75-79</label><br>
                <input type="radio" name="wpcf-age-range" value="80" id="80" <?php echo in_array_r('80', getParameters(), false, 'wpcf-age-range') ? 'checked' : ''; ?>><label for="80">80+</label>
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
<div class="et_pb_fullwidth_section et_pb_section_1 et_pb_with_background et_section_regular group-toggle">
  <span class="group-toggle__tab" data-toggle-id="filter-form"></span>
</div>