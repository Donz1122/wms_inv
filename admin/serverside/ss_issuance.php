<?php

include '../session.php';
$columns = array( 'idissuance','idate','inumber','purpose','status','rvno','amount','requister');
$requestData  = $_REQUEST;

$iduser       = $_SESSION['iduser'];
$restriction  = $_SESSION['restriction'];
$area         = $_SESSION['area'];

$sql = "
select a.idissuance,idate,a.inumber,purpose,if(status=0,(if(issubmitted=0,'Not Submitted', 'Pending')),if(status=1,'Approved','Cancelled')) status, rvno, a.requister,if(b.amount is null,0, b.amount) amount from tbl_issuance a
left outer join (select idissuance, inumber, sum(quantity * unitcost) amount from tbl_issuancedetails where active=0 group by inumber) b on b.idissuance = a.idissuance
inner join tbl_user u on u.iduser = a.iduser ";

$sql.=" where a.active = 0 and if(a.inumber like 'mtt%',1=1,area like '".$_SESSION['area']."') ";
// $sql.=" where a.active = 0 and area like '".$_SESSION['area']."' ";

// if( (intval($restriction) == 2) || (intval($restriction) == 1 && $area != 'DMO') ) {
if( (intval($restriction) == 2) || ($area != "DMO") ) {
  $sql .= " and a.iduser = '".$iduser."' ";
}

$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if($_POST['isdatesearch'] == "no") {
  if( !empty($requestData['search']['value']) ) {
    $sql.=" AND ( a.inumber LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR purpose LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR rvno LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR idate LIKE '".$requestData['search']['value']."' ) ";
  } else {
    $sql.=" AND ( year(idate) LIKE year('".$today."') ) ";
  }
} else {
  $sql.=" AND ( idate between '".$_POST['startdate']."' and '".$_POST['enddate']."' ) ";
}

if (!empty($_SESSION['area_request'])) {
  if($_SESSION['restriction'] <> 2 ) {
    if( intval($_POST['status']) == 0 )  $sql .= " and status = 0 ";
    if( intval($_POST['status']) == 1 )  $sql .= " and status = 1 ";
    if( intval($_POST['status']) == 2 )  $sql .= " and status = 2 ";
    if( intval($_POST['status']) == 3 )  $sql .= "  ";
  }
}

if( (intval($restriction) <> 2) ) {
  $sql .= " and issubmitted = 1 ";
}


$query = mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
// $totalData; = $totalFiltered;
$sql.=" ORDER BY a.idissuance desc LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row = mysqli_fetch_array($query) ) {
  $nestedData = array();
  $nestedData[] = $row["idissuance"];
  $nestedData[] = date('M d, Y', strtotime( $row["idate"] ));
  $nestedData[] = $row["rvno"];
  $nestedData[] = $row["inumber"];
  $nestedData[] = $row["purpose"];
  $nestedData[] = $row["status"];
  $nestedData[] = number_format($row["amount"], 2);
  $nestedData[] = utf8_decode($row["requister"]);

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
