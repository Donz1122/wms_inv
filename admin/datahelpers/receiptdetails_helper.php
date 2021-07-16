
<?php
include '../session.php';
include '../class/clsitems.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select a.*, itemname, b.unit, (quantity * unitcost) amount, dummyqty
	from tbl_receiptsdetails a
	inner join tbl_itemindex b on b.iditemindex = a.iditemindex
	where idreceiptsdetails= '$id'
	order by itemname
	limit 1";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();

	unset($_SESSION['itemname']);
	$_SESSION['itemname'] = $row['itemname'];

	echo json_encode($row);
}

if(isset($_POST['selectitemid'])) {
	$iditemindex = $_POST['selectitemid'];
	$sql = "select a.* from tbl_itemindex a
	where iditemindex = '$iditemindex'
	order by itemname
	limit 1";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$rrno 			= $_POST['rrno'];
	$idreceipts 	= $_POST['idreceipts'];
	$transactiondate= $enddate;

	$iditemindex 	= $_POST['iditemindex'];
	$itemcode 		= $_POST['itemcode'];
	$itemname 		= $_POST['itemname'];
	$quantity 		= $_POST['quantity'];
	$unitcost 		= $_POST['unitcost'];

	$iduser 		= $_SESSION['iduser'];

	$brandname 		= $_POST['brandname'];
	$specs 			= $_POST['specs'];
	$model 			= $_POST['model'];
	$serialnos		= $_POST['serialnos'];

	$iswithwarranty = $_POST['iswithwarranty'];
	$warranty		= $_POST['warranty'];


	$sql = "insert into tbl_receiptsdetails(rrnumber, itemcode, quantity, unitcost, iduser, iditemindex, idreceipts, brandname, specifications, model, serialnos, transactiondate)
	values ('$rrno','$itemcode','$quantity','$unitcost','$iduser','$iditemindex','$idreceipts','$brandname','$specs','$model','$serialnos','$transactiondate')";

	if ($iswithwarranty == 1) {
		$sql = "insert into tbl_receiptsdetails(rrnumber, itemcode, quantity, unitcost, iduser, iditemindex, idreceipts, brandname, specifications, model, serialnos, transactiondate, warranty)
		values ('$rrno','$itemcode','$quantity','$unitcost','$iduser','$iditemindex','$idreceipts','$brandname','$specs','$model', '$serialnos','$transactiondate','$warranty')";
	}

	if($db->query($sql)){
		$last_insertid = mysqli_insert_id($db);
		if($itemcode == 'DMOG0001') {
			$db->query("update tbl_receiptsdetails set particulars = '$itemname' where idreceiptsdetails = '$last_insertid' ");
		}
		// $record->recordlogs($_SESSION['iduser'], "Receipt Details", "Add RR ".$rrno." Item Name ".$itemname." Qty ".$quantity." ");
		$return['saved'] = 'Saved';
	} else {
		$return['saved'] = $db->error;
	}
	//header("location: ../receiptdetails.php?id=".$rrno);
	// header('location: ' . $_SERVER['HTTP_REFERER']);

	echo json_encode($return);
}

if(isset($_POST['edit'])){
	$id 			= $_POST['rdidreceiptsdetails'];

	$rrno 			= $_POST['rderrno'];
	$quantity 		= $_POST['eqty'];
	$unitcost 		= $_POST['eunitcost'];
	$itemname 		= $_POST['eitemname'];
	$brandname 		= $_POST['ebrandname'];
	$specs 			= $_POST['especs'];
	$model 			= $_POST['emodel'];
	$serialnos		= $_POST['eserials'];

	$iswithwarranty = $_POST['eiswithwarrantyz'];
	$warranty		= $_POST['ewarranty'];

	$sql = "
	update tbl_receiptsdetails set quantity='$quantity', unitcost='$unitcost', brandname='$brandname', specifications='$specs', model='$model', serialnos='$serialnos'
	where idreceiptsdetails='$id'";

	if ($iswithwarranty == 1) {
		$sql = "
		update tbl_receiptsdetails set quantity='$quantity', unitcost='$unitcost', brandname='$brandname', specifications='$specs', model='$model', serialnos='$serialnos',
		warranty='$warranty'
		where idreceiptsdetails='$id'";
	}

	if($db->query($sql)){
		$_SESSION['success'] = 'Receipt details information modified successfully';
		$record->recordlogs($_SESSION['iduser'], "Receipt Details", "Modify RR ".$rrno." Item Name ".$itemname." Qty ".$quantity." ");
		$aitemname 		= $_POST['aitemname'];
		if(!empty($aitemname)) {
			$db->query("update tbl_allpurposeitem set itemname = '$aitemname' where idreceiptsdetails = '$id' ");
		}
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id = $_POST['delete'];
	// $sql = "update tbl_receiptsdetails set active=1 where idreceiptsdetails = '$id'";
	$sql = "delete from tbl_receiptsdetails where idreceiptsdetails = '$id'";
	if($db->query($sql)){
		$return['ok'] = 'Receipt details information deleted successfully';
		//$record->recordlogs($_SESSION['iduser'], "Receipt Details", "Modify RR [".$rrno."] Item Name [".$itemname."] Qty [".$quantity."] By: [".$_SESSION['user']."]");
	} else {
		$return['ok'] = $db->error;
	}
	echo json_encode($return);
}

?>