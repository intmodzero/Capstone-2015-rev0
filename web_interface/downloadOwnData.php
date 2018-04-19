
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Temperature Data</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link href="bootstrapCSS/css/bootstrap.min.css" rel="stylesheet">
<?php
include 'verify_login.php';
?>

</head>

	
<body>

<?php
include_once 'navUser.php';
include 'database.php';
include 'exportData.php';

$conn = connect_db();

$query_account = "SELECT * FROM account.account;";

$row = $conn -> query($query_account);
$result = $row -> fetch_assoc();

?>
<div class="row">
	<div class="col-xs-2 col-md-1"></div>
	<div class="col-xs-12 col-md-8">
	
<?php
	echo "<a href=\"dataExport.php/?uid=".$result["account_id"]."&"."name=".$result["first_name"]."_".$result["last_name"]."\"> Download Your Temp Data </a>";

echo "<br/>";

echo "<a href=\"dataExportEc.php/?uid=".$result["account_id"]."&"."name=".$result["first_name"]."_".$result["last_name"]."\"> Download Your Electrical Conductivity Data </a>";


?>
</div>
</div>

</body>
</html>


