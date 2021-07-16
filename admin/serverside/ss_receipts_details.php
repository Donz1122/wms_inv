<?php
include '../db.php';

$columns = array('idreceiptsdetails','itemcode','itemname','brandname', 'specifications', 'model', 'serialnos', 'quantity','unit','unitcost','amount','idreceiptsdetails');

$requestData= $_REQUEST;
$idreceipts = $_POST['idreceipts'];

// $sql = "select * from tbl_poles ";
$sql = "select idreceiptsdetails, a.iditemindex, a.itemcode,
		if(a.itemcode like 'DMOG0001', particulars, itemname) itemname,
		brandname, specifications, model, serialnos, quantity, unit, unitcost, (quantity * unitcost) amount
		from tbl_receiptsdetails a
		inner join tbl_itemindex b on b.iditemindex = a.iditemindex
		";

/*$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;*/


$sql.="where a.active = 0 and idreceipts = '$idreceipts' ";
/*if( !empty($requestData['search']['value']) ) {
	$sql.=" and ( poleno  	like '%".$requestData['search']['value']."%' ";
	$sql.=" or itemname 	like '%".$requestData['search']['value']."%' ";
	$sql.=" or description 	like '%".$requestData['search']['value']."%' ";
	$sql.=" or specs 		like '%".$requestData['search']['value']."%' ) ";
}*/

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
$totalData = $totalFiltered;
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query 	=	mysqli_query($db, $sql);

$data 	= array();
while( $row 	= mysqli_fetch_array($query) ) {
	$nestedData = array();

	$nestedData[] = $row["idreceiptsdetails"];
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["brandname"];
	$nestedData[] = $row["specifications"];
	$nestedData[] = $row["model"];
	$nestedData[] = $row["serialnos"];
	$nestedData[] = number_format($row["quantity"],0);
	$nestedData[] = $row["unit"];
	$nestedData[] = number_format($row["unitcost"],2);
	$nestedData[] = number_format($row["amount"],2);

	$data[] 			= $nestedData;
}

$json_data = array(
	"draw"            => intval( $requestData['draw'] ),
	"recordsTotal"    => intval( $totalData ),
	"recordsFiltered" => intval( $totalFiltered ),
	"data"            => $data
);

echo json_encode($json_data);

?>
