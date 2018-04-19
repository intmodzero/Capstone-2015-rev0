
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Search user</title>
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
$conn = connect_db();
	
$query_value = "SELECT username FROM `account`.`account`;";
$result = $conn->query($query_value);

echo "<script>
$(function() {
var userList = [\"";

$user=array();

// Query result
$i=0;
while($row = $result->fetch_assoc()){
	$user[$i] = $row["username"];
	$i++;
}

echo join('", "', $user);

echo " \"];
$( \"#user\" ).autocomplete({source: userList});
});
</script>";

?>
<div class="row">
	<div class="col-xs-2 col-md-1"></div>
	<div class="col-xs-12 col-md-8">
	
	<form action="searchAccount.php" method="post">
		Search:
		<input id="user" name="selectedUser">
		<input class="btn btn-primary" type="submit" value='view data' />
	</form>

</div>
</div>

<table>
<?php
if($_POST["selectedUser"]){
$query_sel_user = "SELECT * FROM account.account WHERE username='".$_POST[selectedUser]."';";
$result_sel_user = $conn->query($query_sel_user);
$row_sel_user = $result_sel_user->fetch_assoc();

?>

<div class="row">
	<div class="col-xs-2 col-md-1"></div>
	<div class="col-xs-12 col-md-8">
	
<?php
echo "<h3> Account Information </h3>";
echo "username: ".$row_sel_user["username"]."<br/>";
echo "user_id: ".$row_sel_user["account_id"]."<br/>";
echo "group_id: ".$row_sel_user["group_id"]."<br/>";
echo "name: ".$row_sel_user["first_name"]." ".$row_sel_user["last_name"]."<br/>";
echo "<h3> Profile Information </h3>";
echo "email: ".$row_sel_user["email"]."<br/>";
echo "sex: ".$row_sel_user["sex"]."<br/>";
echo "bmi: ".$row_sel_user["BMI"]."<br/>";
echo "height: ".$row_sel_user["height"]."  cm<br/>";

echo "weight: ".$row_sel_user["weight"]." lbs<br/>";
echo "ethnicity: ".$row_sel_user["ethnicity"]."<br/>";
echo "description: ".$row_sel_user["description"]."<br/>";

echo "<h3> Export Data </h3>";

echo "<a href=\"dataExport.php/?uid=".$row_sel_user["account_id"]."&"."name=".$row_sel_user["first_name"]."_".$row_sel_user["last_name"]."\"> Download Temperature Data </a>";

echo "<br/>";

echo "<a href=\"dataExportEc.php/?uid=".$row_sel_user["account_id"]."&"."name=".$row_sel_user["first_name"]."_".$row_sel_user["last_name"]."\"> Download Electrical Conductivity Data </a>";

}
?>
</div>
</div>
</table>

</body>
</html>
