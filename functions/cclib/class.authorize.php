<?php
class bx_p_gateaway {
    //specific variables
    var $authorize_account;
    var $authorize_password;
    var $authorize_url;
    var $authorize_tran_key;
    var $authorize_demo;
    //returned variables
    var $response_code;
    var $reason_text;
    var $avs_code;
    var $trans_id;
    var $md5_hash;
    var $ret_address;
    var $ret_city;
    var $ret_state;
    var $ret_zip;
    var $ret_country;
    var $ret_phone;
    var $ret_email;
    //global variables
    var $auth_cc_name;
    var $auth_cc_type;
    var $auth_cc_num;
    var $auth_cc_expmonth;
    var $auth_cc_expyear;
    var $auth_cc_street;
    var $auth_cc_city;
    var $auth_cc_state;
    var $auth_cc_zip;
    var $auth_cc_country;
    var $auth_cc_phone;
    var $auth_cc_email;
    
    var $submit_return;
    var $cc_amount;
    var $curl_error_message;
    
    function Submit() {

			if (!$this->authorize_url) {
				$this->set_authorize_url();
			}
            $this->set_authorize_account();
            $this->set_authorize_password();
            $this->set_authorize_tran_key();
            $this->set_authorize_demo();
			$this->submit_return = "";
            
            $data = $this->makeURLData();
            $ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_URL, $this->authorize_url);
            curl_setopt ($ch, CURLOPT_POST, $data);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
			$this->submit_return = curl_exec($ch);
            $this->set_curl_error(curl_error($ch));
            curl_close ($ch);
	} // function submit

    function getURLData() {
           $returned = array();
           $returned = split(",", $this->submit_return);
           //$returned = split("\|", $this->submit_return);
           $this->set_response_code($returned[0]);        
           $this->set_reason_text($returned[3]);        
           $this->set_avs_code($returned[5]);        
           $this->set_trans_id($returned[6]);        
           $this->set_md5_hash($returned[37]);        
           $this->set_ret_address($returned[16]);        
           $this->set_ret_city($returned[17]);        
           $this->set_ret_state($returned[18]);        
           $this->set_ret_zip($returned[19]);        
           $this->set_ret_country($returned[20]);        
           $this->set_ret_phone($returned[21]);        
           $this->set_ret_email($returned[23]);        
    } 
    
	function makeURLData() {
      
            $out = "x_Version=3.1";
            $out .= "&x_ADC_URL=FALSE";
            $out .= "&x_ADC_Delim_Data=TRUE";
            $out .= "&x_Card_Num=".$this->auth_cc_num;
            $out .= "&x_Exp_Date=".$this->auth_cc_expmonth.$this->auth_cc_expyear;
            $out .= "&x_Login=".$this->authorize_account;
            $out .= "&x_Password=".$this->authorize_password;
            $out .= "&x_Amount=".$this->cc_amount;
            $last_first = split(" ", $this->auth_cc_name);
            $out .= "&x_First_Name=".$last_first[0];
            $out .= "&x_Last_Name=".$last_first[1];
            $out .= "&x_Address=".$this->auth_cc_street;
            $out .= "&x_City=".$this->auth_cc_city;
            $out .= "&x_State=".$this->auth_cc_state;
            $out .= "&x_Zip=".$this->auth_cc_zip;
            $out .= "&x_Country=".$this->auth_cc_country;
            $out .= "&x_Phone=".$this->auth_cc_phone;
            $out .= "&x_Email=".$this->auth_cc_email;
            $out .= "&x_Test_Request=".$this->authorize_demo;
            $out .= "&x_Type=AUTH_CAPTURE";
            $out .= "&x_Delim_Data=True";
            $out .= "&x_Method=CC";
            $out .= "&x_Tran_Key=".$this->authorize_tran_key;
		    
            return $out;

	} // end makeURLData

    function set_authorize_account() {
        $this->authorize_account = AUTHORIZE_NET_LOGIN;
    }
    function get_authorize_account() {
        return $this->authorize_account;
    }
    function set_authorize_password() {
        $this->authorize_password = AUTHORIZE_NET_PASSWORD;
    }
    function get_authorize_password() {
        return $this->authorize_password;
    }
    function set_authorize_url() {
        $this->authorize_url = AUTHORIZE_NET_URL;
    }
    function get_authorize_url() {
        return $this->authorize_url;
    }
    function set_authorize_tran_key() {
        if(AUTHORIZE_NET_DEMO == "yes") {
                $this->authorize_tran_key = "";
        }
        else {
            $this->authorize_tran_key = AUTHORIZE_NET_TRANKEY;
        }
    }
    function get_authorize_tran_key() {
        return $this->authorize_tran_key;
    }
    function set_authorize_demo() {
        if(AUTHORIZE_NET_DEMO == "yes") {
                $this->authorize_demo = "TRUE";
        }
        else {
            $this->authorize_demo = "FALSE";
        }
        
    }
    function get_authorize_demo() {
        return $this->authorize_demo;
    }
    function set_response_code($a_response_code) {
        $this->response_code = $a_response_code;
    }
    function get_response_code() {
        return $this->response_code;
    }
    function set_reason_text($a_reason_text) {
        $this->reason_text = $a_reason_text;
    }
    function get_reason_text() {
        return $this->reason_text;
    }
    function set_avs_code($a_avs_code) {
        $this->avs_code = $a_avs_code;
    }
    function get_avs_code() {
        return $this->avs_code;
    }
    function set_trans_id($a_trans_id) {
        if($a_trans_id == 0 && AUTHORIZE_NET_DEMO=="yes") {
            $a_trans_id = time();
        }
        $this->trans_id = $a_trans_id;
    }
    function get_trans_id() {
        return $this->trans_id;
    }
    function set_md5_hash($a_md5_hash) {
        $this->md5_hash = $a_md5_hash;
    }
    function get_md5_hash() {
        return $this->md5_hash;
    }
    
    function set_ret_address($a_ret_address) {
        $this->ret_address = $a_ret_address;
    }
    function get_ret_address() {
        return $this->ret_address;
    }
    function set_ret_city($a_ret_city) {
        $this->ret_city = $a_ret_city;
    }
    function get_ret_city() {
        return $this->ret_city;
    }
    function set_ret_state($a_ret_state) {
        $this->ret_state = $a_ret_state;
    }
    function get_ret_state() {
        return $this->ret_state;
    }
    function set_ret_zip($a_ret_zip) {
        $this->ret_zip = $a_ret_zip;
    }
    function get_ret_zip() {
        return $this->ret_zip;
    }
    function set_ret_country($a_ret_country) {
        $this->ret_country = $a_ret_country;
    }
    function get_ret_country() {
        return $this->ret_country;
    }
    function set_ret_phone($a_ret_phone) {
        $this->ret_phone = $a_ret_phone;
    }
    function get_ret_phone() {
        return $this->ret_phone;
    }
    function set_ret_email($a_ret_email) {
        $this->ret_email = $a_ret_email;
    }
    function get_ret_email() {
        return $this->ret_email;
    }


    function set_amount($a_amount) {
        $this->cc_amount = $a_amount;
    }
    function get_amount() {
        return $this->cc_amount;
    }
    
    function set_auth_cc_name($a_auth_cc_name) {
        $this->auth_cc_name = $a_auth_cc_name;
    }
    function get_auth_cc_name() {
        return $this->auth_cc_name;
    }
    function set_auth_cc_type($a_auth_cc_type) {
        $this->auth_cc_type = $a_auth_cc_type;
    }
    function get_auth_cc_type() {
        return $this->auth_cc_type;
    }
    function set_auth_cc_num($a_auth_cc_num) {
        $this->auth_cc_num = eregi_replace(" ", "", $a_auth_cc_num);
    }
    function get_auth_cc_num() {
        return $this->auth_cc_num;
    }
    function set_auth_cc_expmonth($a_auth_cc_expmonth) {
        $this->auth_cc_expmonth = $a_auth_cc_expmonth;
    }
    function get_auth_cc_expmonth() {
        return $this->auth_cc_expmonth;
    }
    function set_auth_cc_expyear($a_auth_cc_expyear) {
        $this->auth_cc_expyear = $a_auth_cc_expyear;
    }
    function get_auth_cc_expyear() {
        return $this->auth_cc_expyear;
    }
    function set_auth_cc_street($a_auth_cc_street) {
        $this->auth_cc_street = $a_auth_cc_street;
    }
    function get_auth_cc_street() {
        return $this->auth_cc_street;
    }
    function set_auth_cc_city($a_auth_cc_city) {
        $this->auth_cc_city = $a_auth_cc_city;
    }
    function get_auth_cc_city() {
        return $this->auth_cc_city;
    }
    function set_auth_cc_state($a_auth_cc_state) {
        $this->auth_cc_state = $a_auth_cc_state;
    }
    function get_auth_cc_state() {
        return $this->auth_cc_state;
    }
    function set_auth_cc_zip($a_auth_cc_zip) {
        $this->auth_cc_zip = $a_auth_cc_zip;
    }
    function get_auth_cc_zip() {
        return $this->auth_cc_zip;
    }
    function set_auth_cc_country($a_auth_cc_country) {
        $this->auth_cc_country = $a_auth_cc_country;
    }
    function get_auth_cc_country() {
        return $this->auth_cc_country;
    }
    function set_auth_cc_phone($a_auth_cc_phone) {
        $this->auth_cc_phone = $a_auth_cc_phone;
    }
    function get_auth_cc_phone() {
        return $this->auth_cc_phone;
    }
    function set_auth_cc_email($a_auth_cc_email) {
        $this->auth_cc_email = $a_auth_cc_email;
    }
    function get_auth_cc_email() {
        return $this->auth_cc_email;
    }
    function set_curl_error($a_curl_error) {
        $this->curl_error_message = $a_curl_error;
    }
    function get_curl_error() {
        return $this->curl_error_message;
    }

}
//end class bx_p_gateaway
?>