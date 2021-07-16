<?php
include '../session.php';

if(isset($_POST['id'])) {
	$id = $_POST['id'];
	$sql = "select * from tbl_returns where idreturns = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}
if(isset($_POST['empno'])) {
	$empno = $_POST['empno'];
	$sql = "select id, docno, iditemindex, itemcode, itemname, brandname, specifications, model, serialnos, quantity, unit, amount
    from
    ((select idissuancedetails id, a.inumber docno, b.iditemindex, a.itemcode, itemname, brandname, specifications, model, serialnos, quantity, b.unit, format((quantity * unitcost),2) amount
    from tbl_issuancedetails a
    left join tbl_issuance i on i.idissuance = a.idissuance
    left join tbl_itemindex b on b.iditemindex = a.iditemindex
    where (a.active = 0 and i.active = 0) and empno = '$empno'
    and approved = 1 order by itemname)
    union all
    (select idempreceiptdetails id, e.mrnumber docno, c.iditemindex, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, c.unit, b.unitcost amount
    from tbl_empreceiptdetails a
    left join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
    left join tbl_itemindex c on c.iditemindex = a.iditemindex
    left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
    where empno = '$empno' and (a.active = 0 and e.active = 0)
    order by itemname)) as items
    order by itemname asc";
    $query = $db->query($sql);
    $totalData = mysqli_num_rows($query);
	echo json_encode($totalData);
}

if(isset($_POST['add']) || isset($_POST['add_open'])) {
	$returneddate 	= $_POST['aReturnedDate'];
	$particulars 		= utf8_encode($_POST['aParticulars']);
	$returnedno 		= $_POST['aMCrTNo'];
	$empno 				= $_POST['aReturnedBy'];
	// $refno 			= $_POST['aRefNo'];
	$iduser 			= $_SESSION['iduser'];

	$sql = "INSERT INTO tbl_returns (returneddate,particulars,returnedno,empno,iduser)
	VALUES ('$returneddate','$particulars','$returnedno','$empno','$iduser');";

	$from 					= $_POST['returnfrom'];
	if ( intval($from) == 1 ) {
		$suppliercode	= $_POST['aReturnedBy'];
		$sql = "INSERT INTO tbl_returns (returneddate,particulars,returnedno,suppliercode,iduser)
		VALUES ('$returneddate','$particulars','$returnedno','$suppliercode','$iduser');";
	}
	if($db->query($sql)){
		$last_id = $db->insert_id;
		$_SESSION['success'] = $msg_save;
		if(isset($_POST['add'])) {
			header('location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			header('location: ../returntowarehousedetails.php?id='.$last_id.'&no='.$returnedno);
		}
	} else {
		$_SESSION['error'] = $db->error;
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
}

if(isset($_POST['edit'])) {
	$id 			= $_POST['eidreturned'];

	$returneddate 	= $_POST['eReturnedDate'];
	$particulars 	= $_POST['eParticulars'];
	$empno 			= $_POST['aReturnedBy'];
	// $refno 			= $_POST['eRefNo'];
	$iduser 		= $_SESSION['iduser'];

	$sql = "update tbl_returns set returneddate='$returneddate',particulars='$particulars', empno='$empno' where idreturns = '$id'";
	$from 			= $_POST['ereturnfrom'];
	if ( intval($from) == 1 ) {
		$suppliercode	= $_POST['aReturnedBy'];
		$sql = "update tbl_returns set returneddate='$returneddate', particulars='$particulars', suppliercode='$suppliercode', empno=NULL where idreturns = '$id'";
	}
	if($db->query($sql)){
		$_SESSION['success'] = $msg_edit;
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])) {
	$id = $_POST['didreturned'];
	$sql = "update tbl_returns set active=1 where idreturns = '$id'";
	if($db->query($sql)){
		$_SESSION['success'] = $msg_delete;
	} else {
		$_SESSION['error'] = $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

?>