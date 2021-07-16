<?php
include '../session.php';
$requestData= $_REQUEST;
$columns = array('iditemindex', 'transactiondate', 'docno', 'particulars', 'inqty', 'outqty', 'unit','unitcost','datelogs');

$keys = $_POST['itemcode'];

/*$sql = "
	select a.active, transactiondate, a.iditemindex, a.itemcode, a.itemname, docno, particulars, a.category, a.unit, unitcost, if(a.type=0,'Non Consumable','Consumable') type, a.reorderpoint rop, f.inqty, f.outqty, (f.inqty - f.outqty) qty, f.datelogs
	from tbl_itemindex a
	left join
	(
		(select
			iditemindex,
			ifnull(rd.quantity,0) as inqty,
			0 as outqty,
			receivedate transactiondate,
			if(rd.rrnumber like 'fb%','BALANCE FORWARDED',suppliername) particulars,
				rd.rrnumber docno, unitcost, rd.datelogs
			from tbl_receipts r
			right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
			left join tbl_supplier s on s.idsupplier = r.idsupplier
			where rd.active = 0	and iditemindex = '$keys'
		)
		union all
		(
			select
			iditemindex,
			ifnull(rd.quantity,0) as inqty,
			0 as outqty,
			returneddate transactiondate,
			particulars,
			r.returnedno docno, 0, rd.datelogs
			from tbl_returns r
			left join tbl_returnsdetails rd on rd.idreturns = r.idreturns
			where rd.active = 0 and iditemindex = '$keys'
		)
		union all
		(
			select
			iditemindex,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			datereceived transactiondate,
			concat(lastname,', ',firstname,' ',middleinitial) particulars,
			r.mrnumber docno, 0, rd.datelogs
			from tbl_empreceipts r
			left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
			left join zanecopayroll.employee e on e.empnumber = r.empno
			where rd.active = 0	and iditemindex = '$keys'
		)
		union all
		(
			select
			iditemindex,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			idate transactiondate,
			purpose particulars,
			r.inumber docno, 0, rd.datelogs
			from tbl_issuance r
			left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
			where rd.active = 0 and iditemindex = '$keys'
		)
		union all
		(
			select
			iditemindex,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			returneddate transactiondate,
			remarks particulars,
			r.returnedno docno, 0, rd.datelogs
			from tbl_returntosupplier r
			left join tbl_returntosupplierdetails rd on rd.idreturns = r.idreturns
			where rd.active = 0	and iditemindex = '$keys'
		)
	) f on f.iditemindex = a.iditemindex
	where a.iditemindex = '$keys' ";*/
$sql = "
select a.active, transactiondate, a.iditemindex, a.itemcode, a.itemname, docno, particulars, a.category, a.unit, unitcost, if(a.type=0,'Non Consumable','Consumable') type, a.reorderpoint rop, f.inqty, f.outqty, (f.inqty - f.outqty) qty, f.datelogs
	from tbl_itemindex a
	left join
	(
		(select
			itemcode,
			ifnull(rd.quantity,0) as inqty,
			0 as outqty,
			receivedate transactiondate,
			if(rd.rrnumber like 'fb%','BALANCE FORWARDED',suppliername) particulars,
				rd.rrnumber docno, unitcost, rd.datelogs
			from tbl_receipts r
			right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
			left join tbl_supplier s on s.idsupplier = r.idsupplier
			where rd.active = 0	and itemcode = '$keys'
		)
		union all
		(
			select
			itemcode,
			ifnull(rd.quantity,0) as inqty,
			0 as outqty,
			returneddate transactiondate,
			particulars,
			r.returnedno docno, 0, rd.datelogs
			from tbl_returns r
			left join tbl_returnsdetails rd on rd.returnedno = r.returnedno
			where rd.active = 0 and itemcode = '$keys'
		)
		union all
		(
			select
			itemcode,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			datereceived transactiondate,
			concat(lastname,', ',firstname,' ',middleinitial) particulars,
			r.mrnumber docno, 0, rd.datelogs
			from tbl_empreceipts r
			left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
			left join zanecopayroll.employee e on e.empnumber = r.empno
			where rd.active = 0	and itemcode = '$keys'
		)
		union all
		(
			select
			itemcode,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			idate transactiondate,
			purpose particulars,
			r.inumber docno, 0, rd.datelogs
			from tbl_issuance r
			left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
			where rd.active = 0 and itemcode = '$keys'
		)
		union all
		(
			select
			itemcode,
			00 as inqty,
			ifnull(rd.quantity,0) as outqty,
			returneddate transactiondate,
			remarks particulars,
			r.returnedno docno, 0, rd.datelogs
			from tbl_returntosupplier r
			left join tbl_returntosupplierdetails rd on rd.returnedno = r.returnedno
			where rd.active = 0	and itemcode = '$keys'
		)
	) f on f.itemcode = a.itemcode
	where a.itemcode = '$keys' ";
$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) {
	$sql.="
	AND ( itemcode LIKE '".$requestData['search']['value']."%'
	OR itemname LIKE '%".$requestData['search']['value']."%'
	OR category LIKE '".$requestData['search']['value']."%' )";
}


// if(isset($_POST['qty'])) {
// 	if ($_POST['qty'] == 'yes') {
// 		$sql.= " and dummyqty > 0 ";
// 	}
// }

// $sql.="group by iditemindex ";

// $query 			= mysqli_query($db, $sql);
// $totalFiltered 	= mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {
	$nestedData=array();

	$nestedData[] = $row["iditemindex"];
	$nestedData[] = $row["transactiondate"];
	$nestedData[] = $row["docno"];
	$nestedData[] = $row["particulars"];
	$nestedData[] = $row["inqty"];
	$nestedData[] = $row["outqty"];
	$nestedData[] = ucwords(strtolower($row["unit"]));
	$nestedData[] = number_format($row["unitcost"],2);
	$nestedData[] = $row["datelogs"];

	$data[] = $nestedData;
}

$json_data = array(
	"draw"            => intval( $requestData['draw'] ),
	"recordsTotal"    => intval( $totalData ),
	"recordsFiltered" => intval( $totalFiltered ),
	"data"            => $data
);

echo json_encode($json_data);

?>
