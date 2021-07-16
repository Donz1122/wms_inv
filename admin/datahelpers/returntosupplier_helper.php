<?php
include '../session.php';

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "select * from tbl_returntosupplier where idreturns = '$id' limit 1";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add']) || isset($_POST['add_open'])) {
	$returneddate 	= $_POST['aReturnedDate'];
	$particulars 	= $_POST['aParticulars'];
	$returnedno 	= $_POST['aMCrTNo'];
	$empno 			= $_POST['aReturnedBy'];
	$suppliercode	= $_POST['suppliercode'];
	$iduser 		= $_SESSION['iduser'];

	$sql = "INSERT INTO tbl_returntosupplier (returneddate,particulars,returnedno,suppliercode,empno,iduser)
	VALUES ('$returneddate','$particulars','$returnedno','$suppliercode','$empno','$iduser');";
	if($db->query($sql)){
		$last_id = $db->insert_id;
		$_SESSION['success'] = $msg_save;		

		if(isset($_POST['add'])) {
			header('location: ' . $_SERVER['HTTP_REFERER']);
		} else {		
			header('location: ../returntosupplierdetails.php?id='.$last_id);					
		}
	} else {
		$_SESSION['error'] = $db->error;
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}	
}

if(isset($_POST['edit'])) {
	$id = $_POST['eidreturned'];
	
	$returneddate 	= $_POST['eReturnedDate'];
	$particulars 	= $_POST['eParticulars'];
	$empno 			= $_POST['eReturnedBy'];
	$suppliercode 	= $_POST['esuppliercode'];
	$iduser 		= $_SESSION['iduser'];

	$sql = "update tbl_returntosupplier set returneddate='$returneddate', particulars='$particulars', suppliercode='$suppliercode', empno='$empno' 
	where idreturns = '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = $msg_edit;		
	} else {
		$_SESSION['error'] = $db->error;
	}	
	header('location: ' . $_SERVER['HTTP_REFERER']);		
}

if(isset($_POST['delete'])) {
	$id = $_POST['didreturned'];
	$sql = "update tbl_returntosupplier set active=1 where idreturns = '$id'";
	if($db->query($sql)){		
		$_SESSION['success'] = $msg_delete;
	} else {
		$_SESSION['error'] = $db->error;
	}	
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

?>