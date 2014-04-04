<?php
require_once("database.php"); // Include the database login/etc

// user-selected bike sharing system - NEEDS TO BE TAKEN FROM LP SELECTION
$systemAbbrev = "NYC";
$homeStation = 'Lafayette Ave & Fort Greene Pl';
$workStation = 'Lispenard St & Broadway';
// $userStationName1 = 'Home'; this seems superferlous
// $userStationName2 = 'Moms House papusa quinuia';

	// let's pull the correct system data from our central DB
	$mysqli=mysqli_connect("$DBHOST", "$DBUSERNAME", "$DBPASSWORD", "$DBASE") or die(mysqli_error());
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

	$query = "SELECT cityName, name, url FROM bikeCities WHERE abbrev='$systemAbbrev' ";

	if ($result = $mysqli->query($query)) {

	/* fetch city name, system name, and URL to get the JSON data */
	while ($row = $result->fetch_row()) {
		$cityName = $row[0];			// what city does this reside in?
		$systemName = $row[1];			// what is the system name
		$systemURL = $row[2];			// system JSON data URL
    	}

	/* free result set */
	$result->close();
	}
	/* close mysql connection */
	$mysqli->close();


	// first, try to pull the most recent data
	$systemBody = 'format=text';
	$systemC = curl_init ($systemURL);
	curl_setopt ($systemC, CURLOPT_POST, true);
	curl_setopt ($systemC, CURLOPT_POSTFIELDS, $systemBody);
	curl_setopt ($systemC, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($systemC);
	curl_close ($systemC);
	$jsondata = $json;
	$jsonArray = json_decode($jsondata, true);
	$timestamp = $jsonArray['executionTime'];


if (empty($jsonArray)) { 			// check to see if the system sent back proper JSON, if not, get the cached copy

	$cacheURL = "http://www.plemel.com/lp/bikefinder/systemJSON/" . "$systemAbbrev" . ".txt";
	$systemBody = 'format=text';
	$systemC = curl_init ($cacheURL);
	curl_setopt ($systemC, CURLOPT_POST, true);
	curl_setopt ($systemC, CURLOPT_POSTFIELDS, $systemBody);
	curl_setopt ($systemC, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($systemC);
	curl_close ($systemC);
	$jsondata = $json;
	$jsonArray = json_decode($jsondata, true);
	$timestamp = $jsonArray['executionTime'];
	
	
};




	// STATION ONE
	
	foreach($jsonArray['stationBeanList'] as $user) {
    if($user['stationName'] == $homeStation) { 
    	if($user['statusValue'] == 'Not In Service') {
	    $stationName = $user['stationName'];
		$availableDocks = 0;
		$totalDocks = $user['totalDocks'];
		$statusValue = $user['statusValue'];
		$availableBikes = 0;
		$barwidth = 100;
		$badgeLocation = $barwidth - 16;
    	} else {
	    	$stationName = $user['stationName'];
			$availableDocks = $user['availableDocks'];
			$totalDocks = $user['totalDocks'];
			$statusValue = $user['statusValue'];
			$availableBikes = $user['availableBikes'];
			$barwidth = 100 - ((round(($availableDocks / $totalDocks),2)) * 100);
			if($barwidth <= 100 && $barwidth >= 80) {$barwidth = "85";} else {		
				$badgeLocation = $barwidth - 7;
				}
			}
		}
		}

	// STATION TWO
	
	foreach($jsonArray['stationBeanList'] as $user) {
    if($user['stationName'] == $workStation) { 
    	if($user['statusValue'] == 'Not In Service') {
	    $WorkstationName = $user['stationName'];
		$WorkavailableDocks = 0;
		$WorktotalDocks = $user['totalDocks'];
		$WorkstatusValue = $user['statusValue'];
		$WorkavailableBikes = 0;
		$Workbarwidth = 100;
		$WorkbadgeLocation = $Workbarwidth - 16;
    	} else {
	    	$WorkstationName = $user['stationName'];
			$WorkavailableDocks = $user['availableDocks'];
			$WorktotalDocks = $user['totalDocks'];
			$WorkstatusValue = $user['statusValue'];
			$WorkavailableBikes = $user['availableBikes'];
			$Workbarwidth = 100 - ((round(($WorkavailableDocks / $WorktotalDocks),2)) * 100);
			if($Workbarwidth <= 100 && $Workbarwidth >= 80) {$WorkbadgeLocation = "82";} else {
				$WorkbadgeLocation = ($Workbarwidth - 7);
				}
			
			}
		}
		}


	

// write time in a human format
function timeAgo($timestamp){
	$datetime1=new DateTime("now");
    $datetime2=date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
		$timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');
		}
		else if($diff->m > 0){
			$timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
		}
		else if($diff->d > 0){
			$timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
		}
		else if($diff->h > 0){
			$timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
		}
		else if($diff->i > 0){
			$timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
		}
		else if($diff->s > 0){
			$timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
		}
		$timemsg = $timemsg.' ago';
		return $timemsg;
	}
$updateTime = timeAgo($timestamp);
   

print"
<html>
    <head>
	<title>$systemName Bike Finder </title>
	<style type='text/css'>

	body {
		width:384px;
		margin:0px;
		font-family: 'cabin';

		}
		
	#mainContainer {
		padding: 0 0 0 0;
		margin: 10px 0 10px 0;
	}
	
	.station {
		padding: 10px 0 10px 0;
		margin: 0 0 20px 0;
		border-bottom: 2px dashed #000;
		}
		
	.graphHome {
		background: #fff;
		width: 372px;
		border: 6px solid black;
		height: 46px;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		overflow: hidden;
		}	
		
	.graphWork {
		background: #fff;
		width: 372px;
		border: 6px solid black;
		height: 46px;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		overflow: hidden;
		}	
		
	.stationHomeBar {
		width: $barwidth%;
		background: #000;
		height: inherit;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		}
		
	.stationWorkBar {
		width: $Workbarwidth%;
		background: #000;
		height: inherit;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		}	
		
	.bikeBadge {
		position: relative;
		left:$badgeLocation%;
		margin-top: -58px;
		-moz-border-radius: 30px;
		border-radius: 30px;
		border: 6px solid black;
		width: 46px;
		height: 46px;
		text-align: center;
		background: #fff;
		display: block;
		}
		
	.bikeBadge p {
		font-family: 'cabin';
		font-size: 34px;
		margin: 0 0 0 0;
		padding: 0 0 0 0;
		vertical-align:middle;
		}
		
	#header {
		font-family: 'cabin';
		font-size: 28px;
		margin: 0 0 10px 0;
		padding: 6px 0 0 10px;
		background: #000;
		background-image:url('bike.png');
		background-repeat:no-repeat;
		background-position: 10 0; 

		color: #fff;
		text-align: left;
		height: 40px;
		}
		
	.name {
		font-family: 'cabin';
		font-size: 34px;
		text-align: left;
		padding: 0 0 10px 0;
		margin: 0 0 10px 0;
		}
		
	.userStationName {	
		font-family: 'cabin';
		font-size: 14px;
		text-align: left;
		text-decoration: underline;
		text-decoration-skip: ink;
		padding: 0 0 0 0;
		margin: 0 0 0 0;
		}
		
	.metadata {
		text-align: center;
		padding: 0 0 0 0;
		margin: 5px 0 0	 0;
	}
	
	#footer {
		text-align: center;
		padding: 0 0 10px 0;
		margin: 0 0 10px 0;
		border-bottom: 3px solid #000;
	}
		
	</style>
	</head>
	<body>
	<div id='mainContainer'>
		<div id='header'>
		$systemName Bike Finder
		</div>
		
		<div id='content'>";




if (empty($jsonArray)) { ;			// checks to see if Citibike website is under maintenance or not

	print "
				<div class='station'>
					<div class='name'>Citibike is under maintenance</div>			
					<div class='metadata'>check back later, please</div>
				</div>
	";
	
	
} else {


print"

		
			<div class='station'>
				<div class='name'>$stationName</div>			
				<div class='graphHome'><div class='stationHomeBar'></div></div>
				<div class='bikeBadge'><p>$availableBikes</p></div>
				<div class='metadata'>$availableBikes bikes available and $availableDocks of $totalDocks docks available</div>
			</div>
			
			<div class='station'>
				<div class='name'>$WorkstationName</div>			
				<div class='graphWork'><div class='stationWorkBar'></div></div>
				<div class='bikeBadge' style='position: relative; left:$WorkbadgeLocation%;'><p>$WorkavailableBikes</p></div>
				<div class='metadata'>$WorkavailableBikes bikes available and $WorkavailableDocks of $WorktotalDocks docks available</div>
			</div>
";
	
	
};





print"
		

		</div>
		<div id='footer'>

$updateTime

		</div>
	</div>
	</body>
</html>



";






?>
