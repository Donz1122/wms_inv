<?php
if (!file_exists('../interface/ireceipts.php')) {
	require 'interface/ireceipts.php';
}
else {
	require '../interface/ireceipts.php';
}

class receipts extends dbs implements ireceipts {
	public function all_receipts() {
		$sql = "select idreceipts, receivedate, a.rrnumber, suppliername, ponumber, drnumber, sinumber, 
		format(if(b.amount is null,0, b.amount),2) amount
		from tbl_receipts a
		inner join tbl_supplier c on c.idsupplier = a.idsupplier
		left outer join (select rrnumber, sum(quantity * unitcost) amount from tbl_receiptsdetails group by rrnumber) b 
		on b.rrnumber = a.rrnumber";
		return $this->query($sql)->fetchAll();
	}

	public function rr_list($idreceiptsdetails='') {
		$sql = "
		select idreceiptsdetails, r.rrnumber, i.iditemindex, i.itemcode, i.itemname, brandname, specifications, model, ifnull(rd.quantity, 0) quantity, unit,
		if(type = 0, 'non consumable','consumable') type, unitcost, (ifnull(rd.quantity, 0) * ifnull(rd.unitcost, 0)) amount, receivedate, serialnos
		from
		tbl_receipts r
		right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
		left join tbl_itemindex i on i.iditemindex = rd.iditemindex
		where year(receivedate) like year(now()) ";
		if(!empty($idreceiptsdetails)) {
			$sql.=" and idreceiptsdetails = '$idreceiptsdetails' ";
			return $this->query($sql)->fetchArray();
		} else {
			return $this->query($sql)->fetchAll();
		}
	} 

	public function search_receipts($supplier, $date) {		
		$sql = "select idreceipts, receivedate, a.rrnumber, suppliername, ponumber, drnumber, sinumber, 
		format(if(b.amount is null,0, b.amount),2) amount
		from tbl_receipts a
		inner join tbl_supplier c on c.idsupplier = a.idsupplier
		left outer join (select rrnumber, sum(quantity * unitcost) amount from tbl_receiptsdetails group by rrnumber) b 
		on b.rrnumber = a.rrnumber";

		if(!empty($supplier)) {			
			$sql .= " where suppliername like '%$supplier%' ";
			//if(!empty($date)) {				
			//	$sql .= " and receivedate = '$date' ";
			//}
		} else {
			if(!empty($date)) {				
				$sql .= " where receivedate = '$date' ";
			} else {
				$sql .= " where receivedate = '$date' ";
			}
		}
		return $this->query($sql)->fetchAll();
	}

}
$receiptitem = new receipts();
?>