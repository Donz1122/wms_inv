<?php
include '../session.php';

// $dates = $_GET['from'];

$trans    = $_GET['id'];
if($trans == 'All') $trans = '';

$from     = $_GET['from'];
$to       = $_GET['to'];

$sql = "select idate, a.inumber, purpose, c.itemcode, itemname, quantity, b.unitcost, (quantity * b.unitcost) amount
from tbl_issuance a
inner join tbl_issuancedetails b on b.idissuance = a.idissuance
inner join tbl_itemindex c on c.iditemindex = b.iditemindex
where a.active=0 
and idate between cast('$from' as date) and cast('$to' as date) 
and a.inumber like '$trans%' 
order by idate, purpose asc";
$rows = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<style type="text/css" media="print"> 
  /*div.page {
    height: 750px;
    width: 800px;
    filter: progid: D/XImageTransform.Microsoft.BasicImage(Rotation=1);
    };*/

    @page { size: landscape; }
  body { height: 850px; width: 1400px; /*writing-mode: tb-rl; */}
/* can shrink; word wrap
</style>

<body class="hold-transition sidebar-mini text-sm">
  <div class="wrapper">

    <section class="invoice">     
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
                  <?= date('M d, Y')?><br>
                  DMO Issuance Summary<br>
                  Period Covered: <?= date('M d, Y', strtotime($from)) ?> - <?= date('M d, Y', strtotime($to)) ?>
                </address>
              </div>              
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th style="width: 120px;">Date</th>
                <th style="width: 150px;">MCT No.</th>
                <th style="width: 600px;">Particular</th>
                <th>Item Code</th>
                <th>Item Description</th>
                <th style="text-align: right">Quantity</th>
                <th></th>
                <!-- <th>Cost</th>
                <th>Amount</th> -->
              </tr>
            </thead>
            <tbody>
              <?php while($row = $rows->fetch_assoc()){ ?>
                <tr>
                  <td><?= date('M d, Y', strtotime($row['idate'])) ?></td>
                  <td><?= $row['inumber'] ?></td>
                  <td class="shrink-to-fit"><?= $row['purpose'] ?></td>
                  <td><?= $row['itemcode'] ?></td>
                  <td><?= $row['itemname'] ?></td>
                  <td style="text-align: right;"><?= $row['quantity'] ?></td>
                  <td></td>
                  <!-- <td style="text-align: right;"><?= number_format($row['unitcost'],2) ?></td>
                  <td style="text-align: right;"><?= number_format($row['amount'],2) ?></td> -->
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



