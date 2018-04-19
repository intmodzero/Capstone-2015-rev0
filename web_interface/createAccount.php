
<!DOCTYPE html>
<html>
<head>

<title> Magic Mouse Create Account </title>

<link href="bootstrapCSS/css/bootstrap.min.css" rel="stylesheet">
<?php
//include 'verify_login.php';
include 'verify_admin.php';
?>

</head>

	
<body>
<?php
include_once "nav.php";
?>
<div class="container">
	<h2> Create Account</h2>
	<form action="createAccount.php" method="post">
		<table>
		<tr><td>username:</td>
		<td> <input type="text" name="create_username" /></td></tr>
		<tr><td>password:</td><td> <input type="password" name="create_password" /></td></tr>
		<tr><td>confirm password</td><td><input type="password" name="repeat_password" /></td></tr>
		<tr><td></td><td><input class="btn btn-primary"  type="submit" value=submit /></td></tr>
		</table>
	</form>
	</div>
	</body>	
</html>
	
<?php
include 'database.php';
//include 'verify_login.php';


// Hashes password using bcrypt()
function hash_password($password){
	// Hashing options
	$options = [
    'cost' => 12,	// Cost determines the work required to brute-force
    //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
	];

	return password_hash($password, PASSWORD_BCRYPT, $options);
}


// Creates new account, group_id=1 for normal user
function create_account($username, $password, $group_id, $salt){

	$password_hash = hash_password($password);
	
	$insert_row = "INSERT INTO `account`.`account` (`account_id`, 
	`username`, `password_hash`, `group_id`, `salt`, `failed_attempts`, 
	`last_failed_time`) VALUES (NULL, '".$username."', '"
	.$password_hash."', '".$group_id."', NULL, NULL, NULL);";

//	$insert_row = "INSERT INTO `account`(`account_id`, `username`, `password_hash`, `group_id`, `salt`, `failed_attempts`, `last_failed_time`, `last_active_login`, `first_name`, `last_name`, `sex`, `BMI`, `email`, `description`, `ethnicity`, `weight`, `height`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16],[value-17]);"

	$conn = connect_db();
	
	$query_user = "SELECT username FROM account where username='".$username."';";
	$result = db_query($conn, $query_user);
	
	if (!$result and $username!=""){
		if ($conn->query($insert_row) === TRUE) {
			echo "<br/>",$username," created successfully";
		} else {
			echo "<br/>Error: " . $sql . "<br>" . $conn->error;
		}
	}
	else{
		echo "<br/> Username is taken, try a different name";
	}
}

// confirm password
if($_POST){

	if($_POST["create_password"]!= $_POST["repeat_password"]){
		echo "<br/> passwords do not match";
	}
	else{
		//create account in group 1
		create_account($_POST["create_username"], $_POST["create_password"], 1, NULL);
	}
}

?>

