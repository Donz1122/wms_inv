<?php
include '../session.php';
$requestData= $_REQUEST;
$columns = array('iditemindex', 'itemcode', 'itemname', 'category', 'unit', 'type', 'qty', 'rop');

$sql = "select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, ifnull(dummyqty,0) qty
from tbl_itemindex a ";

$sql_addons = "
group by a.iditemindex
having qty <= rop ";

$query 						= mysqli_query($db, $sql.$sql_addons);
$totalData 					= mysqli_num_rows($query);
$totalFiltered 				= $totalData;

$sql 	.= " where a.itemcode like '".substr($_SESSION['area'], 0,2)."%' and ( reorderpopup = 0 or reorderpopup is null ) ";
if( !empty($requestData['search']['value']) ) {
  $sql 	.= " and ( a.itemcode LIKE '".$requestData['search']['value']."%' ";
  $sql 	.= " or a.itemname LIKE '%".$requestData['search']['value']."%' ";
  $sql 	.= " or a.category LIKE '".$requestData['search']['value']."%' ) ";
}

$sql.=$sql_addons;

$query 						= mysqli_query($db, $sql);
$totalFiltered 				= mysqli_num_rows($query);
$_SESSION['reordercount'] 	= $totalFiltered;
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query 	= mysqli_query($db, $sql);

$data = array();
foreach ($query as $row) {
	$nestedData	  = array();

	$nestedData[] = $row["iditemindex"];
	$nestedData[] = $row["itemcode"];
	$nestedData[] = utf8_encode(strtoupper($row["itemname"]));
	$nestedData[] = $row["category"];
	$nestedData[] = ucwords(strtolower($row["unit"]));
	$nestedData[] = $row["type"];
	$nestedData[] = $row["qty"];
	$nestedData[] = $row["rop"];  	

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
