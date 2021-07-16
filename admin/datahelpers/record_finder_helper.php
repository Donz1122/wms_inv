<?php
include '../session.php';

if(isset($_POST['empno'])) {
	$empno = $_POST['empno'];
	$sql = "
	select empnumber, concat(lastname,', ',firstname,' ',middleinitial) empname, replace(title,'Ñ','ñ') title from zanecopayroll.employee 
	where concat(lastname,', ',firstname,' ',middleinitial) is not null 
	and empnumber = '$empno'
	order by empname
	limit 1";
	$query = $db->query($sql)->fetch_assoc();

	$return['empno'] = $query['empnumber'];	
	$return['empname'] = utf8_decode($query['empname']);
	$return['title'] = $query['title'];
	
	echo json_encode($return);
}

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "update tbl_itemindex set reorderpopup = 1 where iditemindex = '$id' ";
	$db->query($sql)->fetch_assoc();	
	if($db->query($sql)){
		// $_SESSION['success'] = 'Item is now hidden...';	
	} else {
		// $_SESSION['error'] = $db->error;
	}
}

?>