<?php

// reorder count
$sql = "select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, ifnull(dummyqty,0) qty
from tbl_itemindex a 
where a.itemcode like '".substr($_SESSION['area'], 0,2)."%' and (reorderpopup = 0 or reorderpopup is null)
group by a.iditemindex
having qty <= rop ";
$reordercount = $db->query($sql);
$_SESSION['reordercount'] = mysqli_num_rows($reordercount); 

// under warranty count
$sql = "select idreceiptsdetails, a.iditemindex, a.itemcode, itemname, brandname, specifications, model, serialnos, quantity, unit, unitcost, (quantity * unitcost) amount, warranty
from tbl_receiptsdetails a
inner join tbl_itemindex b on b.iditemindex = a.iditemindex
where a.active = 0
and year(warranty) = year(now()) ";
$warranty = $db->query($sql);
$_SESSION['expirethisyear'] = mysqli_num_rows($warranty); 

// pending request count
$sql = "select a.idissuance,idate,a.inumber,purpose,if(status=0,(if(issubmitted=0,'Not Submitted', 'Pending')),if(status=1,'Approved','Cancelled')) status, rvno, a.requister,if(b.amount is null,0, b.amount) amount from tbl_issuance a
left outer join (select idissuance, inumber, sum(quantity * unitcost) amount from tbl_issuancedetails where active=0 group by inumber) b on b.idissuance = a.idissuance
inner join tbl_user u on u.iduser = a.iduser
where a.active = 0 and status = 0;";
$request = $db->query($sql);
$_SESSION['request'] = mysqli_num_rows($request); 

?>

