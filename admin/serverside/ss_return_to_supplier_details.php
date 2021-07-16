<?php

include '../session.php';
$columns = array('idreturnsdetails','itemcode','itemname','brandname','model','specifications','serialnos','remarks','quantity','unit','unitcost','idreturnsdetails');
$requestData  = $_REQUEST;
$returnedno   = $_POST['returnedno'];

$sql = "
select idreturnsdetails, returnedno, a.itemcode, itemname, brandname, model, specifications, a.serialnos, a.quantity, b.unit, ifnull(c.unitcost,0) unitcost, (ifnull(a.quantity,0) * c.unitcost) amount, remarks
from tbl_returntosupplierdetails a
left join tbl_itemindex b on b.itemcode = a.itemcode and b.active = 0
left join tbl_receiptsdetails c on c.idreceiptsdetails = a.linkid ";

$sql.=" where returnedno = '$returnedno' and a.active = 0 ";
$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


if( !empty($requestData['search']['value']) ) {
  $sql.=" and ( a.itemcode LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or itemname LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or brandname LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or model LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or specifications LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or serialnos LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or remarks LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) {

  $nestedData = array();

  $nestedData[] = $row["idreturnsdetails"];
  $nestedData[] =$returnedno;
  $nestedData[] = $row["itemname"];

  $nestedData[] = $row["brandname"];
  $nestedData[] = $row["model"];
  $nestedData[] = $row["specifications"];
  $nestedData[] = $row["serialnos"];
  $nestedData[] = $row["remarks"];

  $nestedData[] = number_format($row["quantity"],0);
  $nestedData[] = $row["unit"];
  $nestedData[] = number_format($row["unitcost"],2);
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
