
<?php
include '../session.php';
include '../class/clsitems.php';
include '../class/clslogs.php';

if(isset($_POST['id'])){
	$id 		= $_POST['id'];
	//$supplier 	= $_POST['supplier'];

	$sql = "select a.*, c.suppliername, if(b.amount is null,0, b.amount) amount, fullname
	from tbl_receipts a
	left outer join tbl_user b on b.iduser = a.iduser
	left outer join tbl_supplier c on c.idsupplier = a.idsupplier
	left outer join (select rrnumber, sum(quantity * unitcost) amount from tbl_receiptsdetails group by rrnumber) b on b.rrnumber = a.rrnumber
	where idreceipts = '$id'";
	$query = $db->query($sql)->fetch_assoc();

	$return['iduser'] 		= $query['iduser'];
	$return['rrnumber'] 	= $query['rrnumber'];
	$return['ponumber'] 	= $query['ponumber'];
	$return['rvnumber'] 	= $query['rvnumber'];
	$return['drnumber'] 	= $query['drnumber'];
	$return['idsupplier'] 	= $query['idsupplier'];
	$return['sinumber'] 	= $query['sinumber'];
	$return['receivedate'] 	= $query['receivedate'];
	$return['mttnumber'] 	= $query['mttnumber'];
	$return['idpo'] 		= $query['idpo'];
	
	$return['fullname'] 	= utf8_decode(ucwords(trim($query['fullname'])));
	if(empty(json_encode($return))) {
		$return['fullname'] = $query['fullname'];
	}
	$return['suppliername'] = utf8_decode(ucwords(trim($query['suppliername'])));
	if(empty(json_encode($return))) {
		$return['suppliername'] = ($query['suppliername']);
	}
	echo json_encode($return);
}

if(isset($_POST['add']) || isset($_POST['add_open'])){
	$rrno 			= $_POST['rrno'];
	$idpo 			= $_POST['idpo'];
	$pono 			= $_POST['pono'];
	if (!empty($_POST['arvno'])) {
		$rvno      	= $_POST['arvno'];
	} else { 
		$rvno		= 0;
	}

	$suppliercode 	= $_POST['suppliercode'];
	$suppliername 	= $_POST['suppliername'];
	$address 		= $_POST['address'];
	$iduser 		= $_SESSION['iduser'];

	// locate supplier from PO; insert to warehouse if not found
	if(empty($suppliercode)) {
		$sql = "select idsupplier from tbl_supplier where suppliercode = '$suppliercode'";
	}
	else {
		$sql ="select idsupplier from tbl_supplier where suppliername = '$suppliername'";
	}
	$query = $db->query($sql);
	$idsupplier 	= '';
	if(mysqli_num_rows($query) > 0 ){
		$row = $query->fetch_assoc();
		$idsupplier = $row['idsupplier'];
	} else {
		$db->query("insert into tbl_supplier(suppliercode, suppliername, address) values ('$suppliercode', '$suppliername', '$address')");
		$idsupplier = mysqli_insert_id($db);
		$record->recordlogs($iduser,"Receipts", "Add Supplier ".$suppliername." from PO, address ".$address." ");
	}

	$drno 			= $_POST['drno'];
	$sino 			= $_POST['sino'];
	$receivedate 	= $_POST['receivedate'];

	$last_id 		= '';

	if (!empty($_POST['adocno'])) {
		$mttnumber	= $_POST['adocno']; 
	} else {
		$mttnumber	= 0;
	}
	$sql = "INSERT INTO tbl_receipts(rrnumber, ponumber, rvnumber, idsupplier, drnumber, sinumber, receivedate, iduser, idpo, mttnumber)
	VALUES ('$rrno','$pono','$rvno','$idsupplier','$drno','$sino','$receivedate','$iduser','$idpo','$mttnumber')";
	if($db->query($sql)){
		$last_id = $db->insert_id;
		$_SESSION['success'] = 'Receipts information added successfully';
		$record->recordlogs($iduser,"Receipts", "Entry of RR No.".$rrno);
	} else {
		$_SESSION['error'] = $db->error;
	}
	if(isset($_POST['add'])) {
		header('location: ' . $_SERVER['HTTP_REFERER']);
	} else {
		header('location: ../receiptdetails.php?id='.$last_id);		
	}
	die;
}

if(isset($_POST['edit'])){
	$id 			= $_POST['eidreceipts'];
	$rrno 			= $_POST['errno'];
	$pono 			= $_POST['epono'];
	$rvno 			= $_POST['ervno'];
	$drno 			= $_POST['edrno'];
	$sino 			= $_POST['esino'];
	$mttnumber		= $_POST['edocno'];
	$idpo 			= $_POST['eidpo'];
	$sql 			= "update tbl_receipts set rrnumber='$rrno', ponumber='$pono', rvnumber='$rvno', drnumber='$drno', sinumber='$sino', idpo='$idpo',mttnumber='$mttnumber'
	where idreceipts='$id'";
	if($db->query($sql)){
		$_SESSION['success'] = 'Receipts information modified successfully';
		$record->recordlogs($iduser,"Receipts", "Edit RR No.".$rrno);
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])){
	$id 			= $_POST['del_idreceipts'];
	$rrno 			= $_POST['del_rrno'];
	$sql 			= "update tbl_receipts set active=1 where idreceipts= '$id' and iduser = '".$_SESSION['iduser']."'";
	$sqlchild 		= "update tbl_receiptsdetails set active=1 where idreceipts= '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = 'Receipts information deleted successfully';
		$record->recordlogs($iduser,"Receipts", "Delete RR No.".$sqlquery['rrnumber']);
		$db->query($sqlchild);
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
	die;
}

if(isset($_POST['idpo'])){
	$id = $_POST['idpo'];
	$suppliername = $_POST['supplier'];
	$return = array();
	if (!empty($id)) {		
		$sql = "
		select p.idpo, p.pcode, p.podate,
		p.name suppliername,
		p.ponumber,
		r.rvnumber, p.address
		from zanecobudget.po p
		left join zanecobudget.podetail pd on pd.idpo = p.idpo
		left join zanecobudget.requisitiondetail rd on rd.idrequisitiondetail = pd.idrequisitiondetail
		left join zanecobudget.requisition r on r.idrequisition = rd.idrequisition
		where p.ponumber like '$id'
		group by p.ponumber;";
		$query = $db->query($sql)->fetch_assoc();

		$return['podate'] 			= $query['podate'];
		$return['ponumber'] 		= $query['ponumber'];
		$return['rvnumber'] 		= $query['rvnumber'];
		$return['pcode'] 			= $query['pcode'];
		$return['address'] 			= $query['address'];
		$return['suppliername'] 	= utf8_encode($query['suppliername']);
		if(empty(json_encode($return))) {
			$return['suppliername'] = $query['suppliername'];
		}
		$return['area']				= 'po';	
	} else {
		$sql = "
		select idsupplier, suppliercode, suppliername, address
		from tbl_supplier
		where suppliername like '$suppliername'
		group by suppliername ";
		$query = $db->query($sql)->fetch_assoc();

		$return['podate'] 			= $enddate;
		$return['ponumber'] 		= '';
		$return['rvnumber'] 		= '';
		$return['pcode'] 			= $query['suppliercode'];
		$return['address'] 			= $query['address'];
		$return['suppliername'] 	= utf8_encode($query['suppliername']);

		if(empty(json_encode($return))) {
			$return['suppliername'] = $query['suppliername'];
		}	
		$return['area']				= 'area';	
	}
	echo json_encode($return);
}

?>