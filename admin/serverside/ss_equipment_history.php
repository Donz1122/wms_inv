<?php
include '../db.php';

$columns = array('iditemindex', 'itemcode', 'itemname', 'tag_no', 'parts');

$requestData= $_REQUEST;

$sql = "select * from x_equipment_history ";
$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.=" where trim(tag_no) != '' ";
if( !empty($requestData['search']['value']) ) {    
	$sql.=" and ( itemcode like '%".$requestData['search']['value']."%' ";
	$sql.=" or parts			 like '%".$requestData['search']['value']."%' ";
	$sql.=" or tag_no 		 like '%".$requestData['search']['value']."%' ) ";	
}

// $sql.=" group by iditemindex ";

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query 	=	mysqli_query($db, $sql);

$data 	= array();
while( $row 	= mysqli_fetch_array($query) ) {  
	$nestedData = array();

	$nestedData[] = $row["iditemindex"]; 		
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["tag_no"];
	$nestedData[] = $row["parts"];	
	$data[] 			= $nestedData;
}

$json_data = array(
	"draw"            => intval( $requestData['draw'] ),   
	"recordsTotal"    => intval( $totalData ),   
	"recordsFiltered" => intval( $totalFiltered ),  
	"data"            => $data    
);

echo json_encode($json_data);  

?>
