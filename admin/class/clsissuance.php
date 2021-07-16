<?php

if (!file_exists('../interface/iissuance.php')) {
    require 'interface/iissuance.php';
}
else {
    require '../interface/iissuance.php';
}

class item extends dbs implements iissuance {
	public function all_issuance() {
		$sql = "select a.*, format(if(b.amount is null,0, b.amount),2) amount from tbl_issuance a
		left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b on b.inumber = a.inumber limit 50;";
		return $this->query($sql)->fetchAll();
	}
	public function search_issuance($idissuance) {		
		$sql = "select a.*, format(if(b.amount is null,0, b.amount),2) amount, fullname receivedby from tbl_issuance a
		left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b on b.inumber = a.inumber 
		left outer join tbl_user c on c.iduser = a.iduser
		where idissuance = '$idissuance';";		
		return $this->query($sql)->fetchArray();
	}
	public function search_issuance_by_date($from, $to) {		
		$sql = "select a.*, format(if(b.amount is null,0, b.amount),2) amount from tbl_issuance a
		left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b on b.inumber = a.inumber 
		where idate between '$from' and '$to';";		
		return $this->query($sql)->fetchAll();
	}
	public function issuance_details($idissuance) {
		$sql = "select idissuancedetails, a.inumber, a.itemindexcode, itemname, quantity, a.unit, 
		format(unitcost,2) unitcost, format((quantity * unitcost),2) amount
		from tbl_issuancedetails a
		inner join tbl_itemindex b on b.iditemindex = a.iditemindex
		inner join tbl_items c on c.iditems = b.iditems
		where a.idissuance = '$idissuance'
		order by itemname;";
		return $this->query($sql)->fetchAll();
	}



}
$item = new item();
?>