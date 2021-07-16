<?php
include '../db.php';
$columns = array('idreceiptsdetails','rrnumber','itemname','brandname','model','specifications','serialnos','particulars','quantity','unitcost','amount');

$requestData= $_REQUEST;

$sql = "SELECT
idreceiptsdetails, r.rrnumber, i.iditemindex, i.itemcode, i.itemname, brandname, specifications, model, ifnull(rd.quantity, 0) quantity, unit,
if(type = 0, 'non consumable','consumable') type, unitcost, (ifnull(rd.quantity, 0) * ifnull(rd.unitcost, 0)) amount, receivedate,
if(rd.rrnumber like 'fb%', 'balance forwarded', suppliername) particulars, serialnos, rd.rrnumber docno
from
tbl_receipts r
right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
left join tbl_itemindex i on i.iditemindex = rd.iditemindex
left join tbl_supplier s on s.idsupplier = r.idsupplier ";

$query=$db->query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.="where rd.active = 0 and r.rrnumber is not null and (rd.rrnumber not like 'fb%' and rd.rrnumber not like 'yeb%' and rd.rrnumber not like 'ceb%') ";

if( !empty($requestData['search']['value']) ) {    
	$sql.=" and ( r.rrnumber like '%".$requestData['search']['value']."%' ";
	$sql.=" or if(rd.rrnumber like 'fb%', 'BALANCE FORWARDED', suppliername) like '%".$requestData['search']['value']."%' ";
	$sql.=" or i.itemcode like '".$requestData['search']['value']."%' ";
	$sql.=" or i.itemname like '%".$requestData['search']['value']."%' ";
	$sql.=" or brandname like '%".$requestData['search']['value']."%' ";
	$sql.=" or specifications like '%".$requestData['search']['value']."%' ";
	$sql.=" or model like '%".$requestData['search']['value']."%' ) ";
}

$sql.=" and year(receivedate) like year(now()) ";

$query = $db->query($sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 


$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query = $db->query($sql);
$data = array(); 

foreach ($query as $row) {

	$nestedData = array();
	
	$nestedData[] = $row["idreceiptsdetails"];
	$nestedData[] = $row["rrnumber"];
	$nestedData[] = utf8_encode($row["itemname"]);
	$nestedData[] = utf8_encode($row["brandname"]);
	$nestedData[] = utf8_encode($row["model"]);
	$nestedData[] = utf8_encode($row["specifications"]);
	$nestedData[] = utf8_encode($row["serialnos"]);
	$nestedData[] = utf8_encode($row["particulars"]);
	$nestedData[] = number_format($row["quantity"]);	
	$nestedData[] = number_format($row["unitcost"],2);
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
