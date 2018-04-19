<?php
	include 'database.php';

	$conn = connect_db();

	$account_query = "SELECT account_id,password_hash from account WHERE username='".$_POST["username"]."'";

	$result = $conn->query($account_query);

	$row = mysqli_fetch_assoc($result);

	echo $row["account_id"].",".$row["password_hash"];
?>
