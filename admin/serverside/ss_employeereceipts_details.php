<?php
include '../db.php';
$columns = array('idempreceiptdetails','itemcode','itemname','specifications','unitstatus','brandname','model','serialnos','quantity','unitcost','depreciation','rrnumber','idreceiptsdetails','active');
$requestData= $_REQUEST;

$sql = "
select a.active, idempreceiptdetails, c.itemcode, itemname, brandname, ifnull(specifications, specs) specifications, model, a.serialnos, a.quantity,
a.unitcost, unitstatus,
if((if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now())) <=0, 0, (if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now()))) *
(((a.unitcost * a.quantity)/if(depyear is null,1,depyear))/12) depreciation,
depyear, a.idreceipts, dateaquired, a.serialnos, b.rrnumber, a.idreceiptsdetails
from tbl_empreceiptdetails a
left join tbl_itemindex c on c.iditemindex = a.iditemindex
left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails ";

if($_POST['issearch'] == "no") {
	$sql.=" where a.active <> 1 and 1=0 ";
} else {
	$sql.=" where a.active <> 1 and a.idempreceipts = '".trim($_POST['mrno'])."' ";
}

$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query); 
$totalFiltered = $totalData;   

if( !empty($requestData['search']['value']) ) {    
	$sql.=" AND ( c.itemcode LIKE '%".$requestData['search']['value']."%' ";	
	$sql.=" OR itemname LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR brandname LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR specifications LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR model LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR a.serialnos LIKE '%".$requestData['search']['value']."%' ) ";
}

$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {  
	$nestedData = array();

	$nestedData[] = $row["idempreceiptdetails"]; 		
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["specifications"]; 
	$nestedData[] = $row["unitstatus"]; 
	$nestedData[] = $row["brandname"]; 
	$nestedData[] = $row["model"]; 
	$nestedData[] = $row["serialnos"]; 	
	$nestedData[] = $row["quantity"];
	$nestedData[] = number_format($row["unitcost"],2);	
	$nestedData[] = number_format($row["depreciation"],2);		
	$nestedData[] = $row["idreceiptsdetails"];	
	$nestedData[] = $row["active"];		

	$data[] = $nestedData;
}

$json_data = array(
	"draw"            => intval( $_POST['draw'] ),   
	"recordsTotal"    => intval( $totalData ),   
	"recordsFiltered" => intval( $totalFiltered ),  
	"data"            => $data    
);

echo json_encode($json_data);  

?>
