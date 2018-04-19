<?php
if(isset($_POST)) {
	$readings = json_decode($_POST["data"],$assoc=true);
}

include 'database.php';

$conn = connect_db("measured_data");

foreach($readings as $data) {
	$reading_query = "INSERT INTO `measured_data`.`measured_data` (`id`,`tempL`,`tempR`,`ecL`,`ecR`,`x`,`y`,`z`,`timestamp`,`account_id`) VALUES ('".$data["id"]."','".$data["t_l"]."','".$data["t_r"]."','".$data["ec_l"]."','".$data["ec_r"]."','".$data["acc_x"]."','".$data["acc_y"]."','".$data["acc_z"]."','".$data["timestamp"]."',".$data["account_id"].")";

	$conn->query($reading_query);
}
?>
