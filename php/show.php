<?php
session_start();

include "connection.php";

$id = $_GET["id"];

if($id != null){

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
		
		//echo $id;
		
		$sql = "
				SELECT 
					p.id as 'id', 
					p.code as 'code', 
					p.name as 'name', 
					p.price as 'price', 
					p.description as 'description', 
					p.procedure as 'procedure', 
					p.properties as 'properties', 
					p.features as 'features', 
					p.instructions as 'instructions', 
					p.photo as 'photo', 
					f.photo as 'other' 
				FROM product p, photo f 
				WHERE f.idProduct = p.id
				AND p.id = " . $id . "";
		
		$result = mysqli_query($conn, $sql);
		
		if($result->num_rows > 0){
			
			//echo $sql;
			
			$special_characters = array("ñ", "Ñ", "á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
			$scaped_characters = array("&ntilde;", "&Ntilde;", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "11", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");
			
			$collection = array();
			
			while($row = $result->fetch_assoc()) {
					
				$object = array("photo" => $row["other"]);
					
				array_push($collection, $object);
				
				$object = array(
					"id" => (int)$row["id"],
					"code" => $row["code"],
					"name" => $row["name"],
					"price" => (float)$row["price"],
					"description" => str_replace($special_characters, $scaped_characters, $row["description"]),
					"procedure" => str_replace($special_characters, $scaped_characters, $row["procedure"]),
					"properties" => str_replace($special_characters, $scaped_characters, $row["properties"]),
					"features" => str_replace($special_characters, $scaped_characters, $row["features"]),
					"instructions" => str_replace($special_characters, $scaped_characters, $row["instructions"]),
					"photo" => $row["photo"],
					"detailPhotos" => $collection
				);
				//echo "aham: " . str_replace($special_characters, $scaped_characters, $row["procedure"]);
			}
			
			//$row = mysqli_fetch_assoc($result);
			
			print_r(json_encode($object));
			
		}else{
			
			echo null;
			
		}
		
		mysqli_close($conn);
		
	}

}
?>