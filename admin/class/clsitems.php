<?php

if (!file_exists('../interface/interfaces.php')) {
	require_once 'interface/interfaces.php';
}
else {
	require_once '../interface/interfaces.php';
}

class item extends dbs implements interfaces {

	public function search_itemindex_summary($keys) {
		$sql = "
		select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, ifnull(dummyqty,0) qty, closingdate, closingqty, actualqty, average, dummyave
		from tbl_itemindex a
		where a.iditemindex like '$keys' and inactive = 0
		group by iditemindex ";
		return $this->query($sql)->fetchArray();
	}

	// itemindex list/history
	public function search_itemindex_details($keys) {
		$sql = "
		select a.active, transactiondate, a.iditemindex, a.itemcode, a.itemname, docno, particulars, a.category, a.unit, unitcost, if(a.type=0,'Non Consumable','Consumable') type, a.reorderpoint rop, f.inqty, f.outqty, (f.inqty - f.outqty) qty
		from tbl_itemindex a
		left join
		(
		(select
		iditemindex,
		ifnull(rd.quantity,0) as inqty,
		0 as outqty,
		receivedate transactiondate,
		if(rd.rrnumber like 'fb%','BALANCE FORWARDED',suppliername) particulars,
		rd.rrnumber docno, unitcost 
		from tbl_receipts r
		right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
		left join tbl_supplier s on s.idsupplier = r.idsupplier
		where rd.active = 0	and iditemindex = '$keys'
		)
		union all
		(
		select
		iditemindex,
		ifnull(rd.quantity,0) as inqty,
		0 as outqty,
		returneddate transactiondate,
		particulars,
		r.returnedno docno, 0
		from tbl_returns r
		left join tbl_returnsdetails rd on rd.idreturns = r.idreturns
		where rd.active = 0 and iditemindex = '$keys'
		)
		union all
		(
		select
		iditemindex,
		00 as inqty,
		ifnull(rd.quantity,0) as outqty,
		datereceived transactiondate,
		concat(lastname,', ',firstname,' ',middleinitial) particulars,
		r.mrnumber docno, 0
		from tbl_empreceipts r
		left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
		left join zanecopayroll.employee e on e.empnumber = r.empno
		where rd.active = 0	and iditemindex = '$keys'
		)
		union all
		(
		select
		iditemindex,
		00 as inqty,
		ifnull(rd.quantity,0) as outqty,
		idate transactiondate,
		purpose particulars,
		r.inumber docno, 0
		from tbl_issuance r
		left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
		where rd.active = 0 and iditemindex = '$keys'
		)
		union all
		(
		select
		iditemindex,
		00 as inqty,
		ifnull(rd.quantity,0) as outqty,
		returneddate transactiondate,
		remarks particulars,
		r.returnedno docno, 0
		from tbl_returntosupplier r
		left join tbl_returntosupplierdetails rd on rd.idreturns = r.idreturns
		where rd.active = 0	and iditemindex = '$keys'
		)
		) f on f.iditemindex = a.iditemindex
		where a.iditemindex = '$keys' and a.itemcode like '".substr($_SESSION['area'], 0,2)."%'
		having a.active = 0 and inactive = 0
		order by transactiondate asc";
		return $this->query($sql)->fetchAll();
	}

	public function itemindex($limit='') {	
		$sql = "SELECT DISTINCT iditemindex, itemcode, itemname, dummyqty FROM tbl_itemindex WHERE active = 0 and inactive = 0 and itemcode like 'dm%' ORDER BY itemname ASC ";
		if (!empty($limit)) {
			$sql.="limit 1";
			return $this->query($sql)->fetchArray();
		} else {
			return $this->query($sql)->fetchAll();
		}
	}

	public function itemindexyearendqty($keys, $curyear) {
		$sql = "select ifnull(quantity,0) quantity from tbl_receiptsdetails where iditemindex = '$keys' and rrnumber like 'yeb$curyear' limit 1;";
		return $this->query($sql);
	}

	public function categories() {
		$sql = "select * from tbl_itemcategory order by category asc";
		// $sql = "select distinct category from tbl_itemindex order by category asc";
		return $this->query($sql)->fetchAll();
	}

	public function units() {
		$sql = "select distinct unit from tbl_itemindex order by unit asc;";
		return $this->query($sql)->fetchAll();
	}

	public function acctcharts() {
		$sql = "SELECT accountcode, accountname FROM zanecoaccounting.chart ORDER BY accountcode ASC;";
		return $this->query($sql)->fetchAll();
	}

	public function suppliers() {
		$sql = "SELECT * FROM tbl_supplier ORDER BY suppliername;";
		return $this->query($sql)->fetchAll();
	}

	public function employee() {
		$sql = "SELECT empnumber, concat(lastname,', ',firstname,' ',middleinitial) empname, title FROM zanecopayroll.employee WHERE CONCAT(lastname,', ',firstname,' ',middleinitial) IS NOT NULL ORDER BY empname;";
		return $this->query($sql)->fetchAll();
	}

	public function users() {
		$sql = "select iduser,username,password,fullname, area, position,
		if(usertype=0,'Materials','Office') usertype,doccode,
		if(restriction=0,'Admin',if(restriction=1,'Stock Clerk','Guest'))
		restriction
		FROM tbl_user
		ORDER BY username ASC;";
		return $this->query($sql)->fetchAll();
	}

