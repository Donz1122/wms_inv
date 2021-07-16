<?php
include 'session.php';
include 'class/clslogs.php';
$record->recordlogs($_SESSION['iduser'],"Logout", "Logout [".$_SESSION['user']."]");
session_start();
session_destroy();
header('location: login.php');
?>