<?php

include '../session.php';
$columns = array('idreturns','returneddate','returnedno','particulars','amount','empname','suppliername','');
$requestData  = $_REQUEST;

// $iduser       = $_SESSION['iduser'];
// $restriction  = $_SESSION['restriction'];
// $area         = $_SESSION['area'];

$sql = "
select a.*, concat(lastname,', ',firstname,' ',middleinitial) empname, empno, ifnull(d.unitcost,0) unitcost,
sum(ifnull(d.unitcost,0))/count(d.iditemindex) average, (sum(ifnull(d.unitcost,0))/count(d.iditemindex) * sum(b.quantity)) amount,
suppliername
from tbl_returntosupplier a
left join tbl_returntosupplierdetails b on b.returnedno = a.returnedno
left join zanecopayroll.employee c on c.empnumber = a.empno
left join tbl_receiptsdetails d ON d.rrnumber = b.linkrefno
left join tbl_supplier s on s.suppliercode = a.suppliercode ";

$sql.=" where a.active = 0 ";

$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


if( !empty($requestData['search']['value']) ) {
  $sql.=" AND ( a.returneddate LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR returnedno LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR particulars LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR concat(lastname,', ',firstname,' ',middleinitial) LIKE '%".$requestData['search']['value']."%' ) ";
}

$sql .= " group by idreturns ";

$query = mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
// $totalData; = $totalFiltered;
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) {

  $nestedData = array();

  $nestedData[] = $row["idreturns"];
  $nestedData[] = date('M d, Y', strtotime( $row["returneddate"] ));
  $nestedData[] = '<a data-toggle="tooltip" title="View Details" target="" href="returntosupplierdetails.php?id='.$row["idreturns"].'">'.$row['returnedno'].'</a>';
  $nestedData[] = utf8_decode($row["particulars"]);
  $nestedData[] = number_format($row["amount"], 2);
  $nestedData[] = utf8_decode($row["empname"]);
  $nestedData[] = utf8_decode($row["suppliername"]);
  $nestedData[] = '';

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
