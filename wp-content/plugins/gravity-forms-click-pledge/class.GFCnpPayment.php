<?php
/**
* Class for dealing with an Click & Pledge payment
*/
class GFCnpPayment {
	// environment / website specific members
	/**
	* default FALSE, use Click & Pledge sandbox unless set to TRUE
	* @var boolean
	*/
	public $isLiveSite;
	
	/**
	Specify the order mode.  Test mode may be used for testing and development.  In test mode only test credit card numbers may be used.  In Production mode transactions are only performed with real credit cards.
	allowed values 'Test' and 'Production'
	*/
	public $mode;

	/**
	* default TRUE, whether to validate the remote SSL certificate
	* @var boolean
	*/
	public $sslVerifyPeer;

	// payment specific members
	/**
	* Trio account number provide by Click & Pledge
	* @var string min. 1 and max. 9999999999 characters
	*/
	public $AccountID;

	/**
	* A unique identifier provided by Click & Pledge
	* @var string min. 36 and max. 36 characters
	*/
	public $AccountGuid;
	
	/**
	* Global options at Click & Pledge
	*/
	public $Options_new;
	
	/**
	* total amount of payment
	* @var float
	*/
	public $amount;
	public $totalamt;


	/**
	* customer's postcode
	* @var string min 2 and max. 20 characters
	*/
	public $postcode;

	/**
	* name on credit card
	* @var string min. 2 and max. 50 characters
	*/
	public $cardHoldersName;
	
	/**
	* credit card number, with no spaces
	* @var string max. 20 characters
	*/
	public $cardNumber;

	/**
	* month of expiry, numbered from 1=January
	* @var integer max. 2 digits
	*/
	public $cardExpiryMonth;

	/**
	* year of expiry
	* @var integer will be truncated to 2 digits, can accept 4 digits
	*/
	public $cardExpiryYear;

	/**
	* CVN (Creditcard Verification Number) for verifying physical card is held by buyer
	* @var string max. 3 or 4 characters (depends on type of card)
	*/
	public $cardVerificationNumber;


	/**
	* Complete form data which is submitting by user
	* @var string 2 characters
	*/
	public $formData;

public $recrngsts=0;

	/**
	* populate members with defaults, and set account and environment information
	*
	* @param string $AccountID Click & Pledge account ID
	* @param boolean $isLiveSite running on the live (production) website
	*/
	public function __construct($Options_new, $isLiveSite = FALSE, $formData) {
		$this->sslVerifyPeer = TRUE;
		$this->isLiveSite = $isLiveSite;
		$this->AccountID = $Options_new['AccountID'];
		$this->AccountGuid = $Options_new['AccountGuid'];
		$this->mode = ($Options_new['useTest'] == 1) ? 'Test' : 'Production';
		$this->formData = $formData;
		$this->Options_new = $Options_new;
		}
		


	/**
	* process a payment against Click & Pledge; throws exception on error with error described in exception message.
	*/
	public function processPayment() {
		$this->validate($this->Options_new, $this->formData);
		$xml = $this->getPaymentXML($this->Options_new, $this->formData);
		
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXML($xml);
		 $nodes = $dom->getElementsByTagName('OrderItem') ; 
		return $this->sendPayment($xml,$nodes->length);
	}

	/**
	* validate the data members to ensure that sufficient and valid information has been given
	*/
	public function in_multiarray($str1,$str2,$str3, $array)
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

	
	
