<?php

include '../db.php';
$requestData= $_REQUEST;
$columns = array( 'returneddate','salvageno','description','empname','idsalvage');

$sql = "SELECT a.*, concat(lastname,', ',firstname,' ',middleinitial) empname
FROM tbl_salvage a
LEFT OUTER JOIN tbl_salvagedetails b ON b.idsalvage = a.idsalvage AND b.active = 0
LEFT OUTER JOIN zanecopayroll.employee c ON c.empnumber = a.empno ";

$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$sql.=" WHERE a.active = 0 ";
if( !empty($requestData['search']['value']) ) {
  $sql.=" AND ( salvageno LIKE '".$requestData['search']['value']."%'   ";
  $sql.=" OR description  LIKE '".$requestData['search']['value']."%'   ";
  $sql.=" OR refno        LIKE '".$requestData['search']['value']."%'   ";
  $sql.=" OR empname      LIKE '".$requestData['search']['value']."%' ) ";
}

// $sql.=" ORDER BY returneddate ";

$query=$db->query($sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=mysqli_query($db, $sql);

$data = array();
foreach ($query as $row) {
  $nestedData   = array();

  $nestedData[] = date('M d, Y', strtotime( $row["returneddate"] ));
  $nestedData[] = $row["salvageno"];
  $nestedData[] = $row["description"];
  // $nestedData[] = $row["refno"];
  // $nestedData[] = $row["amount"];
  $nestedData[] = $row["empname"];  
  $nestedData[] = $row["idsalvage"];

  $data[]       = $nestedData;
}

$json_data = array(
  "draw"            => intval( $requestData['draw'] ),
  "recordsTotal"    => intval( $totalData ),
  "recordsFiltered" => intval( $totalFiltered ),
  "data"            => $data
);

echo json_encode($json_data);

?>
