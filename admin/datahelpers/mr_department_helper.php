<?php
include 		'../session.php';
include_once 	'../class/clslogs.php';

if(isset($_POST['id'])){
	$id 						= $_POST['id'];
	$sql 						= 
	"SELECT a.idempreceipts, datereceived, deptcode, deptname, a.mrnumber, amount, remarks
	FROM tbl_empreceipts a
	INNER JOIN tbl_department b on b.deptcode = a.empno
	LEFT OUTER JOIN (SELECT idempreceipts, sum(amount) amount FROM zanecoinvphp.tbl_empreceiptdetails GROUP BY idempreceipts) c ON c.idempreceipts = a.idempreceipts
	WHERE a.active = 0 AND a.idempreceipts = '$id'";
	$query 						= $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$empno 						= $_POST['deptcode'];
	$datereceived 				= $_POST['mrdate'];
	$mrnumber 					= $_POST['mrnumber'];
	$remarks 					= utf8_encode($_POST['remarks']);	
	$iduser 					= $_SESSION['iduser'];
	$sql 						= "INSERT INTO tbl_empreceipts (empno, datereceived, mrnumber, remarks, iduser) VALUES ('$empno', '$datereceived', '$mrnumber', '$remarks', '$iduser')";
	if($db->query($sql)){
		$_SESSION['success'] 	= "MR Department information successfully saved!";
		$record->recordlogs($_SESSION['iduser'],"MR-Department", 'Add MR No. ['.$mrnumber.'] remarks ['.$remarks.']');
	} else {
		$_SESSION['error'] 		= $db->error;
	}		
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['edit'])){
	$id 						= $_POST['eidempreceipts'];
	$iduser 					= $_SESSION['iduser'];	
	$deptname					= $_POST['edeptname2'];
	$datereceived 				= $_POST['emrdate'];
	$remarks 					= utf8_encode($_POST['eremarks']);	
	
	$sql = "update tbl_empreceipts set remarks='$remarks' where idempreceipts='$id'";
	if($db->query($sql)){
		$_SESSION['success'] 	= "Department information successfully updated!";
		$record->recordlogs($_SESSION['iduser'],"MR-Department", 'Modify MR id. ['.$id.'] remarks ['.$remarks.']');
	} else {
		$_SESSION['error'] 		= $db->error;
	}

	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id = $_POST['dempreceipts'];
	$mystr = "update tbl_empreceipts set active = 1 where idempreceipts = '$id'";
	if($db->query($mystr)){
		$_SESSION['success'] = "Department information successfully removed!";
		$record->recordlogs($_SESSION['iduser'],"MR-Department", 'Removed MR id. ['.$id.']');
		$db->query("update tbl_empreceiptdetails set active = 1 where idempreceipts = '$id'");
	}
	else{
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}
