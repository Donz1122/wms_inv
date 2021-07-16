<?php
include '../session.php'; 

$area       = substr($_SESSION['area'], 0,2);

$type       = $_GET['type'];
$zero       = $_GET['zero'];

$datefrom   = "";
$dateto     = "";
if (isset($_GET['datefrom'])) {
  $datefrom = $_GET['datefrom'];
  $dateto   = $_GET['dateto'];
} 

$sql = " 
select a.iditemindex, a.itemcode,transactiondate, itemname, category, a.unit, type, if(reorderpoint is null,0,reorderpoint) rop, sum(ifnull(b.quantity,0)) qin,  sum(ifnull(b.quantityout,0)) qout,
 ifnull(actualqty,0) + sum(ifnull(b.quantity,0)) - sum(ifnull(b.quantityout,0)) qty, a.unit, format(sum(ifnull(averagecost,0)),2) averagecost, a.active, sum(ifnull(b.quantity,0)) suggestedqty
from tbl_itemindex a
left join
(select iditemindex, receivedate transactiondate, sum(if(quantity is null,0,quantity)) quantity, '0' as quantityout, sum(if(unitcost is null,0,unitcost))/count(iditemindex) averagecost from tbl_receiptsdetails
left join tbl_receipts on tbl_receipts.idreceipts = tbl_receiptsdetails.idreceipts
where tbl_receipts.active = 0
group by iditemindex
union all
select iditemindex, returneddate transactiondate, sum(if(quantity is null,0,quantity)) quantity, '0' as quantityout, '0' as averagecost from tbl_returnsdetails
left outer join tbl_returns on tbl_returns.idreturns = tbl_returnsdetails.idreturns and tbl_returnsdetails.active = 0
where tbl_returns.active = 0
group by iditemindex
union all
select iditemindex, datereceived transactiondate, '0' as quantity, sum(if(quantity is null,0,quantity)) quantityout, '0' as averagecost from tbl_empreceiptdetails
left outer join tbl_empreceipts on tbl_empreceipts.idempreceipts = tbl_empreceiptdetails.idempreceipts and tbl_empreceiptdetails.active = 0
where tbl_empreceipts.active = 0
group by tbl_empreceipts.idempreceipts
union all
select iditemindex, idate transactiondate, '0' as quantity, sum(if(quantity is null,0,quantity)) quantityout, '0' as averagecost from tbl_issuancedetails
left outer join tbl_issuance on tbl_issuance.idissuance = tbl_issuancedetails.idissuance and tbl_issuancedetails.active = 0
where tbl_issuance.active = 0
group by iditemindex
union all
select iditemindex, returneddate transactiondate, '0' as quantity, sum(if(quantity is null,0,quantity)) quantityout, '0' as averagecost from tbl_returntosupplierdetails
left outer join tbl_returntosupplier on tbl_returntosupplier.idreturns = tbl_returntosupplierdetails.idreturns and tbl_returntosupplierdetails.active = 0
where tbl_returntosupplier.active = 0
group by iditemindex) b on b.iditemindex = a.iditemindex 
where type = '$type' and a.itemcode like '".$area."%'";

if (isset($_GET['datefrom'])) { $sql.=" and transactiondate between '$datefrom' and '$dateto' "; }

$sql.=" group by iditemindex ";
if($zero == 'on') {$sql.=" having qty > 0 "; }
$sql.=" order by itemname asc ";

$itemtype = "";
if(intval($type)== 0) { $itemtype = "Non Consumable"; } else { $itemtype = "Consumable"; }

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper ">
    <section class="invoice Section1">

      <div class="row invoice-info">
        <div class="col-12">
          <div class="invoice p-3 mb-3">
            <div class="row">
              <div class="col-sm-2 invoice-col">
                <img src="../../dist/img/logo2-1.2x1.2.png" alt="ZANECO">
                <br>
                <br>
              </div>
              <div class="col-sm-4 invoice-col">
                <b>Zamboanga del Norte Electric Coop., Inc.</b>
                <address>
                  <strong>ZANECO</strong><br>
                  General Luna St., Dipolog City, <br>
                  Zamboanga del Norte<br>
                </address>
              </div>  

              <div class="col-sm-3 invoice-col">
                <address>
                </address>
              </div>  

              <div class="col-sm-3 invoice-col">
                <address>
                  <?= date('M d, Y')?>
                  <br>
                  List of Items
                  <br>                  
                </address>
              </div>                
              <div class="col-12 row">
                <div class="col-sm-2">
                  Item Type: <br>                   
                </div>
                <div class="col-sm-5">
                  <b><?= $itemtype ?></b> <br>
                  <b></b> <br>
                </div>
                <address>
                  <b></b>
                </address>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th>Category</th>
                <th>Unit</th>                
                <th style="text-align: center;">In</th>
                <th style="text-align: center;">Out</th>
                <th style="text-align: center;">Qty</th>
                <th>Actual Qty</th>
                <th>Difference</th>
              </tr>
            </thead>
            <tbody>
              <?php             

              $query = $db->query($sql);
              while($row = $query->fetch_assoc()){ ?>     
                <tr>
                  <td><?= $row['itemcode'] ?></td>
                  <td><?= $row['itemname'] ?></td>
                  <td><?= $row['category'] ?></td>
                  <td><?= ucwords(strtolower($row['unit'])) ?></td>
                  <td style="text-align: center;"><?= $row['qin'] ?></td>
                  <td style="text-align: center;"><?= $row['qout'] ?></td>
                  <td style="text-align: center;"><?= $row['qty'] ?></td>
                  <td><u></u></td>                  
                  <td><u></u></td>                  
                </tr>
              <?php }; ?>
            </tbody>

          </table>
        </div>
      </div>

    </section>

    <script>
      
      window.addEventListener("load", window.print());

    </script>
  </body>
  </html>


