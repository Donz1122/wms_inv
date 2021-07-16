<?php
include '../db.php';
$columns = array('deptcode', 'deptname', 'mrnumbers', 'amount');
$requestData= $_REQUEST;

$sql = "
select deptcode, deptname,
group_concat(distinct a.mrnumber order by a.mrnumber asc SEPARATOR ', ') mrnumbers, sum(c.unitcost * c.quantity) amount
from tbl_empreceipts a
inner join tbl_department b on b.deptcode = a.empno
left outer join tbl_empreceiptdetails c on c.idempreceipts = a.idempreceipts and c.active =0 ";

$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.=" WHERE a.active=0 ";
if( !empty($requestData['search']['value']) ) {    
	$sql.=" AND ( deptcode LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR deptname LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR mrnumber LIKE '".$requestData['search']['value']."%' ) ";
}

$sql.=" group by a.empno ";

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {  
	$nestedData=array();

	$nestedData[] = $row["deptcode"]; 		
	$nestedData[] = utf8_decode($row["deptname"]);
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
