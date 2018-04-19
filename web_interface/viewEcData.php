<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Electrical conductivity Data</title>
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
//include 'exportData.php';


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

<form action="viewEcData.php" method="post">
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
echo "name: ".$row_sel_user["first_name"]." ".$row_sel_user["last_name"]."<br/>";
//echo "<a href=\"viewTempData.php?init=true\">Export data file</a>";
}

//$query_data = "SELECT valueL,valueR,timestamp FROM `measured_data`.`temperature_data` WHERE account_id='".$row_sel_user["account_id"]."';";

$query_data = "SELECT * FROM (SELECT ecL,ecR,timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY `timestamp` DESC LIMIT 1500)sub ORDER BY `timestamp` ASC;";

//$query_data = "SELECT * FROM (SELECT x,y,z,timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY `timestamp` DESC LIMIT 250)sub ORDER BY `timestamp` ASC;";

$query_last = "SELECT timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY timestamp DESC LIMIT 1;";

$result_last = ($conn->query($query_last)->fetch_assoc());

$result_data = $conn->query($query_data);

$data1 = array();
$data2 = array();
$timestamp = array();
 
//last timestamp from user
$last_timestamp = $result_last['timestamp']*1000; 

// half minute earlier
$start = $last_timestamp-30000;
echo "<script>";

// Query result
$i=0;
while($row_data = $result_data->fetch_assoc()){
	//$data1[$i]=$row_data["valueL"];
	//$data2[$i]=$row_data["valueR"];
	$timestamp[$i]=$row_data["timestamp"]*1000;
	$data1[$i]= '['.$timestamp[$i].','.$row_data["ecL"].']';
	$data2[$i]= '['.$timestamp[$i].','.$row_data["ecR"].']';
	//echo 'dataR.push('.$timestamp.','.$row_data["valueR"].');';
	$i++;
}
echo "data1=[";

echo join(', ', $data1);
echo "];";

echo "data2=[";

echo join(', ', $data2);
echo "];";

/*
echo "times=[";

echo join(', ', $timestamp);
echo "];";
*/


echo "</script>";

?>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="http://code.highcharts.com/stock/highstock.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
	
		$(function () {
		$('#tempGraph').highcharts({	
		chart: { zoomType: 'x'
		//panning: true
		//pinchType: 'x'
		},
		subtitle: {
            	text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' :
                'Pinch the chart to zoom in'
		},

		title: {
		text: 'Electrical Conductivity Visualization',
				x: 0 //center
					 },
		xAxis: { type: 'datetime',gridLineWidth: 1},
		yAxis: {
		title: {
		text: 'Electrical Conductivity (units)'
					 },
		plotLines: [{
		value: 0,
					 width: 1,
					 color: '#808080'
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
		name: 'EC L',
					data: data1
						}, {
		name: 'EC R',
						data: data2
						}]
	
			});
			
	//	$('#tempGraph').highcharts().xAxis[0].setExtremes(<?php echo "1429814280920".",".$last_timestamp; ?>);
		$('#tempGraph').highcharts().xAxis[0].setExtremes(<?php echo $start.",".$last_timestamp; ?>);

		});
        	
		//var xAxis = chart.xAxis[0];
        	//chart.xAxis[0].setExtremes(0, 1);
			
		</script>
		<script src="js/highcharts.js"></script>
		<script src="js/modules/exporting.js"></script>

<?php
	if($_POST["selectedUser"]){
	echo "<div id=\"tempGraph\" style=\"min-width: 310px; height: 600px; margin: 0 auto\"></div>";
	}

	/*
	if($_GET['init'])
	{
    		convert_to_csv($data2, 'Temp_Data.csv', ',');    
	}	 
	*/

?>
</body>
</html>


