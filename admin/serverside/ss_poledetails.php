<?php
include '../db.php';

$columns = array('idpoledetails','itemcode','itemname','description','specs','qty','unit','unitcost','');

$requestData= $_REQUEST;

// $sql = "select * from tbl_poles ";
$sql = "
select idpoledetails, poleno, ii.itemcode, itemname, description, specs, unit, qty, unitcost from tbl_poledetails pd
  left join tbl_itemindex ii on ii.itemcode = pd.itemcode ";

$query=mysqli_query($db, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$poleno = $_POST['poleno'];

$sql.=" where poleno = '$poleno' ";
if( !empty($requestData['search']['value']) ) {
	$sql.=" and ( poleno  	like '%".$requestData['search']['value']."%' ";
	$sql.=" or itemname 	like '%".$requestData['search']['value']."%' ";
	$sql.=" or description 	like '%".$requestData['search']['value']."%' ";
	$sql.=" or specs 		like '%".$requestData['search']['value']."%' ) ";
}

$query=mysqli_query($db, $sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."  ";

$query 	=	mysqli_query($db, $sql);

$data 	= array();
while( $row 	= mysqli_fetch_array($query) ) {
	$nestedData = array();

	$nestedData[] = $row["idpoledetails"];
	$nestedData[] = $row["itemcode"];
	$nestedData[] = $row["itemname"];
	$nestedData[] = $row["description"];
	$nestedData[] = $row["specs"];
	$nestedData[] = $row["qty"];
	$nestedData[] = $row["unit"];
	$nestedData[] = $row["unitcost"];
	$nestedData[] = $row["idpoledetails"];

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
