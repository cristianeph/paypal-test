<?php
session_start();

include("validation.php");

$year = date("Y");
$month = date("n");
$hour = date("G");
$minutes = date("i");
$resultquery = (int)generateOrderSerial($month);
$orderseries = 
	str_pad($year, 4, "0", STR_PAD_LEFT) . 
	str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . 
	/*str_pad($hour, 2, "0", STR_PAD_LEFT) . 
	str_pad($minutes, 2, "0", STR_PAD_LEFT) . "-" .*/ 
	str_pad(($resultquery + 1), 6, "0", STR_PAD_LEFT);
	
$data['firstnames'] = $_POST['firstnames'];
$data['lastnames'] 	= $_POST['lastnames'];
$data['email'] 		= $_POST['email'];
$data['address1'] 	= $_POST['address1'];
$data['address2'] 	= $_POST['address2'];
$data['country'] 	= $_POST['country'];
$data['postalcode'] = $_POST['postalcode'];
$data['phone'] 		= $_POST['phone'];
$data['series']		= $orderseries;

$_SESSION["order"] = $data;

$order = generateOrder($data);

if($order == "SUCCESS"){
	
	$estado = "SUCCESS";
	
	$orderid = getOrderKey($orderseries);
	
	$data["id"] = $orderid;
	$_SESSION["order"] = $data;
	
	$details = $_POST['details'];
	
	foreach ($details as $item){
	
		$price = getPrice($item['idProduct']);
	
		$item['idOrder'] = $orderid;
		$item['total'] = (float)$item['quantity'] * (float)$price;
	
		$estado = generateListOrder($item);
		
		$subtotal += $item['total']; 
	
	}
	
	$data['total'] = $subtotal;
	$_SESSION["order"] = $data;
	
	echo $estado;
	
}else{
	
	echo "ERROR";
	
}
?>