
<?php
include 		'../session.php';
include_once 	'../class/clslogs.php';

// if(isset($_POST['idreceiptdetails']) || isset($_POST['itemcode'])) {
if(isset($_POST['idreceiptdetails'])) {
	if (isset($_POST['idreceiptdetails'])) 	{
		$id = $_POST['idreceiptdetails'];
		$sql = "
		select a.iditemindex, c.idreceipts, idreceiptsdetails, receivedate, c.rrnumber, b.itemcode, itemname,
		brandname, model, serialnos, specifications, depyear, b.unit, quantity, a.unitcost
		from tbl_receiptsdetails a
		inner join tbl_itemindex b on b.iditemindex = a.iditemindex
		inner join tbl_receipts c on c.idreceipts = a.idreceipts
		where a.active = 0 and idreceiptsdetails = '$id' ";
	}
	$query = $db->query($sql)->fetch_assoc();
	$_SESSION['serialnos'] = $query['serialnos'];
	echo json_encode($query);
}

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "
	select idempreceiptdetails, c.itemcode, itemname, brandname, ifnull(specifications, specs) specifications, model, a.serialnos, a.quantity, a.unitcost amount, mrstatus, unitstatus,
	if((if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now())) <=0, 0, (if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now()))) *
	(((a.unitcost * a.quantity)/if(depyear is null,1,depyear))/12) depreciation,
	depyear, a.idreceipts, ifnull(dateaquired, datereceived) dateaquired, b.rrnumber, a.idreceiptsdetails, a.active
	from tbl_empreceiptdetails a
	inner join tbl_empreceipts mr on mr.idempreceipts = a.idempreceipts
	left join tbl_itemindex c on c.iditemindex = a.iditemindex
	left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
	where a.active=0 and a.idempreceiptdetails = '$id';";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add']) || isset($_POST['add_select'])) {
	if(isset($_POST['add'])) {
		$idempreceipts 			= $_POST['idempreceipts'];
		$mrnumber 				= $_POST['mrnumber'];

		$iditemindex 			= $_POST['iditemindex'];
		$quantity 				= $_POST['quantity'];
		$unitcost 				= $_POST['unitcost'];
		$dateaquired 			= $_POST['dateaquired'];
		$serialnos 				= $_POST['serialnos'];
		$idreceipts 			= $_POST['idreceipts'];
		$idreceiptsdetails 		= $_POST['idreceiptsdetails'];

		$itemname 				= $_POST['itemname'];
		$balqty 				= $_POST['balqty'];
	} else {
		$idempreceipts 			= $_POST['sidempreceipts'];
		$mrnumber 				= $_POST['smrnumber'];

		$iditemindex 			= $_POST['siditemindex'];
		$quantity 				= $_POST['squantity'];
		$unitcost 				= $_POST['sunitcost'];
		$dateaquired 			= $_POST['sdateaquired'];
		$serialnos 				= $_POST['sserialnos'];
		$idreceipts 			= $_POST['sidreceipts'];
		$idreceiptsdetails 		= $_POST['sidreceiptsdetails'];

		$itemname 				= $_POST['sitemname'];
		$balqty 				= $_POST['sbalqty'];
	}

	if(intval($quantity) >0 ){
		$iduser 				= $_SESSION['iduser'];
		$sql = "insert into tbl_empreceiptdetails (iditemindex, quantity, unitcost, unitstatus, mrstatus, dateaquired, serialnos, iduser, idempreceipts, mrnumber, idreceipts, idreceiptsdetails)
		values ('$iditemindex','$quantity','$unitcost','Functional','0','$dateaquired','$serialnos','$iduser','$idempreceipts','$mrnumber','$idreceipts','$idreceiptsdetails')";

		if($balqty >= $quantity) {
			if($db->query($sql)){
				$_SESSION['success'] = "MR Item successfully saved!";
				// $record->recordlogs($_SESSION['iduser'],"MR-Details", 'Add Item Name '.$itemname.' - Qty '.$quantity.' on MR No. '.$mrnumber);
			} else {
				$_SESSION['error'] = $db->error;
			}
		} else {
			$_SESSION['error'] = "Error Entry...";
		}
	} else {
		$_SESSION['error'] = "Error Entry...";
	}

	include '../serverside/ss_itemindex_helper.php'; // refresh quantity

	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['edit'])){
	$idempreceipts			= $_POST['eidempreceipts'];
	$mrnumber				= $_POST['emrnumber'];

	$id 					= $_POST['eidempreceiptdetails'];
	$quantity 				= $_POST['equantity'];
	$serialnos 				= $_POST['eserialnos'];
	$unitcost 				= $_POST['eamount'];
	$iduser 				= $_SESSION['iduser'];

	$balqty 				= $_POST['ebalqty'];
	$unitstatus 			= $_POST['eunitstatus'];

	$itemcode 				= $_POST['eitemcode'];

	$sql = "";
	if(empty($_POST['eempnoto'])) {
		$sql = "update tbl_empreceiptdetails set quantity='$quantity',serialnos='$serialnos',unitcost='$unitcost',unitstatus='$unitstatus'
		where idempreceiptdetails = '$id' ";
		if($db->query($sql)){
			$_SESSION['success'] = "MR Item successfully updated!";
			$record->recordlogs($_SESSION['iduser'],"MR-Details", 'Edit Item Name '.$_POST['eitemname'].' - Qty '.$quantity.' on MR No. '.$mrnumber);
		} else {
			$_SESSION['error'] = $db->error;
		}
	} else {

		$empnoto 			= $_POST['eempnoto'];
		$empnameto 			= $_POST['eempnameto'];
		$datetransferred 	= $_POST['edatetransferred'];

		$empnofrom 			= $_POST['eempnofrom'];
		$empnamefrom 		= $_POST['eempnamefrom'];
		$newmrnumber 		= $_POST['newmrnumber'];

		$mrfrom 			= $_POST['eempnofrom'] . '|' . $_POST['eempnamefrom'];
		$mrto 				= $_POST['eempnoto'] . '|' . $_POST['eempnameto'];

		$remarks 			= "Transferred MR from: ".$empnamefrom;

		$sql = "
		INSERT INTO tbl_empreceipts (empno, datereceived, mrnumber, remarks, iduser) VALUES ('$empnoto', '$datetransferred', '$newmrnumber', '$remarks', '$iduser')";
		$db->query($sql);
		$idempreceipts = mysqli_insert_id($db);

		$sql="
		INSERT INTO 	tbl_transferredmr (iditemindex, quantity, unitcost, unitstatus, mrstatus, dateaquired, datetransferred, serialnos, remarks,
		iduser, mrfrom, mrto, idempreceipts, idreceipts, idreceiptsdetails)
		SELECT 			iditemindex, quantity, unitcost, unitstatus, '2', dateaquired, '$datetransferred', serialnos, '$remarks',
		'$iduser', '$mrfrom', '$mrto', '$idempreceipts', idreceipts, idreceiptsdetails
		FROM 			tbl_empreceiptdetails
		WHERE   		idempreceiptdetails='$id';

		INSERT INTO 	tbl_empreceiptdetails (iditemindex, itemcode, quantity, unitcost, unitstatus, mrstatus, dateaquired, datetransferred, serialnos, remarks,
		iduser, mrfrom, mrto, idempreceipts, idreceipts, idreceiptsdetails)
		SELECT 			iditemindex, itemcode, quantity, unitcost, unitstatus, '2', dateaquired, '$datetransferred', serialnos, '$remarks',
		'$iduser', '$mrfrom', '$mrto', '$idempreceipts', idreceipts, idreceiptsdetails
		FROM 			tbl_empreceiptdetails
		WHERE   		idempreceiptdetails='$id';

		UPDATE 			tbl_empreceiptdetails
		SET 			unitstatus = 'Transferred to: $empnameto', mrstatus='2', remarks='Transfer to: $empnameto', datetransferred='$datetransferred', mrfrom='$mrfrom', mrto='$mrto', active = 2
		WHERE 			idempreceiptdetails='$id';";
		if($db->multi_query($sql)){
			$_SESSION['success'] = $msg_edit;
		} else {
			$_SESSION['error'] = $db->error;
		}
	}

	include '../serverside/ss_itemindex_helper.php';
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id = $_POST['id'];

	if($db->query("update tbl_empreceiptdetails set active = 1 where idempreceiptdetails = '$id'")){
		$_SESSION['success'] = "MR Item successfully updated!";
		$record->recordlogs($_SESSION['iduser'],"MR-Details", 'Delete . ['.$_POST['ditemname2'].']');
	}
	else{
		$_SESSION['error'] = $db->error;
	}

	include '../serverside/ss_itemindex_helper.php';
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

