<?php
include '../session.php';
$requestData= $_REQUEST;
$columns = array('iditemindex', 'itemcode', 'itemname', 'category', 'unit', 'type', 'rop','qty','inactive');
$area = substr($_SESSION['area'], 0,2);

$sql = "select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, ifnull(dummyqty,0) qty, inactive
from tbl_itemindex a ";

$sql_addons = "where a.itemcode like '".$area."%' group by iditemindex ";
$query=mysqli_query($db, $sql.$sql_addons);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$sql.="where a.itemcode like '".$area."%' and active = 0 ";

if( !empty($requestData['search']['value']) ) {
	$sql.="
	and ( itemcode like '".$requestData['search']['value']."%'
	or itemname like '%".$requestData['search']['value']."%'
	or category like '%".$requestData['search']['value']."%' ) ";
}

/*if (isset($_POST['category']) || !empty($_POST['category'])) {
	$sql.=" and category like '".$_POST['category']."' ";
}
if (isset($_POST['type']) || !empty($_POST['type'])) {
	$sql.=" and type = '".$_POST['type']."' ";
}*/

if(isset($_POST['qty'])) {
	if ($_POST['qty'] == 'yes') {
		$sql.= " and dummyqty > 0 ";
	}
}

$sql_inactive = " and inactive = '".$_POST['inactive']."' ";
$sql_inactive = str_replace("and inactive = ''", '', $sql_inactive);
$sql.= $sql_inactive;

$sql.= " group by iditemindex ";
// $sql.="having type like '%".$requestData['search']['value']."%' ";

$query 			= mysqli_query($db, $sql);
$totalFiltered 	= mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query=mysqli_query($db, $sql);

$data = array();
while( $row=mysqli_fetch_array($query) ) {
	$nestedData=array();

	$nestedData[] = $row["iditemindex"];
	$nestedData[] = $row["itemcode"];
	$nestedData[] = strtoupper($row["itemname"]);
	$nestedData[] = $row["category"];
	$nestedData[] = $row["type"];
	// $nestedData[] = $row["rop"];
	$nestedData[] = $row["qty"];
	$nestedData[] = ucwords(strtolower($row["unit"]));

	if(intval($row['inactive']) == 0)	{
		$inactive = '<center><button type="button" name="active" id="'.$row["iditemindex"].'" class="btn btn-success btn-xs active" onclick="change_active('.$row["iditemindex"].')"><span style="font-size: 12px;">Active</span></button></center>';
	} else  {
		$inactive = '<center><button type="button" name="inactive" id="'.$row["iditemindex"].'" class="btn btn-danger btn-xs inactive" onclick="change_active('.$row["iditemindex"].')"><span style="font-size: 12px;">In-active</span></button></center>';
	}
	$nestedData[] = $inactive;
	//$nestedData[] = 0;//number_format($row["averagecost"],2);

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
