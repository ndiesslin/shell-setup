<div class='wrap'>
 
 <h3><img alt="<?php _e("Click & Pledge Transactions", "gfcnp") ?>" height="50" src="<?php echo plugins_url(null, __FILE__)?>/images/cnp logo.png" style="vertical-align: middle;padding-right: 12px;"/> Gravity Forms Click & Pledge Payments</h3>

<?php 
	 $cnptransactios = GFCnpData::get_cnpaccountsinfo();
	?>
<script>
		jQuery(document).ready(function(){
			<?php if ($cnptransactios > 0){?>
			jQuery("#cnpfrmregister").hide();
			jQuery("#cnpfrmsettings").show();
			
			<?php } else {?>
			jQuery("#cnpfrmregister").show();
			jQuery('#cnpfrmsettings').hide();
			<?php }?>
			
			
			jQuery(".cnpchangeacnt").click(function(){
  			jQuery("#cnpfrmregister").show();
			jQuery('#cnpfrmsettings').hide();
		      });
			jQuery(".cnpsttings").click(function(){
  			jQuery("#cnpfrmregister").hide();
			jQuery('#cnpfrmsettings').show();
		      });
		});
	</script>

	<div id="cnpfrmsettings">
	<div  style="float: right;" class="cnpchangeacnt" ><a name="chngacnt" href="#">Change User</a></div>
	<form action="<?php echo $this->scriptURL; ?>" method="post" id="cnp-settings-form">
	<table class="form-table">

	<tr>
	<th>Account ID <span style="color:red;">*</span></th>
	<td>
	<?php  $cnptransactios = GFCnpData::getCnPAccountsList();
			foreach($cnptransactios as $v) {
			   if ($v['AccountId'] == $this->frm->AccountID) {
				  $found = true;
				   $cnpactiveuser = $v['AccountId'];
			   }
			} 
		 if(!isset($found)) {$cnpactiveuser = $cnptransactios[0]['AccountId'];} 
		 $cnp_acceptedcards = GFCnpData::getCnPactivePaymentList($cnpactiveuser);

		?>	
		<div class="col-sm-8">
		<select name="AccountID" id="AccountID" class="form-control">
		<?php foreach($cnptransactios as $cnpacnts){?>
		<option value=<?php echo $cnpacnts['AccountId'];?> <?php if($cnpacnts['AccountId'] == $cnpactiveuser){echo "selected";} ?>><?php echo $cnpacnts['AccountId'];?>[<?php echo stripslashes($cnpacnts['Organization']);?>] </option>
		<?php }?>
		</select> <a href="#" id="rfrshtokens">Refresh Accounts </a>
		<div id="dvldimg" class="cnp_loader" style="display:none"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/loading.gif'; ?>" alt="Loading" /></div><div class="col-sm-2"></div></div> 
	
		</td>
	</tr>
	<!--	
		<tr valign='top'>
			<th>Default Order Mode</th>
			<td>
				<label><input type="radio" name="useTest" value="Y" <?php //echo checked($this->frm->useTest, 'Y'); ?> />&nbsp;Test</label>
				&nbsp;&nbsp;<label><input type="radio" name="useTest" value="N" <?php //echo checked($this->frm->useTest, 'N'); ?> />&nbsp;Production</label><br>
				Process transactions in Test Mode or Production Mode via the Click & Pledge Test account (www.clickandpledge.com).
			</td>
		</tr>-->
		
		<tr valign='top'>
			<th>Send Receipt to Patron</th>
			<td>
				<input type="checkbox" name="email_customer" id="email_customer" value="yes" <?php  if(isset($this->frm->email_customer) && $this->frm->email_customer =='yes') { ?>checked<?php } ?>>		
			</td>
		</tr>
		
		<tr valign='top' id='OrganizationInformation_tr'>
			<th>Receipt Header</th>
			<td>
				<textarea name="OrganizationInformation" id="OrganizationInformation" class="regular-text" rows="4" cols="53"><?php echo esc_attr($this->frm->OrganizationInformation); ?></textarea><br>
