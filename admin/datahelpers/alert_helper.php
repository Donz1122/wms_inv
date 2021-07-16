<?php

include '../session.php';

if(isset($_POST['search'])) {
	$sql = $db->query("select * from tbl_alert where alerted = 0 ");
	$return['count'] = mysqli_num_rows($sql);

	$sql = $db->query("select * from tbl_alert where alerted = 0 order by requestid asc limit 1")->fetch_assoc();
	$return['index']	= $sql['idalert'];
	$return['id'] 		= $sql['requestid'];
	$return['remarks'] 	= $sql['remarks'];
	$return['by'] 		= $sql['requester'];
	$return['area'] 	= $sql['area'];
	echo json_encode($return);
}

if(isset($_POST['searchid'])) {
	$id = $_POST['searchid'];
	$sql = $db->query("select * from tbl_alert where alerted = 0 and userid = '$id' order by requestid asc limit 1")->fetch_assoc();
	echo json_encode($sql);
}

if(isset($_POST['remove'])) {
	$id = $_POST['remove'];
	if($db->query("delete from tbl_alert where idalert = '$id' ")) {
		$return['ok'] == "deleted";
	} else {
		$return['ok'] == "not deleted";
	}
	echo json_encode($return);
}

?>