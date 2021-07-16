
<?php
include '../session.php';
include '../class/clslogs.php';


if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select returneddate, rs.returnedno, rsa.*, itemname, brandname, model, specifications, model, ii.unit, format(rsa.unitcost,2) unitcost, format((rsa.quantity * rsa.unitcost),2) amount, remarks
	from tbl_returntosupplierdetails rsa
	inner join tbl_returntosupplier rs on rs.returnedno = rsa.returnedno
	inner join tbl_itemindex ii on ii.itemcode = rsa.itemcode
	inner join tbl_receiptsdetails rd on rd.rrnumber = rsa.linkrefno
	where idreturnsdetails= '$id'
	order by itemname
	limit 1";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();
	echo json_encode($row);
}

if(isset($_POST['add'])){
	$iduser 				= $_SESSION['iduser'];

	$returnedno 	= $_POST['returnedno'];
	$itemcode 		= $_POST['itemcode'];
	$quantity 		= $_POST['quantity'];
	$linkrefno		= $_POST['linkrefno'];
	$serialnos 		= $_POST['serialnos'];
	$remarks			= $_POST['remarks'];
	$remarks 			= $db->escape_string($remarks);
	$unitcost			= $_POST['unitcost'];

	$linkid				= $_POST['linkid'];

	$sql = "insert into tbl_returntosupplierdetails(returnedno, itemcode, quantity, iduser, linkrefno, serialnos, remarks, unitcost, linkid)
	values ('$returnedno','$itemcode','$quantity','$iduser','$linkrefno','$serialnos','$remarks','$unitcost','$linkid')";
	if($db->query($sql)){
		$return['saved'] = 'Receipt details information added successfully ';
		include '../serverside/ss_itemindex_helper.php';
	} else {
		$return['saved'] = $db->error;
	}
	echo json_encode($return);
}

if(isset($_POST['edit'])){
	$id 				= $_POST['edit'];
	$quantity 	= $_POST['quantity'];
	$serialnos	= $_POST['serialnos'];
	$remarks		= utf8_encode($_POST['remarks']);
	$remarks 		= $db->escape_string($remarks);
	$iduser 		= $_SESSION['iduser'];

	$sql = "update tbl_returntosupplierdetails set serialnos='$serialnos', quantity='$quantity', remarks='$remarks' where idreturnsdetails='$id'";
	if($db->query($sql)){
		$return['saved'] = 'Receipt details information modified successfully';
		include '../serverside/ss_itemindex_helper.php';
	} else {
		$return['saved'] = $db->error;
	}
	echo json_encode($return);
	// header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id  = $_POST['delete'];
	$sql = "update tbl_returntosupplierdetails set active=1 where idreturnsdetails = '$id'";
	if($db->query($sql)){
		$return['saved'] = 'Return details information deleted successfully';
	} else {
		$return['saved'] = $db->error;
	}
	echo json_encode($return);
}

?>