Maximum: 1500 characters, the following HTML tags are allowed:
&lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;.  You have <span id="OrganizationInformation_countdown">1500</span> characters left.				
			</td>
		</tr>
			<tr valign='top' id='TermsCondition_tr'>
			<th>Terms & Conditions</th>
			<td>
				<textarea name="TermsCondition" id="TermsCondition" class="regular-text" rows="4" cols="53"><?php echo esc_attr($this->frm->TermsCondition); ?></textarea><br>
To be added at the bottom of the receipt. Typically the text provides proof that the patron has read & agreed to the terms & conditions. The following HTML tags are allowed:
&lt;P&gt;&lt;/P&gt;&lt;BR /&gt;&lt;OL&gt;&lt;/OL&gt;&lt;LI&gt;&lt;/LI&gt;&lt;UL&gt;&lt;/UL&gt;. <br>Maximum: 1500 characters, You have <span id="TermsCondition_countdown">1500</span> characters left.				
			</td>
		</tr>
	<tr valign='top' id="Periodicity_tr">
			<th>Payment Methods</th>
			<td id="cnpacceptedcards">
				<?php
					echo $cnp_acceptedcards;
					
				?>
			</td>
		</tr>
		
		
		<script>
			
jQuery(document).ready(function(){
	
	jQuery('#AccountID').change(function() {
	
		 	 jQuery.ajax({
				  type: "POST", 
				 url: ajaxurl ,
				  data: {
						'action':'cnp_getonchangeAccounts',
					  	'Accountid':jQuery('#AccountID').val(),
					  },
				 
				  cache: false,
				  beforeSend: function() {
				  },
				  complete: function() {
				  },	
				  success: function(htmlText) {
				   if(htmlText !== "")
				  { 
					
					jQuery("#cnpacceptedcards").html(htmlText);  
				  }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	
	
	
		 jQuery('#rfrshtokens').on('click', function() 
		 {  
		 	 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_refreshAccounts',
					  	'Accountid':jQuery('#AccountID').val(),
					  },
				   cache: false,
				  beforeSend: function() {
					jQuery('.cnp_loader').show();
					jQuery("#cnp_login").html("<option>Loading............</option>");
					},
					complete: function() {
						jQuery('.cnp_loader').hide();
						
					//	$("#cnp_btncode").prop('value', 'Login');
					},	
				  success: function(htmlText) {
				  if(htmlText !== "")
				  {
					jQuery("#AccountID").html(htmlText);  
				  //$(".cnpcode").show();
				  }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
				limitText(jQuery('#OrganizationInformation'),jQuery('#OrganizationInformation_countdown'),1500);
				
				//limitText(jQuery('#ThankYouMessage'),jQuery('#ThankYouMessage_countdown'),500);
				
				limitText(jQuery('#TermsCondition'),jQuery('#TermsCondition_countdown'),1500);
				
				jQuery( "form" ).submit(function( event ) {
										
					if(jQuery('#AccountID').val() == '')
					{
						alert('Please enter AccountID');
						jQuery('#AccountID').focus();
						return false;
					}
					
					/*if(jQuery('#AccountGuid').val() == '')
					{
						alert('Please enter AccountGuid');
						jQuery('#AccountGuid').focus();
						return false;
					}
					
					
					var cards = 0;
					if(jQuery('#Visa').is(':checked'))
					{
						cards++;
					}
					if(jQuery('#American_Express').is(':checked'))
					{
						cards++;
					}
					if(jQuery('#Discover').is(':checked'))
					{
						cards++;
					}
					if(jQuery('#MasterCard').is(':checked'))
					{
						cards++;
					}
					if(jQuery('#JCB').is(':checked'))
					{
						cards++;
					}
					
					if(cards == 0) 
					{
						alert('Please select at least  one card');
						jQuery('#Visa').focus();
						return false;
					}*/
				});
				
				function limitText(limitField, limitCount, limitNum) {
					if (limitField.val().length > limitNum) {
						limitField.val( limitField.val().substring(0, limitNum) );
					} else {
						limitCount.html (limitNum - limitField.val().length);
					}
				}
				
				
				//OrganizationInformation
				jQuery('#OrganizationInformation').keydown(function(){
					limitText(jQuery('#OrganizationInformation'),jQuery('#OrganizationInformation_countdown'),1500);
				});
				jQuery('#OrganizationInformation').keyup(function(){
					limitText(jQuery('#OrganizationInformation'),jQuery('#OrganizationInformation_countdown'),1500);
				});
				//ThankYouMessage
				/*jQuery('#ThankYouMessage').keydown(function(){
					limitText(jQuery('#ThankYouMessage'),jQuery('#ThankYouMessage_countdown'),500);
				});
				jQuery('#ThankYouMessage').keyup(function(){
					limitText(jQuery('#ThankYouMessage'),jQuery('#ThankYouMessage_countdown'),500);
				});*/
				//TermsCondition
				jQuery('#TermsCondition').keydown(function(){
					limitText(jQuery('#TermsCondition'),jQuery('#TermsCondition_countdown'),1500);
				});
				jQuery('#TermsCondition').keyup(function(){
					limitText(jQuery('#TermsCondition'),jQuery('#TermsCondition_countdown'),1500);
				});
				
			});
			</script>
			
	</table>
	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
	<input type="hidden" name="action" value="save" />
	<?php wp_nonce_field('save', $this->menuPage . '_wpnonce', false); ?>
	</p>
</form>
</div>
 
  <div id="cnpfrmregister">
	<?php $cnptransactios = GFCnpData::get_cnpaccountsinfo();
	  if ($cnptransactios > 0){?>
	<div  style="float: right;" class="cnpsttings" ><a name="cnpsettings" href="#">Go to Settings</a></div>
	<?php }?>
		<form action="<?php echo $this->scriptURL; ?>" method="post" id="cnp-settings-form">
		<div role="tabpanel">
			
			<div class="tab-content">
			<h2>Login<hr/></h2>
			<div id="content" class="col-sm-12">
     
			  <p>1. Enter the email address associated with your Click & Pledge account, and click on <strong>Get the Code</strong>.</p>
			  <p>2. Please check your email inbox for the Login Verification Code email.</p>
			  <p>3. Enter the provided code and click <strong>Login</strong>.</p>
	  <table class="form-table">
		<tr>
		<td>
				<div class="form-group required">
							
							<div class="col-sm-10">
								<input type="textbox" id="cnp_emailid" placeholder="Connect User Name" name="cnp_emailid" maxlength="50" min="6" size="40" >
							</div>
						
						</div>
			</td>
		</tr>
			
			<tr>
			
			<td>
				<div class="form-group required cnploaderimage" style="display:none">
							
							<div class="col-sm-10">
							<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/loading.gif'; ?>">
							</div>
						</div>
						<div class="form-group required cnpcode" style="display:none">
							
							<div class="col-sm-10">
							<input type="textbox" id="cnp_code" placeholder="Code" name="cnp_code" size="40">
							</div>
						</div>
			</td>
		</tr>
				
						
					<tr>
			
			<td>	
						<div class="form-group required">
						
							<div class="col-sm-10">
							<input type="button" id="cnp_btncode" value="Get the code" name="cnp_btncode" >
							</div>
						</div>				</div>
			</td>
		</tr><tr>
			
			<td>
						<div class="form-group required cnperror" style="display:none">
						
							<div class="col-sm-10">
							<span class="gf_keystatus_invalid_text text-danger">Sorry but we cannot find the email in our system. Please try again.</span>
							<span class="gf_keystatus_valid_text text-success"></span>
							
							</div>
						</div>
										</div>
			</td>
		</tr>
			</table>
			 </div>
			</div></form>
		
			<script>
		
		function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}
				jQuery('#cnp_emailid').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
		jQuery('#cnp_btncode').on('click', function() 
		 {  
		 	 if(jQuery('#cnp_btncode').val() == "Get the code")
			 {
			 var cnpemailid = jQuery('#cnp_emailid').val();
			//	var ajaxurl = "admin-ajax.php" 
			 if(jQuery('#cnp_emailid').val() != "" && validateEmail(cnpemailid))
			 {
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_getcode',
						'cnpemailid' : cnpemailid
					  },
					cache: false,
					beforeSend: function() {
					jQuery('.cnploaderimage').show();
					jQuery(".cnperror").hide();
					},
					complete: function() {
					jQuery('.cnploaderimage').hide();
						
					},	
				  success: function(htmlText) { 
				  var obj = jQuery.parseJSON(htmlText);
					
				 
				  if(obj == "Code has been sent successfully")
				  {
				  jQuery(".cnpcode").show();
				  jQuery("#cnp_btncode").prop('value', 'Login');
				  jQuery(".text-danger").html("");
				  jQuery(".text-success").html("");
				  jQuery(".cnperror").show();
				  jQuery(".text-success").html("Please enter the code sent to your email");
				  }
				  else if(obj.Message !="") 
				  {
				  
				  	jQuery(".cnperror").show();
				  
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			  else{
			  alert("Please enter valid connect user name");
			  jQuery('#cnp_emailid').focus();
			  return false;
			  }
			 }
			 if(jQuery('#cnp_btncode').val() == "Login")
			 {
			 	 var cnpemailid = jQuery('#cnp_emailid').val().trim();;
				  var cnpcode = jQuery('#cnp_code').val().trim();;
			 if(cnpemailid != "" && cnpcode != "")
			 {
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_gfgetAccounts',
						'cnpemailid' : cnpemailid,
					  	'cnpcode' : cnpcode
					  },
				  cache: false,
				  beforeSend: function() {
					jQuery("#cnp_btncode").prop('value', 'Loading....');
					jQuery("#cnp_btncode").prop('disabled', 'disabled');
					},
					complete: function() {
						//$('.cnploaderimage').hide();
						//$("#cnp_btncode").prop('value', 'Login');
					},	
				  success: function(htmlText) {
				console.log(htmlText);
				  if(htmlText !== "error")
				  {
				      jQuery('#cnp_emailid').val("");
					  jQuery('#cnp_code').val("");
  				  	  jQuery(".cnpcode").hide();
					  jQuery("#cnp_btncode").prop('disabled', '');
				      jQuery("#cnp_btncode").prop('value', 'Get the code');
					  jQuery("#cnpfrmregister").hide();
					  location.reload();
					  jQuery("#cnpfrmsettings").show();
        			console.log('add success');
				  }
				  else
				  {
				  jQuery(".text-danger").html("");
				  jQuery(".text-success").html("");
				  jQuery(".cnperror").show();
				  jQuery(".text-danger").html("Invalid");
				  jQuery("#cnp_btncode").prop('value', 'Login');
					jQuery("#cnp_btncode").prop('disabled', false);
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			 }
			 else if(jQuery('#cnp_emailid').val() == "")
			 {
			  alert("Please enter connect user name");
			  return false;
			 }
		 
		
		 });
		
	</script>
			</div>
<script>
(function($) {

	/**
	* check whether both the sandbox (test) mode and Stored Payments are selected,
	* show warning message if they are
	*/
	function setVisibility() {
		var	useTest = ($("input[name='useTest']:checked").val() == "Y"),
			useBeagle = ($("input[name='useBeagle']:checked").val() == "Y"),
			useStored = ($("input[name='useStored']:checked").val() == "Y");

		function display(element, visible) {
			if (visible)
				element.css({display: "none"}).show(750);
			else
				element.hide();
		}

		display($("#gfeway-opt-admin-stored-test"), (useTest && useStored));
		display($("#gfeway-opt-admin-stored-beagle"), (useBeagle && useStored));
		display($("#gfeway-opt-admin-beagle-address"), useBeagle);
	}

	$("#cnp-settings-form").on("change", "input[name='useTest'],input[name='useBeagle'],input[name='useStored']", setVisibility);

	//setVisibility();

})(jQuery);
</script>

