<?php
session_start();

function connection(){

	/*$host = "127.0.0.1:3306";
	$user = "root";
	$pass = "eduardo";
	$db_name = "shop";*/
	
	$host = "fastlinkperu.com.mysql";
	$user = "fastlinkperu_com";
	$pass = "eG8GySM6";
	$db_name = "fastlinkperu_com";
	
	return new mysqli($host, $user, $pass, $db_name);
	
}

function insert_log($description){
	
	$conn = connection();
	
	$sql = "
		INSERT INTO log (description) 
		VALUES ('".$description."')
	";
	
	if ($conn->query($sql) === TRUE) {
		return "SUCCESS";
	} else {
	    return "ERROR: " . $sql . "<br>" . $conn->error;
	}
	
	$conn->close();
	
}

function generateOrderSerial($month){
	
	$conn = connection();
	
	$sql = "
		SELECT 
			COUNT(`id`) AS `cuantos`
		FROM 
			`order` 
		WHERE 
			MONTH(`date`) = " . $month;
	
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0){
		
		$row = $result->fetch_assoc();
		return $row["cuantos"];
		
	}
	
	mysqli_close($conn);
	
}

function getOrderKey($series){
	
	if($series){
	
		$conn = connection();
		
		$sql = "
			SELECT
				`id`
			FROM
				`order`
			WHERE
				`series` = '" . $series . "'";
		
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0){
		
			$row = $result->fetch_assoc();
			return $row["id"];
		
		}
		
		mysqli_close($conn);
		
	}else{
		
		return "ERROR: No data received";
		
	}
	
	
	
}

function getPrice($id){
	
	if($id){
	
		$conn = connection();
		
		$sql = "
			SELECT
				`price`
			FROM
				`product`
			WHERE
				`id` = " . $id;
		
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0){
		
			$row = $result->fetch_assoc();
			return $row["price"];
		
		}
		
		mysqli_close($conn);
		
	}else{
		
		return "ERROR: No data received";
		
	}
	
}

function generateListOrder($data){
	
	if (is_array($data)) {
	
		$conn = connection();
		
		$sql = "
			INSERT 
			INTO `list` (
				`idOrder`, 
				`idProduct`, 
				`quantity`, 
				`total`
			) VALUES (
				".$data['idOrder']." ,
				".$data['idProduct']." ,
				".$data['quantity']." ,
				".$data['total']."  
			)";
		
		if ($conn->query($sql) === TRUE){
			return "SUCCESS";
		}else{
			return "ERROR: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
		
	}else{
		
		return "No data received";
		
	}
	
}

function generateOrder($data){
	
	if (is_array($data)) {
	
		$conn = connection();
		
		$sql = "
			INSERT 
			INTO `order` (
				`firstnames`, 
				`lastnames`, 
				`email`, 
				`address1`, 
				`address2`, 
				`country`, 
				`postalcode`, 
				`phone`,  
				`total`,  
				`series`
			) VALUES (
				'".$data['firstnames']."' ,
				'".$data['lastnames']."' ,
				'".$data['email']."' ,
				'".$data['address1']."' ,
				'".$data['address2']."' ,
				'".$data['country']."' ,
				'".$data['postalcode']."' ,
				'".$data['phone']."' ,
				'".$data['total']."' ,
				'".$data['series']."'  
			)";
		
		if ($conn->query($sql) === TRUE){
			return "SUCCESS";
		}else{
			return "ERROR: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
		
	}else{
		
		return "No data received";
		
	}
	
}

function updatePayments($data){
	
	if (is_array($data)) {
	
		$conn = connection();
		
		$sql = "
			UPDATE 
				`order` 
			SET 
				`paypalid` = '".$data['txn_id']."' ,
				`paypalstatus` = '".$data['payment_status']."',
				`paypaltotal` = ". $data['payment_amount']."  
			WHERE 
				`series` = '" . $data['order_series'] . "'";
	
		if ($conn->query($sql) === TRUE){
			return "SUCCESS";
		}else{
		    return "ERROR: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
		
	}else{
		
		return "No data received";
		
	}
	
}


function check_txnid($tnxid){
	
	$link = connection();
	
	return true;
	
	$valid_txnid = true;
	
	$sql = mysql_query("SELECT * FROM order WHERE paypalid = '$tnxid'", $link);
	
	if ($row = mysql_fetch_array($sql)) {
		$valid_txnid = false;
	}
	
	return $valid_txnid;
	
}