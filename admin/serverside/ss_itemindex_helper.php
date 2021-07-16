<?php

$db->multi_query("
	DROP TEMPORARY table if EXISTS dummy;

	CREATE TEMPORARY TABLE dummy as (
	select iditemindex, sum(inqty) - sum(outqty) qty, average
	from
	(
	(
	    select ii.iditemindex, ifnull(actualqty,0) actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, sum(ifnull(rd.unitcost,0))/sum(ifnull(rd.quantity,0)) as average
			from tbl_receipts r
			right join tbl_receiptsdetails rd on rd.idreceipts = r.idreceipts
	    right join tbl_itemindex ii on ii.itemcode = rd.itemcode
			where rd.active = 0 and receivedate >= closingdate
			group by rd.iditemindex
	 )
	 union all
	 (
	    select ii.iditemindex, ifnull(actualqty,0) actualqty, sum(ifnull(rd.quantity,0)) as inqty, 0 as outqty, 0
			from tbl_returns r
			left join tbl_returnsdetails rd on rd.returnedno = r.returnedno
	    right join tbl_itemindex ii on ii.itemcode = rd.itemcode
			where rd.active = 0 and returneddate >= closingdate
			group by iditemindex
	 )
	 union all
	 (
	    select ii.iditemindex, ifnull(actualqty,0) actualqty, 0 as inqty, sum(ifnull(rd.quantity,0)) as outqty, 0
			from tbl_empreceipts r
			left join tbl_empreceiptdetails rd on rd.idempreceipts = r.idempreceipts
	    right join tbl_itemindex ii on ii.itemcode = rd.itemcode
			where rd.active = 0 and datereceived >= closingdate
			group by rd.iditemindex
	 )
	union all
	 (
	    select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
			from tbl_issuance r
			left join tbl_issuancedetails rd on rd.idissuance = r.idissuance
	    right join tbl_itemindex ii on ii.itemcode = rd.itemcode
			where rd.active = 0 and idate >= closingdate
			group by rd.iditemindex
	 )
	union all
	 (
	    select ii.iditemindex, ifnull(actualqty,0) actualqty, 0, sum(ifnull(rd.quantity,0)) as outqty, 0
			from tbl_returntosupplier r
			right join tbl_returntosupplierdetails rd on rd.returnedno = r.returnedno
	    right join tbl_itemindex ii on ii.itemcode = rd.itemcode
			where rd.active = 0 and returneddate >= closingdate
			group by iditemindex
	 )
	 ) b
	 group by iditemindex);

	update tbl_itemindex a
	left join dummy b on b.iditemindex = a.iditemindex
    set dummyqty = actualqty + ifnull(b.qty,0),
   		dummyave =
    cast(
      (if(a.average is null,(if(a.average=0,1,a.average)),a.average) +
       if(b.average is null,(if(b.average=0,1,b.average)),a.average)) / 2 as decimal);

    update tbl_itemindex set dummyave = ifnull(dummyave,average); ");

//cast(ifnull(b.average, 0) as decimal



?>