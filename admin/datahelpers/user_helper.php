<?php
include '../session.php';

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "select * from tbl_user where iduser = '$id'";
	$query = $db->query($sql)->fetch_assoc();

	$return['iduser'] 		= $query['iduser'];
	$return['username'] 	= $query['username'];
	$return['fullname'] 	= utf8_encode($query['fullname']);
	$return['area'] 		= $query['area'];
	$return['position'] 	= $query['position'];
	$return['usertype'] 	= $query['usertype'];
	$return['doccode'] 		= $query['doccode'];
	$return['restriction'] 	= $query['restriction'];
	$return['empno'] 		= $query['empno'];
	$return['img']			= $query['img'];
	$_SESSION['dummy_iduser']= $query['iduser'];

	$is_encoded = preg_match('~%[0-9A-F]{2}~i', $query['password']);
	if($is_encoded) {
		$return['password'] = decryptStr($query['password']);
	}
	else {
		$return['password'] = $query['password'];
	}
	echo json_encode($return);
}

if(isset($_POST['add']) || isset($_POST['edit'])) {

	$username 		= $_POST['username'];
	$password 		= encryptStr($_POST['password']);
	$fullname 		= $_POST['fullname'];
	$position 		= utf8_encode(str_replace("'", "''", $_POST['position']));
	$restriction 	= $_POST['restriction'];
	$doccode 		= $_POST['doccode'];
	$usertype 		= $_POST['usertype'];
	$area 			= utf8_encode(str_replace("'", "''", $_POST['area']));
	$empno 			= $_POST['empno'];
	$img 			= trim($_POST['img']);

	if(!empty($_POST['add'])) {
		$sql = "INSERT INTO tbl_user (username,password,fullname,area,position,usertype,doccode,restriction,empno,img)
		VALUES ('$username','$password','$fullname','$area','$position','$usertype','$doccode','$restriction','$empno','$img');";
	}
	if(!empty($_POST['edit'])) {
		$id = $_POST['iduser'];
		$sql = "UPDATE tbl_user
		SET username='$username',password='$password',fullname='$fullname',area='$area',position='$position',usertype='$usertype',doccode='$doccode',
			restriction='$restriction',empno='$empno',img='$img'
		WHERE iduser = '$id'";
	}

	if($db->query($sql)){
		$_SESSION['success'] = "Record successfully saved";
	} else {
		$_SESSION['error'] = $db->error;
	}
	// $return['ok'] = $sql;
	echo $sql;
	//echo json_encode($return);
}

if(isset($_POST['delete'])) {
	$id = $_POST['d_iduser'];
	$sql = "delete from tbl_user where iduser = '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = $msg_delete;
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

?>