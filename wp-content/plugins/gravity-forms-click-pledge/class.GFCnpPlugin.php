<?php
/**
* custom exception types
*/
class GFCnpException extends Exception {}
class GFCnpCurlException extends Exception {}

/**
* class for managing the plugin
*/
class GFCnpPlugin {
	public $urlBase;									// string: base URL path to files in plugin
	public $options;									// array of plugin options
	public $responsecodes;
	
	protected $acceptedCards;							// hash map of accepted credit cards
	protected $txResult = null;							// results from credit card payment transaction
	protected $formHasCcField = false;					// true if current form has credit card field
	
	/**
	* static method for getting the instance of this singleton object
	*
	* @return GFEwayPlugin
	*/
	public static function getInstance() {
		static $instance = NULL;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	* initialise plugin
	*/
	private function __construct() {
		// grab options, setting new defaults for any that are missing
		$this->initOptions();

		// record plugin URL base
		$this->urlBase = plugin_dir_url(__FILE__);
		
		//print_r(unserialize($this->options['available_cards']));
		// filter the cards array to just Visa, MasterCard and Amex

		foreach(unserialize($this->options['available_cards']) as $card => $value) {
		if($card == 'Visa') $this->acceptedCards['visa'] = 1;
		if($card == 'MasterCard') $this->acceptedCards['mastercard'] = 1;
		if($card == 'Discover') $this->acceptedCards['discover'] = 1;
		if($card == 'American_Express') $this->acceptedCards['amex'] = 1;		
		if($card == 'JCB') $this->acceptedCards['jcb'] = 1;
		//$this->acceptedCards = array('amex' => 1, 'mastercard' => 1, 'visa' => 1, 'discover' => 1, 'jcb' => 1);
		}

		add_action('init', array($this, 'init'));
		
	}
	
	/**
	* initialise plug-in options, handling undefined options by setting defaults
	*/
	protected function initOptions() {
		$defaults = array (
			'AccountID' => '',
			'AccountGuid' => '',
			'useTest' => true,
			'sslVerifyPeer' => true,
			
			'available_cards' => serialize(array('Visa' => 'Visa', 'MasterCard' => 'MasterCard', 'Discover' => 'Discover', 'American_Express' => 'American Express', 'JCB' => 'JCB')),
			'OrganizationInformation' => '',
			'ThankYouMessage' => '',
			'TermsCondition' => '',
			'isRecurring' => '0',
		);
		$this->responsecodes = array(2054=>'Total amount is wrong',2055=>'AccountGuid is not valid',2056=>'AccountId is not valid',2057=>'Username is not valid',2058=>'Password is not valid',2059=>'Invalid recurring parameters',2060=>'Account is disabled',2101=>'Cardholder information is null',2102=>'Cardholder information is null',2103=>'Cardholder information is null',2104=>'Invalid billing country',2105=>'Credit Card number is not valid',2106=>'Cvv2 is blank',2107=>'Cvv2 length error',2108=>'Invalid currency code',2109=>'CreditCard object is null',2110=>'Invalid card type ',2111=>'Card type not currently accepted',2112=>'Card type not currently accepted',2210=>'Order item list is empty',2212=>'CurrentTotals is null',2213=>'CurrentTotals is invalid',2214=>'TicketList lenght is not equal to quantity',2215=>'NameBadge lenght is not equal to quantity',2216=>'Invalid textonticketbody',2217=>'Invalid textonticketsidebar',2218=>'Invalid NameBadgeFooter',2304=>'Shipping CountryCode is invalid',2305=>'Shipping address missed',2401=>'IP address is null',2402=>'Invalid operation',2501=>'WID is invalid',2502=>'Production transaction is not allowed. Contact support for activation.',2601=>'Invalid character in a Base-64 string',2701=>'ReferenceTransaction Information Cannot be NULL',2702=>'Invalid Refrence Transaction Information',2703=>'Expired credit card',2805=>'eCheck Account number is invalid',2807=>'Invalid payment method',2809=>'Invalid payment method',2811=>'eCheck payment type is currently not accepted',2812=>'Invalid check number',1001=>'Internal error. Retry transaction',1002=>'Error occurred on external gateway please try again',2001=>'Invalid account information',2002=>'Transaction total is not correct',2003=>'Invalid parameters',2004=>'Document is not a valid xml file',2005=>'OrderList can not be empty',3001=>'Invalid RefrenceTransactionID',3002=>'Invalid operation for this transaction',4001=>'Fraud transaction',4002=>'Duplicate transaction',5001=>'Declined (general)',5002=>'Declined (lost or stolen card)',5003=>'Declined (fraud)',5004=>'Declined (Card expired)',5005=>'Declined (Cvv2 is not valid)',5006=>'Declined (Insufficient fund)',5007=>'Declined (Invalid credit card number)');
		$this->options = (array) get_option(GFCNP_PLUGIN_OPTIONS);

		if (count(array_diff_assoc($defaults, $this->options)) > 0) {
			$this->options = array_merge($defaults, $this->options);
			update_option(GFCNP_PLUGIN_OPTIONS, $this->options);
		}

	}

	/**
	* handle the plugin's init action
	*/
	public function init() {

		// do nothing if Gravity Forms isn't enabled
		if (class_exists('GFCommon')) {
			// hook into Gravity Forms to enable credit cards and trap form submissions
			add_filter('gform_pre_render', array($this, 'gformPreRenderSniff'));
			add_filter('gform_admin_pre_render', array($this, 'gformPreRenderSniff'));
			add_action('gform_enable_credit_card_field', '__return_true');		// just return true to enable CC fields
			add_filter('gform_creditcard_types', array($this, 'gformCCTypes'));
			add_filter('gform_currency', array($this, 'gformCurrency'));
			add_filter('gform_validation', array($this, 'gformValidation'), 10, 4);
			add_action('gform_after_submission', array($this, 'gformAfterSubmission'), 10, 2);
			add_filter('gform_custom_merge_tags', array($this, 'gformCustomMergeTags'), 10, 4);
			add_filter('gform_replace_merge_tags', array($this, 'gformReplaceMergeTags'), 10, 7);
			add_filter('gform_enable_entry_info_payment_details', '__return_false'); //For 1.9V later to display payment details in lead details	
			
			add_action( 'wp_enqueue_scripts', function()
			{   
				wp_add_inline_script( 
					'jquery-migrate', 'jQuery.migrateMute = true;',
					'before' 
				);
			} );
	
			// hook into Gravity Forms to handle Recurring Payments custom field
			new GFCnpRecurringField($this);
			
			// hook into Gravity Forms to handle e-Check custom field
			new GFCnpEcheckField($this);

			// hook into Gravity Forms to handle Custom Payment custom field
			new GFCnpCustompaymentField($this);	
			
		    // hook into Gravity Forms to handle Form Mode custom field
			new GFCnpFormModeField($this);	
			
		  // hook into Gravity Forms to handle Connect Campaign custom field
			new GFCnpConnectCampaignField($this);	
			
		}

		if (is_admin()) {
			// kick off the admin handling
			new GFCnpAdmin($this);
		}
		
	}

		
	/**
	* check current form for information
	* @param array $form
	* @return array
	*/
	public function gformPreRenderSniff($form) {
		// test whether form has a credit card field
		$this->formHasCcField = self::hasFieldType($form['fields'], 'creditcard');
		if (!is_admin()) { 
		$gfcnpformmodeval = "";
		if ($this->hasFieldType($form['fields'], 'creditcard')) {
		if (is_array($form['fields'])) {
			foreach ($form['fields'] as $field) { 
			
			//$gfcnpobj = new GFCnpPayment();
			if (RGFormsModel::get_input_type($field) == "gfcnpformmode" && self::hasFieldType($form['fields'], 'gfcnpformmode')){  $gfcnpformmodeval = $field['gfcnp_formmode'];}
				
				/*if (RGFormsModel::get_input_type($field) == "gfcnpconnectcampaign" && self::hasFieldType($form['fields'], 'gfcnpconnectcampaign')){  $gfcnpformmodeval = $field['gfcnp_connectcampaign'];}*/
			
			//if($gfcnpformmodeval == ""){$gfcnpformmodeval =  ($this->options['useTest'] == 1) ? 'Test' : 'Production';} 
				if ((RGFormsModel::get_input_type($field) == "creditcard") &&  $gfcnpformmodeval == "Test"){
					
					$ccfldnm    = "input_".$field['formId']."_".$field['id']."_1";
					$monthfldnm = "input_".$field['formId']."_".$field['id']."_2_month";
					$yrfldnm    = "input_".$field['formId']."_".$field['id']."_2_year";
					$cvvfldnm   = "input_".$field['formId']."_".$field['id']."_3";
					
					?>
			<script>
			jQuery( document ).ready(function() {
			var d = new Date();
			var n = d.getFullYear() + 1;
			document.getElementById('<?php echo $ccfldnm?>').value = "4111111111111111";
			
			jQuery("#<?php echo $ccfldnm?>").prop("readonly", true);
			jQuery("#<?php echo $ccfldnm?>").css({"background-color": "#f4f4f4"});
			
			document.getElementById('<?php echo $monthfldnm?>').value = "6";
			jQuery("#<?php echo $monthfldnm?>").prop("readonly", true);
			document.getElementById('<?php echo $yrfldnm?>').value = n ;
			jQuery("#<?php echo $yrfldnm?>").prop("readonly", true);
			document.getElementById('<?php echo $cvvfldnm?>').value = "123";
			jQuery("#<?php echo $cvvfldnm?>").prop("readonly", true);
			});
			</script>
			
		<?php
					
				//	}
					}
			}
		}
		
		 } }
				return $form;
			}

	/**
	* process a form validation filter hook; if last page and has credit card field and total, attempt to bill it
	* @param array $data an array with elements is_valid (boolean) and form (array of form elements)
	* @return array
	*/
	public function gformValidation($data) {
		
		$meta = GFCnpData::get_feed_by_form($data['form']['id']);
		
	// make sure all other validations passed
		if ($data['is_valid'] && count($meta)) {
			$formData = new GFCnpFormData($data['form']);
		//	echo "cchidden".$formData->isEcHidden()."<br>";
			if($formData->isCcHidden() || $formData->isEcHidden())
				$hiddenmethod = false;
			// make sure form hasn't already been submitted / processed			
			if ($this->hasFormBeenProcessed($data['form'])) 
			{	//echo "enter1";
				$data['is_valid'] = false;				
				$formData->ccField['failed_validation'] = true;
				$formData->ccField['validation_message'] = $this->getErrMsg(GFCNP_ERROR_ALREADY_SUBMITTED);				
			}
			elseif($formData->creditcardCount == 0 && $formData->echeckCount == 0 && $formData->custompaymentCount == 0) //Make sure that either Credit Card or e-Check details should enterd by user
			{	
				if($formData->ccField == "" || $formData->custompaymentField =="" || $formData->ecField == "" )
				{   
				    $data['is_valid'] = false;
					add_filter( 'gform_validation_message', 'change_message', 10, 2 );
							function change_message( $message, $form ) {
							return "<div class='validation_error'>Error in the form. You should enter either credit card or e-Check or C&P Custom details\n</div>";
								}
				}else{
				$errmsg = "Error in the form. You should enter either credit card or e-Check or C&P Custom details\n";
				$formData->ccField['validation_message'] = $errmsg;
				$data['is_valid'] = false;
				$formData->ccField['failed_validation'] = true;
				}
			}
			if($formData->creditcardCount == 0 && $formData->echeckCount == 0 && $formData->custompaymentCount == 1 && $formData->firstName == '' && $formData->lastName == '') {
			
				$errmsg = "Error in the form. You should enter First Name and Last Name\n";
				$formData->custompaymentField['validation_message'] = $errmsg;
				$data['is_valid'] = false;
				$formData->custompaymentField['failed_validation'] = true;
				
			}
			
			if($formData->ccName == NULL && $formData->ccNumber == NULL && $formData->ecRouting == NULL && $formData->custompaymentCount != 0  && $formData->custompaymentField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->custompaymentField, array()) && isset($formData->recurring) && $formData->recurring != '' && ($formData->recurring['isRecurring'] == 'yes')) {
			
				$data['is_valid'] = false;
				$formData->custompaymentField['validation_message'] = 'Sorry but recurring payments are not supported with this payment method'; //Custom Payment type could not support recurring
				$formData->custompaymentField['failed_validation'] = true;
			}

			// make that this is the last page of the form and that we have a credit card field and something to bill
			// and that credit card field is not hidden (which indicates that payment is being made another way)
			else if (!$hiddenmethod && $formData->isLastPage() && ($formData->creditcardCount > 0 || $formData->echeckCount > 0 || $formData->custompaymentCount > 0)) {
			
				if (!$formData->hasPurchaseFields()) {
					$data['is_valid'] = false;
					if($formData->creditcardCount != 0) {
					$formData->ccField['failed_validation'] = true;
					$formData->ccField['validation_message'] = $this->getErrMsg(GFCNP_ERROR_NO_AMOUNT);
					} else {
					$formData->ecField['failed_validation'] = true;
					$formData->ecField['validation_message'] = $this->getErrMsg(GFCNP_ERROR_NO_AMOUNT);
					}
				}
				else {								
					$hasproducts = false;
					foreach ($data['form']['fields'] as $field) {
						if (GFCommon::is_product_field($field['type']) || $field['type'] == 'donation') {
							$hasproducts = true;							
						}
					}
					
					$processtype = 'CreditCard';
					if($formData->creditcardCount > 0 && $formData->ccName != NULL && $formData->ccNumber != NULL) {
					
						$processtype = 'CreditCard';
					} else if($formData->echeckCount > 0 && $formData->ecRouting != NULL) {
				
						$processtype = 'eCheck';
					}else if($formData->custompaymentCount > 0 && $formData->custompaymentCount != NULL) {
						$processtype = 'CnpCustom';
					}
				
					function in_multiarray($str1,$str2,$str3, $array)
						{
							$exists = false;
						
							if (is_array($array)) {
							   foreach ($array as $arr):
							   	   if (in_array($str1, array_map('strtolower',$arr)) || in_array($str2, array_map('strtolower',$arr)) || in_array($str3, array_map('strtolower',$arr))) {
								   		$exists = $arr;
								   }
							   endforeach;
							} else {
								$array . ' = ' . $str . "\n";
								if (strpos($array, $str) !== false) $exists = true;
							}
						
							return $exists;
						}
						
					$new_arry =  in_multiarray('payment method','select payment type','select payment method', $formData->customfields);
						 // do something if the given value does not exist in the array
					
					if (is_array($new_arry)) {
						if ( strtolower($new_arry['FieldValue']) == 'creditcard' || strtolower($new_arry['FieldValue']) == 'credit card' ) {
							$processtype = 'CreditCard';
						} else if (strtolower($new_arry['FieldValue']) == 'echeck' || strtolower($new_arry['FieldValue']) == 'e check') {
							$processtype = 'eCheck';
						} else {
							$processtype = 'CnpCustom';
						}
					}
					
					if(!extension_loaded('soap')) {
						$errmsg = "SOAP Client is need to process C&P Transaction. Please contact administrator.\n";
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						} else if($formData->creditcardCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					
					if($formData->paymentfieldCount >= 1)
					{
					    $data['is_valid'] = false;
						add_filter( 'gform_validation_message', 'change_message', 10, 2 );
						function change_message( $message, $form ) {
							return "<div class='validation_error'>Please select Payment method </div>";
						}
						
					}
					if($formData->creditcardCount > 1)
					{
						$errmsg = "Error in the form. Form should have only one Credit card field. Please contact administrator\n";
						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					if($formData->echeckCount > 1)
					{
						$errmsg = "Error in the form. Form should have only one e-Check field. Please contact administrator\n";
						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					if($formData->custompaymentCount > 1)
					{
						$errmsg = "Error in the form. Form should have only one e-Check field. Please contact administrator\n";						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					if($formData->shippingCount > 1)
					{
						$errmsg = "Error in the form. Form should have only one Shipping field. Please contact administrator\n";
						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					if($formData->recurringCount > 1)
					{
						$errmsg = "Error in the form. Form should have only one recurring field. Please contact administrator\n";
						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					if (count($formData->productdetails) == 0 && $hasproducts) 
					{
						$errmsg = "Please select atleast one product.\n";
						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						}else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					
					//Here we need to validate quantity field					
					if ($formData->amount == 0 && count($formData->productdetails) == 0 && $hasproducts) 
					{
						$errmsg = "Please select atleast one product.\n";						
						$data['is_valid'] = false;
						if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							$formData->ccField['validation_message'] = $errmsg;
							$formData->ccField['failed_validation'] = true;
						} else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
							$formData->custompaymentField['validation_message'] = $errmsg;
							$formData->custompaymentField['failed_validation'] = true;
						}
						else {
							$formData->ecField['validation_message'] = $errmsg;
							$formData->ecField['failed_validation'] = true;
						}
					}
					
					
					// only check credit card details if we've got something to bill
					
					if ( count($formData->productdetails) && $hasproducts ) 
					{						
							
							// check for required fields
							if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
							if(strlen($formData->ccCVN) > 4) {
								$formData->ccField['validation_message'] = 'Security Code should contain 3 or 4 digits only';
								$formData->ccField['failed_validation'] = true;
							}
							if($formData->recurringCount) {
								if($formData->recurring['isRecurring'] == 'yes') {
									if($formData->recurring['RecurringMethod'] == 'Installment') {
										if($formData->recurring['Installments'] > 998) {
											$formData->ccField['validation_message'] = 'Maximum Instalments allowed 998 only';
											$formData->ccField['failed_validation'] = true;
										}
									}
								}
							}
							
							
							$required = array(
								'ccName' => $this->getErrMsg(GFCNP_ERROR_REQ_CARD_HOLDER),
								'ccNumber' => $this->getErrMsg(GFCNP_ERROR_REQ_CARD_NAME),
							);
						} else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
						
						}else {
							$required = array(
								'ecRouting' => $this->getErrMsg(GFCNP_ERROR_REQ_ecRouting),
								'ecCheck' => $this->getErrMsg(GFCNP_ERROR_REQ_ecCheck),
								'ecAccount' => $this->getErrMsg(GFCNP_ERROR_REQ_ecAccount),
								'ecAccount_type' => $this->getErrMsg(GFCNP_ERROR_REQ_ecAccount_type),
								'ecName' => $this->getErrMsg(GFCNP_ERROR_REQ_ecName),
								'ecChecktype' => $this->getErrMsg(GFCNP_ERROR_REQ_ecChecktype),
								'ecIdtype' => $this->getErrMsg(GFCNP_ERROR_REQ_ecIdtype),
							);
						}
						
						foreach ($required as $name => $message) {
							if (empty($formData->$name)) {
								$data['is_valid'] = false;
								if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
									$formData->ccField['failed_validation'] = true;
									if (!empty($formData->ccField['validation_message']))
										$formData->ccField['validation_message'] .= '<br />';
									$formData->ccField['validation_message'] .= $message;
								} else if($formData->custompaymentCount != 0 && $processtype == 'CnpCustom') {
									$formData->custompaymentField['failed_validation'] = true;
									if (!empty($formData->custompaymentField['validation_message']))
										$formData->custompaymentField['validation_message'] .= '<br />';
									$formData->custompaymentField['validation_message'] .= $message;
								} else {
									$formData->ecField['failed_validation'] = true;
									if (!empty($formData->ecField['validation_message']))
										$formData->ecField['validation_message'] .= '<br />';
									$formData->ecField['validation_message'] .= $message;
								}
							}
						}
						
					
						// if no errors, try to bill it
						if ($data['is_valid']) {
							$data = $this->processSinglePayment($data, $formData);
						}
					}
				}
			}
			
			// if errors, send back to credit card page
			if (!$data['is_valid']) {
				GFFormDisplay::set_current_page($data['form']['id'], $formData->ccField['pageNumber']);
			}
		}

		return $data;
	}

	/**
	* check whether this form entry's unique ID has already been used; if so, we've already done a payment attempt.
	* @param array $form
	* @return boolean
	*/
	protected function hasFormBeenProcessed($form) {
		global $wpdb;
		
		$unique_id = RGFormsModel::get_form_unique_id($form['id']);

		$sql = "select lead_id from {$wpdb->prefix}rg_lead_meta where meta_key='gfcnp_unique_id' and meta_value = \"%s\"";
		$lead_id = $wpdb->get_var($wpdb->prepare($sql, $unique_id));
		return !empty($lead_id);
	}

	/**
	* get customer ID to use with payment gateway
	* @return string
	*/
	protected function getCustomerID() {
		return $this->options;
	}

	/**
	* process regular one-off payment
	* @param array $data an array with elements is_valid (boolean) and form (array of form elements)
	* @param GFEwayFormData $formData pre-parsed data from $data
	* @return array
	*/
	protected function processSinglePayment($data, $formData) {
		
		try {
			$cnp = new GFCnpPayment($this->getCustomerID(), !$this->options['useTest'], $formData);
		
			$cnp->sslVerifyPeer = $this->options['sslVerifyPeer'];
			if($formData->creditcardCount != 0) {
				if (empty($formData->firstName) && empty($formData->lastName)) {
					$cnp->lastName = $formData->ccName;// pick up card holder's name for last name
				}
				else {
					$cnp->firstName   = $formData->firstName;
					$cnp->lastName    = $formData->lastName;
				}
				$cnp->cardHoldersName = $formData->ccName;
				$cnp->cardNumber      = $formData->ccNumber;
				$cnp->cardExpiryMonth = $formData->ccExpMonth;
				$cnp->cardExpiryYear  = $formData->ccExpYear;
			} else {
				$cnp->ecRouting       = $formData->ecRouting;
				$cnp->ecCheck         = $formData->ecCheck;
				$cnp->ecAccount       = $formData->ecAccount;
				$cnp->ecAccount_type  = $formData->ecAccount_type;
				$cnp->ecName          = $formData->ecName;
				$cnp->ecChecktype     = $formData->ecChecktype;
				$cnp->ecIdtype        = $formData->ecIdtype;
			}
			$cnp->emailAddress           = $formData->email;
			$cnp->address                = $formData->address;
			$cnp->postcode               = $formData->postcode;
			$cnp->cardVerificationNumber = $formData->ccCVN;
			
			// To get the country code
			if (isset($formData->address_country) && $formData->address_country != '') {
				//$cnp->customerCountryCode = GFCommon::get_country_code($formData->address_country);
				$countries = simplexml_load_file( WP_PLUGIN_URL.DIRECTORY_SEPARATOR.plugin_basename( dirname(__FILE__)).DIRECTORY_SEPARATOR.'Countries.xml' );
				
				foreach( $countries as $country ){
					if( $country->attributes()->Name == $formData->address_country ){
						$billing_country_id = $country->attributes()->Code;
						break;
					} 
				}
				
				if($billing_country_id)
				{
				$cnp->customerCountryCode = $billing_country_id;
				}
			}

			$cnp->amount = $formData->total;
			
			$response = $cnp->processPayment();
			if($response === FALSE) {
				$data['is_valid'] = false;
				$AdditionalInfo = 'Unable to connect to Click & Pledge. Please try after some time';
				if($formData->creditcardCount != 0 && $formData->ccField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ccField, array())) {
					$formData->ccField['failed_validation'] = true;					
					$formData->ccField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
				} else if($formData->echeckCount != 0  && $formData->ecField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ecField, array())) {
					$formData->ecField['failed_validation'] = true;
					$formData->ecField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
				}
				else if($formData->custompaymentCount != 0  && $formData->custompaymentField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->custompaymentField, array())) {
					$formData->custompaymentField['failed_validation'] = true;
					$formData->custompaymentField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
				}
			} else {

				$ResultCode = $response->OperationResult->ResultCode;
				$transation_number = $response->OperationResult->TransactionNumber;
				$VaultGUID = $response->OperationResult->VaultGUID; 
				$AuthorizationCode = $response->OperationResult->AuthorizationCode;
				
				if ($ResultCode == '0') {
					// transaction was successful, so record transaction number and continue								
					$this->txResult = array (
						'transaction_id' => $VaultGUID,
						'order_number' => $transation_number,
						'payment_status' => 'Approved',
						'payment_date' => date('Y-m-d H:i:s'),
						'payment_amount' => $cnp->amount,
						'transaction_type' => 1,
						'authcode' => $AuthorizationCode,
					);
								
				}
				else {
					$data['is_valid'] = false;
					$formData->ccField['failed_validation'] = true;
					if( in_array( $ResultCode, array( 2051,2052,2053 ) ) )
					{
						$AdditionalInfo = $response->OperationResult->AdditionalInfo;
					}
					else
					{
						if( isset( $this->responsecodes[$ResultCode] ) )
						{
							$AdditionalInfo = $this->responsecodes[$ResultCode];
						}
						else if ($response == 'Item-0'){
							$AdditionalInfo= 'Select atleast one item.';
						} else {
							$AdditionalInfo = 'Unknown error';
						}
					}
					
					if($formData->creditcardCount != 0 && $formData->ccField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ccField, array())) {
						$formData->ccField['failed_validation'] = true;
						$formData->ccField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
					} else if($formData->echeckCount != 0  && $formData->ecField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ecField, array())) {
						$formData->ecField['failed_validation'] = true;
						$formData->ecField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
					}
					else if($formData->custompaymentCount != 0  && $formData->custompaymentField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->custompaymentField, array())) {
						$formData->custompaymentField['failed_validation'] = true;
						$formData->custompaymentField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$AdditionalInfo}");
					}
					
					$this->txResult = array (
						'payment_status' => 'Failed',
						'order_number' => $transation_number,
					);
				}
			}
		}
		catch (GFCnpException $e) {			
			$data['is_valid'] = false;
			$this->txResult = array (
				'payment_status' => 'Failed',
			);			
						
			if($formData->creditcardCount != 0 && $formData->ccField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ccField, array())) {
				$formData->ccField['failed_validation'] = true;
				$formData->ccField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$e->getMessage()}");			
			} 
			else if($formData->echeckCount != 0  && $formData->ecField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->ecField, array())) {
				$formData->ecField['failed_validation'] = true;
				$formData->ecField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$e->getMessage()}");				
			}
			else if($formData->custompaymentCount != 0  && $formData->custompaymentField != '' && !RGFormsModel::is_field_hidden($data['form'], $formData->custompaymentField, array())) {
				$formData->custompaymentField['failed_validation'] = true;
				$formData->custompaymentField['validation_message'] = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$e->getMessage()}");			
			}			
			$errmsg = nl2br($this->getErrMsg(GFCNP_ERROR_FAIL) . ":\n{$e->getMessage()}");
		}

		return $data;
	}

	/**
	* save the transaction details to the entry after it has been created
	* @param array $data an array with elements is_valid (boolean) and form (array of form elements)
	* @return array
	*/
	public function gformAfterSubmission($entry, $form) {
		
		$formData = new GFCnpFormData($form);
		
		if (!empty($this->txResult)) {
			foreach ($this->txResult as $key => $value) {
				$entry[$key] = $value;
			}
			//RGFormsModel::update_lead($entry);
			GFAPI::update_entry($entry);
			// record entry's unique ID in database
			$unique_id = RGFormsModel::get_form_unique_id($form['id']);

			gform_update_meta($entry['id'], 'gfcnp_unique_id', $unique_id);
			
			
			// record payment gateway
			gform_update_meta($entry['id'], 'payment_gateway', 'gfcnp');
			//global $wpdb;
			$formData->is_valid = 0;
			
		}
	}

	/**
	* add custom merge tags
	* @param array $merge_tags
	* @param int $form_id
	* @param array $fields
	* @param int $element_id
	* @return array
	*/
	
	public function gformCustomMergeTags($merge_tags, $form_id, $fields, $element_id) {
		if ($fields && $this->hasFieldType($fields, 'creditcard')) {
			$merge_tags[] = array('label' => 'Transaction ID', 'tag' => '{transaction_id}');
			$merge_tags[] = array('label' => 'Order Number', 'tag' => '{order_number}');
			$merge_tags[] = array('label' => 'Auth Code', 'tag' => '{authcode}');
			$merge_tags[] = array('label' => 'Payment Amount', 'tag' => '{payment_amount}');
			$merge_tags[] = array('label' => 'Payment Status', 'tag' => '{payment_status}');
		}

		return $merge_tags;
	}

	/**
	* replace custom merge tags
	* @param string $text
	* @param array $form
	* @param array $lead
	* @param bool $url_encode
	* @param bool $esc_html
	* @param bool $nl2br
	* @param string $format
	* @return string
	*/
	
	public function gformReplaceMergeTags($text, $form, $lead, $url_encode, $esc_html, $nl2br, $format) {
		if ($this->hasFieldType($form['fields'], 'creditcard')) {
			if (is_null($this->txResult)) {
				// lead loaded from database, get values from lead meta
				$transaction_id = isset($lead['transaction_id']) ? $lead['transaction_id'] : '';
				$order_number = isset($lead['order_number']) ? $lead['order_number'] : '';
				$payment_amount = isset($lead['payment_amount']) ? $lead['payment_amount'] : '';
				$payment_status = isset($lead['payment_status']) ? $lead['payment_status'] : '';
				$authcode = (string) gform_get_meta($lead['id'], 'authcode');
				$beagle_score = (string) gform_get_meta($lead['id'], 'beagle_score');
			}
			else {
				// lead not yet saved, get values from transaction results
				$transaction_id = isset($this->txResult['transaction_id']) ? $this->txResult['transaction_id'] : '';
				$order_number = isset($this->txResult['order_number']) ? $this->txResult['order_number'] : '';
				$payment_amount = isset($this->txResult['payment_amount']) ? $this->txResult['payment_amount'] : '';
				$payment_status = isset($this->txResult['payment_status']) ? $this->txResult['payment_status'] : '';
				$authcode = isset($this->txResult['authcode']) ? $this->txResult['authcode'] : '';
				$beagle_score = isset($this->txResult['beagle_score']) ? $this->txResult['beagle_score'] : '';
			}

			$tags = array (
				'{transaction_id}',
				'{order_number}',
				'{payment_amount}',
				'{payment_status}',
				'{authcode}',
				'{beagle_score}',
			);
			$values = array (
				$transaction_id,
				$order_number,
				$payment_amount,
				$payment_status,
				$authcode,
				$beagle_score,
			);

			$text = str_replace($tags, $values, $text);
		}

		return $text;
	}

	/**
	* tell Gravity Forms what credit cards we can process
	* @param array $cards
	* @return array
	*/
	public function gformCCTypes($cards) {
		$new_cards = array();
		foreach ($cards as $i => $card) {
			if (isset($this->acceptedCards[$card['slug']])) {
				$new_cards[] = $card;
			}
		}
		return $new_cards;
	}

	/**
	* tell Gravity Forms what currencies we can process
	* @param string $currency
	* @return string
	*/
	public function gformCurrency($currency) {
		// return the currency if current form has a CC field
		if ($this->formHasCcField) {
			$currency = $currency;
		}

		return $currency;
	}

	/**
	* check form to see if it has a field of specified type
	* @param array $fields array of fields
	* @param string $type name of field type
	* @return boolean
	*/
	public static function hasFieldType($fields, $type) {
		if (is_array($fields)) {
			foreach ($fields as $field) {
				if (RGFormsModel::get_input_type($field) == $type)
					return true;
			}
		}
		return false;
	}

	/**
	* get nominated error message, checking for custom error message in WP options
	* @param string $errName the fixed name for the error message (a constant)
	* @param boolean $useDefault whether to return the default, or check for a custom message
	* @return string
	*/
	public function getErrMsg($errName, $useDefault = false) {
		static $messages = array (
			GFCNP_ERROR_ALREADY_SUBMITTED		=> 'Payment already submitted and processed - please close your browser window',
			GFCNP_ERROR_NO_AMOUNT				=> 'This form has credit card/eCheck fields, but no products or totals',
			GFCNP_ERROR_REQ_CARD_HOLDER		    => 'Card holder name is required for credit card processing',
			GFCNP_ERROR_REQ_CARD_NAME			=> 'Card number is required for credit card processing',
			GFCNP_ERROR_FAIL				    => 'Error processing card transaction',
			
			GFCNP_ERROR_REQ_ecRouting		    => 'Routing Number is required for e-Check processing',
			GFCNP_ERROR_REQ_ecCheck		        => 'Check Number is required for e-Check processing',
			GFCNP_ERROR_REQ_ecAccount		    => 'Account Number is required for e-Check processing',
			GFCNP_ERROR_REQ_ecAccount_type		=> 'Account Type is required for e-Check processing',
			GFCNP_ERROR_REQ_ecName		        => 'Name on Account is required for e-Check processing',
			GFCNP_ERROR_REQ_ecChecktype		    => 'Check Type is required for e-Check processing',
			GFCNP_ERROR_REQ_ecIdtype		    => 'Type of ID is required for e-Check processing',
			
			GFCNP_ERROR_REQ_custPaymentno		=> 'Payment Number is required for Custom Payment processing',
		);

		// default
		$msg = isset($messages[$errName]) ? $messages[$errName] : 'Unknown error';

		// check for custom message
		if (!$useDefault) {
			$msg = get_option($errName, $msg);
		}

		return $msg;
	}

	/**
	* send data via cURL and return result
	* @param string $url
	* @param string $data
	* @param bool $sslVerifyPeer whether to validate the SSL certificate
	* @return string $response
	* @throws GFEwayCurlException
	*/
	public static function curlSendRequest($url, $data, $sslVerifyPeer = true) {
		// send data via HTTPS and receive response
		$response = wp_remote_post($url, array(
			'user-agent' => 'Gravity Forms Click & Pledge',
			'sslverify' => $sslVerifyPeer,
			'timeout' => 60,
			'headers' => array('Content-Type' => 'text/xml; charset=utf-8'),
			'body' => $data,
		));

//~ error_log(__METHOD__ . "\n" . print_r($response,1));

		if (is_wp_error($response)) {
			throw new GFCnpCurlException($response->get_error_message());
		}

		return $response['body'];
	}

	/**
	* get the customer's IP address dynamically from server variables
	* @return string
	*/
	public static function getCustomerIP() {
		// if test mode and running on localhost, then kludge to an Aussie IP address
		$plugin = self::getInstance();
		if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '127.0.0.1' && $plugin->options['useTest']) {
			return '210.1.199.10';
		}

		// check for remote address, ignore all other headers as they can be spoofed easily
		if (isset($_SERVER['REMOTE_ADDR']) && self::isIpAddress($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return '';
	}

	/**
	* check whether a given string is an IP address
	* @param string $maybeIP
	* @return bool
	*/
	protected static function isIpAddress($maybeIP) {
		if (function_exists('inet_pton')) {
			// check for IPv4 and IPv6 addresses
			return !!inet_pton($maybeIP);
		}

		// just check for IPv4 addresses
		return !!ip2long($maybeIP);
	}

	/**
	* display a message (already HTML-conformant)
	* @param string $msg HTML-encoded message to display inside a paragraph
	*/
	public static function showMessage($msg) {
		echo "<div class='updated fade'><p><strong>$msg</strong></p></div>\n";
	}

	/**
	* display an error message (already HTML-conformant)
	* @param string $msg HTML-encoded message to display inside a paragraph
	*/
	public static function showError($msg) {
		echo "<div class='error'><p><strong>$msg</strong></p></div>\n";
	}
	 }
?>