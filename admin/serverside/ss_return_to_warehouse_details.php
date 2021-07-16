<?php

include '../session.php';
$columns = array('idreturnsdetails','itemcode','itemname','quantity','unit','idreturnsdetails');
$requestData  = $_REQUEST;
$returnedno   = $_POST['returnedno'];

$sql = "
select idreturnsdetails, returnedno, a.itemcode, itemname, quantity, b.unit,
format(unitcost,2) unitcost, format((quantity * unitcost),2) as amount
from tbl_returnsdetails a
inner join tbl_itemindex b on b.itemcode = a.itemcode and b.active = 0 ";

$sql.=" where returnedno = '$returnedno' and a.active = 0 ";
$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


if( !empty($requestData['search']['value']) ) {
  $sql.=" and ( a.itemcode LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or itemname LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) {

  $nestedData = array();

  $nestedData[] = $row["idreturnsdetails"];
  $nestedData[] = $row["itemcode"];
  $nestedData[] = $row["itemname"];
  $nestedData[] = $row["quantity"];
  $nestedData[] = $row["unit"];
  $nestedData[] = $row["idreturnsdetails"];
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
