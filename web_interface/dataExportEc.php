<?php
include 'verify_login.php';
include 'database.php';

date_default_timezone_set("America,Toronto");

//$date = date('Y-m-d_H-i-s');
$date = date('Y-m-d__H-i-s');
$user_id = $_GET['uid'];


//force download
header('Content-Type: application/csv');
header('Content-Disposition: attachement; filename="'.$_GET['name']."_".$date.'ec"');
//header('Content-Disposition: attachement; filename="HI";');

$conn = connect_db();

$query_data = "SELECT ecL,ecR,timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$user_id."';";

$result_data = $conn->query($query_data);

echo "EcValueL,EcValueR,UnixTimestamp\xA";

while($row_data = $result_data->fetch_assoc()){
	echo $row_data["ecL"].",".$row_data["ecR"].",".$row_data["timestamp"]."\xA";
}


?>
