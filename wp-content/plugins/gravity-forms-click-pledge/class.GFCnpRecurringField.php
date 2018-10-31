<?php

/**
* with thanks to Travis Smith's excellent tutorial:
* http://wpsmith.net/2011/plugins/how-to-create-a-custom-form-field-in-gravity-forms-with-a-terms-of-service-form-field-example/
*/
class GFCnpRecurringField {

	protected $plugin;
	protected $RecurringMethod;
	protected $first_load;
	public $nwrecbtn = false;
    public $nwrecbtnvw = false;
	protected static $defaults = array (
		'gfcnp_initial_amount_label' => 'Initial Amount',
		'gfcnp_recurring_amount_label' => 'Recurring Amount',
		'gfcnp_initial_date_label' => 'Initial Date',
		'gfcnp_start_date_label' => 'Start Date',
		'gfcnp_end_date_label' => 'End Date',
		'gfcnp_interval_type_label' => 'Interval Type',
	);

	/**
	* @param GFEwayPlugin $plugin
	*/
	public function __construct($plugin) {
		error_reporting(0);
		$this->plugin = $plugin;
		$this->RecurringMethod = array();
		$this->first_load = true;
		// WordPress script hooks -- NB: must happen after Gravity Forms registers scripts
		add_action('wp_enqueue_scripts', array($this, 'registerScripts'), 20);
		add_action('admin_enqueue_scripts', array($this, 'registerScripts'), 20);

		// add Gravity Forms hooks
		// add_filter('gform_pre_render', array($this, 'gformPreRenderSniff')); //commented by lakshmi
		add_action('gform_enqueue_scripts', array($this, 'gformEnqueueScripts'), 20, 2);
		add_action('gform_editor_js', array($this, 'gformEditorJS'));
		
		add_action('gform_field_standard_settings', array($this, 'gformFieldStandardSettings'), 10, 2);
		add_filter('gform_add_field_buttons', array($this, 'gformAddFieldButtons'));
		add_filter('gform_field_type_title', array($this, 'gformFieldTypeTitle'), 10, 2);
		add_filter('gform_field_input', array($this, 'gformFieldInput'), 10, 5);
		add_filter('gform_pre_validation', array($this, 'gformPreValidation'));
		add_filter('gform_field_validation', array($this, 'gformFieldValidation'), 10, 4);
		add_filter('gform_tooltips', array($this, 'gformTooltips'));
		add_filter('gform_pre_submission', array($this, 'gformPreSubmit'));

		if (is_admin()) {
			add_filter('gform_field_css_class', array($this, 'watchFieldType'), 10, 2);
		}
		
	}

	/**
	* register and enqueue required scripts
	* NB: must happen after Gravity Forms registers scripts
	*/
	public function registerScripts() {
		// recurring payments field has datepickers; register required scripts / stylesheets
		if (version_compare(GFCommon::$version, '1.7.6.99999', '<')) {
			// pre-1.7.7 script registrations
			$gfBaseUrl = GFCommon::get_base_url();
			wp_register_script('gforms_ui_datepicker', $gfBaseUrl . '/js/jquery-ui/ui.datepicker.js', array('jquery'), GFCommon::$version, true);
			wp_register_script('gforms_datepicker', $gfBaseUrl . '/js/datepicker.js', array('gforms_ui_datepicker'), GFCommon::$version, true);
			$reqs = array('gforms_datepicker');
		}
		else {
			// post-1.7.7
			$reqs = array('gform_datepicker_init');
		}

		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script('gfcnp_recurring', "{$this->plugin->urlBase}js/recurring$min.js", $reqs, GFCNP_PLUGIN_VERSION, true);

		wp_register_style('gfcnp', $this->plugin->urlBase . 'style.css', false, GFCNP_PLUGIN_VERSION);
	}

	/**
	* enqueue additional scripts if required by form
	* @param array $form
	* @param boolean $ajax
	*/
	public function gformEnqueueScripts($form, $ajax) {
		if ($this->plugin->hasFieldType($form['fields'], GFCNP_FIELD_RECURRING)) {
			// enqueue script for field
			wp_enqueue_script('gfcnp_recurring');

			// enqueue default styling
			wp_enqueue_style('gfcnp');
		}

	}

	/**
	* load custom script for editor form
	*/
	public function gformEditorJS() {
		$version = GFCNP_PLUGIN_VERSION;
		$min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		echo "<script src=\"{$this->plugin->urlBase}js/admin-recurring$min.js?v=$version\"></script>\n";
	}

	/**
	* filter hook for modifying the field buttons on the forms editor
	* @param array $field_groups array of field groups; each element is an array of button definitions
	* @return array
	*/
	public function gformAddFieldButtons($field_groups) {
		foreach ($field_groups as &$group) {
			if ($group['name'] == 'pricing_fields') {
				$group['fields'][] = array (
					'class' => 'button',
					'value' => 'Recurring',
					'name' => 'RecurringButton',
					'id' => 'RecurringButton',
					'data-type' => GFCNP_FIELD_RECURRING,
					//'onclick' => "StartAddField('" . GFCNP_FIELD_RECURRING . "');",
				);
				break;
			}
		}
		return $field_groups;
	}

	/**
	* filter hook for modifying the field title (e.g. on custom fields)
	* @param string $title
	* @param string $field_type
	* @return string
	*/
	public function gformFieldTypeTitle($title, $field_type) {
		if ($field_type == GFCNP_FIELD_RECURRING) {
			$title = 'Recurring Payments';
		}

		return $title;
	}

