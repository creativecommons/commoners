<?php
error_reporting(E_ALL ^ E_NOTICE);


if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$version = $_POST['v'];
	$server = $_POST['s'];
	$port = intval($_POST['p']);
	$directory = $_POST['d'];
	$lib = __DIR__.'/../phpCAS/CAS.php';

	if($fp = @fsockopen($server,$port,$errCode,$errStr,'4')){   
	  echo json_encode(1);
	} else {
	  echo json_encode(0);
	} 
	@fclose($fp);
} else {
	echo 'Ajax only.';
}

