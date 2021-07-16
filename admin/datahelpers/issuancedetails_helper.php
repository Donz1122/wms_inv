<?php
include '../session.php';
include '../class/clsitems.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select a.*, itemname, b.category, b.unit, (quantity * unitcost) amount, issubmitted
	from tbl_issuancedetails a
	left join tbl_issuance i on i.idissuance = a.idissuance
	left join tbl_itemindex b on b.iditemindex = a.iditemindex
	where idissuancedetails = '$id' limit 1;";
	$query = $db->query($sql)->fetch_assoc();

	$return['idissuancedetails'] 	= $query['idissuancedetails'];
	$return['inumber'] 				= $query['inumber'];
	$return['itemcode'] 			= $query['itemcode'];
	$return['quantity'] 			= $query['quantity'];
	$return['unitcost'] 			= $query['unitcost'];
	$return['category'] 			= $query['category'];
	$return['iduser'] 				= $query['iduser'];
	$return['iditemindex'] 			= $query['iditemindex'];
	$return['idissuance'] 			= $query['idissuance'];
	$return['requestdate'] 			= $query['requestdate'];
	$return['approved'] 			= $query['approved'];
	$return['requestqty'] 			= $query['requestqty'];
	$return['itemname']				= utf8_decode($query['itemname']);
	$return['unit'] 				= ucfirst(strtolower($query['unit']));
	$return['amount'] 				= $query['amount'];
	$return['serialnos'] 			= $query['serialnos'];
	$return['issubmitted'] 			= $query['issubmitted'];

	$rows = $item->search_itemindex_summary($query['iditemindex']);
	$return['remainingqty'] 		= $rows['qty'];
	$return['average'] 				= $rows['average'];

	$return['tag_no'] 				= $query['tag_no'];
	$return['tag_remarks'] 			= $query['tag_remarks'];

	echo json_encode($return);
}

if(isset($_POST['selectitemid'])) {
	$iditemindex = $_POST['selectitemid'];
	$query = $item->search_itemindex_summary($iditemindex);
	echo json_encode($query);
}

if(isset($_POST['add'])){

	$iditemindex 	= $_POST['iditemindex'];
	$idissuance 	= $_POST['idissuance'];

	$inumber 		= $_POST['inumber'];
	$itemcode 		= $_POST['itemcode'];
	$requestqty 	= $_POST['quantity'];
	$unitcost 		= $_POST['unitcost'];
	$iduser 		= $_SESSION['iduser'];

	$tag_no			= $_POST['tag_no'];
	$tag_remarks	= $_POST['tag_remarks'];

	$sql = "INSERT INTO tbl_issuancedetails(inumber, itemcode, requestqty, unitcost, iduser, iditemindex, idissuance, requestdate, active, tag_no, tag_remarks)
	VALUES ('$inumber', '$itemcode', '$requestqty', '$unitcost', '$iduser', '$iditemindex', '$idissuance', '$date', '3', '$tag_no', '$tag_remarks')";
	if($db->query($sql)){
		$_SESSION['success'] = 'Issuance details information added successfully ';
		$record->recordlogs($_SESSION['iduser'],"Issuance Details", 'Add Item Name ['.$_POST['aitemname2'].'] Qty ['.$quantity.']');
	} else {
		$_SESSION['error'] = $db->error;
	}

	$return['ok'] = $sql;
	echo json_encode($return);
}

if(isset($_POST['edit'])){

	$id 			= $_POST['idissuancedetails'];
	$requestqty 	= $_POST['quantity'];
	$unitcost 		= $_POST['unitcost'];

	$tag_no			= $_POST['tag_no'];
	$tag_remarks	= $_POST['tag_remarks'];

	$sql = "UPDATE tbl_issuancedetails SET requestqty='$requestqty', unitcost='$unitcost', tag_no='$tag_no', tag_remarks='$tag_remarks' WHERE idissuancedetails = '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = 'Issuance details information updated successfully ';
		$record->recordlogs($_SESSION['iduser'],"Issuance Details", 'Modify Item Name ['.$itemname.'] Qty ['.$requestqty.']');
	} else {
		$_SESSION['error'] = $db->error;
	}

	$return['ok'] = $sql;
	echo json_encode($return);
}

