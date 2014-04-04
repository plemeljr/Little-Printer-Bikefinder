<?php

	// NYC Citibike JSON
	$citibikeURL = 'http://www.citibikenyc.com/stations/json';
	$citibikeBody = 'format=text';
	$citibikeC = curl_init ($citibikeURL);
	curl_setopt ($citibikeC, CURLOPT_POST, true);
	curl_setopt ($citibikeC, CURLOPT_POSTFIELDS, $citibikeBody);
	curl_setopt ($citibikeC, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($citibikeC);
	curl_close($citibikeC);

	$jsondata = $json;
	$noWriteData = "DOCTYPE html";
	if (strpos($jsondata,$noWriteData) !== false) { } else {
		$fp = fopen('NYC.txt', 'w');
		fwrite($fp, $jsondata);
		fclose($fp);
		}


	// Chicago Divvy JSON
	$chiURL = 'http://www.divvybikes.com/stations/json';
	$chiBody = 'format=text';
	$chiCh = curl_init ($chiURL);
	curl_setopt ($chiCh, CURLOPT_POST, true);
	curl_setopt ($chiCh, CURLOPT_POSTFIELDS, $chiBody);
	curl_setopt ($chiCh, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($chiCh);
	curl_close($chiCh);

	$jsondata = $json;
	$noWriteData = "DOCTYPE html";
	if (strpos($jsondata,$noWriteData) !== false) { } else {
		$fp = fopen('CHI.txt', 'w');
		fwrite($fp, $jsondata);
		fclose($fp);
		}

	// Bay Area JSON
	$sfoURL = 'http://www.bayareabikeshare.com/stations/json';
	$sfoBody = 'format=text';
	$sfoCh = curl_init ($sfoURL);
	curl_setopt ($sfoCh, CURLOPT_POST, true);
	curl_setopt ($sfoCh, CURLOPT_POSTFIELDS, $sfoBody);
	curl_setopt ($sfoCh, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($sfoCh);
	curl_close($sfoCh);

	$jsondata = $json;
	$noWriteData = "DOCTYPE html";
	if (strpos($jsondata,$noWriteData) !== false) { } else {
		$fp = fopen('SFO.txt', 'w');
		fwrite($fp, $jsondata);
		fclose($fp);
		}

	// Columbus JSON
	$cmhURL = 'http://www.cogobikeshare.com/stations/json';
	$cmhBody = 'format=text';
	$cmhCh = curl_init ($cmhURL);
	curl_setopt ($cmhCh, CURLOPT_POST, true);
	curl_setopt ($cmhCh, CURLOPT_POSTFIELDS, $cmhBody);
	curl_setopt ($cmhCh, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec ($cmhCh);
	curl_close($cmhCh);

	$jsondata = $json;
	$noWriteData = "DOCTYPE html";
	if (strpos($jsondata,$noWriteData) !== false) { } else {
		$fp = fopen('CMH.txt', 'w');
		fwrite($fp, $jsondata);
		fclose($fp);
		}
?>
