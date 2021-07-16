
<?php
	include '../session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "select * from tbl_supplier where idsupplier= '$id'";
		$query = $db->query($sql)->fetch_assoc();		
		echo json_encode($query);
	}

	if(isset($_POST['add'])){
		$suppliercode = $_POST['suppliercode'];
		$suppliername = $_POST['suppliername'];
		$address = $_POST['address'];
		$contactno = $_POST['contactno'];
		$vatable = $_POST['vatable'];
		$warehouse = $_POST['warehouse'];
		
		$sql = "insert into tbl_supplier(suppliercode, suppliername, address, contactno, vatable, warehouse) values ('$suppliercode', '$suppliername', '$address', '$contactno', '$vatable', '$warehouse')";
		if($db->query($sql)){
			$_SESSION['success'] = 'Supplier information added successfully';
		} else {
			$_SESSION['error'] = $db->error;
		}
		header('location: suppliers.php');
	}

	if(isset($_POST['edit'])){
		$id = $_POST['edit_idsupplier'];
		$suppliercode = $_POST['edit_suppliercode'];
		$suppliername = $_POST['edit_suppliername'];
		$address = $_POST['edit_address'];
		$contactno = $_POST['edit_contactno'];
		$vatable = $_POST['edit_vatable'];
		$warehouse = $_POST['edit_warehouse'];
		
		$sql = "update tbl_supplier set suppliername='$suppliername', address='$address', contactno='$contactno', vatable='$vatable', warehouse='$warehouse' where idsupplier='$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Supplier information modified successfully';
		} else {
			$_SESSION['error'] = $db->error;
		}
		header('location: suppliers.php');
	}

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "update tbl_supplier set active=1 where idsupplier= '$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Supplier information deleted successfully';
		} else {
			$_SESSION['error'] = $db->error;
		}
		header('location: suppliers.php');	
	}	

?>