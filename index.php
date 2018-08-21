<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


// Count lines
$file = 'bento_log.txt';

//Get number of lines
$totalLines = intval(exec("wc -l '$file'"));

//first date
$firstline = explode(" ", file($file)[0]);

//last date
$lastline = explode(" ", file($file)[$totalLines-1]);


$date1 = $firstline[0];
$date2 = $lastline[0];

//$date1 = '2014-12-01';
//$date2 = date('Y-m-d');

$range1 = 3;
$range2 = 5;
$range3 = 7;
$range4 = 10;



if ($_GET['range1'] != "") {
	$range1 = $_GET['range1'];
}

if ($_GET['range2'] != "") {
	$range2 = $_GET['range2'];
}

if ($_GET['range3'] != "") {
	$range3 = $_GET['range3'];
}

if ($_GET['range4'] != "") {
	$range4 = $_GET['range4'];
}

if ($_GET['date1'] != "") {
	$date1 = $_GET['date1'];
}

if ($_GET['date2'] != "") {
	$date2 = $_GET['date2'];
}

$range1Num = 0;
$range2Num = 0;
$range3Num = 0;
$range4Num = 0;
$range5Num = 0;

$endecaXMLTotal = 0;
$endecaTotal = 0;

$endecaArchivalXMLTotal = 0;
$endecaArchivalTotal = 0;

$articlesQueryTotal = 0;
$articlesTotal = 0;

$imagesQueryTotal = 0;
$imagesTotal = 0;

$otherQueryTotal = 0;
$otherTotal = 0;

$pageTotal = 0;

$timeArray = array();

$endecaXMLArray = array();
$endecaArray = array();

$endecaXMLArchivalArray = array();
$endecaArchivalArray = array();

$articlesQueryArray = array();
$articlesArray = array();

$imagesQueryArray = array();
$imagesArray = array();

$otherQueryArray = array();
$otherArray = array();

$pageArray = array();

$fh = fopen( 'bento_log.txt', 'r' ); // 1

$recordCount = 0;

if( $fh ) {

	while ( ( $row = fgetcsv( $fh ) ) !== false ) {

 		if ( ( strtotime($row[0]) <= strtotime($date2 . " +1 day") ) and  ( strtotime($row[0]) >= strtotime($date1) ) ) {

			// used to be $row[10]
			if ($row[12] < $range1) {
				$range1Num += 1;
			}

			if (($row[12] < $range2) and $row[12] > $range1) {
				$range2Num += 1;
			}

			if (($row[12] < $range3) and $row[12] > $range2) {
				$range3Num += 1;
			}

			if (($row[12] < $range4) and $row[12] > $range3) {
				$range4Num += 1;
			}

			if ($row[12] > $range4) {
				$range5Num += 1;
			}

			$timeArray[] = $row[0];

			$endecaXMLArray[] = $row[2];
			$endecaArray[] = $row[3];

			$endecaArchivalXMLArray[] = $row[4];
			$endecaArchivalArray[] = $row[5];

			$articlesQueryArray[] = $row[6]; //4
			$imagesQueryArray[] = $row[7]; //5
			$otherQueryArray[] = $row[8]; //6

			$articlesArray[] = $row[9]; //7
			$imagesArray[] = $row[10]; //8
			$otherArray[] = $row[11]; //9

			$pageArray[] = $row[12]; //10

			$recordCount ++;
		}

	}

}

fclose( $fh );

// percentages

$percentage1 = round(($range1Num / $totalLines) * 100, 1);
$percentage2 = round(($range2Num / $totalLines) * 100, 1);
$percentage3 = round(($range3Num / $totalLines) * 100, 1);
$percentage4 = round(($range4Num / $totalLines) * 100, 1);
$percentage5 = round(($range5Num / $totalLines) * 100, 1);