if(isset($_POST['delete'])){
	$id 			= $_POST['d_idissuancedetails'];
	$inumber 		= $_POST['d_inumber'];
	$itemname 		= $_POST['ditemname'];
	$sql = "update tbl_issuancedetails set active=1 WHERE idissuancedetails= '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = 'Issuance details information deleted successfully';
		$record->recordlogs($_SESSION['iduser'],"Issuance Details", 'Delete Item Name ['.$itemname.']');
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['approved'])){ // change to edit serial nos.
	$return['ok'] 	= "";
	$id 			= $_POST['idissuancedetails'];
	$itemname 		= $_POST['itemname'];
	$quantity 		= $_POST['quantity'];
	$quantity2 		= $_POST['quantity2'];
	$serialnos 		= $_POST['serialnos'];
	$iduser 		= $_SESSION['iduser'];
	$sql 			= "select * from tbl_issuancedetails where idissuancedetails = '$id'";
	$query 			= $db->query($sql)->fetch_assoc();
	$approve 		= ", approved = 1"; // 1 is approved
	if(intval($quantity) >= intval($quantity2)) {
		if(intval($quantity2) <= 0) $approve = ", approved = 0";
		$sql = "update tbl_issuancedetails set quantity='$quantity2', serialnos='$serialnos' where idissuancedetails = '$id'";
		if($db->query($sql)){
			$return['ok'] = 1;
		} else {
			$return['ok'] = 0;
		}
	}
	echo json_encode($return);
}

if(isset($_POST['isdeclined'])){
	$return['ok'] 	= "";
	$idissuance		= $_POST['declinedid'];
	$iduser 		= $_SESSION['iduser'];
	$mrvid 			= $_POST['mrvid'];
	$sql 	= "update tbl_issuancedetails set approveddate='$date', approvedby='$iduser', approved=2 where idissuance = '$id'";
	$sql2 	= "update tbl_issuance set `status`=2, iscancelled=1 where idissuance = '$idissuance'";
	if($db->query($sql)){
		$return['ok'] = 1;
		$db->query($sql2);
		$_SESSION['success'] = 'Request cancelled!';
		$record->recordlogs($iduser,"Issuance Details", "Cancel MRV No. $mrvid");
	} else {
		$return['ok'] = 0;
		$_SESSION['error'] = $db->error;
	}
	echo json_encode($return);
}

if(isset($_POST['mrvid'])) {
	$id = $_POST['mrvid'];
	$sql = "
	select idissuancedetails, a.inumber, a.itemcode, itemname, ifnull(quantity,0) quantity, requestqty, b.unit, brandname,specifications,model,
	serialnos, approved, format(unitcost,2) unitcost, format((ifnull(quantity,0) * unitcost),2) amount,
	if(approved=0,'Pending',(if(approved=1,'Approved','Declined'))) status, b.iditemindex
	from tbl_issuancedetails a
	left join tbl_itemindex b on b.iditemindex = a.iditemindex
	where a.active <> 1 and a.idissuance = '$id'
	and approved = 1 limit 10 ";
	$query = $db->query($sql);
	$rows = mysqli_num_rows($query);
	$return['rows'] = $rows;
	echo json_encode($return);
}

