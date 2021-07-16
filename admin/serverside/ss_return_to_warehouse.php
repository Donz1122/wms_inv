<?php

include '../session.php';
$columns = array('idreturns','returneddate','returnedno','particulars','empname');
$requestData  = $_REQUEST;

$sql = "
select a.*, if(empno is not null, concat(lastname,', ',firstname,' ',middleinitial), suppliername) empname,
sum(if(b.quantity is null,0,b.quantity)) qty, amount
from tbl_returns a
left outer join tbl_returnsdetails b on b.returnedno = a.returnedno and b.active = 0
left outer join (select itemcode, dummyave as amount from tbl_itemindex where active = 0) c on c.itemcode = b.itemcode
left outer join zanecopayroll.employee d on d.empnumber = a.empno
left outer join tbl_supplier s on s.suppliercode = a.suppliercode ";


$sql.=" where a.active = 0 ";

$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;


if( !empty($requestData['search']['value']) ) {
  $sql.=" and ( a.returneddate LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or returnedno LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or particulars LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or suppliername LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" or concat(lastname,', ',firstname,' ',middleinitial) LIKE '%".$requestData['search']['value']."%' ) ";
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
  $nestedData[] = '<a data-toggle="tooltip" title="View Details" href="returntowarehousedetails.php?id='.$row["idreturns"].'&no='.$row["returnedno"].'">'.$row['returnedno'].'</a>';
  // $nestedData[] = '<a data-toggle="tooltip" title="View Details" href="returntowarehousedetails.php?id='.$row["idreturns"].'&no='.$row["returnedno"].'">'.$row['returnedno'].'</a>';
  $nestedData[] = utf8_decode($row["particulars"]);
  // $nestedData[] = number_format($row["amount"], 2);
  $nestedData[] = utf8_decode($row["empname"]);

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
