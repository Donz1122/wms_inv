<?php

if (!file_exists('../interface/ireturns.php')) {
	require 'interface/ireturns.php';
}
else {
	require '../interface/ireturns.php';
}

class returns extends dbs implements ireturns {

  public function employee() {
    $sql = "SELECT empnumber, concat(lastname,', ',firstname,' ',middleinitial) empname, title
    FROM zanecopayroll.employee WHERE CONCAT(lastname,', ',firstname,' ',middleinitial) IS NOT NULL ORDER BY empname;";
    return $this->query($sql)->fetchAll();
  }

  public function all_items_return_to_suppliers($idreturns='') {
    $addons = "";
    if(!empty($idreturns))
      $addons = " AND a.idreturns = '$idreturns' LIMIT 1 ";
    else
      $addons = " GROUP BY idreturns ";
    $sql = "SELECT a.*, concat(lastname,', ',firstname,' ',middleinitial) empname, empno, ifnull(d.unitcost,0) unitcost, sum(ifnull(d.unitcost,0))/count(d.iditemindex) average, (sum(ifnull(d.unitcost,0))/count(d.iditemindex) * sum(b.quantity)) amount
    FROM tbl_returntosupplier a
    LEFT JOIN tbl_returntosupplierdetails b ON b.returnedno = a.returnedno
    LEFT JOIN zanecopayroll.employee c ON c.empnumber = a.empno
    LEFT JOIN tbl_receiptsdetails d ON d.rrnumber = b.linkrefno
    WHERE a.active = 0 $addons;";
    if(!empty($idreturns))
      return $this->query($sql)->fetchArray();
    else
      return $this->query($sql)->fetchAll();
  }

  public function all_items_return_to_suppliers_details($returnedno='', $limit='') {
    $addons = "";
    if (!empty($returnedno)) {
      $addons = " and returnedno = '$returnedno' ";
    }
    $sql = "SELECT idreturnsdetails, returnedno, a.itemcode, itemname, brandname, model, specifications, a.serialnos, a.quantity, b.unit, ifnull(c.unitcost,0) unitcost, (ifnull(a.quantity,0) * c.unitcost) amount, remarks
    FROM tbl_returntosupplierdetails a
    LEFT JOIN tbl_itemindex b ON b.itemcode = a.itemcode AND b.active = 0
    LEFT JOIN tbl_receiptsdetails c ON c.rrnumber = a.linkrefno
    WHERE a.active = 0 $addons
    ORDER BY itemname ";
    if (!empty($limit)) {
      $sql.="limit 1";
      return $this->query($sql)->fetchArray();
    } else {
      return $this->query($sql)->fetchAll();
    }
  }

  public function all_items_return_to_warehouse($returnedno='',$limit='') {
    $addons = "";
    if (!empty($returnedno)) {
      $addons = " and a.returnedno = '$returnedno' ";
    }
    $sql = "select a.*, if(empno is not null, concat(lastname,', ',firstname,' ',middleinitial), suppliername) empname,
    sum(if(b.quantity is null,0,b.quantity)) qty, amount
    from tbl_returns a
    left outer join tbl_returnsdetails b on b.returnedno = a.returnedno and b.active = 0
    left outer join (select itemcode, dummyave as amount from tbl_itemindex where active = 0) c on c.itemcode = b.itemcode
    left outer join zanecopayroll.employee d on d.empnumber = a.empno
    left outer join tbl_supplier s on s.suppliercode = a.suppliercode
    where a.active = 0 $addons
    group by returnedno ";
    if (!empty($limit)) {
      $sql.="limit 1";
      return $this->query($sql)->fetchArray();
    } else {
      return $this->query($sql)->fetchAll();
    }
  }

  public function all_items_return_to_warehouse_details($returnedno='',$limit='') {
    $addons = "";
    if (!empty($returnedno)) {
      $addons = " and returnedno = '$returnedno' ";
    }
    $sql = "select idreturnsdetails, returnedno, a.itemcode, itemname, quantity, b.unit,
    format(unitcost,2) unitcost, format((quantity * unitcost),2) as amount
    from tbl_returnsdetails a
    inner join tbl_itemindex b on b.itemcode = a.itemcode and b.active = 0
    where a.active = 0 $addons
    order by itemname ";
    if (!empty($limit)) {
      $sql.="limit 1";
      return $this->query($sql)->fetchArray();
    } else {
      return $this->query($sql)->fetchAll();
    }
  }

  public function get_mr_and_issuance_of_employee($empno, $limit='') {
    $sql = "
    select id, docno, iditemindex, itemcode, itemname, brandname, specifications, model, serialnos, quantity, unit, amount
    from
    ((select idissuancedetails id, a.inumber docno, b.iditemindex, a.itemcode, itemname, brandname, specifications, model, serialnos, quantity, b.unit, format((quantity * unitcost),2) amount
    from tbl_issuancedetails a
    left join tbl_issuance i on i.idissuance = a.idissuance
    left join tbl_itemindex b on b.iditemindex = a.iditemindex
    where (a.active = 0 and i.active = 0) and empno = '$empno'
    and approved = 1 order by itemname)
    union all
    (select idempreceiptdetails id, e.mrnumber docno, c.iditemindex, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, c.unit, b.unitcost amount
    from tbl_empreceiptdetails a
    left join tbl_empreceipts e on e.idempreceipts = a.idempreceipts
    left join tbl_itemindex c on c.iditemindex = a.iditemindex
    left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
    where empno = '$empno' and (a.active = 0 and e.active = 0)
    order by itemname)) as items
    order by itemname asc ";
    if (!empty($limit)) {
      $sql.="limit 1";
      return $this->query($sql)->fetchArray();
    } else {
      return $this->query($sql)->fetchAll();
    }
  }

  public function get_items_from_supplier($suppliercode, $limit='') {
    $sql = "
    select idreturnsdetails id, suppliercode, rs.returnedno docno, rsd.itemcode, ii.iditemindex, itemname, unit, quantity, unitcost
    from tbl_returntosupplier rs
    left join tbl_returntosupplierdetails rsd on rsd.returnedno = rs.returnedno and rsd.isreturned = 0
    left join tbl_itemindex ii on ii.itemcode = rsd.itemcode
    where rsd.active = 0 and trim(suppliercode) like trim('$suppliercode')

    order by itemname ";
    if (!empty($limit)) {
      $sql.="limit 1";
      return $this->query($sql)->fetchArray();
    } else {
      return $this->query($sql)->fetchAll();
    }
  }

}
$returns = new returns();
?>