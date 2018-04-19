<!DOCTYPE html>
<html>
<head>
<title> The Magic Mouse -  Logout </title>
<link href="bootstrapCSS/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	
<center>	
<?php
	include 'verify_login.php';
	session_unset();
	session_destroy();
	echo "<h3>You have been successfully logged out</h3>";
?>
<br/>
<button onclick="location.href = 'index.php';" id="LoginButton" class="btn btn-lg btn-primary">back to login</button>
</center>

</body>
</html>
