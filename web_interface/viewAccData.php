<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Acc Data</title>
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
include_once 'nav.php';
include 'database.php';

$conn = connect_db();

if(isset($_POST['selectedUser'])){
  $usernamePost = $_POST['selectedUser'];
}
else{
  //if the $_POST['name'] is not set
  $usernamePost = '';
}

/*	
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
*/
?>

<form action="viewAccData.php" method="post">
	Username:
	<input id="user" name="selectedUser" value="<?php echo $usernamePost; ?>">
	<input class="btn btn-primary" type="submit" value='view data' />
</form>
<?php
if($_POST["selectedUser"]){
$query_sel_user = "SELECT * FROM account.account WHERE username='".$_POST[selectedUser]."';";
$result_sel_user = $conn->query($query_sel_user);
$row_sel_user = $result_sel_user->fetch_assoc();

echo "username: ".$row_sel_user["username"]."<br/>";
if($row_sel_user["first_name"]!=NULL){echo "name: ".$row_sel_user["first_name"]." ".$row_sel_user["last_name"]."<br/>";}
//print_r($row_sel_user);
//echo "<a href=\"viewTempData.php?init=true\">Export data file</a>";
}

$query_data = "SELECT * FROM (SELECT x,y,z,timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY `timestamp` DESC LIMIT 1500)sub ORDER BY `timestamp` ASC;";

$result_data = $conn->query($query_data);

$query_last = "SELECT timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY timestamp DESC LIMIT 1;";

$result_last = ($conn->query($query_last)->fetch_assoc());

 
//last timestamp from user
$last_timestamp = $result_last['timestamp']*1000; 

// half minute earlier
$start = $last_timestamp-30000;




$data1 = array();
$data2 = array();
$data3 = array();
$data4 = array();
$timestamp = array();
 
// Query result
$i=0;
while($row = $result_data->fetch_assoc()){
//	$data1[$i]=$row["x"];
//	$data2[$i]=$row["y"];
//	$data3[$i]=$row["z"];
	$timestamp[$i]=$row["timestamp"]*1000;
	//$data1[$i]= '['.$timestamp[$i].','.$row["x"].']';
	//$data2[$i]= '['.$timestamp[$i].','.$row["y"].']';
	//$data3[$i]= '['.$timestamp[$i].','.$row["z"].']';
	$magnitude = sqrt(pow($row["x"],2)+pow($row["y"],2)+pow($row["z"],2)); 
	$data4[$i]= '['.$timestamp[$i].','.$magnitude.']';
	$i++;
}

echo "<script>";
/*
echo "data1=[";

echo join(', ', $data1);
echo "];";

echo "data2=[";

echo join(', ', $data2);
echo "];";


echo "data3=[";

echo join(', ', $data2);
echo "];";

echo "data4=[";

echo join(', ', $data2);
echo "];";


echo "times=[";

echo join(', ', $timestamp);
echo "];";
*/
echo "</script>";

//$data4 = [];

/*
for ($i=0 ; $i < count($data1); $i++){
	$magnitude = floor(sqrt(pow($data1[$i],2)+pow($data2[$i],2)+pow($data3[$i],2)));
	array_push($data4, $magnitude);	
	}
*/
echo"<script>";
echo "data4=[";

echo join(', ', $data4);
echo "];";

echo "</script>";

?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
		$(function () {
			$('#MagPlot').highcharts({
		chart: { zoomType: 'x'},
		subtitle: {
            	text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' :
                'Pinch the chart to zoom in'
        	},	
		title: {
		text: 'Acc Magnitude',
				x: 0 //center
					 },
		xAxis: {
		type: 'datetime',
					gridLineWidth: 1
					 },
		yAxis: {
		title: {
		text: 'acceleration'
					 },
		plotLines: [{
		value: 0,
					 width: 1,
					 color: '#000080'
							 }]
					 },
		tooltip: {
		valueSuffix: ''
						 },
		legend: {
		layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
						},
		series: [{
		name: 'Magnitude',
					data: data4
						}]
			});
		$('#tempGraph').highcharts().xAxis[0].setExtremes(<?php echo $start.",".$last_timestamp; ?>);

		});
		</script>



		<script src="js/highcharts.js"></script>
		<script src="js/modules/exporting.js"></script>
	<?php

		if($_POST["selectedUser"]){
		echo "<div id=\"MagPlot\" style=\"min-width: 310px; height: 600px; margin: 0 auto\"></div>";

		}
	?>
	</body>
</html>