/* to submit request */
if(isset($_POST['submitid'])) {
	$id 	 = $_POST['submitid'];
	$sql 	 = "update tbl_issuance set issubmitted = 1, status = 0 where idissuance like '$id' ";
	if($db->query($sql)){
		$return['ok'] = 1;
		$_SESSION['success'] = 'Request is submitted!';

		$remarks 	= $_POST['remarks'];
		$requester	= $_POST['by'];
		$area 		= $_SESSION['area'];

		$db->query("insert into tbl_alert (requestid, remarks, forms, requester, area) values ('$id','$remarks','issuance','$requester','$area')");

	} else {
		$return['ok'] = 0;
		$_SESSION['error'] = $db->error;
	}
	echo json_encode($return);
}

if(isset($_POST['isapproved'])) {
	$idissuance 		= $_POST['isapproved'];
	$query 				= $db->query("select * from tbl_issuance where idissuance = '$idissuance' and issubmitted = 1 limit 1");
	$row 				= $query->fetch_assoc();
	$return['rows']		= mysqli_num_rows($query);
	$return['approved'] = $row['status'];
	echo json_encode($return);
}
/* to approved transactions */
if(isset($_POST['approvedid'])){
	$id 			= $_POST['approvedid'];
	$mrvno 			= $_POST['mrvno'];
	$iduser 		= $_SESSION['iduser'];
	$area 			= $_SESSION["area"];
	$doc 			= substr($_POST['inumber'],0,3);
	$issuanceno		= "";

	if(strlen($_POST['inumber']) <= 3) {
		$sql 			=
		"select count(inumber)+1 as cnt
		from tbl_issuance where inumber like '$doc%' and date_format(idate,'%m') like date_format(now(),'%m')
		and date_format(idate,'%Y') like date_format(now(),'%Y')
		order by inumber asc
		limit 1;";
		$mctt 					= $db->query($sql)->fetch_assoc();
		$inumber				= $doc.$area.$mos.$year.'-'.$mctt['cnt'];
		$issuanceno				= "inumber='$inumber',";
	} else {
		$issuanceno				= "";
	}

	$sql 	= "update tbl_issuancedetails set $issuanceno quantity=requestqty, approveddate='$date', approvedby='$iduser',
	active=0, approved=1
	where idissuance = '$id'";
	if($db->query($sql)){
		$return['ok'] = 1;
		$db->query($sql2);
		$_SESSION['success'] = 'Request is approved!';

		$requesterid 	= $_POST['requesterid'];
		$approveby 		= $_SESSION['user'];
		$approveby 		= $db->escape_string($approveby);
		$db->multi_query("update tbl_issuance set $issuanceno `status` = 1 where idissuance = '$id';
						  insert into tbl_alert (requestid, remarks, forms, area, userid, approveby)
						  	values ('$id','Material request no.".$mrvno." is now approved','requester','$area','$requesterid','$approveby')");
	} else {
		$return['ok'] = 0;
		$_SESSION['error'] = $db->error;
	}
	echo json_encode($return);
}

/* to pending transactions */
if(isset($_POST['pendingid'])){
	$id 	= $_POST['pendingid'];
	$mrvno 	= $_POST['mrvno'];
	$area	= $_SESSION["area"];
	$sql 	= "update tbl_issuancedetails set quantity=0, approveddate='$date', approved=0, submitted=0 where idissuance = '$id'";
	if($db->query($sql)){
		$return['ok'] = 1;
		$requesterid 	= $_POST['requesterid'];
		$approveby 		= $_SESSION['user'];
		$approveby 		= $db->escape_string($approveby);
		$db->multi_query("update tbl_issuance set `status` = 0, issubmitted = 0 where idissuance = '$id';
						  insert into tbl_alert (requestid, remarks, forms, area, userid, approveby)
						  	values ('$id','Material request no.".$mrvno." is now back to pending','requester','$area','$requesterid','$approveby')");
		$_SESSION['success'] = 'Request is back to pending!';
		// $record->recordlogs($_SESSION['iduser'],"Issuance Details", "Pending MRV No. $mrvno.");
	} else {
		$return['ok'] = 0;
		$_SESSION['error'] = $db->error;
	}
	echo json_encode($return);
}
?>