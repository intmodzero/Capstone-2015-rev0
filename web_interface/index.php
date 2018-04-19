<!DOCTYPE html>
<html>
<head> 
<title>The Magic Mouse Login</title>
<link href="bootstrapCSS/css/bootstrap.min.css" rel="stylesheet">

</head>
	
<body>
	
	
<center>
<div id="logo"><img src="logo_digital.png" width="200"></div>
<h1>Login</h1>
<?php
	session_start();
	if($_SESSION["pw_error"]) echo "Invalid username or password!";
?>
<form action="login.php" method="post">
	<table>
		<tr><td style="text-align:right">login name:</td><td> <input type="text" name="username" /></td></tr>
		<tr><td style="text-align:right">password:</td><td> <input type="password" name="password" /></td></tr>
		<tr><td></td><td><input class="btn btn-primary" type="submit" value=login /></td></tr>
	</table>
</form><br/>

</center>
</body>
</html>