	public function get_purchase_order($limit='') {

		/*$sql = "
		select *
		from
		(
		(select p.idpo, p.pcode suppliercode, p.podate,
		p.name suppliername,
		p.ponumber,
		r.rvnumber, p.address
		from zanecobudget.po p
		left join zanecobudget.podetail pd on pd.idpo = p.idpo
		left join zanecobudget.requisitiondetail rd on rd.idrequisitiondetail = pd.idrequisitiondetail
		left join zanecobudget.requisition r on r.idrequisition =rd.idrequisition
		where p.name is not null and p.name <> '' and p.name <> '.'
		group by p.ponumber
		)
		union all
		( select idsupplier, suppliercode, '', suppliername,'',0, address
		from tbl_supplier
		where suppliername is not null
		group by suppliername
		)
		) a
		order by suppliername asc ";*/
		$sql = "
		select p.idpo, p.pcode suppliercode, p.podate,
		p.name suppliername,
		p.ponumber,
		r.rvnumber, p.address
		from zanecobudget.po p
		left join zanecobudget.podetail pd on pd.idpo = p.idpo
		left join zanecobudget.requisitiondetail rd on rd.idrequisitiondetail = pd.idrequisitiondetail
		left join zanecobudget.requisition r on r.idrequisition =rd.idrequisition
		where p.name is not null and p.name <> '' and p.name <> '.' and pcode is not null and pcode <> ''
		group by p.ponumber ";
		if (!empty($limit)) {
			$sql.="limit 1 ";
		}
		return $this->query($sql);
	}

	public function get_rr($id) {
		$sql = "
		select idreceipts, receivedate, a.rrnumber,a.ponumber, a.rvnumber,a.drnumber,a.sinumber, b.suppliername,b.address, if(c.amount is null,0,c.amount) amount, fullname
		from tbl_receipts a
		inner join tbl_supplier b on b.idsupplier = a.idsupplier
		left outer join (select rrnumber, sum(quantity * unitcost) amount from tbl_receiptsdetails where active = 0 group by rrnumber) c on c.rrnumber = a.rrnumber
		left join tbl_user d on d.iduser = a.iduser
		where a.active = 0 and a.idreceipts = '$id'";
		
		return $this->query($sql)->fetchArray();
	}

	public function get_rrdetails($id) {
		$sql = "
		select idreceiptsdetails, a.iditemindex, a.itemcode, 
		if(a.itemcode like 'DMOG0001', particulars, itemname) itemname, 
		brandname, specifications, model, serialnos, quantity, unit, unitcost, (quantity * unitcost) amount
		from tbl_receiptsdetails a
		inner join tbl_itemindex b on b.iditemindex = a.iditemindex
		where a.active = 0 and idreceipts = '$id'";
		return $this->query($sql)->fetchAll();
	}

	public function get_issuance($id) {
		$sql = "
		select a.*, format(if(b.amount is null,0, b.amount),2) amount, fullname receivedby, issubmitted 
		from tbl_issuance a
		left outer join (select inumber, sum(quantity * unitcost) amount from tbl_issuancedetails where active = 0 group by inumber) b on b.inumber = a.inumber
		left outer join tbl_user c on c.iduser = a.iduser
		where a.active = 0 and a.idissuance = '$id'";
		return $this->query($sql)->fetchArray();
	}

	public function get_issuancedetails($id, $approved='', $filter='', $submitted='') {
		$sql = "
		select idissuancedetails, a.inumber, a.itemcode, itemname, ifnull(quantity,0) quantity, requestqty, b.unit, brandname,specifications,model,
		serialnos, approved, format(unitcost,2) unitcost, format((ifnull(quantity,0) * unitcost),2) amount,
		if(approved=0,'Pending',(if(approved=1,'Approved','Declined'))) status, b.iditemindex, tag_no
		from tbl_issuancedetails a
		left join tbl_issuance i on i.idissuance = a.idissuance
		left join tbl_itemindex b on b.iditemindex = a.iditemindex
		where a.active <> 1 and (a.idissuance = '$id' or a.inumber = '$id')";
		if(!empty($approved)) {
			$sql.=" and approved = 1 ";
		}
		if(!empty($submitted)) {
			$sql.=" and issubmitted = '$submitted' ";
		}
		$sql.="order by itemname ";
		if($filter) { $sql.=" limit 1 "; }
		return $this->query($sql);
	}

	public function UnitStatus() {
		$sql = "select distinct unitstatus from tbl_empreceiptdetails order by unitstatus asc limit 10";
		return $this->query($sql)->fetchAll();
	}

	public function get_allmrdetails($id, $filter='') {
		$sql = "select idempreceiptdetails, c.itemcode, itemname, brandname, specifications, model, a.serialnos, c.unit, a.quantity, amount, if(ifnull(unitstatus,0)=0,'Functional','Salvage') unitstatus,
		depyear, a.idreceipts, dateaquired, a.serialnos, b.rrnumber, a.idreceiptsdetails, c.iditemindex
		from tbl_empreceiptdetails a
		left join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
		left join tbl_itemindex c on c.iditemindex = a.iditemindex
		left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
		where empno = '$id' and a.active = 0
		order by itemname ";
		if($filter) { $sql.="limit 1"; }
		return $this->query($sql);
	}

	public function serialnos() {		
		$sql = "select eno from
		((select serialnos as eno from tbl_empreceiptdetails where serialnos is not null order by serialnos asc )
		union all
		( select poleno as eno from tbl_poles order by poleno asc)) e; ";
		return $this->query($sql)->fetchAll();
	}

	public function equipment_history($id) {
		$sql = "select * from x_equipment_history 
		where iditemindex = '$id'
		order by itemname asc";
		return $this->query($sql)->fetchArray();
	}

}

$item = new item();

?>