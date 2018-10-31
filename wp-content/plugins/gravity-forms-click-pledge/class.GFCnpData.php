<?php
class GFCnpData{


    public static function update_table(){
        global $wpdb;
			$table_name = self::get_cnp_table_name();
			$settingstable_name = self::get_cnp_settingsinfo();
			$tokentable_name    = self::get_cnp_tokeninfo();
			$accountstable_name = self::get_cnp_accountsinfo();
		
        if ( ! empty($wpdb->charset) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) )
            $charset_collate .= " COLLATE $wpdb->collate";

        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE $table_name (
              id mediumint(8) unsigned not null auto_increment,
              form_id mediumint(8) unsigned not null,
              is_active tinyint(1) not null default 1,
              meta longtext,
              PRIMARY KEY  (id),
              KEY form_id (form_id)
            )$charset_collate;";

        dbDelta($sql);

        $table_name = self::get_transaction_table_name();
        $sql = "CREATE TABLE $table_name (
              id mediumint(8) unsigned not null auto_increment,
              entry_id int(10) unsigned not null,
              transaction_type varchar(15),
              subscription_id varchar(50),
              transaction_id varchar(50),
              is_renewal tinyint(1) not null default 0,
              amount decimal(19,2),
              date_created datetime,
              PRIMARY KEY  (id),
              KEY txn_id (transaction_id)
            )$charset_collate;";

        dbDelta($sql);
		
		/*$prevaccounttable =  $wpdb->prefix . 'cnpaccountsinfo';
		$prevsettingtable =  $wpdb->prefix . 'cnpsettingsinfo';
		$prevtokentable   =  $wpdb->prefix . 'cnptokeninfo';
		$cnpselcttable = "show tables like '".$accountstable_name."'";
		$cnpselrowcount = $wpdb->get_var( $cnpselcttable );
		if($cnpselrowcount == ""){
			
			$cnpselcttable = "show tables like '".$prevaccounttable."'";
		    $cnpselrowcount = $wpdb->get_var( $cnpselcttable );
			if($cnpselrowcount != ""){
				$cnpselrowcount = $wpdb->get_var("RENAME TABLE ".$prevaccounttable." TO ".$accountstable_name.",".$prevsettingtable." TO ".$settingstable_name.",".$prevtokentable." TO ".$tokentable_name);
			}
		
		}*/
		
		$settingssql = "CREATE TABLE $settingstable_name (
			  `cnpsettingsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpsettingsinfo_clientid` varchar(255)  NOT NULL,
			  `cnpsettingsinfo_clentsecret` varchar(255) NOT NULL,
			  `cnpsettingsinfo_granttype` varchar(255) NOT NULL,
			  `cnpsettingsinfo_scope` varchar(255) NOT NULL,
			   PRIMARY KEY (`cnpsettingsinfo_id`)
			) $charset_collate;";
			dbDelta( $settingssql );
			
			$tokensql = "CREATE TABLE $tokentable_name (
 			`cnptokeninfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnptokeninfo_username` varchar(255)  NOT NULL,
			  `cnptokeninfo_code` varchar(255) NOT NULL,
			  `cnptokeninfo_accesstoken` text  NOT NULL,
			  `cnptokeninfo_refreshtoken` text  NOT NULL,
			  `cnptokeninfo_date_added` datetime NOT NULL,
			  `cnptokeninfo_date_modified` datetime NOT NULL,
			  PRIMARY KEY (`cnptokeninfo_id`)) $charset_collate;";
		dbDelta( $tokensql );
			
			$accountssql = "CREATE TABLE $accountstable_name (
 			  `cnpaccountsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpaccountsinfo_orgid` varchar(100) NOT NULL,
  			  `cnpaccountsinfo_orgname` varchar(250) NOT NULL,
  	          `cnpaccountsinfo_accountguid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userfirstname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userlastname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_crtdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `cnpaccountsinfo_crtdby` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`cnpaccountsinfo_id`)
			) $charset_collate;";
		dbDelta( $accountssql );
			$cnpsql= "SELECT count(*) FROM ". $settingstable_name;
			$rowcount = $wpdb->get_var( $cnpsql );
			if($rowcount == 0)
			{
					$cnpfldname = 'connectwordpressplugin';
					$cnpfldtext = 'zh6zoyYXzsyK9fjVQGd8m+ap4o1qP2rs5w/CO2fZngqYjidqZ0Fhbhi1zc/SJ5zl';
					$cnpfldpwd = 'password';
					$cnpfldaccsid = 'openid profile offline_access';


					$wpdb->insert( 
						$settingstable_name, 
						array( 
							'cnpsettingsinfo_clientid' => $cnpfldname, 
							'cnpsettingsinfo_clentsecret' => $cnpfldtext, 
							'cnpsettingsinfo_granttype' => $cnpfldpwd,
							'cnpsettingsinfo_scope' => $cnpfldaccsid,
						) 
					);
			}
		$prevaccounttable =  $wpdb->prefix . 'cnpaccountsinfo';
		$cnpselcttable = "show tables like '".$prevaccounttable."'";
		$cnpselrowcount = $wpdb->get_var( $cnpselcttable );
		if($cnpselrowcount!= ""){
			
			
			$prevcnpsqlacounts= "SELECT count(*) FROM ". $prevaccounttable;
			$prevrowcountaccount = $wpdb->get_var( $prevcnpsqlacounts );
			if($prevrowcountaccount != 0)
			{
				$cnpsqlacounts= "SELECT count(*) FROM ". $accountstable_name;
				$rowcountaccount = $wpdb->get_var( $cnpsqlacounts );
				if($rowcountaccount == 0)
				{
				  $cnpselrowcount = $wpdb->get_var("INSERT INTO  ".$accountstable_name ." SELECT * FROM $wpdb->prefix".cnpaccountsinfo);
					$newreclastid = $wpdb->insert_id;
					if($newreclastid!=""){ 
						// $delprevaccountsql = "DROP TABLE $prevaccounttable";
   						// $wpdb->query($delprevaccountsql);
					};
				}
			}
		}
		$prevaccounttabletoken =  $wpdb->prefix . 'cnptokeninfo';
		$cnpselcttabletoken = "show tables like '".$prevaccounttabletoken."'";
		$cnpselrowcounttoken = $wpdb->get_var( $cnpselcttabletoken );
		if($cnpselrowcounttoken!= ""){
			
			$prevcnpsqlacountstoken= "SELECT count(*) FROM ". $prevaccounttabletoken;
			$prevrowcountaccounttoken = $wpdb->get_var( $prevcnpsqlacountstoken );
			if($prevrowcountaccounttoken != 0)
			{
				$cnpsqlacountstoken= "SELECT count(*) FROM ". $tokentable_name;
				$rowcountaccounttoken = $wpdb->get_var( $cnpsqlacountstoken );
				if($rowcountaccounttoken == 0)
				{
				  $cnpselinsrowcounttoken = $wpdb->get_var("INSERT INTO  ".$tokentable_name ." SELECT * FROM $wpdb->prefix".cnptokeninfo);
					$newreclastidtoken = $wpdb->insert_id;
					if($newreclastidtoken !=""){ 
						 //$delprevaccountsqltoken = "DROP TABLE $prevaccounttabletoken";
   						// $wpdb->query($delprevaccountsqltoken);
					};
				}
			}
		}
		
    }

    public static function insert_transaction($entry_id, $transaction_type, $subscription_id, $transaction_id, $amount){
        global $wpdb;
        $table_name = self::get_transaction_table_name();

        $is_renewal = 0;
        if(!empty($subscription_id)){
            $count = $wpdb->get_var($wpdb->prepare("SELECT count(id) FROM $table_name WHERE subscription_id=%s", $subscription_id));
            if($count > 0)
                $is_renewal = 1;
        }

        $sql = $wpdb->prepare(" INSERT INTO $table_name (entry_id, transaction_type, subscription_id, transaction_id, amount, is_renewal, date_created)
                                values(%d, %s, %s, %s, %f, %d, utc_timestamp())", $entry_id, $transaction_type, $subscription_id, $transaction_id, $amount, $is_renewal);
        $wpdb->query($sql);
        $id = $wpdb->insert_id;

        return $id;
    }

    public static function get_transaction_totals($form_id){
        global $wpdb;
        $lead_table_name = RGFormsModel::get_lead_table_name();
        $transaction_table_name = self::get_transaction_table_name();

        $sql = $wpdb->prepare(" SELECT t.transaction_type, sum(t.amount) revenue, count(t.id) transactions
                                 FROM {$transaction_table_name} t
                                 INNER JOIN {$lead_table_name} l ON l.id = t.entry_id
                                 WHERE l.form_id={$form_id}
                                 GROUP BY t.transaction_type", $form_id);

        $results = $wpdb->get_results($sql, ARRAY_A);
        $totals = array();
        if(is_array($results)){
            foreach($results as $result){
                $totals[$result["transaction_type"]] = array("revenue" => empty($result["revenue"]) ? 0 : $result["revenue"] , "transactions" => empty($result["transactions"]) ? 0 : $result["transactions"]);
            }
        }

        return $totals;
    }
	public static function getCnPrefreshtoken() {
		global $wpdb;
	    $table_name = self::get_cnp_tokeninfo();
		$cnprefreshtkn = $wpdb->get_var("SELECT cnptokeninfo_refreshtoken  FROM $table_name");
		$settingstable_name = self::get_cnp_settingsinfo();
        $sql = "SELECT * FROM ". $settingstable_name;
        $results = $wpdb->get_results($sql, ARRAY_A);
        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			$cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=refresh_token&scope=".$results[$i]['cnpsettingsinfo_scope']."&refresh_token=".$cnprefreshtkn;
        }
			
		
			
		return $rtncnpdata;
	}
    public static function get_cnp_table_name(){
        global $wpdb;
        return $wpdb->prefix . "rg_cnp";
    }

    public static function get_transaction_table_name(){
        global $wpdb;
        return $wpdb->prefix . "rg_cnp_transaction";
    }
	
 	public static function get_cnp_settingsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_gfcnpsettingsinfo";
    }

	 public static function get_cnp_tokeninfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_gfcnptokeninfo";
    }

	 public static function get_cnp_accountsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_gfcnpaccountsinfo";
    }
    public static function get_feeds(){
       global $wpdb;
        $table_name = self::get_cnp_table_name();
        $form_table_name = RGFormsModel::get_form_table_name();
        $sql = "SELECT s.id, s.is_active, s.form_id, s.meta, f.title as form_title
                FROM $table_name s
                INNER JOIN $form_table_name f ON s.form_id = f.id";

        $results = $wpdb->get_results($sql, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
            $results[$i]["meta"] = maybe_unserialize($results[$i]["meta"]);
        }

        return $results;
    }
	 public static function update_feed($id, $form_id, $is_active, $setting){
        global $wpdb;
        $table_name = self::get_cnp_table_name();
        $setting = maybe_serialize($setting);				
		$check = self::get_feed_by_form($form_id);
        if($id == 0 ){
            if(count($check) == 0) 
			{
            $wpdb->insert($table_name, array("form_id" => $form_id, "is_active"=> $is_active, "meta" => $setting), array("%d", "%d", "%s"));
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			}
        }
        else{
            //update
            $wpdb->update($table_name, array("form_id" => $form_id, "is_active"=> $is_active, "meta" => $setting), array("id" => $id), array("%d", "%d", "%s"), array("%d"));
        }

        return $id;
    }

	public static function get_cnpgftransactions($cnpemailid,$cnpcode){
        global $wpdb;
		
        $gftable_name = self::get_cnp_settingsinfo();
       
        $sql = "SELECT * FROM ". $gftable_name;

        $results = $wpdb->get_results($sql, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			$cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=".$results[$i]['cnpsettingsinfo_granttype']."&scope=".$results[$i]['cnpsettingsinfo_scope']."&username=".$cnpemailid."&password=".$cnpcode;
        }

        return $rtncnpdata;
    }

	 public static function delete_cnptransactions(){
        global $wpdb;
        $table_name = self::get_cnp_tokeninfo();
        $wpdb->query("DELETE FROM ". $table_name);
    }
	 public static function delete_cnpaccountslist(){
        global $wpdb;
        $table_name = self::get_cnp_accountsinfo();
        $wpdb->query("DELETE FROM ". $table_name);
    }
    public static function delete_feed($id){
        global $wpdb;
        $table_name = self::get_cnp_table_name();
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id=%s", $id));
    }

    public static function get_feed_by_form($form_id, $only_active = false){
        global $wpdb;
        $table_name = self::get_cnp_table_name();
        $active_clause = $only_active ? " AND is_active=1" : "";
        $sql = $wpdb->prepare("SELECT id, form_id, is_active, meta FROM $table_name WHERE form_id=%d $active_clause", $form_id);
        $results = $wpdb->get_results($sql, ARRAY_A);
        if(empty($results))
            return array();
        //Deserializing meta
        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
            $results[$i]["meta"] = maybe_unserialize($results[$i]["meta"]);
        }
        return $results;
    }

    public static function get_feed($id){
        global $wpdb;
        $table_name = self::get_cnp_table_name();
        $sql = $wpdb->prepare("SELECT id, form_id, is_active, meta FROM $table_name WHERE id=%d", $id);
        $results = $wpdb->get_results($sql, ARRAY_A);
        if(empty($results))
            return array();

        $result = $results[0];
        $result["meta"] = maybe_unserialize($result["meta"]);
        return $result;
    }

    public static function insrt_cnptokeninfo($cnpemailid, $cnpcode, $cnptoken, $cnprtoken){
        global $wpdb;
        $table_name = self::get_cnp_tokeninfo();
         $wpdb->insert($table_name, array('cnptokeninfo_username' => $cnpemailid, 
					'cnptokeninfo_code' => $cnpcode, 
					'cnptokeninfo_accesstoken' => $cnptoken,
					'cnptokeninfo_refreshtoken' => $cnprtoken));
		
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
		
    }
public static function insert_cnpaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid){
        global $wpdb;
        $table_name = self::get_cnp_accountsinfo();
      
            $wpdb->insert($table_name, array('cnpaccountsinfo_orgid' => $cnporgid, 
					'cnpaccountsinfo_orgname' => $cnporgname, 
					'cnpaccountsinfo_accountguid' => $cnpaccountid,
					'cnpaccountsinfo_userfirstname' => $cnpufname,
					'cnpaccountsinfo_userlastname'=> $cnplname,
					'cnpaccountsinfo_userid'=> $cnpuid));
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
    }
    public static function drop_tables(){
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS " . self::get_cnp_table_name());
    }

    // get forms that are not assigned to feeds
    public static function get_available_forms($active_form = ''){

        $forms = RGFormsModel::get_forms();
        $available_forms = array();

        foreach($forms as $form) {
            $available_forms[] = $form;
        }

        return $available_forms;
    }
public static function get_cnpaccountsinfo()
{
	 global $wpdb;
        $table_name = self::get_cnp_accountsinfo();
	
	$id = $wpdb->get_var("SELECT count(*) as cnt from $table_name");
			
        return $id;
	
}
		public static function getCnPAccountsList() {
		$data['cnpaccounts'] = array();
			 global $wpdb;
        $table_name = self::get_cnp_accountsinfo();
		$query = "SELECT * FROM $table_name";
		 $results = $wpdb->get_results($query, ARRAY_A);
        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
           	$data['cnpaccounts'][] = array(
       		'AccountId'      => $results[$i]['cnpaccountsinfo_orgid'],
			'GUID'           => $results[$i]['cnpaccountsinfo_accountguid'],
			'Organization'           => $results[$i]['cnpaccountsinfo_orgname']    
   		);
        }

      return $data['cnpaccounts'];
		
	}
	 public static function getCnPAccountGUID($accid) {
		$cnpAccountGUId ="";
		 	 global $wpdb;
        $table_name = self::get_cnp_accountsinfo();
		$cnpaccountguid = $wpdb->get_var("SELECT cnpaccountsinfo_accountguid FROM $table_name where cnpaccountsinfo_orgid ='".$accid."'");
		
       
	 return $cnpaccountguid;
		
	}
		
	public static function getCnPactivePaymentList($cnpaccid)
	{
		$cmpacntacptdcards = "";
		$cnpacountid = $cnpaccid;
		$cnpaccountGUID = self::getCnPAccountGUID($cnpacountid);
		$cnpUID = "14059359-D8E8-41C3-B628-E7E030537905";
		$cnpKey = "5DC1B75A-7EFA-4C01-BDCD-E02C536313A3";
		$connect1  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $client1   = new SoapClient('https://resources.connect.clickandpledge.com/wordpress/Auth2.wsdl', $connect1);
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr1  = new SimpleXMLElement("<GetAccountDetail></GetAccountDetail>");
			$xmlr1->addChild('accountId',$cnpacountid);
			$xmlr1->addChild('accountGUID',$cnpaccountGUID);
			$xmlr1->addChild('username',$cnpUID);
			$xmlr1->addChild('password',$cnpKey);
			$response1                    =  $client1->GetAccountDetail($xmlr1);
			$optionsarry = get_option(gfcnp_plugin);
			$avcrds = unserialize($optionsarry['available_cards']);
			
			 
			$responsearramex              =  $response1->GetAccountDetailResult->Amex;
			$responsearrJcb               =  $response1->GetAccountDetailResult->Jcb;
			$responsearrMaster            =  $response1->GetAccountDetailResult->Master;
			$responsearrVisa              =  $response1->GetAccountDetailResult->Visa;
			$responsearrDiscover          =  $response1->GetAccountDetailResult->Discover;
			$responsearrecheck            =  $response1->GetAccountDetailResult->Ach;
			$responsearrCustomPaymentType =  $response1->GetAccountDetailResult->CustomPaymentType;
				
			 $cnpamex 					  =  $avcrds['American_Express'];
			$cnpjcb 					  =  $avcrds['JCB'];
			$cnpMaster 					  =  $avcrds['MasterCard'];
			$cnpVisa 					  =  $avcrds['Visa'];
			$cnpDiscover 				  =  $avcrds['Discover'];
			$cnpecheck 				      =  $optionsarry['payment_cnp_hidcnpeCheck'];
			$cnpcp 				          =  $optionsarry['payment_cnp_purchas_order'];
			$cnpcc 				          =  $optionsarry['payment_cnp_hidcnpcreditcard'];
				
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpcreditcard" id="payment_cnp_hidcnpcreditcard"';
			if($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true ){ 
				$cmpacntacptdcards .= ' value="CreditCard">';
			}else{ $cmpacntacptdcards .= ' value="">'; }
				$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpeCheck" id="payment_cnp_hidcnpeCheck"';
			if($responsearrecheck == true){
				$cmpacntacptdcards .= ' value="eCheck">';
			}else{ $cmpacntacptdcards .= ' value="">'; }
			if($responsearramex == true){
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpamex" id="payment_cnp_hidcnpamex" value="amex">';
			}
			if($responsearrJcb == true){
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpjcb" id="payment_cnp_hidcnpjcb" value="jcb">';
			}
			if($responsearrMaster == true){
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpMaster" id="payment_cnp_hidcnpMaster" value="Master">';
			}
			if($responsearrVisa == true){
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpVisa" id="payment_cnp_hidcnpVisa" value="Visa">';
			}
			if($responsearrDiscover == true){
			$cmpacntacptdcards .= '<input type="hidden" name="payment_cnp_hidcnpDiscover" id="payment_cnp_hidcnpDiscover" value="Discover">';
			}
			$cmpacntacptdcards .= '<table cellpadding="5" cellspacing="3" style="font-weight:bold;padding:2px;" id="tblacceptedcards">
                    <tbody><tr>
                    <td width="200"><input type="checkbox" id="payment_cnp_creditcard" class="checkbox_active" value="CreditCard" name="payment_cnp_creditcard"  onclick="block_creditcard(this.checked);" ';
			if(($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true) )
			{$cmpacntacptdcards .= 'checked="checked"';}
		     $cmpacntacptdcards .= 'checked="checked" disabled="disabled"> Credit Card</td></tr>
			 <tr class="tracceptedcards"><td></td><td>
			 <table cellspacing="0">
					
					<tbody class="accounts">
						<tr class="account">								
									<td style="padding:2px;"><strong>Accepted Credit Cards</strong></td></tr>';
								if($responsearrVisa == true){
									
							      $cmpacntacptdcards .= '<tr class="account">								
									<td style="padding:2px;"><br><input type="Checkbox" name="payment_cnp_Visa" id="payment_cnp_Visa"';
									if(isset($cnpVisa)){ $cmpacntacptdcards .='checked="checked "'; }
									 $cmpacntacptdcards .= 'value="Visa" checked="checked" disabled="disabled">Visa</td></tr>';
								  }
								if($responsearramex == true){
									$cmpacntacptdcards .= '<tr>
									<td style="padding:2px;"><input type="Checkbox" name="payment_cnp_American_Express" id="payment_cnp_American_Express"';
									if(isset($cnpamex)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= 'value="American Express" checked="checked" disabled="disabled">American Express</td>
								  </tr>';
								}if($responsearrDiscover == true){
								 $cmpacntacptdcards .= ' <tr>
									<td style="padding:2px;"><input type="Checkbox" name="payment_cnp_Discover" id="payment_cnp_Discover"'; 
									if(isset($cnpDiscover)){ $cmpacntacptdcards .='checked="checked"'; }
										$cmpacntacptdcards .= ' value="Discover" checked="checked" disabled="disabled">Discover</td>
								  </tr>';
								}if($responsearrMaster == true){
								  $cmpacntacptdcards .= '<tr>
									<td style="padding:2px;"><input type="Checkbox" name="payment_cnp_MasterCard" id="payment_cnp_MasterCard"';
									if(isset($cnpMaster)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="MasterCard"  checked="checked" disabled="disabled">MasterCard</td>
								  </tr>';
								}if($responsearrJcb == true){
								  $cmpacntacptdcards .= '<tr>
									<td style="padding:2px;"><input type="Checkbox" name="payment_cnp_JCB" id="payment_cnp_JCB"';
									if(isset($cnpjcb)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="JCB" checked="checked" disabled="disabled">JCB</td>
								  </tr>';
								}
			$cmpacntacptdcards .= '</tbody></table></td></tr>';
			if($responsearrecheck == true){
			$cmpacntacptdcards .='<tr><td><input type="checkbox" value="eCheck" id="payment_cnp_check" class="checkbox_active" name="payment_cnp_check" onclick="block_echek(this.checked);"';
				if(isset($cnpecheck)){ $cmpacntacptdcards .='checked="checked"'; }
				 $cmpacntacptdcards .= ' checked="checked" disabled="disabled"> eCheck</td></tr>';
			}
			if($responsearrCustomPaymentType == true){
			$cmpacntacptdcards .='<tr><td><input type="checkbox" value="Purchase Order" id="payment_cnp_purchas_order" class="checkbox_active" name="payment_cnp_purchas_order" onclick="block_custom(this.checked);"'; if(isset($cnpcp) && $cnpcp !=""){ $cmpacntacptdcards .='checked="checked"'; }	
			
			if($responsearrCustomPaymentType == true && (!isset($cnpcp) ))
			{$cmpacntacptdcards .='checked="checked"';}	
				$cmpacntacptdcards .= '> Custom Payment</td></tr>';
			}
					$cmpacntacptdcards .= '</tbody></table>';
				
	
		

		}	
		return $cmpacntacptdcards;
		
	}
	
}

?>