	private function validate($options, $formData) {

		$errmsg = '';
		$adminerrors = false;
		if($formData->creditcardCount == 0 && $formData->echeckCount == 0 && $formData->custompaymentCount == 0) {
			$errmsg .= "Payment form should have 'Credit Card', 'eCheck', 'C&P Custom' for processing. Please contact administrator\n";
			$adminerrors = true;
		}
		if($formData->creditcardCount == 0 && $formData->echeckCount == 0 && $formData->custompaymentCount == 1 && $formData->firstName == '' && $formData->lastName == '') {
			$errmsg .= "Please enter First name and last name.\n";
			$adminerrors = true;
		}
		$form_currency = GFCommon::get_currency();
		if(!in_array($form_currency, array('USD', 'EUR', 'CAD', 'GBP', 'HKD')))
		{
			$errmsg .= "We are supporting 'USD', 'EUR', 'CAD', 'GBP', 'HKD'. Check your API credentials and make sure your currency is supported.\n";
			$adminerrors = true;
		}
		if (strlen($this->AccountID) === 0) {
			$errmsg .= "AccountID cannot be empty. Please contact administrator.\n";
			$adminerrors = true;
		}			
		if (strlen($options['AccountGuid']) === 0) {
			//$errmsg .= "GUID cannot be empty. Please contact administrator\n";
			//$adminerrors = true;
		}
		
		if($formData->firstName == '' && $formData->lastName == '') {
			if($formData->namefieldCount != 0) {
				$errmsg .= 'Please enter First Name and Last Name';
				$adminerrors = true;
			} else if($formData->creditcardCount != 0) {
				$firstName = $lastName = $mess = '';				
				if($formData->ccName != '') {
					$parts = explode(' ', $formData->ccName);
					if(count($parts) > 1) {
						$firstName = $parts[0];
						$lastName = $parts[1];
					} else {
						$firstName = $parts[0];
					}
					$mess = 'Name on card should be in the form of Fist Name Last Name';
				} else {
					$firstName = $formData->firstName;
					$lastName =  $formData->lastName;
					if($firstName == '')
					$mess = "Please enter First Name.\n";
					if($lastName == '')
					$mess = "Please enter Last Name.\n";
				}
				if($firstName == '' || $lastName == '') {
					$adminerrors = true;
					$errmsg .= $mess;
				}				
			} else if($formData->echeckCount != 0) {
				if($formData->ecName == '') {
					$errmsg .= "Please enter account name\n";
					$adminerrors = true;
				} else {
					$parts = explode(' ', $formData->ecName);
					if(count($parts) == 1) {
						$adminerrors = true;
						$errmsg .= 'Name of card should be in the form of Fist Name Last Name';
					}
				}
			} else {
				$adminerrors = true;
				$errmsg .= 'Your Form don\'t have First Name and Last Name fields which are required to process with Click & Pledge. Please contact administrator.';
			}				
		}
		
		if(isset($formData->shippingfields) && count($formData->shippingfields) && $formData->address_street == '') {
			$errmsg .= "Form contains shipping fields but do not have shipping address. Please contact administrator.\n";
			$adminerrors = true;
		}		
		
		if(!$adminerrors) {
		if (strlen($formData->firstName) > 50)
			$errmsg .= "First Name should not exceed 50 characters length.\n";		
		if (strlen($formData->lastName) > 50)
			$errmsg .= "Last Name should not exceed 50 characters length.\n";
		if (!is_array( $formData->productdetails ) && strlen($formData->productdetails) === 0)
			$errmsg .= "Cart should have at least one item.\n";
		
		if(isset($formData->address) && $formData->address != '') {
			if( strlen($formData->address_street) < 2 )
			$errmsg .= "Address should be greater than 2 Characters.\n";
			if( strlen($formData->address_street) > 100 )
			$errmsg .= "Address should not be more than 100 Characters.\n";
			
			if(!empty($formData->address_suburb)) {
				if( strlen($formData->address_suburb) < 2 )
				$errmsg .= "City should be greater than 2 Characters.\n";
				if( strlen($formData->address_suburb) > 50 )
				$errmsg .= "City should not be more than 50 Characters.\n";
			}
			
			if(!empty($formData->address_state)) {
				if( strlen($formData->address_state) > 50 )
				$errmsg .= "State should not be more than 50 Characters.\n";
			}
			
			if(!empty($formData->postcode)) {
				if( strlen($formData->postcode) < 2 )
				$errmsg .= "Postal Code should be greater than 2 Characters.\n";
				if( strlen($formData->postcode) > 50 )
				$errmsg .= "Postal Code should not be more than 50 Characters.\n";
			}
		}		
		if(isset($formData->shippingfields) && count($formData->shippingfields)) {
			if( $formData->address_street_shipping == '' )
			$errmsg .= "Please enter shipping address.\n";
			if( $formData->address_suburb_shipping == '' )
			$errmsg .= "Please enter shipping city.\n";
			if( $formData->address_state_shipping == '' )
			$errmsg .= "Please enter shipping state.\n";
			if( $formData->postcode_shipping == '' )
			$errmsg .= "Please enter shipping postal code.\n";
			if( $formData->address_country_shipping == '' )
			$errmsg .= "Please select shipping country.\n";
		}
		if(count($formData->needtovalidatefields)) {
			for($r = 0; $r < count($formData->needtovalidatefields); $r++)
			{
				if($formData->needtovalidatefields[$r]['type'] == 'price' && !is_numeric($formData->needtovalidatefields[$r]['value']))
				{
					$errmsg .= "Invalid price.\n";
				}
			}
		}
		
		if (!is_float($this->amount))
			$this->amount = (float) $this->amount; 
	
		if((isset($formData->recurring)) && ($formData->recurring['isRecurring'] == 'yes') && $this->amount == 0) {
			$errmsg .= "amount must be greater than zero for recurring transaction.\n";
		}

		$processtype = 'CreditCard';
		if($formData->creditcardCount > 0 && $this->cardHoldersName != NULL && $this->cardNumber != NULL) {
			$processtype = 'CreditCard';
		} else if($formData->echeckCount > 0 && $formData->ecRouting != NULL) {
			$processtype = 'eCheck';
		} else if($formData->custompaymentCount > 0 && $formData->custompaymentCount != NULL) {
			$processtype = 'custompayment';
		}
		$new_arry =  $this->in_multiarray('payment method','select payment type','select payment method', $formData->customfields);
		
		// do something if the given value does not exist in the array
		
		if (is_array($new_arry)) {
			if ( strtolower($new_arry['FieldValue']) == 'creditcard' || strtolower($new_arry['FieldValue']) == 'credit card' ) {
				$processtype = 'CreditCard';
			} else if (strtolower($new_arry['FieldValue']) == 'echeck' || strtolower($new_arry['FieldValue']) == 'e check') {
				$processtype = 'eCheck';
			} else {
				if ($formData->custompaymentField['isRequired'] == 1 && $formData->custompaymentCount > 0 && $formData->paymentnumber == NULL) {
					$errmsg .= 'This field is required.';
				}
				$processtype = 'CnpCustom';
			}
		}	
		 $optionsarry = get_option(gfcnp_plugin);
		 $cnpcustompaymt = $optionsarry['payment_cnp_purchas_order'];
		 $cnpecheckpaymt = $optionsarry['payment_cnp_hidcnpeCheck'];
		 $cnpcreditpaymt = $optionsarry['payment_cnp_hidcnpcreditcard'];
			
			if(($processtype == "CnpCustom" || $processtype == 'custompayment') && $cnpcustompaymt == "" && $optionsarry['AccountGuid'] == "")
			{
				$errmsg .= 'This payment method is not available for your settings.';
			}
			else if($processtype == "eCheck" && $cnpecheckpaymt == "" && $optionsarry['AccountGuid'] == "")
			{
				$errmsg .= 'This payment method is not available for your settings.';
			}
			else if($processtype == "CreditCard" && $cnpcreditpaymt == "" && $optionsarry['AccountGuid'] == "")
			{
				$errmsg .= 'This payment method is not available for your settings.';
			}
			//echo $processtype;exit;
		if($formData->creditcardCount != 0 && $processtype == 'CreditCard') {
			if (strlen($this->cardHoldersName) === 0)
				$errmsg .= "card holder's name cannot be empty.\n";
			if (strlen($this->cardNumber) === 0)
				$errmsg .= "card number cannot be empty.\n";

			// make sure that card expiry month is a number from 1 to 12
			if (gettype($this->cardExpiryMonth) != 'integer') {
				if (strlen($this->cardExpiryMonth) === 0)
					$errmsg .= "card expiry month cannot be empty.\n";
				else if (!is_numeric($this->cardExpiryMonth))
					$errmsg .= "card expiry month must be a number between 1 and 12.\n";
				else
					$this->cardExpiryMonth = intval($this->cardExpiryMonth);
			}
			if (gettype($this->cardExpiryMonth) == 'integer') {
				if ($this->cardExpiryMonth < 1 || $this->cardExpiryMonth > 12)
					$errmsg .= "card expiry month must be a number between 1 and 12.\n";
			}

			// make sure that card expiry year is a 2-digit or 4-digit year >= this year
			if (gettype($this->cardExpiryYear) != 'integer') {
				if (strlen($this->cardExpiryYear) === 0)
					$errmsg .= "card expiry year cannot be empty.\n";
				else if (!preg_match('/^\d\d(\d\d)?$/', $this->cardExpiryYear))
					$errmsg .= "card expiry year must be a two or four digit year.\n";
				else
					$this->cardExpiryYear = intval($this->cardExpiryYear);
			}
			if (gettype($this->cardExpiryYear) == 'integer') {
				$thisYear = intval(date_create()->format('Y'));
				if ($this->cardExpiryYear < 0 || $this->cardExpiryYear >= 100 && $this->cardExpiryYear < 2000 || $this->cardExpiryYear > $thisYear + 20)
					$errmsg .= "card expiry year must be a two or four digit year.\n";
				else {
					if ($this->cardExpiryYear > 100 && $this->cardExpiryYear < $thisYear)
						$errmsg .= "card expiry year can't be in the past.\n";
					else if ($this->cardExpiryYear < 100 && $this->cardExpiryYear < ($thisYear - 2000))
						$errmsg .= "card expiry year can't be in the past.\n";
				}
			}
			
			if (!is_numeric($formData->ccCVN)) {
				$errmsg .= "Security Code should be digits only.\n";
			}
			if (preg_match('/^\d+\.\d+$/',$formData->ccCVN)) {
				$errmsg .= "Security Code should be digits only.\n";
			}
			if (!empty($formData->ccCVN) && preg_match("/^3[47][0-9]{13}$/", $this->cardNumber)) {
					 if(( strlen($formData->ccCVN) < 4 )) {
					$errmsg .="AmEx uses a 4-digit CVV code.  Please verify and try again";
				}
				}
			if (!preg_match("/^3[47][0-9]{13}$/", $this->cardNumber) && (strlen($formData->ccCVN) > 4 || strlen($formData->ccCVN) < 3)   )
				$errmsg .= "CVV should be 3 or 4 digits only.\n";
		
			if (strlen($formData->ccName) == 1)
				$errmsg .= "Card holder Name should be 2 to 50 characters length.\n";
			if (strlen($formData->ccName) > 50)
				$errmsg .= "Card holder Name should not exceed 50 characters length.\n";
		} else {
			if (strlen($formData->ecRouting) > 9)
				$errmsg .= "Routing Number should be max 9 digits only.\n";
		}
		} 
		
		if (strlen($errmsg) > 0) {
			throw new GFCnpException($errmsg);
		}
	}

