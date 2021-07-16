
<?php
include '../session.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select idpoledetails, poleno, pd.itemcode, itemname, description, specs, qty, unit, unitcost
	from tbl_poledetails pd
	left join tbl_itemindex ii on ii.itemcode = pd.itemcode
	where idpoledetails = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$poleno 	= $_POST['poleno'];
	$itemcode	= $_POST['itemcode'];
	$description= $_POST['description'];
	$specs		= $_POST['specs'];
	$qty		= $_POST['qty'];
	$unitcost	= $_POST['unitcost'];

	if($db->query("insert into tbl_poledetails (poleno, itemcode, description, specs, qty, unitcost)
		values    ('$poleno', '$itemcode', '$description', '$specs', '$qty', '$unitcost')")){
		$return['saved'] 		= 'saved';
	} else {
		$return['saved'] 		= 'not saved';
	}
	echo json_encode($return);
}

if(isset($_POST['edit'])){
	$id 		 = $_POST['edit'];
	$description = $_POST['description'];
	$specs		 = $_POST['specs'];
	$qty		 = $_POST['qty'];
	$unitcost	 = $_POST['unitcost'];

	if($db->query("update tbl_poledetails set description='$description', specs='$specs', qty='$qty', unitcost='$unitcost' where idpoledetails='$id' ")) {
		$return['saved'] = 'updated';
	} else {
		$return['saved'] = 'not update';
	}
	echo json_encode($return);
}

if(isset($_POST['delete'])){
	$id 	= $_POST['delete'];
	if($db->query("delete from tbl_poledetails where idpoledetails = '$id' ")){
		$return['saved'] = 'deleted';
	} else {
		$return['saved'] = 'not deleted';
	}
	echo json_encode($return);
}


?>