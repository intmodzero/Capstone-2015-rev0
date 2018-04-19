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

$conn = connect_db();
	
$query_sel_user = "SELECT * FROM account.account WHERE username='".$_SESSION["login_user"]."';";
$result_sel_user = $conn->query($query_sel_user);
$row_sel_user = $result_sel_user->fetch_assoc();


echo "Below is a visualization of the temperature data collected from you <br/>";

$query_data = "SELECT * FROM (SELECT tempL,tempR,timestamp FROM `measured_data`.`measured_data` WHERE account_id='".$row_sel_user["account_id"]."' ORDER BY `timestamp` DESC LIMIT 1500)sub ORDER BY `timestamp` ASC;";


$result_data = $conn->query($query_data);

$data1 = array();
$data2 = array();
$timestamp = array();
 

echo "<script>";

// Query result
$i=0;
while($row_data = $result_data->fetch_assoc()){
	//$data1[$i]=$row_data["valueL"];
	//$data2[$i]=$row_data["valueR"];
	$timestamp[$i]=$row_data["timestamp"]*1000;
	$data1[$i]= '['.$timestamp[$i].','.$row_data["tempL"].']';
	$data2[$i]= '['.$timestamp[$i].','.$row_data["tempR"].']';
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
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
		$(function () {
			$('#tempGraph').highcharts({
		
		chart: { zoomType: 'x'},
		subtitle: {
            	text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' :
                'Pinch the chart to zoom in'
        	},
		title: {
		text: 'Temperature Visualization',
				x: -20 //center
					 },
		xAxis: { type: 'datetime',gridLineWidth: 1},
		yAxis: {
		title: {
		text: 'Temperature (°C)'
					 },
		plotLines: [{
		value: 0,
					 width: 1,
					 color: '#808080'
							 }]
					 },
		tooltip: {
		valueSuffix: '°C'
						 },
		legend: {
		layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
						},
		series: [{
		name: 'Temperature L',
					data: data1
						}, {
		name: 'Temperature R',
						data: data2
						}]
			});
		});
		</script>
		<script src="js/highcharts.js"></script>
		<script src="js/modules/exporting.js"></script>

<?php
	
	echo "<div id=\"tempGraph\" style=\"min-width: 310px; height: 600px; margin: 0 auto\"></div>";
	

	/*
	if($_GET['init'])
	{
    		convert_to_csv($data2, 'Temp_Data.csv', ',');    
	}	 
	*/

?>
</body>
</html>


