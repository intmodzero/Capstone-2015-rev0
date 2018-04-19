<?php
	session_start();

	if (!$_SESSION["is_admin"]){
		header("location: index.php");
		exit();
	}

?>
