
<?php

function connect_db($dbname="account"){
	
	$servername = "localhost";
	$username = "admin";
	$password = "XyQzW37Jpj9e5jTP";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
		die("<br/>Connection failed: " . $conn->connect_error);
	}
	else{
	}

	//$conn->select_db($dbname);
	return $conn;
}

function db_query($conn, $query){
	
	$result = $conn->query($query);
	
	// Query result
	$row = $result->fetch_assoc();	
	
	return $row;
}
?>
