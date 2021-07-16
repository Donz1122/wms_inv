<?php
include '../session.php';
$columns = array('empno', 'empname', 'mrnumbers', 'amount');
$requestData= $_REQUEST;

$iduser = $_SESSION['iduser'];

// $sql = "
// select a.empno, empname,
// group_concat(distinct a.mrnumber order by a.mrnumber asc SEPARATOR ', ') mrnumbers, sum(c.unitcost * (if(c.active=0,c.quantity,0))) amount
// from tbl_empreceipts a
// left outer join tbl_empreceiptdetails c on c.idempreceipts = a.idempreceipts and c.active <> 1 ";

$sql = "
select a.empno, concat(lastname,', ',firstname,' ',middleinitial) empname,
group_concat(distinct a.mrnumber order by a.mrnumber asc SEPARATOR ', ') mrnumbers, sum(amount) amount
from tbl_empreceipts a
inner join zanecopayroll.employee b on b.empnumber = a.empno
left outer join (select idempreceipts, sum(if(active=0,unitcost,0)) amount from tbl_empreceiptdetails group by idempreceipts) c on c.idempreceipts = a.idempreceipts ";


// $query=mysqli_query($db, $sql."where area like '".$_SESSION['area']."' ");
$query = mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

// $sql.=" WHERE a.active = 0 and area like '".$_SESSION['area']."' ";
// $sql.=" WHERE a.active = 0 and empname is not null and a.iduser = '$iduser' ";
// $sql.=" WHERE a.active = 0 and empname is not null ";
$sql.=" WHERE a.active = 0 ";
if( !empty($requestData['search']['value']) ) {
	$sql.=" AND ( concat(lastname,', ',firstname,' ',middleinitial) LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.mrnumber LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR empname LIKE '%".$requestData['search']['value']."%' ) ";
}

$sql.=" group by a.empno ";

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {
	$nestedData=array();

	$nestedData[] = $row["empno"];
	$nestedData[] = $row["empname"];
	$nestedData[] = $row["mrnumbers"];
	$nestedData[] = number_format($row["amount"],2);

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
