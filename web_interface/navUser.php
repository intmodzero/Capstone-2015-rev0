<nav class="navbar navbar-default">
	<div id="navbar">
        	<ul class="nav navbar-nav">
		<?php
		echo "<li><a href=\"logout.php\">Logout ".$_SESSION["login_user"]."</a></li>";
		echo "<li><a href=\"viewOwnTempData.php\"> View Temp Data </a></li>";
		echo "<li><a href=\"viewOwnEcData.php\"> View Ec Data </a></li>";	
		echo "<li><a href=\"downloadOwnData.php\"> Download Data </a></li>";
	?>
		<li><a></a></li>
          	</ul>
        </div>
</nav>

