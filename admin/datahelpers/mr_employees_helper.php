<?php
include 		'../session.php';
include_once 	'../class/clslogs.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select a.idempreceipts, a.empno, concat(lastname,', ',firstname,' ',middleinitial) empname,
	a.mrnumber, amount, datereceived, remarks, status, transfers
	from tbl_empreceipts a
	inner join zanecopayroll.employee b on b.empnumber = a.empno
	left outer join (select idempreceipts, sum(quantity*unitcost) amount from tbl_empreceiptdetails group by idempreceipts) c on c.idempreceipts = a.idempreceipts
	where a.idempreceipts = '$id'";

	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if( isset($_POST['add']) || isset($_POST['add_open']) ) {
	$empno 				= $_POST['a_empno'];
	$empname			= $_POST['empname'];

	$datereceived = $_POST['a_datereceived'];
	$mrnumber 		= $_POST['a_mrnumber'];
	$remarks 			= utf8_encode($_POST['a_remarks']);
	$status 			= utf8_encode($_POST['a_status']);
	$iduser 			= $_SESSION['iduser'];
	$sql = "insert into tbl_empreceipts (empno, datereceived, mrnumber, remarks, iduser, active, empname)
	values ('$empno', '$datereceived', '$mrnumber', '$remarks', '$iduser','0','$empname')";
	if($db->query($sql)){
		$_SESSION['success'] 	= "MR Employees information successfully saved!".$sql;
		$record->recordlogs($_SESSION['iduser'],"MR-Employees", 'Add MR No. ['.$mrnumber.'] Remarks ['.$remarks.']');
	} else {
		$_SESSION['error'] 		= $db->error;
	}	
	if (isset($_POST['add'])) {
		header('location: ' . $_SERVER['HTTP_REFERER']);	
	} else {
		header('location: ../mr_employees_details.php?id='.$empno);	
	}
}

if(isset($_POST['edit'])){
	$id 			= $_POST['e_idempreceipts'];	
	$empno 			= $_POST['e_empno'];	
	$remarks 		= utf8_encode($_POST['e_remarks']);	
	$iduser 		= $_SESSION['iduser'];	

	$sql = "update tbl_empreceipts set empno='$empno', remarks='$remarks' where idempreceipts='$id'";
	if($db->query($sql)){
		$_SESSION['success'] = $msg_edit;
		$record->recordlogs($_SESSION['iduser'],"MR-Employees", 'Edit MR No. ['.$mrnumber.'] Remarks ['.$remarks.']');
	} else {
		$_SESSION['error'] = $db->error;
	}	
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id 			= $_POST['dempreceipts'];	
	$mrnumber 		= $_POST['dmrnumber'];	
	$mystr 			= "update tbl_empreceipts set active = 1 where idempreceipts = '$id'";
	if($db->query($mystr)){
		$_SESSION['success'] = "Department information successfully removed!";
		$record->recordlogs($_SESSION['iduser'],"MR-Department", 'Delete MR No. ['.$mrnumber.']');
		$db->query("update tbl_empreceiptdetails set active = 1 where idempreceipts = '$id'");
	}
	else{
		$_SESSION['error'] = $db->error;
	}	
	header('location: ' . $_SERVER['HTTP_REFERER']);
}
