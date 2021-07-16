<?php
include '../db.php';
$requestData= $_REQUEST;
$columns = array('idtransferredmr','datetransferred','itemcode','itemname','brandname','specifications','model','serialnos','mrfrom','mrto');

$sql = "select idtransferredmr, c.itemcode, itemname, brandname, specifications, model, a.serialnos, datetransferred,
  substring_index(mrfrom,'|',-1) mrfrom,
  substring_index(mrto,'|',-1) mrto
  from tbl_transferredmr a
  left join tbl_itemindex c on c.iditemindex = a.iditemindex
  left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails ";

$sql.=" where a.active <> 1 ";

$query=$db->query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=$db->query($sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {  
	$nestedData=array();
	$nestedData[] = $row["idtransferredmr"]; 		
	$nestedData[] = $row["datetransferred"];
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["brandname"];
	$nestedData[] = $row["specifications"];
	$nestedData[] = $row["model"];
	$nestedData[] = $row["serialnos"];
	$nestedData[] = $row["mrfrom"];
	$nestedData[] = $row["mrto"];		
	
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
