<?php
include '../db.php';

$columns = array('idpoles', 'poleno', 'description', 'location', 'mdate');

$requestData= $_REQUEST;

// $sql = "select * from tbl_poles ";
$sql = "
select p.idpoles, poleno, p.category, poletype, address, street, latitude, longitude, length, 
	group_concat(distinct date_format(requestdate,'%m-%d-%Y') order by requestdate desc separator ', ') mdate
	  from tbl_poles p
	  left join tbl_issuancedetails id on id.tag_no = p.poleno
	  left join tbl_itemindex ii on ii.iditemindex = id.iditemindex  
";

$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.=" where poleno is not null ";
if( !empty($requestData['search']['value']) ) {    
	$sql.=" and ( poleno  like '%".$requestData['search']['value']."%' ";
	$sql.=" or p.category like '%".$requestData['search']['value']."%' ";
	$sql.=" or address 	  like '%".$requestData['search']['value']."%' ) ";	
}

$sql.=" group by poleno ";

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query 	=	mysqli_query($db, $sql);

$data 	= array();
while( $row 	= mysqli_fetch_array($query) ) {  
	$nestedData = array();

	$nestedData[] = $row["idpoles"]; 		
	$nestedData[] = $row["poleno"];
	$nestedData[] = $row["category"];
	$nestedData[] = $row["poletype"];	
	$nestedData[] = $row["street"];	
	$nestedData[] = $row["address"];	
	$nestedData[] = $row["latitude"];	
	$nestedData[] = $row["longitude"];	
	$nestedData[] = $row["length"];	
	$nestedData[] = $row["mdate"];

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
