<?php
include '../session.php';
if(isset($_POST['signs'])) {
	$sql = $db->query("select * from tbl_signatory")->fetch_assoc();	
	echo json_encode($sql);
}

if(isset($_POST['receiving'])) {
	$sql = $db->query("select * from tbl_signatory");
	if(mysqli_num_rows($sql) > 0){
		$sql = "update tbl_signatory set"	
	}

		
	if($db->query($sql)){
		$_SESSION['success'] = $msg_save;		
	} else {
		$_SESSION['error'] = $db->error;
	}
	echo '';
}
?>