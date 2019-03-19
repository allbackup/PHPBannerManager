<?php
class bx_p_gateaway {
    //specific variables
    var $checkout_account;
    var $checkout_password;
    var $checkout_url;
    var $checkout_demo;
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
    
    var $cc_amount;
    
    var $submit_return;
    var $cc_amount;
    
    function Submit() {

			if (!$this->checkout_url) {
				$this->set_checkout_url();
			}
            $this->set_checkout_account();
            //$this->set_checkout_password();
			$this->submit_return = "";
            
            $data = $this->getURLData();
            print $data;
            $ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_URL, $this->checkout_url);
            curl_setopt ($ch, CURLOPT_POST, $data);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
			$this->submit_return = curl_exec($ch);
            curl_close ($ch);
            print $this->submit_return;

			// Get all the metadata.
			return $this->submit_return;
	} // function submit


	function getURLData() {
        
            $out = "x_Version=3.0";
            $out .= "&x_ADC_URL=FALSE";
            $out .= "&x_ADC_Delim_Data=TRUE";
            $out .= "&x_Card_Num=".$this->auth_cc_num;
            $out .= "&x_Card_Code=123";
            $out .= "&demo=Y";
            $out .= "&x_Method=CC";
            $out .= "&x_Exp_Date=".$this->auth_cc_expmonth.$this->auth_cc_expyear;
            $out .= "&x_Login=".$this->checkout_account;
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
            $out .= "&x_Test_Request=TRUE";
		
			return $out;

	} // end getURLData

    function test() {
        print CHECKOUT_COM_LOGIN;
    }
    
    function set_checkout_account() {
        $this->checkout_account = CHECKOUT_COM_ACCOUNT;
    }
    function get_checkout_account() {
        return $this->checkout_account;
    }
    function set_checkout_password() {
        $this->checkout_password = AUTHORIZE_NET_PASSWORD;
    }
    function get_checkout_password() {
        return $this->checkout_password;
    }
    function set_checkout_url() {
        $this->checkout_url = CHECKOUT_COM_URL;
    }
    function get_checkout_url() {
        return $this->checkout_url;
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
        $this->auth_cc_num = eregi_replace(" ","",$a_auth_cc_num);
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
    
}
//end class bx_p_gateaway
?>