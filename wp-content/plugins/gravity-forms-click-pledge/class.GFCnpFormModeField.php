<?php
/**
* with thanks to Travis Smith's excellent tutorial:
* http://wpsmith.net/2011/plugins/how-to-create-a-custom-form-field-in-gravity-forms-with-a-terms-of-service-form-field-example/
*/
class GFCnpFormModeField {

	protected $plugin;
	protected $RecurringMethod;
	protected $first_load;

	/**
	* @param GFEwayPlugin $plugin
	*/
	public function __construct($plugin) {
		$this->plugin = $plugin;
		
		$this->RecurringMethod = array();
		$this->first_load = true;
		// WordPress script hooks -- NB: must happen after Gravity Forms registers scripts
		add_action('wp_enqueue_scripts', array($this, 'registerScripts'), 20);
		add_action('admin_enqueue_scripts', array($this, 'registerScripts'), 20);

		// add Gravity Forms hooks
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

		add_filter( 'gform_admin_pre_render', function ( $form ) {
			echo GFCommon::is_form_editor() ? "
				<script type='text/javascript'>
				gform.addFilter('gform_form_editor_can_field_be_added', function (canFieldBeAdded, type) {
					
					if (type == 'CnpFormmodeButton' || type == 'gfcnpformmode') {
					
						if (GetFieldsByType(['CnpFormmodeButton']).length > 0 || GetFieldsByType(['gfcnpformmode']).length > 0) {
							alert('" . __( 'Only one Order Mode field can be added to the form', 'gfcnp_plugin' ) . "');
							return false;
						}
					}
					return canFieldBeAdded;
				});
				</script>" : '';
				
			//return the form object from the php hook
			return $form;
		} );
		
		
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
		wp_register_script('gfcnp_formmode', "{$this->plugin->urlBase}js/admin-ordermode$min.js", $reqs, GFCNP_PLUGIN_VERSION, true);

		wp_register_style('gfcnp', $this->plugin->urlBase . 'style.css', false, GFCNP_PLUGIN_VERSION);
	}

