
<?php
include '../session.php';

if(isset($_POST['id'])){
	$id = $_POST['id'];
	$sql = "select a.*, itemname, b.unit, (quantity * unitcost) amount
	from tbl_returnsdetails a
	inner join tbl_itemindex b on b.itemcode = a.itemcode
	where idreturnsdetails= '$id'
	order by itemname
	limit 1";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

// select mr and issuance item or supplier return item
if(isset($_POST['from'])) {
	if($_POST['from'] == 'employee') {
		$empno 				= $_POST['empno'];
		$indexid 			= $_POST['indexid'];
		$sql 	= "
		select id, docno, iditemindex, itemcode, itemname, brandname, specifications, model, serialnos, quantity, unit, amount
		from
		((select idissuancedetails id, a.inumber docno, b.iditemindex, a.itemcode, itemname, brandname, specifications, model, serialnos, quantity, b.unit, format((quantity * unitcost),2) amount
		from tbl_issuancedetails a
		right join tbl_issuance i on i.idissuance = a.idissuance
		right join tbl_itemindex b on b.iditemindex = a.iditemindex
		where (a.active <> 1 and i.active = 0) and empno = '$empno' and idissuancedetails = '$indexid'
		and approved = 1 order by itemname)
		union all
		(select idempreceiptdetails id, a.mrnumber docno, c.iditemindex, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, c.unit, format((a.quantity * a.unitcost),2) amount
		from tbl_empreceiptdetails a
		right join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
		right join tbl_itemindex c on c.iditemindex = a.iditemindex
		left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
		where (a.active <> 1 and e.active = 0) and empno = '$empno' and idempreceiptdetails = '$indexid'
		order by itemname)) as items
		order by itemname asc
		limit 1;";
		$query 	= $db->query($sql)->fetch_assoc();

		$return['id'] 						= $query['id'];
		$return['docno'] 					= $query['docno'];
		// $return['iditemindex'] = $query['iditemindex'];
		$return['itemcode'] 			= $query['itemcode'];
		$return['itemname'] 			= $query['itemname'];
		$return['brandname'] 			= $query['brandname'];
		$return['specifications'] = $query['specifications'];
		$return['model'] 					= $query['model'];
		$return['serialnos'] 			= $query['serialnos'];
		$return['quantity'] 			= $query['quantity'];
		$return['unit'] 					= $query['unit'];
		$return['amount'] 				= $query['amount'];

	} else {
		$suppliercode	= $_POST['suppliercode'];
		$indexid 			= $_POST['indexid'];
		$sql = "select idreturnsdetails id, rs.returnedno , suppliercode, rsd.itemcode, itemname, unit, quantity
		from tbl_returntosupplier rs
		left join tbl_returntosupplierdetails rsd on rsd.returnedno = rs.returnedno
		left join tbl_itemindex ii on ii.itemcode = rsd.itemcode
		where rsd.active = 0 and rsd.idreturnsdetails = '$indexid'
		order by itemname";
		$query = $db->query($sql)->fetch_assoc();
		$return['id'] 				= $query['id'];
		$return['docno'] 			= $query['returnedno'];
		$return['suppliercode'] 	= $query['suppliercode'];
		$return['itemcode'] 		= $query['itemcode'];
		$return['itemname'] 		= $query['itemname'];
		$return['unit'] 			= $query['unit'];
		$return['quantity'] 		= $query['quantity'];
	}

	$return['from'] = $_POST['from'];
	echo json_encode($return);
}

if(isset($_POST['selectitemid'])) {
	$id = $_POST['selectitemid'];
	$sql = "
	select idempreceiptdetails, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, amount, if(ifnull(unitstatus,0)=0,'Functional','Salvage') unitstatus,
	depyear, a.idreceipts, dateaquired, a.serialnos, b.rrnumber, a.idreceiptsdetails
	from tbl_empreceiptdetails a
	left join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
	left join tbl_itemindex c on c.iditemindex = a.iditemindex
	left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
	where a.active = 0 and idempreceiptdetails = '$id'
	order by itemname";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add']) || isset($_POST['addmr'])) {

	$returnedno 			= $_POST['returnedno'];
	$itemcode 				= $_POST['itemcode'];
	$quantity 				= $_POST['quantity'];
	$linkrefno				= $_POST['linkrefno']; //determine issuance or mr
	$linkid						= $_POST['linkid']; //determine issuance or mr
	$ref        			= substr($linkrefno, 0, 2);
	$from 						= $_POST['trans_from'];
	$iduser 					= $_SESSION['iduser'];

	$sql = "insert into tbl_returnsdetails( returnedno, itemcode, quantity, iduser, linkid, linkrefno)
	values ('$returnedno','$itemcode','$quantity','$iduser','$linkid','$linkrefno')";
	if($db->query($sql)){
		if($from == 'supplier') {
			$id = $_POST['idreturnsdetails'];
			$db->query("update tbl_returntosupplierdetails set isreturned = 1 where idreturnsdetails = '$id' ");
		} else {
			if($ref == 'MR') {
				$db->query("update tbl_empreceiptdetails set unitstatus = 'Return to warehouse', active = 2  where idempreceiptdetails = '$linkid'");
			} else {
				if(intval($quantity > 1)) {
					$query = $db->query("select quantity from tbl_issuancedetails where idissuancedetails = '$linkid'")->fetch_assoc();
					$get_quantity = $query['quantity'];
					$remaining_quantity = $get_quantity - $quantity;
					if($remaining_quantity <= 0) {
						$db->query("update  tbl_issuancedetails set quantity=0 where idissuancedetails = '$linkid'");
					} else {
						$db->query("update  tbl_issuancedetails set quantity=quantity-'$quantity' where idissuancedetails = '$linkid'");
					}
				}
			}
		}
		$return['saved'] 		= 'saved';
	} else {
		$return['saved'] 		= 'not saved';
	}
	echo json_encode($return);
}

if(isset($_POST['edit'])) {
	$id 						= $_POST['eidreturnsdetails'];

	$iditemindex  	= $_POST['eiditemindex'];
	$itemcode  			= $_POST['eitemcode'];

	$xquantity 			= $_POST['eqty'];
	$quantity 			= $_POST['eretqty'];
	$unitcost 			= $_POST['eunitcost'];
	$iduser 				= $_SESSION['iduser'];
	if (intval($quantity)<=intval($xquantity)) {
		$sql = "update tbl_returnsdetails set iditemindex='$iditemindex', itemcode='$itemcode', quantity='$quantity' where idreturnsdetails='$id'";
		if($db->query($sql)){
			$_SESSION['success'] = 'Receipt details information modified successfully';
		} else {
			$_SESSION['error'] = $db->error;
		}
	} else {
		$_SESSION['error'] = "Error in return quantity...";
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['delete'])) {
	$id 				= $_POST['delete'];
	$iduser 		= $_SESSION['iduser'];
	$sql 			  = "select * from tbl_returnsdetails where idreturnsdetails = '$id' and iduser = '".$_SESSION['iduser']."'";
	if (intval($iduser) == 0) {
		$sql = "select * from tbl_returnsdetails where idreturnsdetails = '$id'";
	}
	$query 			= $db->query($sql);
	if(mysqli_num_rows($query) <= 0){
		$return['saved']    	= "User are not allowed to delete this item!";
	} else {
		$query 								= $db->query($sql)->fetch_assoc();
		$mctmtt_or_mr_no		 	= $query['linkrefno'];
		$quantity 						= $query['quantity'];
		$sql = "update tbl_returnsdetails set active = 1 where idreturnsdetails = '$id'";
		if($db->query($sql)){
			$ref        				= substr($mctmtt_or_mr_no, 0, 2);
			if($ref == "MR") {
				$sql = "update tbl_empreceiptdetails set quantity = (quantity + '$quantity'), amount = amount + ((amount/quantity) * '$quantity'), active = 0
				where mrnumber = '$mctmtt_or_mr_no'";
			} else {
				$sql = "update tbl_issuancedetails set quantity = (quantity + '$quantity'), amount = amount + ((amount/quantity) * '$quantity'), active = 0
				where inumber = '$mctmtt_or_mr_no'";
			}
			mysqli_query($db, $sql);
			$return['saved'] = 'deleted';
		} else {
			$return['saved'] = 'not deleted';
		}
		echo json_encode($return);
	}
}

?>