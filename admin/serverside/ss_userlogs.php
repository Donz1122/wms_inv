<?php 
include '../db.php'; 

$requestData= $_REQUEST;
$columns = array('dates', 'fullname', 'forms', 'transactions');
$sql = "select fullname, forms, dates, transactions
from tbl_logs a
left join tbl_user b on b.iduser = a.iduser ";

$query=$db->query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.=" WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {    
	$sql.=" and ( fullname like '%".$requestData['search']['value']."%' ";
	$sql.=" or forms like '%".$requestData['search']['value']."%' ";
	$sql.=" or transactions like '%".$requestData['search']['value']."%' ) ";
}

$query=$db->query($sql);
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query=mysqli_query($db, $sql);

$data = array();
foreach ($query as $row) {
	$nestedData=array();

	$nestedData[] = $row["dates"];		
	$nestedData[] = $row["transactions"];
	$nestedData[] = $row["fullname"]; 
	
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
