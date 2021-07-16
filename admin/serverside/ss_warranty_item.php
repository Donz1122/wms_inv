<?php
include '../db.php';
$requestData= $_REQUEST;
$columns = array('idreceiptsdetails', 'warranty', 'itemcode', 'itemname', 'brandname', 'specifications', 'model', 'serialnos', 'quantity', 'unit', 'unitcost');

$sql = "
select idreceiptsdetails, a.iditemindex, a.itemcode, itemname, brandname, specifications, model, serialnos, quantity, unit, unitcost, (quantity * unitcost) amount, warranty
from tbl_receiptsdetails a
inner join tbl_itemindex b on b.iditemindex = a.iditemindex
where a.active = 0 and warranty is not null ";


if(isset($_POST['switchs'])) {	

	if(intval($_POST['switchs']) == 0 ) {
		$sql.= " and year(warranty) = year(now()) ";	
	} elseif(intval($_POST['switchs']) == 1 ) {
		$sql.= " and warranty >= now() ";	
	} elseif(intval($_POST['switchs']) == 2 ) {
		$sql.= " and warranty < now() ";	
	} else {
		$sql.= " ";	
	}

} else if(isset($_POST['after'])) {	
	$sql.= " and date(warranty) > date('".$_POST['after']."') ";	
}

$query=$db->query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=$db->query($sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {  
	$nestedData=array();
	$nestedData[] = $row["idreceiptsdetails"]; 		
	$nestedData[] = date('M d, Y', strtotime($row["warranty"]));
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["brandname"];
	$nestedData[] = $row["specifications"];
	$nestedData[] = $row["model"];
	$nestedData[] = $row["serialnos"];
	$nestedData[] = $row["quantity"];
	$nestedData[] = $row["unit"];		
	$nestedData[] = number_format($row["unitcost"], 2);		
	
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
