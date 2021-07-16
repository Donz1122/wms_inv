<?php
include '../session.php';
$dbs = new dbs();

if(isset($_POST['dates'])) {
	$dates = $_POST['dates'];
  /*$sql = "select a.*, if(b.amount is null,0, b.amount) amount, fullname receivedby from tbl_issuance a
  left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b 
  on b.inumber = a.inumber
  left outer join tbl_user c on c.iduser = a.iduser
  where month(idate) = month('$dates') and year(idate) = year('$dates')
  order by inumber asc;";
  $query = $db->query($sql)->fetch_assoc();*/


  $sql = "select idate, a.inumber, purpose, c.itemindexcode, itemname, quantity, b.unitcost, (quantity * b.unitcost) amount
  from tbl_issuance a
  inner join tbl_issuancedetails b on b.idissuance = a.idissuance
  inner join tbl_itemindex c on c.iditemindex = b.iditemindex
  inner join tbl_items d on d.iditems = c.iditems
  where month(idate) = month('$dates') and year(idate) = year('$dates')
  order by a.inumber";
  $rows = $dbs->query($sql)->fetchAll();
  exportProductDatabase($rows);

}

function exportProductDatabase($productResult) {

	//header('Content-Type: text/csv; charset=utf-8');
	//header('Content-Disposition: attachment; filename=issuance_data.csv');

	$timestamp = time();
	$filename = 'Export_excel_' . $timestamp . '.xls';  	
	//$filename = 'C:\Users\MIS-FeL\Desktop\pictures\export-to-excel.xls';
	header("Content-Type: application/vnd.ms-excel;");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	//header("Pragma: no-cache"); 
	//header("Expires: 0");

	$isPrintHeader = false;
	foreach ($productResult as $row) {
		if (! $isPrintHeader) {
			echo implode("\t", array_keys($row)) . "\n";
			$isPrintHeader = true;
		}
		echo implode("\t", array_values($row)) . "\n";
	}
	exit();
}

?>