	/**
	* add custom fields to form editor
	* @param integer $position
	* @param integer $form_id
	*/
	public function gformFieldStandardSettings($position, $form_id) {
		// add inputs for labels right after the field label input
	  if ($position == 25) {
	   
		 $formFSS = GFFormsModel::get_form_meta( $form_id); 
		 $valuesFSS= array();
		 $nwrecbtn = 2;
	
   	 	 foreach( $formFSS['fields'] as $fieldFSS ) {
			$valuesFSS[$fieldFSS['id']] = array(
				'id'    => $fieldFSS['id'],
				'label' => $fieldFSS['label'],
				'type' => $fieldFSS['type'],
				'gfcnp_payoptn' => $fieldFSS['gfcnp_payoptn'],
				'value' => $lead[ $fieldFSS['id'] ],
			);
          }
	     $options = $this->plugin->options;
		
			/*foreach($valuesFSS as $valueFSS)
			{
				if(in_array("gfcnprecurring", $valueFSS)) //old form
				{
				  if($valueFSS['gfcnp_payoptn'] == "") {
					$nwrecbtn = 1; 
					}
					else if(isset($valueFSS['gfcnp_payoptn']) && $valueFSS['gfcnp_payoptn'] != ""){
					$nwrecbtn = 2;
					}
				}
				
				
			}
			
			
			if((count($valuesFSS)== 0) ||$nwrecbtn == 2 )
			{	*/		
			
		   ?>
					<li class="gfcnprecurring_setting field_setting">
					<div id="RecurringMethod">
					<table width="100%" border="0" cellpadding="5" cellspacing="1" class="rectbl">
					<tbody>
					<tr><td valign="top">
					<label for="gfcnp_recurring_paymentoptions_label">	
				
				    <input type="text" name="gfcnp_payoptn" id="gfcnp_payoptn" value='Payment options' placeholder='Payment options' onchange="GFCnpRecurring.FieldSet(this)"/>
					</label>
					</td>
					<td><table><tr><td>&nbsp;</td><td>SKU Suffix</td></tr>
					<tr><td><input type="checkbox" id="gfcnp_payoptns_onetimeonly" value="One Time Only" onclick="GFCnpRecurring.FieldSet3(this)">&nbsp;One Time Only</td>
					<td><input type="text"  id="txtsku_onetime"  name="txtsku_onetime" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td>	
					<input type="checkbox" id="gfcnp_payoptns_recurring" value="Recurring" checked="checked" disabled="disabled">&nbsp;Recurring</td>
					<td>&nbsp;</td></tr>
				    </table>
					</td></tr>
					
					<tr class="trdfltpymntoptn"><td><label>Default payment options </label></td><td>
					<select name="gfcnp_dfltpayoptn" id="gfcnp_dfltpayoptn" onchange="GFCnpRecurring.FieldSet(this)">
					<option value="Recurring">Recurring</option>
					<option value="One Time Only">One Time Only</option>
					</select></td></tr>	
					
					<tr><td valign="top">
					<label for="gfcnp_recurring_RecurringTypes_label"><input type="text" name="gfcnp_rectype" id="gfcnp_rectype" value='Recurring types' placeholder='Recurring types' onchange="GFCnpRecurring.FieldSet(this)"/></label>	
					</td><td><div><input type="checkbox" id="gfcnp_recurringtype_installment"  value="Installment" onclick="GFCnpRecurring.ToggleInstallmentSetting(this)">&nbsp;Installment (e.g. pay $1000 in 10 installments of $100 each)</div>
					<div><input type="checkbox" id="gfcnp_recurringtype_subscription" value="Subscription" onclick="GFCnpRecurring.ToggleSubscriptionSetting(this)">&nbsp;Subscription (e.g. pay $100 every month for 12  months)</div>	
					
					</td></tr>
					
					<tr class="trdfltrecoptn" id="trdfltrecoptn"><td><label>Default Recurring type</label></td><td>
					<select name="gfcnp_dfltrectypoptn" id="gfcnp_dfltrectypoptn" onchange="GFCnpRecurring.FieldSet(this)">
					<option value="Subscription">Subscription</option>
					<option value="Installment">Installment</option>
					</select></td></tr>	
					<script language="javascript">
					
						jQuery('#gfcnp_recurringtype_subscription').click(function(){
						if(jQuery("#gfcnp_recurringtype_installment").is(':checked') && jQuery("#gfcnp_recurringtype_subscription").is(':checked'))
						{
						  jQuery("tr.trdfltrecoptn").show();
						}
						});
					</script>
					<tr><td valign="top">
					<label for="gfcnp_recurring_periodicity_label">
					<input type="text" name="gfcnp_periodicity" id="gfcnp_periodicity" value='Periodicity' placeholder='Periodicity' onchange="GFCnpRecurring.FieldSet(this)"/>
					</label></td>
					<td>
					<div><table><tr><td>&nbsp;</td><td>SKU Suffix</td></tr>
					<tr><td><input type="checkbox" id="gfcnp_Week_setting" value="Week" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_week')">&nbsp;Week</td>
					<td><input type="text"  id="txtsku_week"  name="txtsku_week" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_2_Weeks_setting" value="2 Weeks" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_tweeks')">&nbsp;2 Weeks</td>
					<td><input type="text"  id="txtsku_tweeks"   name="txtsku_tweeks" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_Month_setting" value="Month" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_month')">&nbsp;Month</td>
					<td><input type="text"  id="txtsku_month"  name="txtsku_month" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_2_Months_setting" value="2 Months" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_tmonth')">&nbsp;2 Months</td>
					<td><input type="text"  id="txtsku_tmonth"   name="txtsku_tmonth" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_Quarter_setting" value="Quarter" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_quarter')">&nbsp;Quarter</td>
					<td><input type="text"  id="txtsku_quarter"   name="txtsku_quarter" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_6_Months_setting" value="6 Months" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_smonths')">&nbsp;6 Months</td>
					<td><input type="text"  id="txtsku_smonths"   name="txtsku_smonths" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
				    <tr><td><input type="checkbox" id="gfcnp_Year_setting" value="Year" onclick="GFCnpRecurring.FieldSet2(this);GFCnpRecurring.FieldSet5(this,'txtsku_year')">&nbsp;Year</td>
					<td><input type="text"  id="txtsku_year"   name="txtsku_year" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
					</table></div></td></tr>
					<tr><td valign="top">
					<label for="gfcnp_recurring_RecurringNoofpaymnts_label"><input type="text" name="gfcnp_noofpayments" id="gfcnp_noofpayments" value='Number of payments' placeholder='Number of payments' onchange="GFCnpRecurring.FieldSet(this)"/></label>
					</td><td><div id="indefinite_div">
					<input type="radio" name="gfcnp_numberofpayments" id="gfcnp_numberofpayments" value="indefiniten" onclick="GFCnpRecurring.Togglenumberofpayments(this)">&nbsp;Indefinite Only
					</div><div id="openfild_div">
					<input type="radio" name="gfcnp_numberofpayments" id="gfcnp_numberofpayments" value="openfield" onclick="GFCnpRecurring.Togglenumberofpayments(this)">&nbsp;Open Field Only
					</div><div id="indefinite_openfield_div">
					<input type="radio" name="gfcnp_numberofpayments"  id="gfcnp_numberofpayments" value="indefinite_openfield" onclick="GFCnpRecurring.Togglenumberofpayments(this)">&nbsp;Indefinite + Open Field Option</div><div id="fixdnumber_div">
					<input type="radio" name="gfcnp_numberofpayments"  id="gfcnp_numberofpayments" value="fixednumber" onclick="GFCnpRecurring.Togglenumberofpayments(this)">&nbsp;Fixed Number - No Change Allowed</div>
				   </td></tr>
					<tr class="dfltnoofpaymnts"><td>
					<label><input type="text" name="gfcnp_dfltnoofpaymentslbl" id="gfcnp_dfltnoofpaymentslbl" value='Default number of payments'  placeholder='Default number of payments' onchange="GFCnpRecurring.FieldSet(this)"/></label></td>
					<td><input type="text"  id="gfcnp_dfltnoofpaymnts"  maxlength="3" name="gfcnp_dfltnoofpaymnts" onchange="GFCnpRecurring.FieldSet(this)"/></td></tr>
					<tr class="maxnoofinstlmnts"><td><div id="maxnoofinstlmntslbl_div">
					<label><input type="text" name="gfcnp_maxnoofinstallments" id="gfcnp_maxnoofinstallments" value='Maximum number of installments allowed' placeholder='Maximum number of installments allowed' onchange="GFCnpRecurring.FieldSet(this)"/></label></div></td>
					<td><div id="maxnoofinstlmnts_div"><input type="text" id="gfcnp_maxnoofinstallmentsallowed" name="gfcnp_maxnoofinstallmentsallowed" onchange="GFCnpRecurring.FieldSet(this)"  maxlength="3"/></div></td></tr>
</tbody></table>
					</div>					
				</li>			
<?php
/*}
			else
			{*/
			?>
				<!--<li class="gfcnprecurring_setting field_setting">
				<div id="RecurringMethod">
					<label for="gfcnp_recurring_RecurringMethod_label"><strong>Labels</strong></label>
					<table width="100%" border="0" cellpadding="5" cellspacing="1" class="rectbl">
					<tbody>
					<tr>
					<div id="label_isthisrecurring">
					<td valign="top"><label>Is this a recurring payment</label></td><td valign="top"><input type="text" id="gfcnp_label_isthisrecurring" onchange="GFCnpRecurring.FieldSet(this)"></td>
					</div></tr><tr>
					<div id="label_periods">
						<td valign="top"><label>Periodicity</label></td><td valign="top"><input type="text" id="gfcnp_label_periods" onchange="GFCnpRecurring.FieldSet(this)"></td>
					</div>
					</tr><tr>
					<div id="label_periods">
					<td valign="top">	<label># of Installments</label></td><td valign="top"><input type="text" id="gfcnp_label_installments" onchange="GFCnpRecurring.FieldSet(this)"></td>
					</div>
					</tr><tr>
					<div id="label_RecurringMethod">
					<td valign="top">	<label>Recurring Type</label></td><td valign="top"><input type="text" id="gfcnp_label_RecurringMethod" onchange="GFCnpRecurring.FieldSet(this)"></td>
					</div>
					</tr><tr>
					<div id="label_RecurringMethod">
					<td valign="top">	<label>Indefinite Recurring</label></td><td valign="top"><input type="text" id="gfcnp_label_IndefiniteRecurring" onchange="GFCnpRecurring.FieldSet(this)"></td>
					</div></tr>
					</tbody></table>
				</div><br/>
				
				<div id="RecurringMethod">
						<label for="gfcnp_recurring_RecurringMethod_label">
						<strong>Recurring Types</strong>
						<?php gform_tooltip("gfeway_recurring_RecurringMethod_label") ?>
						<?php gform_tooltip("gfeway_recurring_RecurringMethod_label_html") ?>
					</label>
					<input type="checkbox" id="Subscription" value="Subscription" onclick="GFCnpRecurring.ToggleoSubscriptionSetting(this)">&nbsp;Subscription<br>
					<div id="maxrecurrings_Subscription_label" style="display:none;">
					<input type="text" id="gfcnp_maxrecurrings_Subscription" onchange="GFCnpRecurring.FieldSet(this)">Subscription Max. Recurrings Allowed<br>
					</div>	
					<input type="checkbox" id="Installment" value="Installment" onclick="GFCnpRecurring.ToggleoInstallmentSetting(this)">&nbsp;Installment<br>
					<div id="maxrecurrings_Installment_label" style="display:none;">
					<input type="text" id="gfcnp_maxrecurrings_Installment" onchange="GFCnpRecurring.FieldSet(this)">Installment Max. Recurrings Allowed<br>
					</div>
					</div>
					
				    <label for="gfcnp_recurring_amount_label">
						<strong>Periodicity</strong>
						<?php gform_tooltip("gfeway_recurring_amount_label") ?>
						<?php gform_tooltip("gfeway_recurring_amount_label_html") ?>
					</label>
					
					<input type="checkbox" id="gfcnp_Week_setting" value="Week" onclick="GFCnpRecurring.FieldSet4(this)" <?php echo $Week;?>>&nbsp;Week<br>
					<input type="checkbox" id="gfcnp_2_Weeks_setting" value="2 Weeks" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;2 Weeks<br>
					<input type="checkbox" id="gfcnp_Month_setting" value="Month" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;Month<br>
					<input type="checkbox" id="gfcnp_2_Months_setting" value="2 Months" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;2 Months<br>
					<input type="checkbox" id="gfcnp_Quarter_setting" value="Quarter" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;Quarter<br>
					<input type="checkbox" id="gfcnp_6_Months_setting" value="6 Months" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;6 Months<br>
					<input type="checkbox" id="gfcnp_Year_setting" value="Year" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;Year<br><br>
					<div id="indefinite_div">
					<input type="checkbox" id="indefinite" value="indefinite" onclick="GFCnpRecurring.FieldSet4(this)">&nbsp;Allow indefinite recurring<br>
					</div>					
			</li>	-->
			<?php
			//}
			 }
	   }

	/**
	* add custom tooltips for fields on form editor
	* @param array $tooltips
	* @return array
	*/
	public function gformTooltips($tooltips) {
		$tooltips['gfcnp_initial_setting'] = "<h6>Show Initial Amount</h6>Select this option to show Initial Amount and Initial Date fields.";
		$tooltips['gfcnp_initial_amount_label'] = "<h6>Initial Amount</h6>The label shown for the Initial Amount field.";
		$tooltips['gfcnp_initial_date_label'] = "<h6>Initial Date</h6>The label shown for the Initial Date field.";
		$tooltips['gfcnp_recurring_amount_label'] = "<h6>Recurring Amount</h6>The label shown for the Recurring Amount field.";
		$tooltips['gfcnp_recurring_date_setting'] = "<h6>Show Start/End Dates</h6>Select this option to show Start Date and End Date fields.";
		$tooltips['gfcnp_start_date_label'] = "<h6>Start Date</h6>The label shown for the Start Date field.";
		$tooltips['gfcnp_end_date_label'] = "<h6>End Date</h6>The label shown for the End Date field.";
		$tooltips['gfcnp_interval_type_label'] = "<h6>Interval Type</h6>The label shown for the Interval Type field.";
		return $tooltips;
	}

	/**
	* grab values and concatenate into a string before submission is accepted
	* @param array $form
	*/
	public function gformPreSubmit($form) {
		
		foreach ($form['fields'] as $field) {
			if ($field['type'] == GFCNP_FIELD_RECURRING && !RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values'))) {
				$recurring = self::getPost($field['id']);
			
		
				//die();
				/*
				$_POST["input_{$field['id']}"] = '$' . number_format($recurring['amountRecur'], 2)
					. " {$recurring['intervalTypeDesc']} from {$recurring['dateStart']->format('d M Y')}";
					*/
				if($recurring['isRecurring'] == 'yes') {
				if($recurring['Installments'])
				$installments = $recurring['Installments'];
				else
				$installments = 999;
				if($recurring['RecurringMethod'] == 'Installment')
				$str = "Your card will be charged every {$recurring['Periodicity']} for {$installments} times (Installment)";
				else
				$str = "Your card will be charged every {$recurring['Periodicity']} for {$installments} times (Subscription)";
				$_POST["input_{$field['id']}"] = $str;
				} else {
					//$_POST["input_{$field['id']}"] = 'Simple Payment';
				}
			}
		}
		
	}

