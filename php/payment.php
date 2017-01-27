<?php
session_start();

include("validation.php");

// PayPal settings
$paypal_email = 'cristian.peralta@fastlinkperu.com';
/*$return_url = 'http://localhost/~cristian/paypal-php/payment-successful.html';
$cancel_url = 'http://localhost/~cristian/paypal-php/payment-cancelled.html';
$notify_url = 'http://localhost/~cristian/paypal-php/payments.php';*/
$return_url = 'http://fastlinkperu.com/customers/palosanto/checkout.html';
$cancel_url = 'http://fastlinkperu.com/customers/palosanto/cancelled.html';
$notify_url = 'http://fastlinkperu.com/customers/palosanto/php/payment.php';

$item_name = "#" . $_SESSION["order"]["series"] . " - Order from Palo Santo Experience";
$item_amount = (float)$_SESSION["order"]["total"];

insert_log("antes de peticion: " . $item_name . " = " . $item_amount);

if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	
	insert_log("enviando a paypal");
	
	$querystring = '';
	
	$querystring .= "?business=".urlencode($paypal_email)."&";
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url)."&";
	$querystring .= "custom=".urlencode($_SESSION["order"]["series"]);
	
	// Append querystring with custom field
	//$querystring .= "&custom=".USERID;
	
	// Redirect to paypal IPN
	$redirecturlquery = 'location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring; 
	header($redirecturlquery);
	
	insert_log("se procesa: " . $redirecturlquery);
	
	exit();
	
} else {
	
	insert_log("respuesta de paypal");
	
	// Response from Paypal

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
	insert_log("built req: " . $req);
	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['order_series'] 		= $_POST['custom'];
	
	insert_log("paypal responde con orden: " . $data['order_series']);
	
	$debug=true;
	
	// post back to PayPal system to validate
	//$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	//$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	
	$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
	$header .= "Host: www.sandbox.paypal.com\r\n";
	$header .= "Connection: close\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	$fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
	
	if (!$fp) {
		// HTTP ERROR
		insert_log("error http");
		
	} else {
		
		insert_log("no hubo error http");
		
		$paypalresponse = "";
		
		fputs($fp, $header . $req);
		
		while (!feof($fp)) {
			
			insert_log("consultando mediante sockets");
			
			$paypalresponse .= stream_get_contents($fp, 1024);
			
		}

		fclose ($fp);
		
		$escapedpaypalresponse = str_replace(array("\r\n", "\n", "\r"),"",$paypalresponse);
		
		insert_log("rpta: " . $escapedpaypalresponse);
		
		$checkedstring = strstr($escapedpaypalresponse, "VERIFIED");
		
		insert_log("resultado evaluacion: " . strpos($checkedstring, "VERIFIED"));
		
		if (strpos($checkedstring, "VERIFIED") >= 0) {
		
			insert_log("operacion verificada");
			
			//verificando si el nro de id de pago existe en la db
			$valid_txnid = check_txnid($data['txn_id']);
			
			if ($valid_txnid) {
				
				$updateresponse = updatePayments($data);
				
				insert_log("actualizando orden: " . $updateresponse);
				
			} else {
				
				// Payment made but data has been changed
				// E-mail admin or alert user
				
			}
				
		}else if(strpos($checkedstring, "INVALID") >= 0) {
		
			insert_log("operacion invalida: " . print_r($post, true));
			
		}
	
	}
}
?>