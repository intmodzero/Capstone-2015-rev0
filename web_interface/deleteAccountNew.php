
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Delete user</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<link href="bootstrapCSS/css/bootstrap.min.css" rel="stylesheet">
<?php
include 'verify_admin.php';
?>

</head>

	
<body>
<?php
include_once "nav.php";
?>

<?php
include 'database.php';
include 'verify_login.php';

//connect to database
$conn = connect_db();
	
$query_value = "SELECT * FROM `account`.`account`;";
//$query_value = "SELECT * FROM `account` JOIN `person` ON account.`account_id`=person.`user_id`;";
$result = $conn->query($query_value);
?>

<center>
<table width="500" border="0">
<tr>
<td><form name="form" method="post" onsubmit="return confirm('Are you sure? Currently account deletion is irreversible')"  action="deleteAccountNew.php">
<table class="table table-striped" width="500" border="1">
<tr>
<td></td>
<td colspan="4"><strong>Account Deletion</strong> </td>
</tr>
<tr>
<td align="center"><strong>#</strong></td>
<td align="center"><strong>Username</strong></td>
<td align="center"><strong>Name</strong></td>
<td align="center"><strong>Email</strong></td>
<td align="center"><strong>Last Login</strong></td>
</tr>
</center>
<?php
$count = 0;
while($row= $result->fetch_assoc()){
	echo "<tr>
<td align=\"center\"><input name=\"checkbox[]\" type=\"checkbox\" id=\"checkbox[]\" value=\"".$row["account_id"]."\"></td>";
	echo "<td>".$row["username"]."</td>";
	echo "<td>".$row["first_name"]." ".$row["last_name"]."</td>";
	echo "<td>".$row["email"]."</td>";
	echo "<td>".$row["last_active_login"]."</td>";
	$count++;
}
?>

<tr>
<td colspan="6" align="center"><input class='btn btn-primary' name="delete" type="submit" id="delete" value="Delete" ></td>
</tr>

</table>
</form>
</td>
</tr>
</table>

<?php
foreach($_POST['checkbox'] as $selected){
	$delete_query = "DELETE FROM account WHERE account_id ='".$selected."';";
	$resultDel = $conn->query($delete_query);
	header("Location: deleteAccountNew.php");
}
?>
</body>
</html>
