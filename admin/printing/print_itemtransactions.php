<?php
include '../session.php';
try {
  require "../class/clsitems.php";
}
catch (Excemption $ex) {};

$iditemindex = $_GET['id'];
$query = $item->search_itemindex_summary($iditemindex, 0);

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
                  Transaction History
                  <br>
                </address>
              </div>
              <div class="col-12 row">
                <div class="col-sm-2">
                  Item Name: <br>
                  Item Code: <br>
                </div>
                <div class="col-sm-5">
                  <b><?=$query['itemname'];?></b> <br>
                  <b><?=$query['itemcode'];?></b> <br>
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
          <table class="table text-sm">
            <thead>
              <tr>
                <th style="display: none;"></th>
                <th>Date</th>
                <th>Document No.</th>
                <th>Particulars</th>
                <th style="text-align: center;">In</th>
                <th style="text-align: center;">Out</th>
                <th>Unit</th>
              </tr>
            </thead>
            <tbody>
              <?php $rows = $item->search_itemindex_details($iditemindex);
              foreach($rows as $row): ?>
                <tr align="center">
                  <td style="display: none;"><?= $row['id'] ?></td>
                  <td align="left"><?= $row['transactiondate']; ?></td>
                  <td align="left"><?= $row['docno']; ?></td>
                  <td align="left"><?= utf8_decode($row['particulars']); ?></td>
                  <td><?= $row['inqty']; ?></td>
                  <td><?= $row['outqty']; ?></td>
                  <td align="left"><?= $row['unit']; ?></td>
                </tr>
              <?php endforeach; ?>
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