	/**
	* prime the inputs that will be checked by standard validation tests,
	* e.g. so that "required" fields don't fail
	* @param array $form
	* @return array&& (isset($field['gfcnp_dfltpayoptn']) && $field['gfcnp_dfltpayoptn'] != '')
	*/
	public function gformPreValidation($form) {

		foreach($form["fields"] as $field) {
		if((isset($field['gfcnp_payoptn']) && $field['gfcnp_payoptn'] != "") && (isset($field['gfcnp_rectype']) && $field['gfcnp_rectype'] != ""))
			{
				if ($field['type'] == GFCNP_FIELD_RECURRING && !RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values'))) {
					$recurring = self::getPost($field['id']);
					$_POST["input_{$field['id']}"] = serialize($recurring);
					$this->first_load = false;
				}
			}
			else
			{
			   if ($field['type'] == GFCNP_FIELD_RECURRING && !RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values')) && isset($_POST['gfp_'.$field['id']]) && $_POST['gfp_'.$field['id']] == 'on') {
				$recurring = self::getPost($field['id']);
				$_POST["input_{$field['id']}"] = serialize($recurring);
				$this->first_load = false;
			}
			}
		}

		return $form;
	}

	/**
	* validate inputs
	* @param array $validation_result an array with elements is_valid (boolean) and form (array of form elements)
	* @param string $value
	* @param array $form
	* @param array $field
	* @return array&&
	*/
	public function gformFieldValidation($validation_result, $value, $form, $field) {
	
		if ($field['type'] == GFCNP_FIELD_RECURRING) {
		if((isset($field['gfcnp_payoptn']) && $field['gfcnp_payoptn'] != "") && (isset($field['gfcnp_rectype']) && $field['gfcnp_rectype'] != ""))
			{
			if (!RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values')) ) {
			
				// get the real values
				$value = self::getPost($field['id']);
			
				if (!is_array($value)) {
					$validation_result['is_valid'] = false;
					$validation_result['message'] = __("This field is required.", "gravityforms");
				}

				else {
					$messages = array();
					
					//$options = $this->plugin->options;
				if($value['isRecurring'] == 'yes') {
					$options = $field;
					if (empty($value['RecurringMethod'])) {
						$messages[] = "Please select recurring type.";
					}
					if (empty($value['Periodicity'])) {
						$messages[] = "Please select Periodicity.";
					}				
				    if($value['RecurringMethod'] == 'Installment' && $value['indefinite'] == 'yes')
					{
					   $messages[] = "Recurring type Installment not allow Indefinite number of payments";
					}
					if($options['gfcnp_numberofpayments'] != 'indefiniten') {
						if(empty($value['Installments'])) {
							$messages[] = "Please enter value greater than 1.";
						}
						elseif (preg_match('/^\d+\.\d+$/',$value['Installments'])) {
							$messages[] = "Please enter integer numbers only.";
						}
						elseif($value['Installments'] <= 1) {
							$messages[] = "Please enter value greater than 1.";
						}
							
						elseif ($value['RecurringMethod'] == 'Subscription') {
							if(!empty($options['gfcnp_maxnoofinstallmentsallowed'])) {
								if($value['Installments'] > $options['gfcnp_maxnoofinstallmentsallowed'])
								$messages[] = "Please enter value between 2 to ".$options['gfcnp_maxnoofinstallmentsallowed'].".";	
							} else {
								if(empty($value['Installments'])) {
									$messages[] = "Please enter value between 2 to 999.";
								} else if($value['Installments'] > 999) {
									$messages[] = "Please enter value between 2 to 999 for installment.";
								}						
							}
						} else {
					
							if(!empty($options['gfcnp_maxnoofinstallmentsallowed']) && $value['Installments'] < 998) {
								if($value['Installments'] > $options['gfcnp_maxnoofinstallmentsallowed'])
								$messages[] = "Please enter value between 2 to ".$options['gfcnp_maxnoofinstallmentsallowed'].".";	
							} else {
								if(empty($value['Installments'])) {
									$messages[] = "Please enter value between 2 to 998.";
								} else if($value['Installments'] > 998) {
									$messages[] = "Please enter value between 2 to 998 for installment.";
								}
							}
						}
					 }
					}
					
					if (count($messages) > 0) {
						$validation_result['is_valid'] = false;
						$validation_result['message'] = implode("<br />\n", $messages);
					}
				}
			}
			}
			else
			{
			   if (!RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values')) && (isset($_POST['gfp_'.$field['id']]) && 
			        $_POST['gfp_'.$field['id']] == 'on')) {
				// get the real values
				$value = self::getPost($field['id']);
				
				if (!is_array($value)) {
					$validation_result['is_valid'] = false;
					$validation_result['message'] = __("This field is required.", "gravityforms");
				}

				else {
					$messages = array();
					
					//$options = $this->plugin->options;
					$options = $field;
					
					if (empty($value['RecurringMethod'])) {
						$messages[] = "Please select recurring type.";
					}
					if (empty($value['Periodicity'])) {
						$messages[] = "Please select Periodicity.";
					}				
					if($value['RecurringMethod'] == 'Installment' && $value['indefinite'] != 'no')
					{
					   $messages[] = "Recurring type Installment not allow indefinite number of payments";
					}	
					if($value['indefinite'] == 'no') {
						if(empty($value['Installments'])) {
							$messages[] = "Please enter value greater than 1.";
						}
						elseif (preg_match('/^\d+\.\d+$/',$value['Installments'])) {
							$messages[] = "Please enter integer numbers only.";
						}
						elseif($value['Installments'] <= 1) {
							$messages[] = "Please enter value greater than 1.";
						}
						
						elseif ($value['RecurringMethod'] == 'Subscription') {
							if(!empty($options['gfcnp_maxrecurrings_Subscription'])) {
								if($value['Installments'] > $options['gfcnp_maxrecurrings_Subscription'])
								$messages[] = "Please enter value between 2 to ".$options['gfcnp_maxrecurrings_Subscription'].".";	
							} else {
								if(empty($value['Installments'])) {
									$messages[] = "Please enter value between 2 to 999.";
								} else if($value['Installments'] > 999) {
									$messages[] = "Please enter value between 2 to 999 for installment.";
								}						
							}
						} else {
							if(!empty($options['gfcnp_maxrecurrings_Installment'])) {
								if($value['Installments'] > $options['gfcnp_maxrecurrings_Installment'])
								$messages[] = "Please enter value between 2 to ".$options['gfcnp_maxrecurrings_Installment'].".";	
							} else {
								if(empty($value['Installments'])) {
									$messages[] = "Please enter value between 2 to 998.";
								} else if($value['Installments'] > 998) {
									$messages[] = "Please enter value between 2 to 998 for installment.";
								}
							}
						}
					}
					
					if (count($messages) > 0) {
						$validation_result['is_valid'] = false;
						$validation_result['message'] = implode("<br />\n", $messages);
					}
				}
			}
			
			}
		}

