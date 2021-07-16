
<?php
include '../session.php';
include '../class/clssalvage.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id 	= $_POST['id'];
	$sql 	= "select a.*, itemname, b.unit, (quantity * unitcost) amount
	from tbl_salvagedetails a
	inner join tbl_itemindex b on b.iditemindex = a.iditemindex
	where idsalvagedetails= '$id'
	order by itemname
	limit 1;";
	$query 	= $db->query($sql);
	$row 	= $query->fetch_assoc();
	echo json_encode($row);
}

if(isset($_POST['selectitemid'])) {
	$id 	= $_POST['selectitemid'];
	$sql 	= "
	select idempreceiptdetails, a.mrnumber, c.iditemindex, ifnull(dateaquired, datereceived) dateaquired, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, c.unit, a.unitcost, (a.unitcost * a.quantity) amount,
	if((ifnull(depyear,1)*12) - (timestampdiff(month, ifnull(dateaquired, datereceived), now())) <= 0, 0, (ifnull(depyear,1)*12) - (timestampdiff(month, ifnull(dateaquired, datereceived), now()))) * (ifnull((a.unitcost * a.quantity)/ifnull(depyear,1),0)/12) depreciation
	from tbl_empreceiptdetails a
	left join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
	left join tbl_itemindex c on c.iditemindex = a.iditemindex
	left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
	where a.active = 0 and idempreceiptdetails = '$id'
	order by itemname";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	$idsalvage 				= $_POST['aidsalvage'];
	$salvageno 				= $_POST['salvageno'];
	$quantity 				= $_POST['quantity'];
	$serialnos 				= $_POST['serialnos'];
	$remarks 				= $_POST['remarks'];
	$mrno 					= $_POST['mrno'];
	$idempreceiptdetails 	= $_POST['idempreceiptdetails'];
	$iduser 				= $_SESSION['iduser'];
	// $details 				= $db->query("select * from tbl_empreceiptdetails where idempreceiptdetails = '$refmr_iddetails'")->fetch_assoc();
	// $details 				= json_encode($details);
	$sql 					=
	"INSERT INTO tbl_salvagedetails (idsalvage,salvageno,quantity,serialnos,remarks,mrno,idempreceiptdetails,iduser)
	VALUES ('$idsalvage','$salvageno','$quantity','$serialnos','$remarks','$mrno','$idempreceiptdetails','$iduser')";
	if($db->query($sql)){
		$_SESSION['success'] = $msg_save;		
		$db->query("update tbl_empreceiptdetails set unitstatus = 'Salvage', active = 2  where idempreceiptdetails = '$idempreceiptdetails'");

		// $record->recordlogs($_SESSION['iduser'], "Salvage Details", "Entry of Item Name ".$itemname);
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['edit'])){
	$id 		= $_POST['eidsalvagedetails'];
	$sqlquery 	= "select * from tbl_salvagedetails where idsalvagedetails = '$id' and iduser = '".$_SESSION['iduser']."'";
	$query = $db->query($sqlquery);
	if(mysqli_num_rows($query) <= 0){
		$_SESSION['error'] = "User are not allowed to modify this item! ";
	} else {
		$iditemindex 	= $_POST['eiditemindex'];
		$itemcode 		= $_POST['eitemcode'];
		$quantity 		= $_POST['eqty'];
		$unitcost 	= $_POST['eunitcost'];
		$iduser 		= $_SESSION['iduser'];
		$sql 			= "update tbl_salvagedetails set iditemindex='$iditemindex', itemcode='$itemcode', quantity='$quantity', unitcost='$unitcost' where idsalvagedetails='$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Salvage details information modified successfully';
			// $record->recordlogs($_SESSION['iduser'], "Salvage Details", "Edit Item Name ".$itemname);
		} else {
			$_SESSION['error'] = $db->error;
		}
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id = $_POST['didsalvagedetails'];
	$sqlquery = "select * from tbl_salvagedetails where idsalvagedetails = '$id' and iduser = '".$_SESSION['iduser']."'";
	$query = $db->query($sqlquery);
	if(mysqli_num_rows($query) <= 0){
		$_SESSION['error'] = "User are not allowed to modify this item!";
	} else {
		$query = $db->query($sqlquery)->fetch_assoc();
		$idempreceiptdetails = $query['linkrefno'];
		$sql = "update tbl_salvagedetails set active = 1 where idsalvagedetails = '$id'";
		if($db->query($sql)){
			//$sql = "update tbl_empreceiptdetails set unitstatus = 'Salvage' where idempreceiptdetails = '$idempreceiptdetails'";
			//mysqli_query($db, $sql);
			$_SESSION['success'] = 'Salvage details information deleted successfully';
		} else {
			$_SESSION['error'] = $db->error;
		}
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

?>