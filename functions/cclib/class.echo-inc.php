<?php
class bx_p_gateaway {
    //specific variables
    var $echo_account;
    var $echo_pin;
    var $echo_url;
    var $echo_demo;
    var $echo_ip_address;
    var $echotype1;
    var $echotype2;
    var $echotype3;
    var $openecho;
    //returned variables
    var $echo_auth_code;
    var $echo_order_number;
    var $echo_reference;
    var $echo_status;
    var $echo_avs_result;
    var $echo_security_result;
    var $echo_mac;
    var $echo_cs_factors;
    var $echo_cs_flag;
    var $echo_cs_host_score;
    var $echo_cs_reference_number;
    var $echo_cs_response;
    var $echo_cs_score;
    var $echo_cs_status;
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

			if (!$this->echo_url) {
				$this->set_echo_url();
			}
             
            $this->set_echo_account();
            $this->set_echo_pin();
            $this->set_echo_demo();
			$this->submit_return = "";
            
            $data = $this->makeURLData();
            $ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_URL, $this->echo_url);
            curl_setopt ($ch, CURLOPT_POST, $data);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
			$this->submit_return = curl_exec($ch);
            $this->set_curl_error(curl_error($ch));
            curl_close ($ch);
	} // function submit

    function getURLData() {
            $startpos = strpos($this->submit_return, "<ECHOTYPE1>") + 11;
			$endpos = strpos($this->submit_return, "</ECHOTYPE1>");
			$this->echotype1 = substr($this->submit_return, $startpos, $endpos - $startpos);

			$startpos = strpos($this->submit_return, "<ECHOTYPE2>") + 11;
			$endpos = strpos($this->submit_return, "</ECHOTYPE2>");
			$this->echotype2 = substr($this->submit_return, $startpos, $endpos - $startpos);

			$startpos = strpos($this->submit_return, "<ECHOTYPE3>") + 11;
			$endpos = strpos($this->submit_return, "</ECHOTYPE3>");
			$this->echotype3 = substr($this->submit_return, $startpos, $endpos - $startpos);

			if (strpos($this->submit_return, "<OPENECHO>")) {
				$startpos = strpos($this->submit_return, "<OPENECHO>") + 10;
				$endpos = strpos($this->submit_return, "</OPENECHO>");
				$this->openecho = substr($this->submit_return, $startpos, $endpos - $startpos);
			}

			// Get all the metadata.
            $this->set_echo_auth_code($this->get_echo_prop($this->echotype3, "auth_code"));
			$this->set_echo_order_number($this->get_echo_prop($this->echotype3, "order_number"));
			$this->set_echo_reference($this->get_echo_prop($this->echotype3, "echo_reference"));
            $this->set_echo_status($this->get_echo_prop($this->echotype3, "status"));
			$this->set_echo_avs_result($this->get_echo_prop($this->echotype3, "avs_result"));
			$this->set_echo_security_result($this->get_echo_prop($this->echotype3, "security_result"));
			$this->set_echo_mac($this->get_echo_prop($this->echotype3, "mac"));
			$this->set_echo_cs_factors($this->get_echo_prop($this->echotype3, "cs_factors"));
			$this->set_echo_cs_flag($this->get_echo_prop($this->echotype3, "cs_flag"));
			$this->set_echo_cs_host_score($this->get_echo_prop($this->echotype3, "cs_host_score"));
			$this->set_echo_cs_reference_number($this->get_echo_prop($this->echotype3, "cs_reference_number"));
			$this->set_echo_cs_response($this->get_echo_prop($this->echotype3, "cs_response"));
			$this->set_echo_cs_score($this->get_echo_prop($this->echotype3, "cs_score"));
			$this->set_echo_cs_status($this->get_echo_prop($this->echotype3, "cs_status"));
    } 
    
	function makeURLData() {
      
            $out = "order_type=S";
            $out .= "&transaction_type=ES";
            $out .= "&merchant_echo_id=".$this->echo_account;
            $out .= "&merchant_pin=".$this->echo_pin;
            $out .= "&grand_total=".$this->cc_amount;
            $out .= "&billing_ip_address=".$this->echo_ip_address;
            $last_first = split(" ", $this->auth_cc_name);
            $out .= "&billing_first_name=".$last_first[0];
            $out .= "&billing_last_name=".$last_first[1];
            $out .= "&billing_address1=".$this->auth_cc_street;
            $out .= "&billing_city=".$this->auth_cc_city;
            $out .= "&billing_state=".$this->auth_cc_state;
            $out .= "&billing_zip=".$this->auth_cc_zip;
            $out .= "&billing_country=".$this->auth_cc_country;
            $out .= "&billing_phone=".$this->auth_cc_phone;
            $out .= "&billing_email=".$this->auth_cc_email;
            $out .= "&cc_number=".$this->auth_cc_num;
            $out .= "&ccexp_month=".$this->auth_cc_expmonth;
            $out .= "&ccexp_year=".$this->auth_cc_expyear;
            $out .= "&counter=".$this->get_echo_rcounter();
            $out .= "&debug=".$this->echo_demo;
            
			return $out;

	} // end makeURLData

    function set_echo_account() {
        $this->echo_account = ECHO_INC_COM_LOGIN;
    }
    function get_echo_account() {
        return $this->echo_account;
    }
    function set_echo_pin() {
        $this->echo_pin = ECHO_INC_COM_PASSWORD;
    }
    function get_echo_pin() {
        return $this->echo_pin;
    }
    function set_echo_url() {
        $this->echo_url = ECHO_INC_COM_URL;
    }
    function get_echo_url() {
        return $this->echo_url;
    }
    function set_echo_demo() {
        if(ECHO_INC_COM_DEMO == "yes") {
                $this->echo_demo = "T";
        }
        else {
            $this->echo_demo = "";
        }
        
    }
    function get_echo_demo() {
        return $this->echo_demo;
    }
    function set_ip_address($a_ip_address) {
                $this->echo_ip_address = $a_ip_address;
    }
    function get_ip_address() {
        return $this->echo_ip_address;
    }
    
    function get_echo_rcounter() {
			mt_srand ((double) microtime() * 1000000);
			return mt_rand();
    }
    function get_echo_prop($haystack, $prop) {
            // prepend garbage in case the property
            // starts at position 0 .. I know, there's a better way
            // to do this, right?
            $haystack = "garbage" . $haystack;
        
            if  ($start_pos = strpos(strtolower($haystack), "<$prop>")) {
                $start_pos = strpos(strtolower($haystack), "<$prop>") + strlen("<$prop>");
                $end_pos = strpos(strtolower($haystack), "</$prop");
                return substr($haystack, $start_pos, $end_pos - $start_pos);
             } else {
                  return "";
             }
     }
     function set_echo_auth_code($a_echo_auth_code) {
        $this->echo_auth_code = $a_echo_auth_code;
     }
     function get_echo_auth_code() {
        return $this->echo_auth_code;
     }
     function set_echo_order_number($a_echo_order_number) {
        $this->echo_order_number = $a_echo_order_number;
     }
     function get_echo_order_number() {
        return $this->echo_order_number;
     }
     function set_echo_reference($a_echo_reference) {
        $this->echo_reference = $a_echo_reference;
     }
     function get_echo_reference() {
        return $this->echo_reference;
     }
     function set_echo_status($a_echo_status) {
        $this->echo_status = $a_echo_status;
     }
     function get_echo_status() {
        return $this->echo_status;
     }
     function set_echo_avs_result($a_echo_avs_result) {
        $this->echo_avs_result = $a_echo_avs_result;
     }
     function get_echo_avs_result() {
        return $this->echo_avs_result;
     }
     function set_echo_security_result($a_echo_security_result) {
        $this->echo_security_result = $a_echo_security_result;
     }
     function get_echo_security_result() {
        return $this->echo_security_result;
     }
     function set_echo_mac($a_echo_mac) {
        $this->echo_mac = $a_echo_mac;
     }
     function get_echo_mac() {
        return $this->echo_mac;
     }
     function set_echo_cs_factors($a_echo_cs_factors) {
        $this->echo_cs_factors = $a_echo_cs_factors;
     }
     function get_echo_cs_factors() {
        return $this->echo_cs_factors;
     }
     function set_echo_cs_flag($a_echo_cs_flag) {
        $this->echo_cs_flag = $a_echo_cs_flag;
     }
     function get_echo_cs_flag() {
        return $this->echo_cs_flag;
     }
     function set_echo_cs_host_score($a_echo_cs_host_score) {
        $this->echo_cs_host_score = $a_echo_cs_host_score;
     }
     function get_echo_cs_host_score() {
        return $this->echo_cs_host_score;
     }
     function set_echo_cs_reference_number($a_echo_cs_reference_number) {
        $this->echo_cs_reference_number = $a_echo_cs_reference_number;
     }
     function get_echo_cs_reference_number() {
        return $this->echo_cs_reference_number;
     }
     function set_echo_cs_response($a_echo_cs_response) {
        $this->echo_cs_response = $a_echo_cs_response;
     }
     function get_echo_cs_response() {
        return $this->echo_cs_response;
     }
     function set_echo_cs_score($a_echo_cs_score) {
        $this->echo_cs_score = $a_echo_cs_score;
     }
     function get_echo_cs_score() {
        return $this->echo_cs_score;
     }
     function set_echo_cs_status($a_echo_cs_status) {
        $this->echo_cs_status = $a_echo_cs_status;
     }
     function get_echo_cs_status() {
        return $this->echo_cs_status;
     }

    function get_echo_type1() {
        return $this->echotype1;
    }
    function get_echo_type2() {
        return $this->echotype2;
    }
    function get_echo_type3() {
        return $this->echotype3;
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