		return $validation_result;
	}


	/**
	* watch the field type so that we can use hooks that don't pass enough information
	* @param string $classes
	* @param array $field
	* @return string
	*/
	public function watchFieldType($classes, $field) {
		// if field type matches, add filters that don't allow testing for field type
		if ($field['type'] == GFCNP_FIELD_RECURRING) 
		{
			//echo GFCNP_FIELD_RECURRING.':Adi';
			add_filter('gform_duplicate_field_link', array($this, 'gformDuplicateFieldLink'));
		}
		return $classes;
	}

	/**
	* filter the field duplication link, we don't want one for this field type
	* @param string $duplicate_field_link
	* @return $duplicate_field_link
	*/
	public function gformDuplicateFieldLink($duplicate_field_link) {
		// remove filter once called, only process current field
		//remove_filter('gform_duplicate_field_link', array($this, __FUNCTION__));
		add_filter('gform_duplicate_field_link', array($this, __FUNCTION__));
		// erase duplicate field link for this field
		return '';
	}

	/**
	* filter hook for modifying a field's input tag (e.g. on custom fields)
	* @param string $input the input tag before modification
	* @param array $field
	* @param string $value
	* @param integer $lead_id
	* @param integer $form_id
	* @return string
	*/
	public function gformFieldInput($input, $field, $value, $lead_id, $form_id) {
		
		if ($field['type'] == GFCNP_FIELD_RECURRING) {
			// pick up the real value
			 $formfi = GFFormsModel::get_form_meta( $form_id); 
			 $valuesfi= array();
			 $nwrecbtnvw =2;

    	 	foreach( $formfi['fields'] as $fieldfi ) {
			$valuesfi[$fieldfi['id']] = array(
				'id'    => $fieldfi['id'],
				'label' => $fieldfi['label'],
				'type' => $fieldfi['type'],
				'gfcnp_payoptn' => $fieldfi['gfcnp_payoptn'],
				'value' => $lead[ $fieldfi['id'] ],
			);
          }

			
			/*foreach($valuesfi as $valuefi)
			{
			
				if(in_array("gfcnprecurring", $valuefi)) //old form
				{
				
					
				    if($valuefi['gfcnp_payoptn'] == "") {
					$nwrecbtnvw = 1;
					}
					else if(isset($valuefi['gfcnp_payoptn']) && $valuefi['gfcnp_payoptn'] != ""){
					$nwrecbtnvw = 2;  }
				}
				
				
			}
			if((count($valuesfi) == 0) ||( $nwrecbtnvw == 2))
			{*/
			
			$value    = rgpost('gfcnp_' . $field['id']);
		    $isrecurr = $_POST['gfcnp_rctyp_' . $field['id']];
		
			$isadmin = ( IS_ADMIN ) ? TRUE : FALSE;
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled' " : "";
			$css = isset($field['cssClass']) ? esc_attr($field['cssClass']) : '';
			
			$recurring_label = empty($value[3]) ? '' : $value[3];
			$Period          = empty($value[4]) ? '' : $value[4];
			$gfcnp_recurring_maxrecurrings_Installment  = $field['gfcnp_maxrecurrings_Installment'];
			$gfcnp_recurring_maxrecurrings_Subscription = $field['gfcnp_maxrecurrings_Subscription'];
			$input         = "<div class='$css' id='input_{$field['id']}'><table cellspacing='0' cellpadding='0' class='nothing'><tbody>";
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? " disabled='disabled'" : "";
			$isrecurring = empty($isrecurr) ? '' : ' checked';
		    $Paymentsoption_Lable = (isset($field['gfcnp_payoptn']) && empty($field['gfcnp_payoptn'])) ? 'Payment Options' : $field['gfcnp_payoptn'];
			$Paymentsoption_Lable = empty($Paymentsoption_Lable) ? 'Payment Options' : $Paymentsoption_Lable;
			
			if((isset($field['gfcnp_dfltpayoptn']) && $field['gfcnp_dfltpayoptn'] != "") && !$isadmin)
			{
				$input       .= "<tr class='trpayoptns'><td><lable>$Paymentsoption_Lable</label></td><td>";
		
			 $gfcnprectyps = (isset($field['gfcnp_dfltpayoptn']) && $_POST['gfcnp_rctyp_' . $field['id']]=="") ? $field['gfcnp_dfltpayoptn'] : $_POST['gfcnp_rctyp_' . $field['id']];
				$cnpotoval ="";$cnprecval="";$hidval="";
				if($gfcnprectyps == "One Time Only")
				{
				$cnpotoval ="checked";
				$hidval="no";
				}
				else if($gfcnprectyps == "Recurring")
				{
				$cnprecval="checked";
				$hidval="yes";
				}
					$input .="<input type='radio' class='recpayoptions' name='gfcnp_rctyp_{$field['id']}' id='gfcnp_rctyp_{$field['id']}' value='One Time Only' $cnpotoval onclick='manageshowhideRecpay();'> One Time Only";
						$input .= "<input name='gfp_onetimesku' id='gfp_onetimesku' type='hidden' value='".$field['txtsku_onetime']."' />";
			$input .= "<input name='gfp_{$field['id']}' id='gfp_{$field['id']}' type='hidden' value='{$hidval}' class='RecurringMethodSelectdeflt gfprctyp'/>";
		
			$input       .= "</td><td>";
			   $input .="<input type='radio' class='recpayoptions' name='gfcnp_rctyp_{$field['id']}' id='gfcnp_rctyp_{$field['id']}' $cnprecval value='Recurring' onclick='manageshowhideRecpay();'>Recurring"; 
					if(isset($field['gfcnp_dfltpayoptn']) && $field['gfcnp_dfltpayoptn']== "Recurring" && $field['gfcnp_payoptns_onetimeonly'] == '')
					{
					$input .= "<script type='text/javascript'>
			manageshowhideRecpayoptn1()
						function manageshowhideRecpayoptn1() {
						jQuery('tr.trpayoptns').hide();
						}
			</script>";
			}
			
			$input       .= "</td></tr>";
			
			}
		
			// Recurring Method
			
			 $Recurringtype_Lable = (isset($field['gfcnp_rectype']) && empty($field['gfcnp_rectype'])) ? 'Recurring Types' : $field['gfcnp_rectype'];
			 $Recurringtype_Lable = empty($Recurringtype_Lable) ? 'Recurring Types' : $Recurringtype_Lable;
			 
			
			if(isset($field['gfcnp_recurringtype_installment']) && $field['gfcnp_recurringtype_installment'] == 1 &&   $field['gfcnp_recurringtype_subscription'] == "")
			{
				 $Periods = array();
				$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Installment_label',
			);
				$Periods['Installment'] = 'Installment';
				$input       .= "<tr class='dvrecurtyp'><td><lable>$Recurringtype_Lable</label></td><td colspan=2>";
				$input 		 .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				$id           = $field['id'];
				$name         = key($this->RecurringMethod[0]);
				$value        = key($this->RecurringMethod[0][$name]);
				$input       .= "<b>".$value."</b>";
				$input       .= "<input name='gfcnp_{$id}_RecurringMethod' id='$name' type='hidden' value='$name|$value' class='RecurringMethodSelect'/>";	
				$input       .= "</td></tr>";
		   }
		
			
			if(isset($field['gfcnp_recurringtype_subscription']) && $field['gfcnp_recurringtype_subscription'] == 1 && $field['gfcnp_recurringtype_installment'] == "")
			{
			$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Subscription_label',
			);
			//$Periods = array('Subscription' => 'Subscription');
			$Periods = array();
				$Periods['Subscription'] = 'Subscription';
				$input       .= "<tr class='dvrecurtyp'><td><lable>$Recurringtype_Lable</label></td><td colspan=2>";
				$input .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				$id = $field['id'];
				$name = key($this->RecurringMethod[0]);
				$value = key($this->RecurringMethod[0][$name]);
				$input .= "<b>".$value."</b>";
				$input .= "<input name='gfcnp_{$id}_RecurringMethod' id='$name' type='hidden' value='$name|$value' class='RecurringMethodSelect'/>";	
				$input       .= "</td></tr>";
			}
				
			
			
			$selval = (isset($_POST['gfcnp_'.$field['id'].'_RecurringMethod'])) ? $_POST['gfcnp_'.$field['id'].'_RecurringMethod'] : '';
			
			if(count($this->RecurringMethod) == 0 && !$isadmin)
			{		
					
				$sub_field = array (
				'type' => 'Recurring Type',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Type',
				'value' => $Period,
				'label_class' => 'gfcnp_Subscription_label',
				);				
				$Periods = array();
				$Periods['Subscription'] = 'Subscription';
				$input .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				
				$sub_field = array (
				'type' => 'Recurring Type',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Type',
				'value' => $Period,
				'label_class' => 'gfcnp_Installment_label',
				);
				$Periods = array();				
				$Periods['Installment'] = 'Installment';
				$input .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				//print_r($this->RecurringMethod);
				$selval = (isset($_POST['gfcnp_'.$field['id'].'_RecurringMethod'])) ? $_POST['gfcnp_'.$field['id'].'_RecurringMethod'] : '';
				
				$id = $field['id'];
				$sid = 0;
				$input  .= "<div class='ginput_container RecurringMethod'><tr class='dvrecurtyp'><td>$Recurringtype_Lable</td><td colspan=2>";
				$input  .= "<select name='gfcnp_{$id}_RecurringMethod' class='$v RecurringMethodSelect' id='$field_id' onchange='manageRecpay();'>";
				foreach($this->RecurringMethod as $key => $val) { 
				$val_key = key($val);
				$val_value = key($val[key($val)]);
				$selval = (isset($field['gfcnp_dfltrectypoptn']) && $_POST['gfcnp_'.$field['id'].'_RecurringMethod']=="") ? $val_key.'|'.$field['gfcnp_dfltrectypoptn'] : $_POST['gfcnp_'.$field['id'].'_RecurringMethod'];
				if($selval == $val_key.'|'.$val_value) {
				$input .= "<option value='$val_key|$val_value' selected>$val_value</option>";
				}
				else
				{
				$input .= "<option value='$val_key|$val_value'>$val_value</option>";
				}
				//$input .= "<input type=radio name='gfcnp_{$id}_RecurringMethod' class='gfield_choice_radio' value='$val_key|$val_value'>$val_value";
				$sid++;
				}
				$input .= "</select>";
				$input .= "</td></tr></div>";
			}
			else if(
			        isset($field['gfcnp_recurringtype_subscription']) && $field['gfcnp_recurringtype_subscription'] == 1 && 
			        isset($field['gfcnp_recurringtype_installment']) && $field['gfcnp_recurringtype_installment'] == 1)
	
			{
				$input  .= "<div><tr><td>$Recurringtype_Lable</td></tr><tr><td colspan=2>";
			    $Periods = array();
				$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Installment_label',
			);
				$Periods['Installment'] = 'Installment (e.g. pay $1000 in 10 installments of $100 each)';
				
				$input 		 .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				$sub_field = array (
				'type' => 'Recurring Type',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Type',
				'value' => $Period,
				'label_class' => 'gfcnp_Subscription_label',
				);				
				$Periods = array();
				$Periods['Subscription'] = 'Subscription (e.g. pay $100 every month for 12 months)';
				$input .= $this->fieldCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				$input .= "</td></tr></div>";
				
			}
			
			// Periods
			if(!$isadmin)	
			{$sub_field = array (
				'type' => 'Periods',
				'id' => $field['id'],
				'sub_id' => '4',
				'label' => 'Payment Options',
				'value' => $Period,
				'label_class' => 'gfcnp_Periods_label',
			);//'One Time Only' => 'One Time Only',
			$Periods = array('Week' => 'Week', '2_Weeks' => '2 Weeks', 'Month' => 'Month', '2_Months' => '2 Months', 'Quarter' => 'Quarter', '6_Months' => '6 Months', 'Year' => 'Year');
			
			$selected_Periods = array();
			/*if(isset($field['gfcnp_One_Time_Only_setting']) && $field['gfcnp_One_Time_Only_setting'] == 1)
			$selected_Periods['One_Time_Only'] = 'One Time Only';*/
			if(isset($field['gfcnp_Week_setting']) && $field['gfcnp_Week_setting'] == 1)
			$selected_Periods['Week'] = 'Week|'.$field['txtsku_week'];
			if(isset($field['gfcnp_2_Weeks_setting']) && $field['gfcnp_2_Weeks_setting'] == 1)
			$selected_Periods['2_Weeks'] = '2 Weeks|'.$field['txtsku_tweeks'];
			if(isset($field['gfcnp_Month_setting']) && $field['gfcnp_Month_setting'] == 1)
			$selected_Periods['Month'] = 'Month|'.$field['txtsku_month'];
			if(isset($field['gfcnp_2_Months_setting']) && $field['gfcnp_2_Months_setting'] == 1)
			$selected_Periods['2_Months'] = '2 Months|'.$field['txtsku_tmonth'];
			if(isset($field['gfcnp_Quarter_setting']) && $field['gfcnp_Quarter_setting'] == 1)
			$selected_Periods['Quarter'] = 'Quarter|'.$field['txtsku_quarter'];
			if(isset($field['gfcnp_6_Months_setting']) && $field['gfcnp_6_Months_setting'] == 1)
			$selected_Periods['6_Months'] = '6 Months|'.$field['txtsku_smonths'];
			if(isset($field['gfcnp_Year_setting']) && $field['gfcnp_Year_setting'] == 1)
			$selected_Periods['Year'] = 'Year|'.$field['txtsku_year'];
			
		    $PeriodsLabel = (isset($field['gfcnp_periodicity']) && empty($field['gfcnp_periodicity'])) ? 'Periodicity' : $field['gfcnp_periodicity'];
			$PeriodsLabel = empty($PeriodsLabel) ? 'Periodicity' : $PeriodsLabel;
			
			$input       .= "<div class='paymentoptions'><tr class='dvperdcty'><td><lable>$PeriodsLabel</label></td><td colspan=2>";
			
			$input       .= $this->fieldCheckbox($sub_field, $selected_Periods, $lead_id, $form_id, '' , $isadmin); //$PeriodsLabel
		    $input       .= "</td><tr></div>";
			
			}
			$Noofpayments_Label = (isset($field['gfcnp_noofpayments']) && empty($field['gfcnp_noofpayments'])) ? 'Number of payments' : $field['gfcnp_noofpayments'];
			$Noofpayments_Label = empty($Noofpayments_Label) ? 'Number of payments' : $Noofpayments_Label;
			
			if(($field['gfcnp_numberofpayments'] != '') && (!$isadmin))
			{
			 $input .= "<tr class='dvnoofpymnts'><td><lable>$Noofpayments_Label</label></td><td colspan=2>";
			 $input .= "<input name='gfpnopaymntstyp' id='gfpnopaymntstyp' type='hidden' value='{$field['gfcnp_numberofpayments']}' class='RecurringMethodSelectdeflt'/>";
			}
			if(($field['gfcnp_numberofpayments'] == 'fixednumber') && (!$isadmin))
			{
			 	$input .= $field['gfcnp_dfltnoofpaymnts'];
				$input .= "<input name='gfpindefinite_{$field['id']}' id='gfpindefinite_{$field['id']}' type='hidden' value='no' class='RecurringMethodSelectdeflt'/>";
				$input .= "<input name='gfcnp_{$field['id']}[3]' id='$field_id' maxlength='3' type='hidden' value='$field[gfcnp_dfltnoofpaymnts]' class='{$field['label_class']} ginput_quantity $class' $tabindex $disabled_text />";
			}
			if(($field['gfcnp_numberofpayments'] == 'indefiniten') && (!$isadmin))
			{
			 	$input .= "Indefinite Recurring Only";
				$input .= "<input name='gfpindefinite_{$field['id']}' id='gfpindefinite_{$field['id']}' type='hidden' value='yes' class='RecurringMethodSelectdeflt'/>";
				$input .= "<input name='gfcnp_{$field['id']}[3]' id='$field_id' maxlength='3' type='hidden' value='999' class='{$field['label_class']} ginput_quantity $class' $tabindex $disabled_text />";
				//$input .= $this->inputText($sub_field, $installments, $lead_id, $form_id, 'style="display:none;"', !$isadmin);
			}
			if(($field['gfcnp_numberofpayments'] == 'openfield') && (!$isadmin))
			{
			 	$sub_field = array (
				'type' => 'openfield',
				'id' => $field['id'],
				'sub_id' => '3',
				'label' => '',
				'isRequired' => true,
				'size' => 'medium',
				'label_class' => 'gfcnp_recurring_label',
			    );
			
				$instal = $field['gfcnp_dfltnoofpaymnts'];
				$rcpostval = $_POST['gfcnp_' . $field['id']]; 
				$installments = (isset($rcpostval[3])) ? $rcpostval[3] : $instal;
				$input .= "<input name='gfpindefinite_{$field['id']}' id='gfpindefinite_{$field['id']}' type='hidden' value='no' class='RecurringMethodSelectdeflt'/>";
				$input .= $this->inputText($sub_field, $installments, $lead_id, $form_id, '', !$isadmin);
			}
			if(($field['gfcnp_numberofpayments'] == 'indefinite_openfield') && (!$isadmin))
			{
			 	$sub_field = array (
				'type' => 'indefinite_openfield',
				'id' => $field['id'],
				'sub_id' => '3',
				'label' => '',
				'isRequired' => true,
				'size' => 'medium',
				'label_class' => 'gfcnp_recurring_label',
			    );
			
				$instal = $field['gfcnp_dfltnoofpaymnts'];
				$rcpostval = $_POST['gfcnp_' . $field['id']]; 
				$installments = (isset($rcpostval[3])) ? $rcpostval[3] : $instal;
				
				$input .= "<input name='gfpindefinite_{$field['id']}' id='gfpindefinite_{$field['id']}' type='hidden' value='no' class='RecurringMethodSelectdeflt'/>";
				$input .= $this->inputText($sub_field, $installments, $lead_id, $form_id, '', !$isadmin);
			}
			if(($field['gfcnp_numberofpayments'] != '') && (!$isadmin))
			{
				$input .= "</td></tr>";
			}
			
		
			$input .= "<script type='text/javascript'>
			manageshowhideRecpay();manageRecpay();
				function manageshowhideRecpay() {

					if(jQuery('.recpayoptions:checked').val() == 'One Time Only') {	
						jQuery('tr.dvperdcty').hide();
						jQuery('tr.dvrecurtyp').hide();
						jQuery('tr.dvnoofpymnts').hide();
						jQuery('.gfprctyp').val('no');
						
					} else {	
						jQuery('tr.dvperdcty').show();
					    jQuery('tr.dvrecurtyp').show();	
						jQuery('tr.dvnoofpymnts').show();
						jQuery('.gfprctyp').val('yes');
						
						}
						manageRecpay();
					}
					
					function manageRecpay() { 
					if(jQuery('.recpayoptions:checked').val() == 'Recurring') {
					
					 if(jQuery('.RecurringMethodSelect').length > 0) { 
					
					  if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment' && jQuery('#gfpnopaymntstyp').val() == 'indefinite_openfield') {
					 		
					if(jQuery('.txtinputval').val() != '' && jQuery('.txtinputval').val() <= 998){jQuery('.txtinputval').val(jQuery('.txtinputval').val());}else{jQuery('.txtinputval').val('998');}
						
						} 
						if(jQuery('.RecurringMethodSelect').val().substr(-12) == 'Subscription' && jQuery('#gfpnopaymntstyp').val() == 'indefinite_openfield') {
							if(jQuery('.txtinputval').val() != ''){jQuery('.txtinputval').val(jQuery('.txtinputval').val());}else{jQuery('.txtinputval').val('999');}			
						} 
					}
					}
					}
			</script>";
			
					
			// concatenated value added to database
			$sub_field = array (
				'type' => 'hidden',
				'id' => $field['id'],
				'isRequired' => true,
			);
			$input .= $this->fieldConcatenated($sub_field, $interval_type, $lead_id, $form_id);

			$input .= "</tbody></table></div>";
		/*}
		else
		{
			
			
			$value = rgpost('gfcnp_' . $field['id']);
			$isrecurr = $_POST['gfp_' . $field['id']];
			$isadmin = ( IS_ADMIN ) ? TRUE : FALSE;
		
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled' " : "";
			$css = isset($field['cssClass']) ? esc_attr($field['cssClass']) : '';
			
			$recurring_label = empty($value[3]) ? '' : $value[3];
			$Period = empty($value[4]) ? '' : $value[4];
			$gfcnp_recurring_maxrecurrings_Installment = $field['gfcnp_maxrecurrings_Installment'];
			$gfcnp_recurring_maxrecurrings_Subscription = $field['gfcnp_maxrecurrings_Subscription'];
$css_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "ginput_container ginput_container_checkbox" : "ginput_complex ginput_container gfcnp_recurring_complex";
			$input = "<div class='$css_text $css' id='input_{$field['id']}'>";
			$isrecurring = empty($isrecurr) ? '' : ' checked';
			$isthislable = (isset($field['gfcnp_label_isthisrecurring']) && empty($field['gfcnp_label_isthisrecurring'])) ? 'Is this a recurring payment' : $field['gfcnp_label_isthisrecurring'];
			$isthislable = empty($isthislable) ? 'Is this a recurring payment' : $isthislable;
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? " disabled='disabled'" : "";
			$input .= "<input type='checkbox' name='gfp_{$field['id']}' id='gfp_{$field['id']}' class='recurring_checkbox'$isrecurring$disabled_text>&nbsp;&nbsp;$isthislable <br>";
			
			// Recurring Method
			$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Installment_label',
			);
			$Periods = array();
			if(isset($field['Installment']) && $field['Installment'] == 1)
			$Periods['Installment'] = 'Installment';
			$input .= $this->fieldoCheckbox($sub_field, $Periods, $lead_id, $form_id, 'Recurring Type', $isadmin);
			
			$sub_field = array (
				'type' => 'gfcnp_recurring_maxrecurrings_Installment',
				'id' => $field['id'],
				'sub_id' => '3',
				'label' => 'Max. Recurrings Allowed',
				'isRequired' => true,
				'size' => 'small',
				'label_class' => 'gfcnp_recurring_Installment_label',
			);
			$input .= $this->inputoText($sub_field, $gfcnp_recurring_maxrecurrings_Installment, $lead_id, $form_id, 'style="display:none;"', $isadmin);
			
			$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Subscription_label',
			);
			//$Periods = array('Subscription' => 'Subscription');
			$Periods = array();
			if(isset($field['Subscription']) && $field['Subscription'] == 1)
			$Periods['Subscription'] = 'Subscription';
			
		
			$input .= $this->fieldoCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
			
			$sub_field = array (
				'type' => 'gfcnp_recurring_maxrecurrings_Subscription',
				'id' => $field['id'],
				'sub_id' => '3',
				'label' => 'Max. Recurrings Allowed',
				'isRequired' => true,
				'size' => 'small',
				'label_class' => 'gfcnp_recurring_Subscription_label',
			);
			$input .= $this->inputoText($sub_field, $gfcnp_recurring_maxrecurrings_Subscription, $lead_id, $form_id, 'style="display:none;"', $isadmin);
			
			$selval = (isset($_POST['gfcnp_'.$field['id'].'_RecurringMethod'])) ? $_POST['gfcnp_'.$field['id'].'_RecurringMethod'] : '';
			
			if(count($this->RecurringMethod) > 1)
			{
				$id = $field['id'];
				$sid = 0;
				$RecurringMethod_Label = (isset($field['gfcnp_label_RecurringMethod']) && empty($field['gfcnp_label_RecurringMethod'])) ? 'Recurring Type' : $field['gfcnp_label_RecurringMethod'];
				$RecurringMethod_Label = empty($RecurringMethod_Label) ? 'Recurring Type' : $RecurringMethod_Label;
				$input  .= "<div class='ginput_container RecurringMethod'>$RecurringMethod_Label<ul class='gfield_checkbox'>";
				$input .= "<select name='gfcnp_{$id}_RecurringMethod' class='$v RecurringMethodSelect' id='$field_id' >";
				//asort($this->RecurringMethod)
				foreach($this->RecurringMethod as $key => $val) { 
				$val_key = key($val);
				$val_value = key($val[key($val)]);
				if($selval == $val_key.'|'.$val_value) {
				$input .= "<option value='$val_key|$val_value' selected>$val_value</option>";
				} else {
				$input .= "<option value='$val_key|$val_value'>$val_value</option>";
				}
				$sid++;
				}
				$input .= "</select>";
				$input .= "</ul></div>";
			}
			else if(count($this->RecurringMethod) == 0 && !$isadmin)
			{				
				$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Subscription_label',
				);				
				$Periods = array();
				$Periods['Subscription'] = 'Subscription';
				$input .= $this->fieldoCheckbox($sub_field, $Periods, $lead_id, $form_id, '', $isadmin);
				
				$sub_field = array (
				'type' => 'Recurring Method',
				'id' => $field['id'],
				'sub_id' => '5',
				'label' => 'Recurring Method',
				'value' => $Period,
				'label_class' => 'gfcnp_Installment_label',
				);
				$Periods = array();				
				$Periods['Installment'] = 'Installment';
				$input .= $this->fieldoCheckbox($sub_field, $Periods, $lead_id, $form_id, 'Recurring Type', $isadmin);
				
				$id = $field['id'];
				$sid = 0;
				$input  .= "<div class='ginput_container RecurringMethod'>Recurring Type<ul class='gfield_checkbox'>";
				$input .= "<select name='gfcnp_{$id}_RecurringMethod' class='$v RecurringMethodSelect' id='$field_id' >";
				foreach($this->RecurringMethod as $key => $val) { 
				$val_key = key($val);
				$val_value = key($val[key($val)]);
				$input .= "<option value='$val_key|$val_value'>$val_value</option>";
				$sid++;
				}
				$input .= "</select>";
				$input .= "</ul></div>";
			}
			else
			{
				$id = $field['id'];
				$name = key($this->RecurringMethod[0]);
				$value = key($this->RecurringMethod[0][$name]);
				$input  .= "<div class='ginput_container RecurringMethod'>Recurring Type : ";
				$input .= "<b>".$value."</b>";
				$input .= "</div>";
				$input .= "<input name='gfcnp_{$id}_RecurringMethod' id='$name' type='hidden' value='$name|$value' class='RecurringMethodSelect'/>";	
			}
			
			// Periods
			$sub_field = array (
				'type' => 'Periods',
				'id' => $field['id'],
				'sub_id' => '4',
				'label' => 'Periodicity',
				'value' => $Period,
				'label_class' => 'gfcnp_Periods_label',
			);
			$Periods = array('Week' => 'Week', '2_Weeks' => '2 Weeks', 'Month' => 'Month', '2_Months' => '2 Months', 'Quarter' => 'Quarter', '6_Months' => '6 Months', 'Year' => 'Year');
			
			$selected_Periods = array();
			if(isset($field['gfcnp_Week_setting']) && $field['gfcnp_Week_setting'] == 1)
			$selected_Periods['Week'] = 'Week';
			if(isset($field['gfcnp_2_Weeks_setting']) && $field['gfcnp_2_Weeks_setting'] == 1)
			$selected_Periods['2_Weeks'] = '2 Weeks';
			if(isset($field['gfcnp_Month_setting']) && $field['gfcnp_Month_setting'] == 1)
			$selected_Periods['Month'] = 'Month';
			if(isset($field['gfcnp_2_Months_setting']) && $field['gfcnp_2_Months_setting'] == 1)
			$selected_Periods['2_Months'] = '2 Months';
			if(isset($field['gfcnp_Quarter_setting']) && $field['gfcnp_Quarter_setting'] == 1)
			$selected_Periods['Quarter'] = 'Quarter';
			if(isset($field['gfcnp_6_Months_setting']) && $field['gfcnp_6_Months_setting'] == 1)
			$selected_Periods['6_Months'] = '6 Months';
			if(isset($field['gfcnp_Year_setting']) && $field['gfcnp_Year_setting'] == 1)
			$selected_Periods['Year'] = 'Year';
			$PeriodsLabel = (isset($field['gfcnp_label_periods']) && empty($field['gfcnp_label_periods'])) ? 'Periodicity' : $field['gfcnp_label_periods'];
			$PeriodsLabel = empty($PeriodsLabel) ? 'Periodicity' : $PeriodsLabel;
			
			$input .= $this->fieldoCheckbox($sub_field, $selected_Periods, $lead_id, $form_id, $PeriodsLabel, $isadmin);
			
			if($field['indefinite'] == 1) {
			$gfpindefinite = empty($_POST['gfpindefinite_' . $field['id']]) ? '' : ' checked';
			
			$IndefiniteRecurring_Lable = (isset($field['gfcnp_label_IndefiniteRecurring']) && empty($field['gfcnp_label_IndefiniteRecurring'])) ? 'Indefinite Recurring' : $field['gfcnp_label_IndefiniteRecurring'];
			$IndefiniteRecurring_Lable = empty($IndefiniteRecurring_Lable) ? 'Indefinite Recurring' : $IndefiniteRecurring_Lable;
			
			$input .= "<span class='indefinitespan'><input type='checkbox' class='indefinite' name='gfpindefinite_{$field['id']}' id='gfpindefinite_{$field['id']}'$disabled_text$gfpindefinite>&nbsp;&nbsp;$IndefiniteRecurring_Lable</span><br>";
			$input .= "<script type='text/javascript'>
				manageshowhide();
				function manageshowhide() {
					if(jQuery('.RecurringMethodSelect').length > 0) {
						if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
							jQuery('.indefinitespan').hide();						
						} else {
							jQuery('.indefinitespan').show();
						}
					}
					
					if(jQuery('.indefinite').length > 0) {
						if(jQuery('.indefinite').is(':checked')) {
							jQuery('.gfcnp_recurring_label').val('');
							jQuery('.gfcnp_recurring_label').hide();
							jQuery('.gfcnp_recurring_label').prop('readonly', true);
						}
						else {						
							jQuery('.gfcnp_recurring_label').show();
							jQuery('.gfcnp_recurring_label').prop('readonly', false);
						}
					}
					
				}
				jQuery('.RecurringMethodSelect').change(function(){					
					if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
						jQuery('.indefinite').prop('checked', false);
						jQuery('.indefinitespan').hide();						
					} else {
						jQuery('.indefinitespan').show();
					}
					
					//alert(jQuery('.indefinite').is(':checked'));
					if(jQuery('.indefinite').length > 0) {
						if(jQuery('.indefinite').is(':checked')) {
							jQuery('.gfcnp_recurring_label').val('');
							jQuery('.gfcnp_recurring_label').hide();
							jQuery('.gfcnp_recurring_label').prop('readonly', true);
						}
						else {						
							jQuery('.gfcnp_recurring_label').show();
							jQuery('.gfcnp_recurring_label').prop('readonly', false);
						}
					}
					
				});
				jQuery('.indefinite').click(function(){
					if(jQuery('.indefinite').is(':checked')) {
					jQuery('.gfcnp_recurring_label').val('');
						jQuery('.gfcnp_recurring_label').hide();
						jQuery('.gfcnp_recurring_label').prop('readonly', true);						
					}
					else {						
						jQuery('.gfcnp_recurring_label').show();
						jQuery('.gfcnp_recurring_label').prop('readonly', false);						
						}
				});
			</script>";
			}
			
			$gfcnp_label_installments = (isset($field['gfcnp_label_installments']) && empty($field['gfcnp_label_installments'])) ? '# of Installments' : $field['gfcnp_label_installments'];
			$sub_field = array (
				'type' => 'donation',
				'id' => $field['id'],
				'sub_id' => '3',
				'label' => $gfcnp_label_installments,
				'isRequired' => true,
				'size' => 'medium',
				'label_class' => 'gfcnp_recurring_label',
			);
			
			$instal = $_POST['gfcnp_'.$field['id']];
			$instal = $instal[3];
			$installments = empty($instal) ? '' : $instal;
			$input .= $this->inputoText($sub_field, $installments, $lead_id, $form_id, '', $isadmin);
			
			

			
			// concatenated value added to database
			$sub_field = array (
				'type' => 'hidden',
				'id' => $field['id'],
				'isRequired' => true,
			);
			$input .= $this->fieldConcatenated($sub_field, $interval_type, $lead_id, $form_id);

			$input .= "</div>";
			
			
			
		}*/

		return $input;
	}

}	

	/**
	* get HTML for input and label for donation (amount) field
	* @param array $field
	* @param string $value
	* @param integer $lead_id
	* @param integer $form_id
	* @return string
	*/
	protected function inputText($field, $value="", $lead_id=0, $form_id=0, $style='', $isadmin=TRUE) {
		$id = $field["id"];
		$sub_id = $field["sub_id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "gfcnp_{$id}_{$sub_id}" : "gfcnp_{$form_id}_{$id}_{$sub_id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$size = rgar($field, "size");
		$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
		//$isadmin = ( IS_ADMIN ) ? TRUE : FALSE;
		$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
		$class = $size . $class_suffix;

		$tabindex = GFCommon::get_tabindex();
		//~ $logic_event = GFCommon::get_logic_event($field, "keyup");

		$spanClass = '';
		if (!empty($field['hidden'])) {
			$spanClass = 'gf_hidden';
		}

		$value = esc_attr($value);		
		$class = esc_attr($class);	

		$label = htmlspecialchars($field['label']);

		if($isadmin) {
		//$input  = "<span class='gfcnp_recurring_left'>";
		$input = "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label</label>";	
		
		$input .= "<input name='gfcnp_{$id}[{$sub_id}]' id='$field_id' type='text' maxlength='3' value='$value' class='{$field['label_class']} ginput_quantity $class txtinputval' $tabindex $disabled_text />";
			
		//$input .= "</span>";
		} else {
			//if(in_array($field['label_class'], array('gfcnp_recurring_label'))) { $spanClass' $style
			$input  = "<span class='gfcnp_recurring_leftd'>";
			$input .= "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label</label>";
			$input .= "<input name='gfcnp_{$id}[{$sub_id}]' id='$field_id' maxlength='3' type='text' value='$value' class='{$field['label_class']} ginput_quantity $class txtinputval' $tabindex $disabled_text />";		
			$input .= "</span>";
			//}
		}

		return $input;
	}
	
	protected function fieldCheckbox($field, $value="", $lead_id=0, $form_id=0, $title='', $isadmin=TRUE) {
	//
	
		$id = $field["id"];
		$sub_id = $field["sub_id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "gfcnp_{$id}_{$sub_id}" : "gfcnp_{$form_id}_{$id}_{$sub_id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$size = rgar($field, "size");
		$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
		$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
		$class = $size . $class_suffix;

		$tabindex = GFCommon::get_tabindex();
		//~ $logic_event = GFCommon::get_logic_event($field, "keyup");

		$spanClass = '';
		$inputClass = array($size . $class_suffix);
		if (!empty($field['hidden'])) {
			$spanClass = 'gf_hidden';
		}
		if (empty($field['hidden'])) {
			$inputClass[] = 'datepicker';
		}
		else {
			$spanClass[] = 'gf_hidden';
		}
		//$value = esc_attr($value);
		$class = esc_attr($class);
		$inputClass = esc_attr(implode(' ', $inputClass));
		$label = htmlspecialchars($field['label']);
		$sid = $sub_id;

		if($isadmin) {

		$input  = "<div class='ginput_container ginput_container_checkbox'>$title<ul class='gfield_checkbox'>";
		foreach($value as $v) { 
		$input .= "<li><input name='gfcnp_{$id}[{$sid}]' class='$v' id='$field_id' type='checkbox' checked value='gfcnp_{$id}[{$sid}]|$v'  $tabindex $disabled_text/><label>$v</label></li>";
		$sid++;
		}
		$input .= "</ul></div>";
		} else {
	
				if(in_array($field['label_class'], array('gfcnp_Periods_label'))) 
				{
					//$Periods = unserialize($this->plugin->options['Periods']);
					 $Periods = $value;
					if(count($Periods) == 0 && !$isadmin)
					{
						$Periods = array('Week' => 'Week', '2_Weeks' => '2 Weeks', 'Month' => 'Month', '2_Months' => '2 Months', 'Quarter' => 'Quarter', '6_Months' => '6 Months', 'Year' => 'Year');
						$selected = $_POST['gfcnp_'.$id];
					
						$input  = "<div class='ginput_container ginput_container_checkbox'><tr><td>$title</td><td><ul class='gfield_checkbox'><select name='gfcnp_{$id}[{$sid}]' class='".$field['label_class']."' id='$field_id' >";
						foreach($Periods as $v) { 
						$varr = explode("|",$v);
						if($selected[4] == 'gfcnp_'.$id.'['.$sid.']|'.$v) {
						
					    $input .= "<option value='gfcnp_{$id}[{$sid}]|$v' selected>$varr[0]</option>";
						
						} else {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v'>$varr[0]</option>";
						
						}
						$sid++;
						}
						
						$input .= "</select></ul></td></tr></div>";
					}
					else if(count($Periods) > 1) 
					{
					
						$selected = $_POST['gfcnp_'.$id];
						
						$input  = "<ul class='gfield_checkbox'><select name='gfcnp_{$id}[{$sid}]' class='".$field['label_class']."' id='$field_id' >";
						foreach($Periods as $v) {
						$varr = explode("|",$v);
						if($selected[4] == 'gfcnp_'.$id.'['.$sid.']|'.$v) {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v' selected>$varr[0]</option>";
					
						} else {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v'>$varr[0]</option>";
						
						}
						$sid++;
						}
					
						$input .= "</ul>";
					}
					else
					{
					
						$keys = array_values($Periods);
						if($_POST['gfcnp_'.$id.'_Periodicity'])
						{
						  $selectedarr = $_POST['gfcnp_'.$id.'_Periodicity'];
						  $varrarr        = explode("|",$selectedarr);
						  $selected      = $varrarr[0];
						 
						}
						else
						{
						  $selectedarr = $keys[0];
						  $varrarr        = explode("|",$selectedarr);
						  $selected      = $varrarr[0];
						}
					
						$input .= "<b>".$selected."</b>";
					$input .= "<input type='hidden' name='gfcnp_{$id}[{$sid}]' value='".$selected."'>";
					}
				}
				
				if(in_array($field['label_class'], array('gfcnp_Installment_label', 'gfcnp_Subscription_label'))) 
				{
					$options = $value; 
					
					if(in_array(key($value), $options)) {
					 
					  $this->RecurringMethod[]['gfcnp_'.$id.'['.$sid.']'] = $value;	
					
					}
				
				}
				
		}

		return $input;
	}
	protected function inputoText($field, $value="", $lead_id=0, $form_id=0, $style='', $isadmin=TRUE) {
		$id = $field["id"];
		$sub_id = $field["sub_id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "gfcnp_{$id}_{$sub_id}" : "gfcnp_{$form_id}_{$id}_{$sub_id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$size = rgar($field, "size");
		$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
		//$isadmin = ( IS_ADMIN ) ? TRUE : FALSE;
		$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
		$class = $size . $class_suffix;

		$tabindex = GFCommon::get_tabindex();
		//~ $logic_event = GFCommon::get_logic_event($field, "keyup");

		$spanClass = '';
		if (!empty($field['hidden'])) {
			$spanClass = 'gf_hidden';
		}

		$value = esc_attr($value);		
		$class = esc_attr($class);	

		$label = htmlspecialchars($field['label']);

		if($isadmin) {
		$input  = "<span class='gfcnp_recurring_left $spanClass' $style>";
		$input .= "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label</label>";	
		
		$input .= "<input name='gfcnp_{$id}[{$sub_id}]' id='$field_id' type='text' value='$value' class='{$field['label_class']} ginput_amount $class' $tabindex $disabled_text />";
			
		$input .= "</span>";
		} else {
			if(in_array($field['label_class'], array('gfcnp_recurring_label'))) {
			$input  = "<span class='gfcnp_recurring_left $spanClass' $style>";
			$input .= "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label</label>";
			$input .= "<input name='gfcnp_{$id}[{$sub_id}]' id='$field_id' maxlength='3' type='text' value='$value' class='{$field['label_class']} ginput_quantity $class' $tabindex $disabled_text />";		
			$input .= "</span>";
			}
		}

		return $input;
	}
	
	protected function fieldoCheckbox($field, $value="", $lead_id=0, $form_id=0, $title='', $isadmin=TRUE) {
		$id = $field["id"];
		$sub_id = $field["sub_id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "gfcnp_{$id}_{$sub_id}" : "gfcnp_{$form_id}_{$id}_{$sub_id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$size = rgar($field, "size");
		$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
		$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
		$class = $size . $class_suffix;

		$tabindex = GFCommon::get_tabindex();
		//~ $logic_event = GFCommon::get_logic_event($field, "keyup");

		$spanClass = '';
		$inputClass = array($size . $class_suffix);
		if (!empty($field['hidden'])) {
			$spanClass = 'gf_hidden';
		}
		if (empty($field['hidden'])) {
			$inputClass[] = 'datepicker';
		}
		else {
			$spanClass[] = 'gf_hidden';
		}
		//$value = esc_attr($value);
		$class = esc_attr($class);
		$inputClass = esc_attr(implode(' ', $inputClass));
		$label = htmlspecialchars($field['label']);
		$sid = $sub_id;
		
		if($isadmin) {
		$input  = "<div class='ginput_container'>$title<ul class='gfield_checkbox'>";
		foreach($value as $v) { 
		$input .= "<li><input name='gfcnp_{$id}[{$sid}]' class='$v' id='$field_id' type='checkbox' checked value='gfcnp_{$id}[{$sid}]|$v'  $tabindex $disabled_text/><label>$v</label></li>";
		$sid++;
		}
		$input .= "</ul></div>";
		} else {
			
				if(in_array($field['label_class'], array('gfcnp_Periods_label'))) 
				{
					
					$Periods = $value;
				
					if(count($Periods) == 0 && !$isadmin)
					{
						$Periods = array('Week' => 'Week', '2_Weeks' => '2 Weeks', 'Month' => 'Month', '2_Months' => '2 Months', 'Quarter' => 'Quarter', '6_Months' => '6 Months', 'Year' => 'Year');
						$selected = $_POST['gfcnp_'.$id];
						$input  = "<div class='ginput_container'>$title<ul class='gfield_checkbox'><select name='gfcnp_{$id}[{$sid}]' class='".$field['label_class']."' id='$field_id' >";
						foreach($Periods as $v) { 
						if($selected[4] == 'gfcnp_'.$id.'['.$sid.']|'.$v) {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v' selected>$v</option>";
						} else {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v'>$v</option>";
						}
						$sid++;
						}
						$input .= "</select></ul></div>";
					}
					else if(count($Periods) > 1) 
					{
						$selected = $_POST['gfcnp_'.$id];
						$input  = "<div class='ginput_container'>$title<ul class='gfield_checkbox'><select name='gfcnp_{$id}[{$sid}]' class='".$field['label_class']."' id='$field_id' >";
						foreach($Periods as $v) { 
						if($selected[4] == 'gfcnp_'.$id.'['.$sid.']|'.$v) {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v' selected>$v</option>";
						} else {
						$input .= "<option value='gfcnp_{$id}[{$sid}]|$v'>$v</option>";
						}
						$sid++;
						}
						$input .= "</select></ul></div>";
					}
					else
					{
						$keys = array_values($Periods);
						if($_POST['gfcnp_'.$id.'_Periodicity'])
						$selected = $_POST['gfcnp_'.$id.'_Periodicity'];
						else
						$selected = $keys[0];
						$input .= "<input type='hidden' name='gfcnp_{$id}[{$sid}]' value='".$selected."'>";
					}
				}
				
				if(in_array($field['label_class'], array('gfcnp_Installment_label', 'gfcnp_Subscription_label'))) 
				{
					
					$options = $value;
					if(in_array(key($value), $options)) {
					$this->RecurringMethod[]['gfcnp_'.$id.'['.$sid.']'] = $value;
					}
					
				}
		}

		return $input;
	}
	/**
	* get HTML for input and label for Interval Type field
	* @param array $field
	* @param string $value
	* @param integer $lead_id
	* @param integer $form_id
	* @return string
	*/
	protected function fieldIntervalType($field, $value="", $lead_id=0, $form_id=0) {
		$id = $field["id"];
		$sub_id = $field["sub_id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "gfcnp_{$id}_{$sub_id}" : "gfcnp_{$form_id}_{$id}_{$sub_id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$size = rgar($field, "size");
		$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? "disabled='disabled'" : "";
		$class_suffix = RG_CURRENT_VIEW == "entry" ? "_admin" : "";
		$class = $size . $class_suffix;

		$tabindex = GFCommon::get_tabindex();

		$spanClass = '';
		if (!empty($field['hidden'])) {
			$spanClass = 'gf_hidden';
		}

		$class = esc_attr($class);

		$label = htmlspecialchars($field['label']);

		$periods = apply_filters('gfcnp_recurring_periods', array('weekly', 'fortnightly', 'monthly', 'quarterly', 'yearly'), $form_id, $field);
		if (count($periods) == 1) {
			// build a hidden field and label
			$input  = "<span class='gfcnp_recurring_left $spanClass'>";
			$input .= "<input type='hidden' name='gfcnp_{$id}[{$sub_id}]' value='{$periods[0]}' />";
			$input .= "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label: {$periods[0]}</label>";
			$input .= "</span>";
		}
		else {
			// build a drop-down list
			$opts = '';
			foreach ($periods as $period) {
				$opts .= "<option value='$period'";
				if ($period == $value)
					$opts .= " selected='selected'";
				$opts .= ">$period</option>";
			}

			$input  = "<span class='gfcnp_recurring_left $spanClass'>";
			$input .= "<select size='1' name='gfcnp_{$id}[{$sub_id}]' id='$field_id' $tabindex class='gfield_select $class' $disabled_text>$opts</select>";
			$input .= "<label class='{$field['label_class']}' for='$field_id' id='{$field_id}_label'>$label</label>";
			$input .= "</span>";
		}

		return $input;
	}

	/**
	* get HTML for hidden input with concatenated value for complex field
	* @param array $field
	* @param string $value
	* @param integer $lead_id
	* @param integer $form_id
	* @return string
	*/
	protected function fieldConcatenated($field, $value="", $lead_id=0, $form_id=0) {
		$id = $field["id"];
		$field_id = IS_ADMIN || $form_id == 0 ? "input_{$id}" : "input_{$form_id}_{$id}";
		$form_id = IS_ADMIN && empty($form_id) ? rgget("id") : $form_id;

		$input = "<input type='hidden' name='input_{$id}' id='$field_id' />";
		
		if(IS_ADMIN) {
		$input .= "<script type='text/javascript'>
			/*jQuery(document).ready(function(){
				jQuery('#RecurringButton').hide();
			});		
			
			jQuery('.Installment').click(function(){
				if(jQuery('.Installment').is(':checked'))
				jQuery('.gfcnp_recurring_Installment_label').closest('span').show();
				else
				jQuery('.gfcnp_recurring_Installment_label').closest('span').hide();
			});
			
			jQuery('.Subscription').click(function(){
				if(jQuery('.Subscription').is(':checked'))
				jQuery('.gfcnp_recurring_Subscription_label').closest('span').show();
				else
				jQuery('.gfcnp_recurring_Subscription_label').closest('span').hide();
			});*/
		
		</script>";
		} else {
		
		$formfc = GFFormsModel::get_form_meta( $form_id); 
			 $valuesfc= array();
			$nwrecbtnfc =2;
    	 	
    	 	foreach( $formfc['fields'] as $fieldfc ) {
			$valuesfc[$fieldfc['id']] = array(
				'id'    => $fieldfc['id'],
				'label' => $fieldfc['label'],
				'type' => $fieldfc['type'],
				'gfcnp_payoptn' => $fieldfc['gfcnp_payoptn'],
				'value' => $lead[ $fieldfc['id'] ],
			);
          }

			
			foreach($valuesfc as $valuefc)
			{
			
				if(in_array("gfcnprecurring", $valuefc)) //old form
				{
						
				    if($valuefc['gfcnp_payoptn'] == "") {
					$nwrecbtnfc = 1;
					}
					else if(isset($valuefc['gfcnp_payoptn']) && $valuefc['gfcnp_payoptn'] != ""){
					$nwrecbtnfc = 2;}
				}
				
				
			}
			if((count($valuefc) == 0) ||( $nwrecbtnfc == 2))
			{
				
		
			$input .= "<script type='text/javascript'>
				jQuery(document).ready(function(){
					
					togglerecurring();
					
					function togglerecurring()
					{	
						
						if(jQuery('.recpayoptions').is(':checked')) {	
							jQuery('.indefinite').closest('span').show();
							jQuery('.gfcnp_recurring_label').closest('span').show();
							jQuery('.gfcnp_Periods_label').closest('div').show();
							jQuery('.RecurringMethod').show();
							
							if(jQuery('.RecurringMethodSelect').length > 0) {
								if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
									jQuery('.indefinitespan').hide();
								} else {
									if(jQuery('.indefiniten').length > 0) {
										if(jQuery('.indefinite').is(':checked')) {
											jQuery('.gfcnp_recurring_label').val('');
											jQuery('.gfcnp_recurring_label').hide();
											jQuery('.gfcnp_recurring_label').prop('readonly', true);
										}
										else {						
											jQuery('.gfcnp_recurring_label').show();
											jQuery('.gfcnp_recurring_label').prop('readonly', false);
										}
									} else {
										jQuery('.indefinitespan').show();
									}
								}
							}
							
						} else {
							jQuery('.indefinite').closest('span').hide();
							jQuery('.gfcnp_recurring_label').closest('span').hide();
							jQuery('.gfcnp_Periods_label').closest('div').hide();
							jQuery('.RecurringMethod').hide();
							
							if(jQuery('.RecurringMethodSelect').length > 0) {
								if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
									jQuery('.indefinitespan').hide();
								} else {
									jQuery('.indefinitespan').hide();
								}
							}							
						}
					}
				});			
			</script>";
			}
			else
			{
			$input .= "<script type='text/javascript'>
				jQuery(document).ready(function(){
					
					togglerecurring();
					jQuery('.recurring_checkbox').click(function(){
						 togglerecurring();
					});
					
					function togglerecurring()
					{						
						if(jQuery('.recurring_checkbox').is(':checked')) {
							jQuery('.indefinite').closest('span').show();
							jQuery('.gfcnp_recurring_label').closest('span').show();
							jQuery('.gfcnp_Periods_label').closest('div').show();
							jQuery('.RecurringMethod').show();
							
							if(jQuery('.RecurringMethodSelect').length > 0) {
								if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
									jQuery('.indefinitespan').hide();
								} else {
									if(jQuery('.indefinite').length > 0) {
										if(jQuery('.indefinite').is(':checked')) {
											jQuery('.gfcnp_recurring_label').val('');
											jQuery('.gfcnp_recurring_label').hide();
											jQuery('.gfcnp_recurring_label').prop('readonly', true);
										}
										else {						
											jQuery('.gfcnp_recurring_label').show();
											jQuery('.gfcnp_recurring_label').prop('readonly', false);
										}
									} else {
										jQuery('.indefinitespan').show();
									}
								}
							}
							
						} else {
							jQuery('.indefinite').closest('span').hide();
							jQuery('.gfcnp_recurring_label').closest('span').hide();
							jQuery('.gfcnp_Periods_label').closest('div').hide();
							jQuery('.RecurringMethod').hide();
							
							if(jQuery('.RecurringMethodSelect').length > 0) {
								if(jQuery('.RecurringMethodSelect').val().substr(-11) == 'Installment') {
									jQuery('.indefinitespan').hide();
								} else {
									jQuery('.indefinitespan').hide();
								}
							}							
						}
					}
				});			
			</script>";
			
		}
}
		return $input;
	}

	/**
	* safe checkdate function that verifies each component as numeric and not empty, before calling PHP's function
	* @param string $month
	* @param string $day
	* @param string $year
	* @return boolean
	*/
	protected static function checkdate($month, $day, $year) {
		if (empty($month) || !is_numeric($month) || empty($day) || !is_numeric($day) || empty($year) || !is_numeric($year) || strlen($year) != 4)
			return false;

		return checkdate($month, $day, $year);
	}

	/**
	* get input values for recurring payments field
	* @param integer $field_id
	* @return array
	*/
	public static function getPost($field_id) {
	$recurring = rgpost('gfcnp_' . $field_id);
	if(isset($_POST['gfp_'.$field_id]) && $_POST['gfp_'.$field_id] == 'yes')
	{  

	 	 $recskusuffix ="";
	    if (is_array($recurring)) { 
			$Periodicity = explode('|', $recurring[4]);
			if(count($Periodicity) > 1){
			$recskusuffix = $Periodicity[2];
			$Periodicity  = $Periodicity[1];
			
			}
			else{
			$Periodicity = $recurring[4];}
			list($f, $RecurringMethod) = explode('|', $_POST['gfcnp_'.$field_id.'_RecurringMethod']);			
			$indefinite = 'no';
			
			if(isset($_POST['gfpindefinite_'.$field_id]) && $_POST['gfpindefinite_'.$field_id] == 'yes') 
			 {
				if($RecurringMethod == 'Subscription')
				$Installments = 999;
				else
				$Installments = 998;
				$indefinite = 'yes';
			}
			elseif(isset($recurring[3]) && $recurring[3])			
			$Installments = GFCommon::to_number($recurring[3]);
			else
			$Installments = 0;
		 
			if(isset($_POST['gfp_'.$field_id]) && $_POST['gfp_'.$field_id] == 'yes')
			$isRecurring = 'yes';
			else
			$isRecurring = 'no';
			$recurring = array (
				'Installments' => $Installments,
				'Periodicity' => $Periodicity,
				'SKUsuffix' => $recskusuffix,
				'RecurringMethod' => $RecurringMethod,
				'isRecurring' => $isRecurring,
				'indefinite' => $indefinite,
				
			);
			
		}
		else {
			$recurring = false;
		  }
		}
		else if(isset($_POST['gfp_'.$field_id]) && $_POST['gfp_'.$field_id] == 'no')
		{
			if(isset($_POST['gfp_onetimesku']) && $_POST['gfp_onetimesku'] != '')
				$onetimeSKUsuffix =  $_POST['gfp_onetimesku'];
				else
				$onetimeSKUsuffix = '';
				$recurring = array ('OnetimeSKU' => $onetimeSKUsuffix,);
		}
		else
		{
		if (is_array($recurring)) {
			$Periodicity = explode('|', $recurring[4]);
			if(count($Periodicity) > 1)
			$Periodicity = $Periodicity[1];
			else
			$Periodicity = $recurring[4];
			
			list($f, $RecurringMethod) = explode('|', $_POST['gfcnp_'.$field_id.'_RecurringMethod']);			
			$indefinite = 'no';
			if(isset($_POST['gfpindefinite_'.$field_id]) && $_POST['gfpindefinite_'.$field_id] == 'on') {
				if($RecurringMethod == 'Subscription')
				$Installments = 999;
				else
				$Installments = 998;
				$indefinite = 'yes';
			}
			elseif(isset($recurring[3]) && $recurring[3])			
			$Installments = GFCommon::to_number($recurring[3]);
			else
			$Installments = 0;
			if(isset($_POST['gfp_'.$field_id]) && $_POST['gfp_'.$field_id] == 'on')
			$isRecurring = 'yes';
			else
			$isRecurring = 'no';
			$recurring = array (
				'Installments' => $Installments,
				'Periodicity' => $Periodicity,
				'RecurringMethod' => $RecurringMethod,
				'isRecurring' => $isRecurring,
				'indefinite' => $indefinite,
			);
		}
		else {
			$recurring = false;
		}
		}
	
		return $recurring;
	}

	/**
	* no date_create_from_format before PHP 5.3, so roll-your-own
	* @param string $value date value in dd/mm/yyyy format
	* @return DateTime
	*/
	protected static function parseDate($value) {
		if (preg_match('#(\d{1,2})/(\d{1,2})/(\d{4})#', $value, $matches)) {
			$date = date_create();
			$date->setDate($matches[3], $matches[2], $matches[1]);
			return $date;
		}

		return false;
	}
}
add_filter( 'gform_admin_pre_render', function ( $form ) {
    echo GFCommon::is_form_editor() ? "
        <script type='text/javascript'>
        gform.addFilter('gform_form_editor_can_field_be_added', function (canFieldBeAdded, type) {
			
            if (type == 'gfcnprecurring') {
			
                if (GetFieldsByType(['gfcnprecurring']).length > 0) {
                    alert('" . __( 'Only one Recurring field can be added to the form', 'gfcnp_plugin' ) . "');
                    return false;
                }
            }
            return canFieldBeAdded;
        });
        </script>" : '';
        
    //return the form object from the php hook
    return $form;
} );