<?php
if (!file_exists('../interface/imreceipts.php')) {
    require 'interface/imreceipts.php';
}
else {
    require '../interface/imreceipts.php';
}

class item extends dbs implements imreceipts {
	public function all_receipts() {
		$sql = "select idreceiptsdetails, a.rrnumber, b.itemcode, itemname  from tbl_receiptsdetails a
                inner join tbl_itemindex b on b.iditemindex = a.iditemindex;";
		return $this->query($sql)->fetchAll();
	}
}
$mr = new item();
?>