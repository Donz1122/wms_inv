
<?php
include '../session.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select * from tbl_department where iddepartment = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$deptcode = $_POST['deptcode'];
	$deptname = utf8_encode($_POST['deptname']);
	$sql = "insert into tbl_department(deptcode, deptname) values ('$deptcode', '$deptname')";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'],"Department", "Add Department Code.[".$deptcode."] Department [".$deptname."]");
		$_SESSION['success'] = 'Supplier information successfully saved!';
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: suppliers.php');
}

if(isset($_POST['edit'])){
	$id = $_POST['iddepartment'];
	$odeptname = utf8_encode($_POST['odeptname']);
	$deptname = utf8_encode($_POST['edeptname']);
	$sql = "update tbl_department set deptname='$deptname' where iddepartment='$id'";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'],"Department", "Modify Department [".$odeptname."] to [".$deptname."]");
		$_SESSION['success'] = 'Department information successfully updated!';
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: suppliers.php');
}

if(isset($_POST['delete'])){
	$id = $_POST['id'];
	$deptname = utf8_encode($_POST['ddeptname']);
	$sql = "update tbl_department set active=1 where iddepartment= '$id'";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'],"Department", "Remove Department [".$deptname."]");
		$_SESSION['success'] = 'Department information successfully removed!';
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: suppliers.php');
}

?>