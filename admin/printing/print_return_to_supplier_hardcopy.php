<?php
include '../session.php';
include '../class/clsreturns.php';



if(isset($_GET['id'])) {
  $idreceipts = $_GET['id'];
  $returnedno = $_GET['no'];
  $query = $returns->all_items_return_to_suppliers($idreceipts);
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<style type="text/css" media="print">
  @print{
    @page :footer {color: transparent;}
    @page :header {color: transparent;}
  }
</style>

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

              <div class="col-sm-4 invoice-col row" style="font-size: 14px;">
                <div class="col-sm-1.5">
                </div>
                <div class="col">
                </div>
              </div>

              <div class="col-sm-2 invoice-col">
                <address>
                  <?= date('M d, Y')?>
                  <br>
                  Return Receipts
                  <br>
                  <?= $_SESSION['area']; ?>
                  <br>
                  <br>

                </address>
              </div>


              <div class="col-12 row">
                <div class="col-sm-1">
                  Particulars: <br>
                  Control No.:
                </div>
                <div class="col-sm-5">
                  <b><?= $query['particulars']; ?></b> <br>
                  <b><?= $query['returnedno']; ?></b> <br>
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
                <th>Brand Name</th>
                <th>Specification</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Cost/Unit</th>
                <th style="text-align: right;">Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $rows = $returns->all_items_return_to_suppliers_details($returnedno);
              foreach($rows as $row): ?>
                <tr>
                  <td><?= $row['itemcode']; ?></td>
                  <td><?= $row['itemname']; ?></td>
                  <td><?= $row['brandname']; ?></td>
                  <td><?= $row['specifications']; ?></td>
                  <td style="text-align: center;"><?= number_format($row['quantity'],0); ?></td>
                  <td style="text-align: right;"><?= number_format($row['unitcost'],2); ?></td>
                  <td style="text-align: right;"><?= number_format($row['amount'],2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>

            <thead>
              <?php $sqlquery = "select sum(quantity * unitcost) amount
              from tbl_receiptsdetails a
              where a.active = 0 and idreceipts = '$idreceipts'";
              $rows = $db->query($sqlquery)->fetch_assoc();?>
             <!--  <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: right;">Amount</th>
                <th style="text-align: right;"><?= number_format($rows['amount'],2) ?></th>
              </tr> -->
              <!-- <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: right;">VAT</th>
                <th style="text-align: right;"><?= number_format($rows['amount'] * 0.12,2) ?></th>
              </tr> -->
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: right;"><?= number_format($query['amount'],2) ?></th>
              </tr>
            </thead>

          </table>
        </div>
      </div>

      <?php
      $sqlquery = "select * from tbl_signatory";
      $signs = $db->query($sqlquery)->fetch_assoc();
      ?>
      <div class="row">
        <div class="col-sm-12 row" style="position: fixed; bottom: 0;">
          <div class="col-sm-4">
            Received by:
            <address>
              <br>
              <div style="margin-right: 10px;">
                <center>
                  <b><u><?= utf8_decode($_SESSION['user']) ?></u></b><br>
                  <b><?= $_SESSION['position'] ?></b>
                </center>
              </div>
            </address>
          </div>

          <div class="col-sm-4 invoice-col">
            <br>
            <br>
            Noted by:
            <address>
              <br>
              <div style="margin-right: 10px;">
                <center>
                  <b><u><?= $signs['RRNotedby'] ?></u></b><br>
                  <b><?= $signs['RRNotedPos'] ?></b>
                </center>
              </div>
            </address>
          </div>

          <div class="col-sm-4 invoice-col">
            Verified by:
            <address>
              <br>
              <div style="margin-right: 10px;">
                <center>
                  <b><u> </u></b><br>
                  <b></b>
                </center>
              </div>
            </address>
          </div>

        </div>
      </div>
    </section>

    <script>
      //window.addEventListener("load", window.print());
    </script>
  </body>
  </html>


