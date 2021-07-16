<?php

include '../session.php';

if(isset($_POST['del'])) {
	$file = $_POST['filename'];
	if(file_exists($file)) { 
		unlink($file);				
		header('location: ' . $_SERVER['HTTP_REFERER']);   
		echo 1;
	} else {
		echo 0;
	}
	die;
}
die;


