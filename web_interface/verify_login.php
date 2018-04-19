<?php
	session_start();
	if(!$_SESSION["login_user"]) {
		header("Location: index.php");
	}
?>