	/**
	* enqueue additional scripts if required by form
	* @param array $form
	* @param boolean $ajax
	*/
	public function gformEnqueueScripts($form, $ajax) {
		if ($this->plugin->hasFieldType($form['fields'], GFCNP_FIELD_FORMMODE) || $this->plugin->hasFieldType($form['fields'], 'CnpFormmodeButton')) {
			// enqueue script for field
			wp_enqueue_script('gfcnp_formmode');

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
		echo "<script src=\"{$this->plugin->urlBase}js/admin-ordermode$min.js?v=$version\"></script>\n";
		
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
					'value' => 'C&P Order Mode',
					'name' => 'CnpFormmodeButton',
					'id' => 'CnpFormmodeButton',
					'data-type' => GFCNP_FIELD_FORMMODE,
					
				);
				break;
			}
		}//echo "<pre>";print_r($field_groups);
		return $field_groups;
	}

	/**
	* filter hook for modifying the field title (e.g. on custom fields)
	* @param string $title
	* @param string $field_type
	* @return string
	*/
	public function gformFieldTypeTitle($title, $field_type) {
		if ($field_type == GFCNP_FIELD_FORMMODE) {
			$title = 'C&P Order Mode';
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
			$options = $this->plugin->options;
			?>
			<li class="gfcnpformmode_setting field_setting">
			<table width="100%" border="0" cellpadding="5" cellspacing="1" class="rectbl">
					<tbody>
					<tr><td colspan="2" class="section_label"><strong> Order Mode</strong></td></tr>
					<tr><td valign="top">
					<label for="gfcnp_recurring_paymentoptions_label" class="section_label">	
				
				   <strong>Mode</strong>
					</label>
					</td>
					<td>
					<ul class = "gfcnp_formmode">
					<li><input type="radio" name="gfcnp_formmode" id="gfcnp_formmode" value="Test" class="gfield_radio" onclick="GFCnpformmode.FieldSet(this)">&nbsp;Test
					</li>
					<li>
					<input type="radio" name="gfcnp_formmode"  id="gfcnp_formmode" value="Production" class="gfield_radio" onclick="GFCnpformmode.FieldSet(this)">&nbsp;Production</li>
					</ul>
					</td></tr></tbody></table>
			</li>	
			<?php
		}
	}

	/**
	* add custom tooltips for fields on form editor
	* @param array $tooltips
	* @return array
	*/
	public function gformTooltips($tooltips) {
		return $tooltips;
	}
	/**
	* get input values for recurring payments field
	* @param integer $field_id
	* @return array
	*/
	public static function getPost($field_id) {
		$custompayment = rgpost('gfp_' . $field_id);
		
		if (is_array($custompayment)) {
			$custompayment = array (
				'paymentnumber' => $custompayment['paymentnumber'],
			);
		}
		else {
			$custompayment = false;
		}

		return $custompayment;
	}
	/**
	* grab values and concatenate into a string before submission is accepted
	* @param array $form
	*/
	public function gformPreSubmit($form) {
	
		foreach ($form['fields'] as $field) {
			if (($field['type'] == GFCNP_FIELD_FORMMODE || $field['type'] == 'CnpFormmodeButton') && !RGFormsModel::is_field_hidden($form, $field, RGForms::post('gform_field_values'))) {
				$custompayment = self::getPost($field['id']);
				$_POST["input_{$field['id']}"] = '';
				
			}
		}
		
	}

	/**
	* prime the inputs that will be checked by standard validation tests,
	* e.g. so that "required" fields don't fail
	* @param array $form
	* @return array
	*/
	public function gformPreValidation($form) {
		
		foreach($form["fields"] as $field) {
			if (($field['type'] == GFCNP_FIELD_FORMMODE || $field['type'] == 'CnpFormmodeButton') && (isset($_POST['gfp_'.$field['id']]))) {
				$custompayment = self::getPost($field['id']);				
				$_POST["input_{$field['id']}"] = serialize($custompayment);
				$this->first_load = false;				
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
	* @return array
	*/
	public function gformFieldValidation($validation_result, $value, $form, $field) {
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
		if ($field['type'] == GFCNP_FIELD_FORMMODE || $field['type'] == 'CnpFormmodeButton') 
		{
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
	
	if ($field['type'] == GFCNP_FIELD_FORMMODE || $field['type'] == 'CnpFormmodeButton') {
			// pick up the real valuegfcnp_formmode
			$nm = "field_{$form_id}_{$field['id']}";
			$frmmode = $field['gfcnp_formmode'];
			$isadmin = ( IS_ADMIN ) ? TRUE : FALSE;
			$css ="";
			$cnpfrmtval ="";$cnpfrmlval="";
			if($frmmode == "Test"){	$cnpfrmtval ="checked";	}else if($frmmode == "Production"){$cnpfrmlval="checked";}
			if(!$isadmin){echo '<style type="text/css">#'.$nm.'{ display:none; }</style>';}
			$input = "<div class='ginput_container ginput_container_radio $css' id='input_{$field['id']}'>";
			$disabled_text = (IS_ADMIN && RG_CURRENT_VIEW != "entry") ? " disabled='disabled'" : "";
		
			$input .= '<ul class = "gfcnp_formmode"><li>
			           <input type="radio" name="gfcnp_formmode" id="gfcnp_formmode" value="Test" ' .$disabled_text.' class="gfield_radio" '.$cnpfrmtval.'>&nbsp;Test</li><li>
					   <input type="radio" name="gfcnp_formmode"  id="gfcnp_formmode" value="Production" '.$disabled_text.'class="gfield_radio"'. $cnpfrmlval .' >&nbsp;Production
					   </li><ul>';
					
			$id = $field["id"];
			$field_id = IS_ADMIN || $form_id == 0 ? "input_{$id}" : "input_{$form_id}_{$id}";	
			$input .= "<input type='hidden' name='input_{$id}' id='$field_id' />";
			$input .= "<input type='hidden' name='gfcnp_formmode_hid' id='gfcnp_formmode_hid' value='$frmmode' />";
			$input .= "</div>";
		}

		return $input;
	
	}
}