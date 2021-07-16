<?php

if (!file_exists('../interface/isalvage.php')) {
	require 'interface/isalvage.php';
}
else {
	require '../interface/isalvage.php';
}

class salvageitem extends dbs implements isalvage {

	public function all_salvage_items() {
		$sql = "
		SELECT a.*, concat(lastname,', ',firstname,' ',middleinitial) empname, 
		if((b.quantity * b.unitcost) is NULL,0,(b.quantity * b.unitcost)) amount
		FROM tbl_salvage a
		LEFT OUTER JOIN tbl_salvagedetails b ON b.salvageno = a.salvageno AND b.active = 0
		LEFT OUTER JOIN zanecopayroll.employee c ON c.empnumber = a.empno
		WHERE a.active = 0
		ORDER BY returneddate;";
		return $this->query($sql)->fetchAll();
	}

	public function get_employee_mr_items($empno='', $limit='') {
		$sql="SELECT idempreceiptdetails, e.idempreceipts, e.mrnumber, c.iditemindex, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, c.unit, a.unitcost, (a.quantity * a.unitcost) amount
		FROM tbl_empreceiptdetails a
		LEFT JOIN tbl_empreceipts e ON e.idempreceipts = a.idempreceipts
		LEFT JOIN tbl_itemindex c ON c.iditemindex = a.iditemindex
		LEFT JOIN tbl_receiptsdetails b ON b.idreceiptsdetails = a.idreceiptsdetails
		WHERE a.active = 0 AND empno = '$empno' 
		ORDER BY itemname ";
		if (!empty($limit)) {
			$sql.="limit 1";
			return $this->query($sql)->fetchArray();
		} else {
			return $this->query($sql)->fetchAll();
		}
	}

}

$salvage = new salvageitem();
?>