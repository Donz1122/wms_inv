<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){	
	header('location: login.php');		
} else {
	/*
	$sql = "select * from tbl_user where iduser = '".$_SESSION['iduser']."' limit 1";	
	$query = $db->query($sql);
	$user = $query->fetch_assoc();
	*/
}

?>