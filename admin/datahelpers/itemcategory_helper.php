
<?php
	include '../session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "select * from tbl_itemcategory where iditemcategory = '$id'";
		$query = $db->query($sql)->fetch_assoc();		
		echo json_encode($query);
	}

	if(isset($_POST['add'])){
		$category= $_POST['category'];		
		$sql = "insert into tbl_itemcategory(category) values ('$category')";
		if($db->query($sql)){
			$_SESSION['success'] = 'Category successfully saved!';
		} else {
			$_SESSION['error'] = $db->error;
		}
		header('location: ../itemindexcategory.php');
	}

	if(isset($_POST['edit'])){
		$id = $_POST['eiditemcategory'];		
		$category = $_POST['ecategory'];		
		$sql = "update tbl_itemcategory set category='$category' where iditemcategory = '$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Category successfully modified!';
		} else {
			$_SESSION['error'] = $db->error;
		}
		
		header('location: ../itemindexcategory.php');
	}

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "delete from tbl_itemcategory where iditemcategory= '$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Category successfully deleted!';
		} else {
			$_SESSION['error'] = $db->error;
		}
		header('location: ../itemindexcategory.php');	
	}	

?>