	/**
	     * Get user's IP address
	     */
	function get_user_ip() {
		$ipaddress = '';
		 if (isset($_SERVER['HTTP_CLIENT_IP']))
			 $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		 else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		 else if(isset($_SERVER['HTTP_X_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		 else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		 else if(isset($_SERVER['HTTP_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED'];
		 else
			 $ipaddress = $_SERVER['REMOTE_ADDR'];
		$parts = explode(',', $ipaddress);
        if(count($parts) > 1) $ipaddress = $parts[0];
		 return $ipaddress; 
	}
	
	function safeString( $str,  $length=1, $start=0 )
	{
		return substr( ( ( $str ) ), $start, $length );
	}
	
	/**
	* create XML request document for payment parameters
	*
	* @return string
	*/
	public function getPaymentXML($configValues, $orderplaced) 
	{ 
	//print_r($orderplaced);exit;
		foreach ($orderplaced->productdetails as  $totpr){if($totpr['UnitPrice'] != '') {
			 $this->totalamt = $this->totalamt + ($totpr['UnitPrice']*$totpr['Quantity']);}}
		
	
			if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) {
				if($orderplaced->recurring['RecurringMethod'] == 'Installment')
				{	
				     $this->totalcalculate = ($this->totalamt/$orderplaced->recurring['Installments']);
					 $this->totalcalculate = preg_replace('/(\.\d\d).*/', '$1', $this->totalcalculate);
				
					if($this->totalcalculate == '0.00'){
					$orderplaced->recurring['isRecurring'] ='no';
					$this->recrngsts =1;
					}
						
				}
			 } 
		

	
		$cnpVersion = "3.700.004/WP:v".get_bloginfo('version')."/GF:v".GFCommon::$version;
		$dom        = new DOMDocument('1.0', 'UTF-8');
		$root       = $dom->createElement('CnPAPI', '');
		$root->setAttribute("xmlns","urn:APISchema.xsd");
		$root       = $dom->appendChild($root);

		$version=$dom->createElement("Version","1.5");
		$version=$root->appendChild($version);

		$engine = $dom->createElement('Engine', '');
		$engine = $root->appendChild($engine);

		$application = $dom->createElement('Application','');
		$application = $engine->appendChild($application);

		$applicationid=$dom->createElement('ID','CnP_PaaS_FM_GravityForm'); //
		$applicationid=$application->appendChild($applicationid);

		$applicationname=$dom->createElement('Name','CnP_PaaS_FM_GravityForm'); 
		$applicationid=$application->appendChild($applicationname);

		$applicationversion=$dom->createElement('Version',$cnpVersion);
		$applicationversion=$application->appendChild($applicationversion);

		$request = $dom->createElement('Request', '');
		$request = $engine->appendChild($request);

		$operation=$dom->createElement('Operation','');
		$operation=$request->appendChild( $operation );

		$operationtype=$dom->createElement('OperationType','Transaction');
		$operationtype=$operation->appendChild($operationtype);
		
		if($this->get_user_ip() != '') {
		$ipaddress=$dom->createElement('IPAddress',$this->get_user_ip());
		$ipaddress=$operation->appendChild($ipaddress);
		}
		
		$httpreferrer=$dom->createElement('UrlReferrer',$_SERVER['HTTP_REFERER']);
		$httpreferrer=$operation->appendChild($httpreferrer);
		
		$authentication=$dom->createElement('Authentication','');
		$authentication=$request->appendChild($authentication);
		
		if($configValues['AccountGuid'] == "")
		{
			$AccountGuid = GFCnpData::getCnPAccountGUID($configValues['AccountID']);
		}
		else
		{
			$AccountGuid = $configValues['AccountGuid'];
		}
		
		
		$accounttype=$dom->createElement('AccountGuid',$AccountGuid ); 
		$accounttype=$authentication->appendChild($accounttype);
		
		$accountid=$dom->createElement('AccountID',$configValues['AccountID'] );
		$accountid=$authentication->appendChild($accountid);
				 
		$order=$dom->createElement('Order','');
		$order=$request->appendChild($order);
		if($orderplaced->ordermode != "")
		{
				$ordermode=$dom->createElement('OrderMode',$orderplaced->ordermode);
				$ordermode=$order->appendChild($ordermode);	
		}
		else
		{
				$ordermode=$dom->createElement('OrderMode',$this->mode);
				$ordermode=$order->appendChild($ordermode);	
		}			
		$transcampinc =1;$connectcampinc =1;
		foreach(array_reverse($orderplaced->customfields) as $sub)
		{
			//Campain Handling
			if($transcampinc == 1){
				if((substr($sub['FieldName'], 0, 14) == '{CAMPAIGNNAME}') && $sub['FieldValue'] != '') {
				
						$cmpparts = explode('}{OPTION=', $sub['FieldName']);
						if(count($cmpparts) > 1) //TO handle if product has options
						{
							$cmpid = $cmpparts[0];
							$cmpid = substr($cmpid, 23);
							$cmpval = substr($cmpparts[1], 0, -1);
						}
						else
						{
							$cmpid = substr($cmpparts[0], 23);
							$cmpid = substr($cmpid, 0, -1);
							$cmpval = '';
						}
						
						if(($cmpval == '') && ($cmpid == ''))
						 { 
						 $transcampinc = $transcampinc+1;
								$trans_cmp=$dom->createElement('Campaign',$this->safeString($sub['FieldValue'], 80));
								$trans_cmp=$order->appendChild($trans_cmp); 
							
						 }
							
					  }
					}
			}	
	
		
		if($orderplaced->connectcampaign != "")
		{
				$trans_cmp=$dom->createElement('ConnectCampaignAlias',$orderplaced->connectcampaign);
				$trans_cmp=$order->appendChild($trans_cmp);	
		}
		else
		{
				foreach(array_reverse($orderplaced->customfields) as $sub)
				{
			//ConnectCampaignAlias Handling
			if($sub['FieldName'] == "{CONNECT_CAMPAIGN_URL_ALIAS}")
			{
		     if($connectcampinc == 1){
				if(($sub['FieldName'] == '{CONNECT_CAMPAIGN_URL_ALIAS}') && $sub['FieldValue'] != '') 
					{				
						$connectcampinc = $connectcampinc+1;
						$trans_cmp      = $dom->createElement('ConnectCampaignAlias',$this->safeString($sub['FieldValue'], 50));
						$trans_cmp      = $order->appendChild($trans_cmp); 
							
					}
				}				
				}
			}	
		}	
		
		if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) 
		{    $skusuffix = "";
			if($orderplaced->recurring['SKUsuffix'] != "")
			{
		      $skusuffix = $orderplaced->recurring['SKUsuffix'];
			 }
		}
		else if((isset($orderplaced->recurring)) && ($orderplaced->recurring['OnetimeSKU'] != '')) {
		 $skusuffix = $orderplaced->recurring['OnetimeSKU'];
		}	
							
		$cardholder = $dom->createElement('CardHolder','');
		$cardholder = $order->appendChild($cardholder);
		
		if($orderplaced->firstName || $orderplaced->ccName) {
		$billinginfo=$dom->createElement('BillingInformation','');
		$billinginfo=$cardholder->appendChild($billinginfo);
		if($orderplaced->firstName != '') {
		$billfirst_name=$dom->createElement('BillingFirstName',$this->safeString($orderplaced->firstName,50));
		$billfirst_name=$billinginfo->appendChild($billfirst_name);
		} else {
			$parts = explode(' ',$orderplaced->ccName);
			if(count($parts) > 1) {
				$BillingFirstName = $parts[0];
			} else {
				$BillingFirstName = $orderplaced->ccName;
			}
			$billfirst_name=$dom->createElement('BillingFirstName',$this->safeString($BillingFirstName,50));
			$billfirst_name=$billinginfo->appendChild($billfirst_name);
		}
		
		if($orderplaced->lastName != '') {		
			$billlast_name=$dom->createElement('BillingLastName',$this->safeString($orderplaced->lastName,50));
			$billlast_name=$billinginfo->appendChild($billlast_name);
		} else {
			$parts = explode(' ',$orderplaced->ccName);
			if(count($parts) > 1) {
				$BillingLastName = substr($orderplaced->ccName,strlen($parts[0]));
			} else {
				$BillingLastName = '';
			}
			if($BillingLastName != '') {
			$billlast_name=$dom->createElement('BillingLastName',$this->safeString($BillingLastName,50));
			$billlast_name=$billinginfo->appendChild($billlast_name);
			}
		}
		if (isset($orderplaced->email) && $orderplaced->email != '')
		{
			$bill_email=$dom->createElement('BillingEmail',$orderplaced->email);
			$bill_email=$billinginfo->appendChild($bill_email);
		}
		else
		{
			$current_user = wp_get_current_user();
			if($current_user->ID)
			{
			$bill_email=$dom->createElement('BillingEmail',$current_user->data->user_email);
			$bill_email=$billinginfo->appendChild($bill_email);
			}
		}
		
		if( $orderplaced->phone != '' )
		{
			$bill_phone=$dom->createElement('BillingPhone',$this->safeString($orderplaced->phone, 50));
			$bill_phone=$billinginfo->appendChild($bill_phone);
		}
		} //Billing Information
		
		if( $orderplaced->address != '' ) {		
		$billingaddress=$dom->createElement('BillingAddress','');
		$billingaddress=$cardholder->appendChild($billingaddress);
		
		if( $orderplaced->address_street != '' ) {
		$billingaddress1=$dom->createElement('BillingAddress1',$this->safeString($orderplaced->address_street,100));
		$billingaddress1=$billingaddress->appendChild($billingaddress1);
		}
				
		if(!empty($orderplaced->address_suburb)) {
		$billing_city=$dom->createElement('BillingCity',$this->safeString($orderplaced->address_suburb,50));
		$billing_city=$billingaddress->appendChild($billing_city);
		}

		if(!empty($orderplaced->address_state)) {
		$billing_state=$dom->createElement('BillingStateProvince',$this->safeString($orderplaced->address_state,50));
		$billing_state=$billingaddress->appendChild($billing_state);
		}
		
		if(!empty($orderplaced->postcode)) {		
		$billing_zip=$dom->createElement('BillingPostalCode',$this->safeString( $orderplaced->postcode,20 ));
		$billing_zip=$billingaddress->appendChild($billing_zip);
		}

		if(!empty($orderplaced->address_country)) {
		$billing_country_id = '';
		if(ini_get('allow_url_fopen')) {//To check if fopen is "ON"
			$countries = simplexml_load_file( plugin_dir_path( __FILE__ ).'Countries.xml' );		
			foreach( $countries as $country ){
				if( $country->attributes()->Name == $orderplaced->address_country ){
					$billing_country_id = $country->attributes()->Code;
				} 
			}
		}
		if($billing_country_id) {
		$billing_country=$dom->createElement('BillingCountryCode',str_pad($billing_country_id, 3, "0", STR_PAD_LEFT));
		$billing_country=$billingaddress->appendChild($billing_country);
		}
		}
		} //Billing Address
		
		if(isset($orderplaced->shippingfields) && count($orderplaced->shippingfields)) {
			$shippinginfo=$dom->createElement('ShippingInformation','');
			$shippinginfo=$cardholder->appendChild($shippinginfo);
			
			$shippingaddress=$dom->createElement('ShippingAddress','');
			$shippingaddress=$shippinginfo->appendChild($shippingaddress);
			
			if( $orderplaced->address_street_shipping != '' )
			{
				$parts = explode(',', $orderplaced->address_street_shipping);
				$ship_address1=$dom->createElement('ShippingAddress1',$this->safeString($parts[0],60));
				$ship_address1=$shippingaddress->appendChild($ship_address1);
				
				if(count($parts) > 1) {
				$ship_address2=$dom->createElement('ShippingAddress2',$this->safeString($parts[1],60));
				$ship_address2=$shippingaddress->appendChild($ship_address2);
				}
			} else {
				$parts = explode(',', $orderplaced->address_street);
				$ship_address1=$dom->createElement('ShippingAddress1',$this->safeString($parts[0],60));
				$ship_address1=$shippingaddress->appendChild($ship_address1);
				
				if(count($parts) > 1) {
				$ship_address2=$dom->createElement('ShippingAddress2',$this->safeString($parts[1],60));
				$ship_address2=$shippingaddress->appendChild($ship_address2);
				}
			}

			if( $orderplaced->address_suburb_shipping != '' )
			{
				$ship_city=$dom->createElement('ShippingCity',$this->safeString($orderplaced->address_suburb_shipping, 40));
				$ship_city=$shippingaddress->appendChild($ship_city);
			}
			else
			{
				$ship_city=$dom->createElement('ShippingCity',$this->safeString($orderplaced->address_suburb, 40));
				$ship_city=$shippingaddress->appendChild($ship_city);
			}

			if( $orderplaced->address_state_shipping != '' )
			{
				$ship_state=$dom->createElement('ShippingStateProvince',$this->safeString($orderplaced->address_state_shipping, 40));
				$ship_state=$shippingaddress->appendChild($ship_state);
			}
			else
			{
				$ship_state=$dom->createElement('ShippingStateProvince',$this->safeString($orderplaced->address_state, 40));
				$ship_state=$shippingaddress->appendChild($ship_state);
			}
			
			if( $orderplaced->postcode_shipping != '' )
			{
				$ship_zip=$dom->createElement('ShippingPostalCode',$this->safeString($orderplaced->postcode_shipping, 20));
				$ship_zip=$shippingaddress->appendChild($ship_zip);
			}
			else
			{
				$ship_zip=$dom->createElement('ShippingPostalCode',$this->safeString($orderplaced->postcode, 20));
				$ship_zip=$shippingaddress->appendChild($ship_zip);
			}
			
			if( $orderplaced->address_country_shipping != '' )
			{
				$shipping_country_id = '';
				if(ini_get('allow_url_fopen')) {//To check if fopen is "ON"
					$countries = simplexml_load_file( plugin_dir_path( __FILE__ ).'Countries.xml' );
					foreach( $countries as $country ){
						if( $country->attributes()->Name == $orderplaced->address_country_shipping ){
							$shipping_country_id = $country->attributes()->Code;
						} 
					}
				}
				if($shipping_country_id) {
				$ship_country=$dom->createElement('ShippingCountryCode',$shipping_country_id);
				$ship_country=$shippingaddress->appendChild($ship_country);
				}
			}
			else
			{
				$shipping_country_id = '';
				if(ini_get('allow_url_fopen')) {//To check if fopen is "ON"
					$countries = simplexml_load_file( plugin_dir_path( __FILE__ ).'Countries.xml' );
					foreach( $countries as $country ){
						if( $country->attributes()->Name == $orderplaced->address_country ){
							$shipping_country_id = $country->attributes()->Code;
						} 
					}
				}
				if($shipping_country_id) {
				$ship_country=$dom->createElement('ShippingCountryCode',$shipping_country_id);
				$ship_country=$shippingaddress->appendChild($ship_country);
				}
			}
			
		}
		
		if(isset($orderplaced->customfields) && count($orderplaced->customfields))
		{
			$customfieldlist = $dom->createElement('CustomFieldList','');
			$customfieldlist = $cardholder->appendChild($customfieldlist);
			$custonodes = 0;
			for ($p = 0; $p < count($orderplaced->customfields); $p++) 
			{
			if((substr($orderplaced->customfields[$p]['FieldName'], 0, 5) != '{SKU}') && 
			   (substr($orderplaced->customfields[$p]['FieldName'], 0, 14) != '{CAMPAIGNNAME}')&& 
			    $orderplaced->customfields[$p]['FieldName'] != "{CONNECT_CAMPAIGN_URL_ALIAS}" && 
			    strtolower($orderplaced->customfields[$p]['FieldName']) != "payment method" && 
				strtolower($orderplaced->customfields[$p]['FieldName']) != "select payment type" && 
				strtolower($orderplaced->customfields[$p]['FieldName']) != "select payment method" ) {
				$custonodes++;
				$customfield = $dom->createElement('CustomField','');
				$customfield = $customfieldlist->appendChild($customfield);
					
				$fieldname = $dom->createElement('FieldName',$orderplaced->customfields[$p]['FieldName']);
				$fieldname = $customfield->appendChild($fieldname);
					
				$fieldvalue = $dom->createElement('FieldValue',$this->safeString($orderplaced->customfields[$p]['FieldValue'], 500));
				$fieldvalue = $customfield->appendChild($fieldvalue);
			}
			}
			if($custonodes == 0)
			$cardholder->removeChild($customfieldlist);
		}
		
		
		$processtype = 'CreditCard';
		if($orderplaced->creditcardCount > 0 && $this->cardHoldersName != NULL && $this->cardNumber != NULL) {
			$processtype = 'CreditCard';
		} else if($orderplaced->echeckCount > 0 && $orderplaced->ecRouting != NULL) {
			$processtype = 'eCheck';
		} else if($orderplaced->custompaymentCount > 0 && $orderplaced->custompaymentCount != NULL) {
			$processtype = 'custompayment';
		}
		$new_arry =  $this->in_multiarray('payment method','select payment type','select payment method', $orderplaced->customfields);
						 // do something if the given value does not exist in the array
			
		if (is_array($new_arry)) {
			if ( strtolower($new_arry['FieldValue']) == 'creditcard' || strtolower($new_arry['FieldValue']) == 'credit card' ) {
				$processtype = 'CreditCard';
			} else if (strtolower($new_arry['FieldValue']) == 'echeck' || strtolower($new_arry['FieldValue']) == 'e check') {
				$processtype = 'eCheck';
			} else {
				if ($orderplaced->custompaymentField['isRequired'] == 1 && $orderplaced->custompaymentCount > 0 && $orderplaced->paymentnumber == NULL) {
					$errmsg .= 'This field is required.';
				}
				$processtype = 'CnpCustom';
			}
		}

		$paymentmethod=$dom->createElement('PaymentMethod','');
		$paymentmethod=$cardholder->appendChild($paymentmethod);
		if($orderplaced->creditcardCount != 0 && $processtype == 'CreditCard') 
		{
			$payment_type=$dom->createElement('PaymentType','CreditCard');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
		 	$creditcard=$dom->createElement('CreditCard','');
			$creditcard=$paymentmethod->appendChild($creditcard);
			
			$credit_card_name = $orderplaced->ccName;						
			$credit_name=$dom->createElement('NameOnCard',$this->safeString( $credit_card_name, 50));
			$credit_name=$creditcard->appendChild($credit_name);
					
			$credit_number=$dom->createElement('CardNumber',$this->safeString( str_replace(' ', '', $orderplaced->ccNumber), 17));
			$credit_number=$creditcard->appendChild($credit_number);

			$credit_cvv=$dom->createElement('Cvv2',$orderplaced->ccCVN);
			$credit_cvv=$creditcard->appendChild($credit_cvv);

			$credit_expdate=$dom->createElement('ExpirationDate',str_pad($orderplaced->ccExpMonth,2,'0',STR_PAD_LEFT) ."/" .substr($orderplaced->ccExpYear,2,2));
			$credit_expdate=$creditcard->appendChild($credit_expdate);
		} 
		else if($orderplaced->echeckCount != 0 && $processtype == 'eCheck')
		{
			
			$payment_type=$dom->createElement('PaymentType','Check');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
			$echeck=$dom->createElement('Check','');
			$echeck=$paymentmethod->appendChild($echeck);
			
			$ecAccount=$dom->createElement('AccountNumber',$this->safeString( $orderplaced->ecAccount, 17));
			$ecAccount=$echeck->appendChild($ecAccount);
			
			$ecAccount_type=$dom->createElement('AccountType',$orderplaced->ecAccount_type);
			$ecAccount_type=$echeck->appendChild($ecAccount_type);
			
			$ecRouting=$dom->createElement('RoutingNumber',$this->safeString( $orderplaced->ecRouting, 9));
			$ecRouting=$echeck->appendChild($ecRouting);
			
			$ecCheck=$dom->createElement('CheckNumber',$this->safeString( $orderplaced->ecCheck, 10));
			$ecCheck=$echeck->appendChild($ecCheck);
			
			$ecChecktype=$dom->createElement('CheckType',$orderplaced->ecChecktype);
			$ecChecktype=$echeck->appendChild($ecChecktype);
			
			$ecName=$dom->createElement('NameOnAccount',$this->safeString( $orderplaced->ecName, 100));
			$ecName=$echeck->appendChild($ecName);
			
			$ecIdtype=$dom->createElement('IdType',$orderplaced->ecIdtype);
			$ecIdtype=$echeck->appendChild($ecIdtype);
		} else {
			$payment_type=$dom->createElement('PaymentType','CustomPaymentType');
			$payment_type=$paymentmethod->appendChild($payment_type);			
			$CustomPayment=$dom->createElement('CustomPaymentType','');
			$CustomPayment=$paymentmethod->appendChild($CustomPayment);
			$customlabel = RGFormsModel::get_label($orderplaced->custompaymentField);
			if($customlabel == '') $customlabel = 'Custom Payment';
			$CustomPaymentName=$dom->createElement('CustomPaymentName',$this->safeString($customlabel,50));
			$CustomPaymentName=$CustomPayment->appendChild($CustomPaymentName);
			if($orderplaced->paymentnumber != '') {
				$CustomPaymentNumber=$dom->createElement('CustomPaymentNumber',$this->safeString($orderplaced->paymentnumber,50));
				$CustomPaymentNumber=$CustomPayment->appendChild($CustomPaymentNumber);
			}
		}
		
		$total_calculate = 0;
		
		//Products processing
		if(isset($orderplaced->productdetails) && count($orderplaced->productdetails))
		{
			$orderitemlist=$dom->createElement('OrderItemList','');
			$orderitemlist=$order->appendChild($orderitemlist);
			$OptionLabel = '';			
			$p = 101101;  // $p = 0;  Changed 0 to 101100 standardize our ItemID’s
			$products_included = array();
			foreach ( $orderplaced->productdetails as  $pr) 
			{	
				if(isset($pr['OptionValue']) && $pr['OptionValue'] != '' && !in_array($pr['ItemID'], $products_included) && array_key_exists($pr['productField'], $orderplaced->productdetails)) { 
				
					$OptionValue = '';
					$orderitem=$dom->createElement('OrderItem','');
					$orderitem=$orderitemlist->appendChild($orderitem);

					$itemid=$dom->createElement('ItemID',$p++);
					$itemid=$orderitem->appendChild($itemid);				
					$tempName = $pr['ItemName'];
					if(isset($pr['OptionLabel']) && $pr['OptionLabel'] != '')  
					{ 
						$tempName2 = $pr['OptionLabel']; 
					} 
					else 
					{
						$tempName2 = (isset($pr['OptionValue']) && $pr['OptionValue'] != '') ? $pr['OptionValue'] : '';
					}
					$optcost = 0;
					$conditionalfieldlabel = '';
				
					$cost = $pr['UnitPrice'];
					array_push($products_included, $pr['ItemID']);
					foreach($orderplaced->productdetails as $searchopt) { //Search for related products
						if(strtolower($pr['OptionValue']) == strtolower('Field ID ' . $searchopt['ItemID'])) {
							$optcost +=	$searchopt['UnitPrice'];
							array_push($products_included,$searchopt['ItemID']);
							if(isset($searchopt['OptionLabel']) && $searchopt['OptionLabel'] != '')
							$conditionalfieldlabel = $searchopt['OptionLabel'];
							else
							$conditionalfieldlabel = $searchopt['ItemName'];
						}
					}
					$cost = $cost + $optcost;
					if($conditionalfieldlabel != '') {
						$tempName = $tempName . ' ('.$conditionalfieldlabel.')';
					} else {
						$tempName = ($tempName2) ? $tempName . ' ('.$tempName2.')' : $tempName;
					}
					$OptionLabel = $pr['OptionValue'];
					$itemname=$dom->createElement('ItemName',$this->safeString(trim($tempName), 50));
					$itemname=$orderitem->appendChild($itemname);
					
					$quntity=$dom->createElement('Quantity',$pr['Quantity']);
					$quntity=$orderitem->appendChild($quntity);
					if($this->recrngsts == 1){$cost = 0;}else{ $cost = $pr['UnitPrice'];}	
					if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) {
						if($orderplaced->recurring['indefinite'] == 'yes') {
							$Installments = ($orderplaced->recurring['RecurringMethod'] == 'Installment') ? 998 : 999;
						} elseif($orderplaced->recurring['Installments']) {
							$Installments = $orderplaced->recurring['Installments'];
						}
						else {
							$Installments = 999;
						}
							
						if($orderplaced->recurring['RecurringMethod'] == 'Installment') {
										
						$total_calculate += $this->number_format(($cost/$Installments),2,'.','')*$pr['Quantity'];
							
					
						$unitprice=$dom->createElement('UnitPrice',($this->number_format(($cost/$Installments),2,'.','')*100));
						$unitprice=$orderitem->appendChild($unitprice);
						} else {
						$total_calculate += $cost*$pr['Quantity'];
						$unitprice=$dom->createElement('UnitPrice',($cost*100));
						$unitprice=$orderitem->appendChild($unitprice);
						}
					} else {
					$total_calculate += $cost*$pr['Quantity'];
					$unitprice=$dom->createElement('UnitPrice',($cost*100));
					$unitprice=$orderitem->appendChild($unitprice);
					}
					$inc=1;
					//SKU Handling
					if(isset($orderplaced->customfields)&& !empty($orderplaced->customfields)){
					foreach($orderplaced->customfields as $sub)
					{
						if((substr($sub['FieldName'], 0, 5) == '{SKU}') && $sub['FieldValue'] != '') {
							$parts = explode('}{OPTION=', $sub['FieldName']);
							if(count($parts) > 1) //TO handle if product has options
							{
							$id = $parts[0];
							$id = substr($id, 14);
							$val = substr($parts[1], 0, -1);
							}
							else
							{
							$id = substr($parts[0], 14);
							$id = substr($id, 0, -1);
							$val = '';
							}
							if($id == $pr['ItemID'] && $OptionLabel == substr($parts[1],0,-1)) 
							{
								
								if(count($parts) > 1) //TO handle if product has options
								{
									if($OptionLabel != '' && $val != '' && $OptionLabel == substr($parts[1],0,-1))
									{
										$sku_code=$dom->createElement('SKU',$this->safeString($sub['FieldValue']."".$skusuffix, 100));
										$sku_code=$orderitem->appendChild($sku_code);
									}
								} else {
								$sku_code=$dom->createElement('SKU',$this->safeString($sub['FieldValue']."".$skusuffix, 100));
								$sku_code=$orderitem->appendChild($sku_code);
								}
							}
							elseif($id == $pr['ItemID'] && $val == '') {
							
							}
							
						}
						
						}
						}else if($skusuffix != "" )
						{
								$sku_code=$dom->createElement('SKU',$this->safeString($skusuffix, 100));
								$sku_code=$orderitem->appendChild($sku_code);
						}
						foreach(array_reverse($orderplaced->customfields) as $sub)
					    {
						//Campain Handling
						if($inc == 1) {
						
						if((substr($sub['FieldName'], 0, 14) == '{CAMPAIGNNAME}') && $sub['FieldValue'] != '') {
							$cmpparts = explode('}{OPTION=', $sub['FieldName']);
							if(count($cmpparts) > 1) //TO handle if product has options
							{
							$cmpid = $cmpparts[0];
							$cmpid = substr($cmpid, 23);
							$cmpval = substr($cmpparts[1], 0, -1);
							}
							else
							{
							$cmpid = substr($cmpparts[0], 23);
							$cmpid = substr($cmpid, 0, -1);
							$cmpval = '';
							}
							if($cmpid == $pr['ItemID'] && $OptionLabel == substr($cmpparts[1],0,-1)) 
							{
								$inc++;
								if(count($cmpparts) > 1) //TO handle if product has options
								{
									if($OptionLabel != '' && $cmpval != '' && $OptionLabel == substr($cmpparts[1],0,-1))
									{
										$cmp_name=$dom->createElement('Campaign',$this->safeString($sub['FieldValue'], 80));
										$cmp_name=$orderitem->appendChild($cmp_name);
									}
								} else {
								$cmp_name=$dom->createElement('Campaign',$this->safeString($sub['FieldValue'], 80));
								$cmp_name=$orderitem->appendChild($cmp_name);
								}
							}
							elseif($cmpid == $pr['ItemID'] && $cmpval == '') {
							
							}
							
						}
						}
					}
				}
			}

			//Products which are not having options	
		
			foreach ( $orderplaced->productdetails as  $pr) 
			{	
		
				if (array_search('{SKIPIF0}', $pr,true) == NULL) {
				if(!in_array($pr['ItemID'], $products_included) && $pr['productField'] == NULL) {
					$OptionValue = '';	
					$orderitem=$dom->createElement('OrderItem','');
					$orderitem=$orderitemlist->appendChild($orderitem);
	
					$itemid=$dom->createElement('ItemID',$p++);
					$itemid=$orderitem->appendChild($itemid);				
					$tempName = $pr['ItemName']; 
					$tempName2 = (isset($pr['OptionValue']) && $pr['OptionValue'] != '') ? $pr['OptionValue'] : '';					
					$cost = $pr['UnitPrice'];
					$tempName = ($tempName2) ? $tempName . ' ('.$tempName2.')' : $tempName;
					$OptionLabel = $pr['OptionValue'];
					$itemname=$dom->createElement('ItemName',$this->safeString(trim($tempName), 50));
					$itemname=$orderitem->appendChild($itemname);

					$quntity=$dom->createElement('Quantity',$pr['Quantity']);
					$quntity=$orderitem->appendChild($quntity);
					if($this->recrngsts == 1){$cost = 0;}else{ $cost = $pr['UnitPrice'];}	
					if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) {
						if($orderplaced->recurring['indefinite'] == 'yes') {
							$Installments = ($orderplaced->recurring['RecurringMethod'] == 'Installment') ? 998 : 999;
						} elseif($orderplaced->recurring['Installments']) {
							$Installments = $orderplaced->recurring['Installments'];
						}
						else {
							$Installments = 999;
						}
						
						if($orderplaced->recurring['RecurringMethod'] == 'Installment') {	
						  				
						$total_calculate += $this->number_format(($cost/$Installments),2,'.','')*$pr['Quantity'];						
						$unitprice=$dom->createElement('UnitPrice',($this->number_format(($cost/$Installments),2,'.','')*100));
						$unitprice=$orderitem->appendChild($unitprice);
						} else {
						$total_calculate += $cost*$pr['Quantity'];
						$unitprice=$dom->createElement('UnitPrice',($cost*100));
						$unitprice=$orderitem->appendChild($unitprice);
						}
					} else {
					$total_calculate += $cost*$pr['Quantity'];
					$unitprice=$dom->createElement('UnitPrice',($cost*100));
					$unitprice=$orderitem->appendChild($unitprice);
					}
					$inccust=1;
					//SKU Handling
					//print_r(array_reverse($orderplaced->customfields));exit;
					
					if(isset($orderplaced->customfields)&& !empty($orderplaced->customfields)){
					foreach($orderplaced->customfields as $sub)
					{ 
						if((substr($sub['FieldName'], 0, 5) == '{SKU}') && $sub['FieldValue'] != '') {
							$parts = explode('}{OPTION=', $sub['FieldName']);
							if(count($parts) > 1) //TO handle if product has options
							{
							$id = $parts[0];
							$id = substr($id, 14);
							$val = substr($parts[1], 0, -1);
							}
							else
							{
							$id = substr($parts[0], 14);
							$id = substr($id, 0, -1);
							$val = '';
							}
							if($id == $pr['ItemID'] && $OptionLabel == substr($parts[1],0,-1)) 
							{
								
								if(count($parts) > 1) //TO handle if product has options
								{
									if($OptionLabel != '' && $val != '' && $OptionLabel == substr($parts[1],0,-1))
									{
										$sku_code=$dom->createElement('SKU',$this->safeString($sub['FieldValue']."".$skusuffix, 100));
										$sku_code=$orderitem->appendChild($sku_code);
									}
								} else {
								$sku_code=$dom->createElement('SKU',$this->safeString($sub['FieldValue']."".$skusuffix, 100));
								$sku_code=$orderitem->appendChild($sku_code);
								}
							}
							elseif($id == $pr['ItemID'] && $val == '') {
							
							}
							
						}
						
					}
					}elseif($skusuffix != "" )
						{
								$sku_code=$dom->createElement('SKU',$this->safeString($skusuffix, 100));
								$sku_code=$orderitem->appendChild($sku_code);
						}
					foreach(array_reverse($orderplaced->customfields) as $sub)
					{	
						//Campain Handling
						if($inccust == 1)
						{
						
						if((substr($sub['FieldName'], 0, 14) == '{CAMPAIGNNAME}') && $sub['FieldValue'] != '') {
							
						    $cmpparts = explode('}{OPTION=', $sub['FieldName']);
							if(count($cmpparts) > 1) //TO handle if product has options
							{
							$cmpid = $cmpparts[0];
							$cmpid = substr($cmpid, 23);
							$cmpval = substr($cmpparts[1], 0, -1);
							}
							else
							{
							$cmpid = substr($cmpparts[0], 23);
							$cmpid = substr($cmpid, 0, -1);
							$cmpval = '';
							}
							
							if($cmpid == $pr['ItemID'] && $OptionLabel == substr($cmpparts[1],0,-1)) 
							{
							    $inccust = $inccust + 1;
							
								if(count($cmpparts) > 1) //TO handle if product has options
								{
									if($OptionLabel != '' && $cmpval != '' && $OptionLabel == substr($cmpparts[1],0,-1))
									{
										$cmp_name=$dom->createElement('Campaign',$this->safeString($sub['FieldValue'], 80));
										$cmp_name=$orderitem->appendChild($cmp_name);
									}
								} else {
								$cmp_name=$dom->createElement('Campaign',$this->safeString($sub['FieldValue'], 80));
								$cmp_name=$orderitem->appendChild($cmp_name);
								}
							}
							elseif($cmpid == $pr['ItemID'] && $cmpval == '') {
							
							}
						  }
						}
					}
				}
			}
		   }	
		}
		$ShippingValue = 0;
		if(isset($orderplaced->shippingfields) && count($orderplaced->shippingfields)) {
			$shipping=$dom->createElement('Shipping','');
			$shipping=$order->appendChild($shipping);
			foreach ( $orderplaced->shippingfields as  $sp) 
			{
				$ShippingValue_Local = 0;
				$shipping_method=$dom->createElement('ShippingMethod',$this->safeString($sp['ShippingMethod'],50));
				$shipping_method=$shipping->appendChild($shipping_method);
				$ShippingValue_Local = $sp['ShippingValue'];
				if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) {
					if($orderplaced->recurring['indefinite'] == 'yes' ) {
						$Installments = 999;
					} elseif($orderplaced->recurring['Installments']) {
						$Installments = $orderplaced->recurring['Installments'];
					}
					else {
						$Installments = 999;
					}
					if($orderplaced->recurring['RecurringMethod'] == 'Installment') {
					$ShippingValue_Local = $ShippingValue_Local/$Installments;
					} else {
					$ShippingValue_Local = $ShippingValue_Local;
					}
				}
				$ShippingValue += $this->number_format($ShippingValue_Local, 2, '.', '');
				$shipping_value = $dom->createElement('ShippingValue', $this->number_format($ShippingValue_Local, 2, '.', '')*100);
				$shipping_value=$shipping->appendChild($shipping_value);				
			}
		}
		
	
		$receipt=$dom->createElement('Receipt','');
		$receipt=$order->appendChild($receipt);
		if($configValues['email_customer'] == 'yes') 
		{
			$email_sendreceipt =$dom->createElement('SendReceipt',"true");
			$email_sendreceipt=$receipt->appendChild($email_sendreceipt);
		}
		else
		{
			$email_sendreceipt=$dom->createElement('SendReceipt',"false");
			$email_sendreceipt=$receipt->appendChild($email_sendreceipt);		
		}
		$recipt_lang=$dom->createElement('Language','ENG');
		$recipt_lang=$receipt->appendChild($recipt_lang);
		
		if( $configValues['OrganizationInformation'] != '')
		{
			$recipt_org=$dom->createElement('OrganizationInformation',$this->safeString($configValues['OrganizationInformation'], 1500));
			$recipt_org=$receipt->appendChild($recipt_org);
		}
		
		if( $configValues['TermsCondition'] != '')
		{
			$recipt_terms=$dom->createElement('TermsCondition',$this->safeString($configValues['TermsCondition'], 1500));
			$recipt_terms=$receipt->appendChild($recipt_terms);
		}
		
		    $recipt_email=$dom->createElement('EmailNotificationList','');
			$recipt_email=$receipt->appendChild($recipt_email);			
										
			$email_note=$dom->createElement('NotificationEmail','');
			$email_note=$recipt_email->appendChild($email_note);
		
		
		$transation=$dom->createElement('Transaction','');
		$transation=$order->appendChild($transation);

		$trans_type=$dom->createElement('TransactionType','Payment');
		$trans_type=$transation->appendChild($trans_type);

		$trans_desc=$dom->createElement('DynamicDescriptor','DynamicDescriptor');
		$trans_desc=$transation->appendChild($trans_desc); 
		
		if((isset($orderplaced->recurring)) && ($orderplaced->recurring['isRecurring'] == 'yes')) 
		{
			$trans_recurr=$dom->createElement('Recurring','');
			$trans_recurr=$transation->appendChild($trans_recurr);
			if($orderplaced->recurring['indefinite'] == 'yes' && $orderplaced->recurring['RecurringMethod'] == 'Installment') {
				$total_installment=$dom->createElement('Installment',998);
				$total_installment=$trans_recurr->appendChild($total_installment);
			}
			elseif  ( $orderplaced->recurring['Installments'] )
			{
				$total_installment=$dom->createElement('Installment',$orderplaced->recurring['Installments']);
				$total_installment=$trans_recurr->appendChild($total_installment);
			}
			else
			{
				$total_installment=$dom->createElement('Installment',999);
				$total_installment=$trans_recurr->appendChild($total_installment);
			}			
			$total_periodicity=$dom->createElement('Periodicity',$orderplaced->recurring['Periodicity']);
			$total_periodicity=$trans_recurr->appendChild($total_periodicity);
			
			if( $orderplaced->recurring['RecurringMethod'] != '' ) {
				$RecurringMethod=$dom->createElement('RecurringMethod',$orderplaced->recurring['RecurringMethod']);
				$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			} else {
				$RecurringMethod=$dom->createElement('RecurringMethod','Subscription');
				$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			}	
		}
		
		$coupontotal = $coupontotal_grandtotal = $coupontotal_grandtotal_calculate = 0;
		$couponcodes = '';		
		if(count($orderplaced->couponfields) > 0) {			
			foreach($orderplaced->couponfields as $coupon) {
				if (isset($orderplaced->shippingfields) && count($orderplaced->shippingfields)) {
					$temp_total = $total_calculate + $ShippingValue;
				} else {
					$temp_total = $total_calculate;
				}	
				$coupontotal += number_format(GFCommon::to_number(GFCoupons::get_discount($coupon,$temp_total)),2, '.', '');
				if($orderplaced->recurring['RecurringMethod'] == 'Installment' && $orderplaced->recurring['isRecurring'] == 'yes') {
					if ($coupon['type'] == 'flat') {
						$coupontotal = number_format(GFCommon::to_number($coupon['amount']/$Installments),2, '.', '');
					}
					if ($coupon['type'] == 'percentage') {
					
					}
									
				}			
				$couponcodes .= $coupon['code'] . ';';
			}
			$coupontotal_grandtotal = $coupontotal;
			$coupontotal_grandtotal_calculate = $coupontotal;
			$coupontotal = $coupontotal * 100;			
		}
		$trans_totals=$dom->createElement('CurrentTotals','');
		$trans_totals=$transation->appendChild($trans_totals);
		
		if($coupontotal > 0) {		
			if(isset($orderplaced->recurring) && $orderplaced->recurring['isRecurring'] == 'yes' && $orderplaced->recurring['RecurringMethod'] == 'Installment') {
				$total_discount=$dom->createElement('TotalDiscount', $coupontotal_grandtotal_calculate * 100);
				$total_discount=$trans_totals->appendChild($total_discount);
			} else {
				$total_discount=$dom->createElement('TotalDiscount', $coupontotal);
				$total_discount=$trans_totals->appendChild($total_discount);
			}
		}
		
		if(isset($orderplaced->shippingfields) && count($orderplaced->shippingfields)) {
			$total_ship=$dom->createElement('TotalShipping',$ShippingValue*100);
			$total_ship=$trans_totals->appendChild($total_ship);
		}
		
		if(isset($orderplaced->recurring) && $orderplaced->recurring['isRecurring'] == 'yes' && $orderplaced->recurring['RecurringMethod'] == 'Installment') {
			if($orderplaced->recurring['indefinite'] == 'yes' && $orderplaced->recurring['RecurringMethod'] == 'Installment') {
				$Installments = 998;
			} 
			elseif($orderplaced->recurring['Installments']) {
				$Installments = $orderplaced->recurring['Installments'];
			}
			else {
				$Installments = 999;
			}
			$Total = $total_calculate;
		} else {
			$Total = $total_calculate;
		}
		
		$GrandTotal = ($Total + $ShippingValue) - $coupontotal_grandtotal_calculate;
		$total_amount=$dom->createElement('Total',($GrandTotal*100));
		$total_amount=$trans_totals->appendChild($total_amount);
		
		if(count($coupontotal) > 0 && $couponcodes != '') {			
			$trans_coupon=$dom->createElement('CouponCode',$this->safeString(substr($couponcodes,0,-1), 50));
			$trans_coupon=$transation->appendChild($trans_coupon);		
		}
		
		if($coupontotal > 0) {
			if(isset($orderplaced->recurring) && $orderplaced->recurring['isRecurring'] == 'yes' && $orderplaced->recurring['RecurringMethod'] == 'Installment') {				
				$trans_coupon_discount=$dom->createElement('TransactionDiscount', $coupontotal_grandtotal_calculate * 100);
				$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);				
			} else {
				$trans_coupon_discount=$dom->createElement('TransactionDiscount',$this->number_format($coupontotal, 2, '.', ''));
				$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);
			}
		}
		
		$strParam =$dom->saveXML();	
//echo $strParam;exit;
		return $strParam;
	}
	
	public function number_format($number, $decimals = 2,$decsep = '', $ths_sep = '') {
		$parts = explode('.', $number);
		if(count($parts) > 1) {
			return $parts[0].'.'.substr($parts[1],0,$decimals);
		} else {
			return $number;
		}
	}

	/**
	* send the Click & Pledge payment request and retrieve and parse the response
	*
	* @return response object from Click & Pledge
	* @param string $xml Click & Pledge payment request as an XML document, as per Click & Pledge specifications
	*/
	private function sendPayment($xml,$item_count) {
		if ($item_count >0)	{		
		$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		$client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
		$soapParams = array('instruction'=>$xml);		 
		$response = $client->Operation($soapParams);
		return $response;
		} else {
		$response = 'Item-0';
		return $response;
		}
	}
}
