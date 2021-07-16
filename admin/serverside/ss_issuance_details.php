<?php

include '../session.php';
$columns = array( 'idissuancedetails','itemcode','itemname','tag_no','unit','requestqty','idissuancedetails');
$requestData= $_REQUEST;

$id         = $_POST['idissuance'];
$approved   = $_POST['approved'];
$submitted  = $_POST['submitted'];

$sql = "
select idissuancedetails, a.inumber, a.itemcode, itemname, ifnull(quantity,0) quantity, requestqty, b.unit, brandname,specifications,model,
serialnos, approved, format(unitcost,2) unitcost, format((ifnull(quantity,0) * unitcost),2) amount,
if(approved=0,'Pending',(if(approved=1,'Approved','Declined'))) status, b.iditemindex, tag_no
from tbl_issuancedetails a
inner join tbl_issuance i on i.idissuance = a.idissuance
left join tbl_itemindex b on b.iditemindex = a.iditemindex
where a.active <> 1 and (a.idissuance = '$id' or a.inumber = '$id') ";

// if(!isset($approved)) {
//   $sql.=" and approved = 1 ";
// }
if(!empty($submitted)) {
  $sql.=" and issubmitted = '$submitted' ";
}

// $query          = mysqli_query($db, $sql);
// $totalData      = mysqli_num_rows($query);
// $totalFiltered  = $totalData;


if( !empty($requestData['search']['value']) ) {
  $sql.=" and ( itemname LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or a.itemcode LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or serialnos LIKE '".$requestData['search']['value']."%' ) ";
}

$query          = mysqli_query($db, $sql);
$totalFiltered  = mysqli_num_rows($query);
$totalData      = $totalFiltered;
$sql.=" order by idissuancedetails desc limit ".$requestData['start']." ,".$requestData['length']." ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {
  $nestedData = array();
  $nestedData[] = $row["idissuancedetails"];
  $nestedData[] = $row["itemcode"];
  $nestedData[] = utf8_decode($row["itemname"]);
  $nestedData[] = $row["tag_no"];
  $nestedData[] = $row["unit"];
  $nestedData[] = $row["requestqty"];
  $nestedData[] = $row["idissuancedetails"];

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
