<?php
include '../session.php';
include '../class/clsitems.php';
include '../class/clslogs.php';

if(isset($_POST['itemcode'])){
	$itemcode 		= $_POST['itemcode'];
	$category 		= $_POST['category'];
	$a 				= substr($itemcode, 0,3);
	$b 				= getFirstLetterInWords($category);
	// $c 				= str_replace($a, $a.$b, $itemcode);
	$c 				= $b.$itemcode;
	$d 				= getFirstLetterOnly($itemcode);
	$return['newitemcode'] 	= $c;
	$return['newarea'] 		= $a;
	echo json_encode($return);
}

if(isset($_POST['id'])){
	$id 			= $_POST['id'];
	$sql 			= "
	select *,
	if(itemcode like 'DMO%','Dipolog Main Office',if(itemcode like 'PAS%','Piñan Area Services',if(itemcode like 'SAS%','Sindangan Area Services','Liloy Area Services'))) Areas
	from tbl_itemindex where iditemindex = '$id'";
	$query = $db->query($sql)->fetch_assoc();
	echo json_encode($query);
}

if(isset($_POST['add'])){
	// $itemcode 		= $_POST['aitemcode'];
	$itemname 		= $_POST['itemname'];
	$category 		= $_POST['category'];
	$unit 			= $_POST['unit'];
	$type 			= $_POST['consumable'];
	$acct_in 		= $_POST['acct_in'];
	$acct_out 		= $_POST['acct_out'];
	$reorderpoint 	= $_POST['reorderpoint'];
	$depyear 		= 0;
	if (!empty($_POST['adepyear'])) {
		$depyear 	= $_POST['adepyear'];
	}

	//$area 			= $_SESSION['area'];
	$iduser 		= $_SESSION['iduser'];

	$itemcode 		= $_POST['aitemcode'];
	$arr 			= array('DMO','PAS','SAS','LAS');

	for( $i= 0 ; $i <= 3 ; $i++ ) {
		$sql 			= "
		insert into tbl_itemindex(itemcode, itemname, category, unit, acct_in, acct_out, iduser, area, type, reorderpoint, depyear)
		values('".$arr[$i]."$itemcode','$itemname','$category','$unit','$acct_in','$acct_out','$iduser','".substr($arr[$i],0,1)."','$type','$reorderpoint','$depyear')";
		$db->query($sql);
	}

	/*if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'], "ItemIndex", "Entry of Item Name ".$itemname);
		$_SESSION['success'] = 'Item Index successfully saved!';
	}
	else{
		$_SESSION['error'] = $db->error;
	}	 */
	header('location: ' . $_SERVER['HTTP_REFERER']);
	//header('location: ../itemindex.php');
}

if(isset($_POST['edit'])){
	$id 					= $_POST['xe_itemindex'];
	$itemname 		= $_POST['e_itemname'];
	$category 		= $_POST['e_category'];
	$unit 				= $_POST['e_unit'];
	$type 				= $_POST['e_consumable'];
	$acct_in 			= $_POST['e_acct_in'];
	$acct_out 		= $_POST['e_acct_out'];
	$reorderpoint = $_POST['ereorderpoint'];
	$depyear 			= $_POST['edepyear'];

	if(!$reorderpoint) $reorderpoint = 0;
	$sql = "
	update tbl_itemindex set itemname='$itemname',category='$category',type='$type', unit='$unit',
	acct_in='$acct_in',acct_out='$acct_out',reorderpoint='$reorderpoint',depyear='$depyear'
	where iditemindex='$id'";
	if($db->query($sql)){
		$record->recordlogs($_SESSION['iduser'], "ItemIndex", "Edit Item Name ".$itemname);
		$_SESSION['success'] = 'Item Index successfully updated!';
	}
	else{
		$_SESSION['error'] = $db->error;
	}
	header('location: ../itemindex.php');
}

if(isset($_POST['delete'])){
	$id 			= $_POST['ditemindex'];
	$itemname = $_POST['ditemname'];

	$sql = "update tbl_itemindex set active = 1 where iditemindex = '$id'";
	if($db->query($sql)) {
		$record->recordlogs($_SESSION['iduser'],"ItemIndex", "Delete Item Name ".$itemname);
		$_SESSION['success'] 	= 'Item Index successfully deleted!';
	} else {
		$_SESSION['error'] 		= $db->error;
	}
	echo $sql;
	// header('location: ../itemindex.php');
}

if(isset($_POST['addyearendqty'])){
	$iditemindex 	= $_POST['iditemindexye'];
	$itemcode 		= $_POST['itemcodeye'];
	$closingqty		= $_POST['qtya'];
	$actualqty		= $_POST['qtyb'];
	$closingdate	= $_POST['ayear'];

	$iduser 		= $_SESSION['iduser'];

	$sql 				= " update tbl_itemindex set closingdate = '$closingdate', closingqty='$closingqty', actualqty='$actualqty' where iditemindex='$iditemindex' ";
	if($db->query($sql)){
		$_SESSION['success'] 	= 'Year end quantity successfully saved! ';
		$db->query("insert into tbl_closing (iditemindex, itemcode, closingdate, closingqty, actualqty) values ('$iditemindex', '$itemcode', '$closingdate', '$closingqty', '$actualqty')");
	} else {
		$_SESSION['error'] 		= $db->error;
	}
	header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['addyearendqtyb'])){
	$iditemindex 	= $_POST['ciditemindex'];
	$itemcode 		= $_POST['citemcode'];
	$closingqty		= $_POST['cqtya'];
	$actualqty		= $_POST['cqtyb'];
	$closingdate	= $_POST['cyear'];
	$average		= $_POST['average'];

	$iduser 		= $_SESSION['iduser'];
	$return['ok']	= 0;
	$sql 				= " update tbl_itemindex set closingdate = '$closingdate', closingqty='$closingqty', actualqty='$actualqty', average='$average' where iditemindex='$iditemindex' ";
	if($db->query($sql)){
		//$_SESSION['success'] 	= 'Year end quantity successfully saved! ';
		$db->query("insert into tbl_closing (iditemindex, itemcode, closingdate, closingqty, actualqty, average) values ('$iditemindex', '$itemcode', '$closingdate', '$closingqty', '$actualqty', '$average')");
		$return['ok'] = 1;
	} else {
		//$_SESSION['error'] 		= $db->error;
		$return['ok'] = 0;
	}
	include '../serverside/ss_itemindex_helper.php';
	echo json_encode($return);
	//header('location: ' . $_SERVER['HTTP_REFERER']);
}