// get Average
function getAverage($arr) {
		$total = 0;
    $count = count($arr); //total numbers in array
    foreach ($arr as $value) {
        $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
}

// get Median
function getMedian($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}

// get Max
function getMax($arr) {
    sort($arr);
    $count = count($arr); //total numbers in array
    $max = $arr[$count - 1];
    return $max;
}

// get Min
function getMin($arr) {
    sort($arr);
    $min = $arr[0];
    return $min;
}

?>

<!DOCTYPE html>
<html>

<head>


	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([

          ['Queries', 'Time Taken'],

    <?php

    	echo "['" . number_format($range1Num) . " were less than " . $range1 . " seconds', " . $percentage1 . "],";
    	echo "['" . number_format($range2Num) . " were between " . $range1 . " and " . $range2 . " seconds', " . $percentage2 . "],";
    	echo "['" . number_format($range3Num) . " were between " . $range2 . " and " . $range3 . " seconds', " . $percentage3 . "],";
    	echo "['" . number_format($range4Num) . " were between " . $range3 . " and " . $range4 . " seconds', " . $percentage4 . "],";
    	echo "['" . number_format($range5Num) . " were more than " . $range4 . " seconds', " . $percentage5 . "]";

    ?>
        ]);

        var options = {

          backgroundColor: { fill:'transparent' },

          legend: 'none',

          pieHole: 0.5,

          slices: {
            0: { color: '#F24738' },
            1: { color: '#0E7369' },
            2: { color: '#1BA698' },
            3: { color: '#04D9D9' },
            4: { color: '#e3e3e3' }
          },

    	  title: 'Page Loads by Time',

    <?php

        //echo "title: '" . number_format($totalLines) . " Total Queries',";

    ?>

        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>


<style>

	html {
		height: 100%;
	}

	body {
		margin:0; padding:0;
		background: #e3e3e3;
		overflow: visible;
	}

	.wrapper {
		width: 70%;
		margin: 30px auto 30px auto;
		max-width: 1000px;
		background: #fff;
		padding: 20px;
		overflow: visible;
	}

	#donutchart {
		width: 70%;
		height: 70%;
		min-height: 500px;
		float: right;
		top: -420px;
		position: relative;
	}

	.form-horizontal {
		float: left;
		width: 30%;
	}


	.controls p {
		position: relative;
		top: -1.6em;
		left: 5em;
		color: #999;
	}

	.grey {
		background: #e9e9e9;
	}

	.light-grey {
		background: #f9f9f9;
	}

	.grey-text {
		color: #999;
	}

	.big {
		font-size: 125%;
	}

	.date {
		margin: 0 !important;
		padding: 0 !important;
		width: 10em;
		border: 1px solid #ccc !important;
	}



</style>

</head>

<body>

<div class="wrapper">

<h1>Bento Performance Data</h1>

<?php //echo '<p class="grey-text">There have been <span class="big">' . number_format($totalLines) . '</span> queries between <span class="big">' . date("n/j/Y", strtotime($timeArray[0])) .'</span> and <span class="big">' . date("n/j/Y", strtotime($timeArray[$totalLines-1])) . '</span></p>' ; ?>


<form name="myform" method="get" action="index.php">

<fieldset>
<?php echo '<p class="grey-text">There have been <span class="big">' . $recordCount . '</span> queries between <input class="date" type="date" id="date1" name="date1" value="' . $date1 . '"> and <input class="date" id="date2" name="date2" type="date" value="' . $date2 . '"> <button class="btn btn-default">Reload</button></p>' ; ?>
</fieldset>

<table class="table">

<tr>
	<th>&nbsp;</th>
	<th>Max</th>
	<th>Min</th>
	<th>Median</th>
	<th class="grey">Average</th>
</tr>

<tr class="light-grey">
	<th class="rowhead">Endeca (Query):</th>
	<td><?php echo round((getMax($endecaXMLArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($endecaXMLArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($endecaXMLArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($endecaXMLArray)),2); ?> seconds</td>
</tr>

<tr>
	<th class="rowhead">Endeca (Output):</th>
	<td><?php echo round((getMax($endecaArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($endecaArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($endecaArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($endecaArray)),2); ?> seconds</td>
</tr>

<tr class="light-grey">
	<th class="rowhead">Endeca Archival (Query):</th>
	<td><?php echo round((getMax($endecaArchivalXMLArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($endecaArchivalXMLArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($endecaArchivalXMLArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($endecaArchivalXMLArray)),2); ?> seconds</td>
</tr>

<tr>
	<th class="rowhead">Endeca Archival (Output):</th>
	<td><?php echo round((getMax($endecaArchivalArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($endecaArchivalArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($endecaArchivalArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($endecaArchivalArray)),2); ?> seconds</td>
</tr>

<tr class="light-grey">
	<th class="rowhead">Articles (Query):</th>
	<td><?php echo round((getMax($articlesQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($articlesQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($articlesQueryArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($articlesQueryArray)),2); ?> seconds</td>
</tr>

<tr>
	<th class="rowhead">Articles (Output):</th>
	<td><?php echo round((getMax($articlesArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($articlesArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($articlesArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($articlesArray)),2); ?> seconds</td>
</tr>

<tr class="light-grey">
	<th class="rowhead">Images (Query):</th>
	<td><?php echo round((getMax($imagesQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($imagesQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($imagesQueryArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($imagesQueryArray)),2); ?> seconds</td>
</tr>

<tr>
	<th class="rowhead">Images (Output):</th>
	<td><?php echo round((getMax($imagesArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($imagesArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($imagesArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($imagesArray)),2); ?> seconds</td>
</tr>

<tr class="light-grey">
	<th class="rowhead">Other (Query):</th>
	<td><?php echo round((getMax($otherQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($otherQueryArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($otherQueryArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($otherQueryArray)),2); ?> seconds</td>
</tr>

<tr>
	<th class="rowhead">Other (Output):</th>
	<td><?php echo round((getMax($otherArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($otherArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($otherArray)),2); ?> seconds</td>
	<td class="grey"><?php echo round((getAverage($otherArray)),2); ?> seconds</td>
</tr>

<tr class="light-grey">
	<th class="rowhead">Full Page:</th>
	<td><?php echo round((getMax($pageArray)),2); ?> seconds</td>
	<td><?php echo round((getMin($pageArray)),2); ?> seconds</td>
	<td><?php echo round((getMedian($pageArray)),2); ?> seconds</td>
	<td class="grey"><strong><?php echo round((getAverage($pageArray)),2); ?> seconds</strong></td>
</tr>

</table>

<br />

<!--<form name="myform" class="form-horizontal" method="get" action="index.php">-->
<fieldset>

<!-- Form Name -->
<legend>Graph Controls</legend>

<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="range1">Time 1</label>
  <div class="controls">
    <input id="range1" name="range1" type="number" size="3" min="1" max="99" value=<?php echo $range1; ?>>
    <p>seconds</p>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="range2">Time 2</label>
  <div class="controls">
    <input id="range2" name="range2" type="number" size="3" min="1" max="99" value=<?php echo $range2; ?>>
    <p>seconds</p>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="range3">Time 3</label>
  <div class="controls">
    <input id="range3" name="range3" type="number" size="3" min="1" max="99" value=<?php echo $range3; ?>>
    <p>seconds</p>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="range4">Time 4</label>
  <div class="controls">
    <input id="range4" name="range4" type="number" size="3" min="1" max="99" value=<?php echo $range4; ?>>
    <p>seconds</p>
  </div>
</div>


<!-- Button -->
<div class="control-group">
  <label class="control-label" for="singlebutton"></label>
  <div class="controls">
    <button class="btn btn-default">Reload the Graph</button>
  </div>
</div>

</fieldset>
</form>





<div id="donutchart"></div>

</div>


</body>

</html>
