<?php
session_start();

include "connection.php";

header('Content-Type: application/json');

$servername = $_servername;
$username = $_username;
$password = $_password;
$dbname = $_dbname;

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
		
	printf("Conexión fallida: %s\n", mysqli_connect_error());
	exit();
	
}else{
	
	//echo "entro";
	
	$sql = "SELECT `id`, `code`, `name`, `price`, `description`, `photo` FROM `product`";
	
	$result = $conn->query($sql);
	
	if($result->num_rows > 0){
		
		//echo "resultados" . $result->num_rows;
		
		$collection = array();
		
		while($row = $result->fetch_assoc()) {
			
			$object = array(
				"id" => (int)$row["id"],
				"code" => $row["code"],
				"name" => $row["name"],
				"price" => (float)$row["price"],
				"description" => $row["description"],
				"photo" => $row["photo"]
			);

			//echo "obj: " . json_encode($object);
			
			array_push($collection, $object);
			
		}
		//echo "result: " . sizeof($collection);
		echo json_encode($collection);
		
	}else{
		
		echo null;
		
	}
	
	$conn->close();
	
}

?>