if(isset($_POST['getquantity'])) {
	$id 			= $_POST['getquantity'];
	$givendate		= $_POST['givendate'];
	/*$mystr = "
	select iditemindex, actualqty, actualqty + (sum(inqty) - sum(outqty)) as qty, average
	from
	(
	(
	select ii.iditemindex, (select ifnull(actualqty,0) from tbl_itemindex where iditemindex = '$id') actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, sum(ifnull(rd.unitcost,0))/count(ii.iditemindex) as average
	from tbl_itemindex ii
	left join tbl_receiptsdetails rd on rd.iditemindex = ii.iditemindex
	inner join tbl_receipts r on r.idreceipts = rd.idreceipts
	where rd.active = 0 and rd.iditemindex = '$id'
	and receivedate between cast(closingdate as date) and cast('$givendate' as date)
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, 0
	from tbl_returns r
	left join tbl_returnsdetails rd on rd.idreturns = r.idreturns
	right join tbl_itemindex ii on ii.iditemindex = rd.iditemindex
	where rd.active = 0 and returneddate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.iditemindex = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0 as inqty, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_empreceipts r
	left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
	right join tbl_itemindex ii on ii.iditemindex = rd.iditemindex
	where rd.active = 0 and datereceived between cast(closingdate as date) and cast('$givendate' as date)
	and rd.iditemindex = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_issuance r
	left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
	right join tbl_itemindex ii on ii.iditemindex = rd.iditemindex
	where rd.active = 0 and idate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.iditemindex = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_returntosupplier r
	right join tbl_returntosupplierdetails rd on rd.idreturns = r.idreturns
	right join tbl_itemindex ii on ii.iditemindex = rd.iditemindex
	where rd.active = 0 and returneddate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.iditemindex = '$id'
	)
) b;";*/
$mystr = "select iditemindex, actualqty, actualqty + (sum(inqty) - sum(outqty)) as qty, average
	from
	(
	(
	select ii.iditemindex, (select ifnull(actualqty,0) from tbl_itemindex where itemcode = '$id') actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, sum(ifnull(rd.unitcost,0))/count(ii.iditemindex) as average
	from tbl_itemindex ii
	left join tbl_receiptsdetails rd on rd.itemcode = ii.itemcode
	inner join tbl_receipts r on r.rrnumber = rd.rrnumber
	where rd.active = 0 and rd.itemcode = '$id'
	and receivedate between cast(closingdate as date) and cast('$givendate' as date)
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, 0
	from tbl_returns r
	left join tbl_returnsdetails rd on rd.returnedno = r.returnedno
	right join tbl_itemindex ii on ii.itemcode = rd.itemcode
	where rd.active = 0 and returneddate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.itemcode = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0 as inqty, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_empreceipts r
	left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
	right join tbl_itemindex ii on ii.itemcode = rd.itemcode
	where rd.active = 0 and datereceived between cast(closingdate as date) and cast('$givendate' as date)
	and rd.itemcode = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_issuance r
	left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
	right join tbl_itemindex ii on ii.itemcode = rd.itemcode
	where rd.active = 0 and idate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.itemcode = '$id'
	)
	union all
	(
	select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
	from tbl_returntosupplier r
	right join tbl_returntosupplierdetails rd on rd.returnedno = r.returnedno
	right join tbl_itemindex ii on ii.itemcode = rd.itemcode
	where rd.active = 0 and returneddate between cast(closingdate as date) and cast('$givendate' as date)
	and rd.itemcode = '$id'
	)
) b;";

$query = $db->query($mystr)->fetch_assoc();
echo json_encode($query);
}

if(isset($_POST['idclosing'])) {
	$idclosing = $_POST['idclosing'];

	$sql = "delete from tbl_closing where idclosing = '$idclosing'";
	if($db->query($sql)){
		$return['ok']	= 1;
	}
	else{
		$return['ok']	= 0;
	}
	echo json_encode($return);
}

if(isset($_POST['inactive'])) {
	$iditemindex = $_POST['inactive'];

	$sql = "update tbl_itemindex set inactive = if(inactive=0,1,0) where iditemindex = '$iditemindex'";
	if($db->query($sql)){
		$return['ok']	= 1;
	}
	else{
		$return['ok']	= 0;
	}
	echo json_encode($return);
}

// if(isset($_POST['saveinactivevalue']) || intval($_POST['saveinactivevalue']) >=0 ) {
// 	unset($_SESSION['inactive']);
// 	$_SESSION['inactive'] = $_POST['saveinactivevalue'];
// 	echo json_encode($_SESSION['inactive']);
// }

?>