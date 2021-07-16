<?php 
include '../session.php'; 

$requestData= $_REQUEST;
$columns = array('idreceipts', 'receivedate', 'rrnumber', 'suppliername', 'ponumber', 'rvnumber','drnumber', 'sinumber', 'amount');
//replace(replace(suppliername,'Ñ','N'),'ñ','n') 
$sql = "
select a.idreceipts, receivedate, a.rrnumber, ponumber, rvnumber, drnumber, sinumber, if(b.amount is null,0, b.amount) amount, 
if(idpo is null or idpo = 0,
(select suppliername from tbl_supplier s 
	left join tbl_receipts r on r.idsupplier = s.idsupplier 
	where r.idreceipts = a.idreceipts),
(select name as suppliername from zanecobudget.po p  
	left join tbl_receipts r on r.idpo = p.idpo
    where r.idreceipts = a.idreceipts)) supplier
from tbl_receipts a
left join (select idreceipts, sum(quantity * unitcost) amount from tbl_receiptsdetails where active=0 group by idreceipts) b
on b.idreceipts = a.idreceipts 
inner join tbl_user u on u.iduser = a.iduser ";

$query=$db->query($sql."where area like '".$_SESSION['area']."' order by receivedate desc;");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;   

$sql.=" WHERE a.active = 0 and area like '".$_SESSION['area']."' ";
if($_POST['isdatesearch'] == "no")  {
	if( !empty($requestData['search']['value']) ) {    
		$sql.=" AND ( a.rrnumber LIKE '".$requestData['search']['value']."%' ";		
		$sql.=" OR rvnumber LIKE '%".$requestData['search']['value']."%' ";		
		$sql.=" OR sinumber LIKE '%".$requestData['search']['value']."%' ";		
		$sql.=" OR ponumber LIKE '%".$requestData['search']['value']."%' ";	
		$sql.=" OR drnumber LIKE '%".$requestData['search']['value']."%' ";	
		$sql.=" OR receivedate LIKE '%".$requestData['search']['value']."%' ";
		$sql.=" OR if(idpo is null,
				(select suppliername from tbl_supplier s 
					left join tbl_receipts r on r.idsupplier = s.idsupplier 
					where r.idreceipts = a.idreceipts),
				(select name as suppliername from zanecobudget.po p  
					left join tbl_receipts r on r.idpo = p.idpo
				    where r.idreceipts = a.idreceipts)) LIKE '%".$requestData['search']['value']."%' ) ";
	} else {
		//$sql.=" AND ( year(receivedate) LIKE year('".$today."') AND  month(receivedate) LIKE month('".$today."') ) ";
		$sql.=" AND year(receivedate) LIKE year('".$today."') ";
	}
} else {
	$sql.=" AND receivedate BETWEEN '".$_POST['fromdate']."' AND '".$_POST['todate']."' ";
}

$query=$db->query($sql);
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=mysqli_query($db, $sql);

$data = array();
foreach ($query as $row) {
	$nestedData=array();

	$nestedData[] = $row["idreceipts"];	
	$nestedData[] = date('M d, Y', strtotime($row["receivedate"]));
	$nestedData[] = $row["rrnumber"]; 
	$nestedData[] = utf8_decode($row["supplier"]);
	$nestedData[] = $row["ponumber"];
	$nestedData[] = $row["rvnumber"];
	$nestedData[] = $row["drnumber"];
	$nestedData[] = $row["sinumber"];
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
