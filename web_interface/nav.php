<nav class="navbar navbar-default">
	<div id="navbar">
        	<ul class="nav navbar-nav">
		<?php
		echo "<li><a href=\"logout.php\">Logout ".$_SESSION["login_user"]."</a></li>";
		?>
		<li><a></a></li>
           	<li><a href="createAccount.php">Create Account</a></li>
           	<li><a href="deleteAccountNew.php">Delete Account</a></li>
           	<li><a href="searchAccount.php">Search Account</a></li>
           	<li><a href="viewTempDataNew.php">View Temperature Data</a></li>
           	<li><a href="viewAccData.php">View ACC Data</a></li>
		<li><a href="viewEcData.php">View Ec Data</a></li>

          	</ul>
        </div>
</nav>

