<?php
include '../session.php';

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "select * from tbl_salvage where idsalvage = '$id'";
	$query = $db->query($sql)->fetch_assoc();

	echo json_encode($query);
}

if(isset($_POST['add'])) {
	$returneddate 		= $_POST['transactiondate'];
	$description 		= $_POST['description'];
	$salvageno 			= $_POST['salvageno'];
	$empno 				= $_POST['returnedby'];	
	$iduser 			= $_SESSION['iduser'];

	$sql = "INSERT INTO tbl_salvage (returneddate,description,salvageno,empno,iduser)
	VALUES ('$returneddate','$description','$salvageno','$empno','$iduser');";
	if($db->query($sql)){
		$_SESSION['success'] 	= $msg_save;		
	} else {
		$_SESSION['error'] 		= $db->error;
	}
	header('location: ../salvagematerials.php');
}

if(isset($_POST['edit'])) {
	$id 			= $_POST['eidsalvage'];	
	$description 	= $_POST['edescription'];
	$empno 			= $_POST['ereturnedby'];		
	$iduser 		= $_SESSION['iduser'];

	$sql = "UPDATE tbl_salvage 
	SET description='$description', empno='$empno'
	WHERE idsalvage = '$id'";
	if($db->query($sql)){
		$_SESSION['success'] 	= $msg_edit;		
	} else {
		$_SESSION['error'] 		= $db->error;
	}
	
	header('location: ../salvagematerials.php');
}

if(isset($_POST['delete'])) {
	$id 			= $_POST['didsalvage'];	
	$sql = "update tbl_salvage set active=1 where idsalvage = '$id'";
	if($db->query($sql)){		
		$_SESSION['success'] 	= $msg_delete;
	} else {
		$_SESSION['error'] 		= $db->error;
	}		

	header('location: ../salvagematerials.php');
}

?>