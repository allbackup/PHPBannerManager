<?php
class bx_p_gateaway {
    //specific variables
	var $paypal_post_vars;
	var $paypal_response;
	var $timeout;
	var $error_email;

	function bx_p_gateaway($paypal_post_vars)	{
		$this->paypal_post_vars = $paypal_post_vars;
		$this->timeout = 120;
	}

	function send_response()	{
		$fp = @fsockopen( "www.paypal.com", 80, &$errno, &$errstr, 120 ); 
		if( !$fp )
		{ 
			$this->error_out("PHP fsockopen() error: " . $errstr);
		}

		else 
		{
			foreach($this->paypal_post_vars AS $key => $value)
			{
				if( @get_magic_quotes_gpc() )
				{
					$value = stripslashes($value);
				}
				$values[] = "$key" . "=" . urlencode($value);
			}

			$response = @implode("&", $values);

			$response .= "&cmd=_notify-validate";

			fputs( $fp, "POST /cgi-bin/webscr HTTP/1.0\r\n" ); 
			fputs( $fp, "Host: https://www.paypal.com\r\n" ); 
			fputs( $fp, "User-Agent: ".$HTTP_SERVER['HTTP_USER_AGENT'] ."\r\n" ); 
			fputs( $fp, "Accept: */*\r\n" ); 
			fputs( $fp, "Accept: image/gif\r\n" ); 
			fputs( $fp, "Accept: image/x-xbitmap\r\n" ); 
			fputs( $fp, "Accept: image/jpeg\r\n" ); 
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" ); 
			fputs( $fp, "Content-length: " . strlen($response) . "\r\n\n" ); 
			fputs( $fp, "$response\n\r" ); 
			fputs( $fp, "\r\n" );

			$this->send_time = time();
			$this->paypal_response = ""; 

			while( !feof( $fp ) ) 
			{ 
				$this->paypal_response .= fgets( $fp, 1024 ); 

				if( $this->send_time < time() - $this->timeout )
				{
					$this->error_out("Timed out waiting for a response from PayPal. ($this->timeout seconds)");
				}

			} // end while

            fclose( $fp );

		} // end else
	} // end function send_response()

	function is_verified() {
		if( ereg("VERIFIED", $this->paypal_response) ) {
			return true;
		}
		else {
			return false;
		}
	} // end function is_verified

	function get_payment_status() {
		return $this->paypal_post_vars['payment_status'];
	}

	function error_msg($message) {
		$date = date("D M j G:i:s T Y", time());
		$message .= "\n\nThe following input was received from (and sent back to) PayPal:\n\n";

		@reset($this->paypal_post_vars);
		while( @list($key,$value) = @each($this->paypal_post_vars) )
		{
			$message .= $key . ':' . " \t$value\n";
		}
        $message .= "Date: ".$date."\n";
        
		if( $this->error_email ) {
			@mail($this->error_email, "Paypal IPN error", $message);
		}
	} // end function error_out
}//end class bx_p_gateaway
?>