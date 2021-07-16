<?php
	include dirname(__DIR__,1).'\session.php';
	if(isset($_POST['export']))
	{
		//header('Content-Type: text/csv');
	    //header('Content-Disposition: attachment; filename="issuance_data.csv"');
	    //header('Pragma: no-cache');
	    //header('Expires: 0');

		//header('Content-Type: text/csv; charset=utf-8');
		//header('Content-Disposition: attachment; filename=issuance_data.csv');
		$output = fopen('issuance_data.csv', 'w') or die('');
		//$output = fopen('php://output', 'w');
		fputcsv($output, array('idate','inumber','purpose','rvno','idissuance','requester'));
		$sql = "select a.*, format(if(b.amount is null,0, b.amount),2) amount from tbl_issuance a
                left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b on b.inumber = a.inumber";
        $query = $db->query($sql);
        while($row = $query->fetch_assoc()){
        	fputcsv($output, $row);
        }
        fclose($output);
	}
?>