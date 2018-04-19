<?php
include 'database.php';

$conn = connect_db();


// Queries the database for password hash of the account
$query_hash = "SELECT password_hash, group_id FROM account where username='".$_POST["username"]."';";
$result = db_query($conn, $query_hash);


session_start();
// Login to Welcome page
if (password_verify($_POST["password"], $result["password_hash"])){
	$_SESSION['pw_error']= false; 
	$_SESSION['login_user']= $_POST["username"];
	$update_time_query = "UPDATE `account` SET `last_active_login`=(now()) WHERE username='".$_POST["username"]."';";
	//updates last login time
	$update_result = $conn->query($update_time_query);
	
	//Admin login
	if ($result["group_id"]==2){
		$_SESSION["is_admin"]= TRUE;
		header("Location: welcome.php"); // Redirects To Welcome Page;
	}
	//User login
	else{
		header("Location: welcomeUser.php");
	}
} 
else {
	$_SESSION['pw_error']= true; 
	header("Location: index.php"); // Redirects To Login Page;
}

// Close database connection
$conn->close();


?>
