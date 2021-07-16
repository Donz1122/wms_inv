<?php 
header('content-Type: text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html>
<html lang="en">
<?php 
include 'header.php';
include '../session.php';
include '../class/clsitems.php';

if(isset($_GET['id'])) {
  $idissuance = $_GET['id'];
  $query = $item->get_issuance($idissuance);
}
?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <section class="invoice" style="size: 8.5in 11in; size: portrait;">

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
                  JO Number: <?= $query['jono']; ?><br>
                  RV Number: <?= $query['rvno']; ?><br>
                  Date Issued: <?= $query['idate']; ?>
                </address>
              </div>
              <div class="col-sm-3 invoice-col">
                <address>
                  <?= date('M d, Y')?><br>
                  Material Transfer Ticket <br>
                  <?= $_SESSION['area']; ?>
                </address>
              </div>


              <div class="col-sm-2 invoice-col">
                MCT/MTT Number: <b></b>
                <address>
                  Description: <b></b>
                </address>
              </div>
              <div class="col-sm-10 invoice-col">
                <b><?= $query['inumber']?></b>
                <address>
                  <b><?= $query['purpose']?></b>
                </address>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 table-responsive ">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th style="text-align: right;">Qty</th>                
              </tr>
            </thead>

            <tbody>
              <?php 
              $rows = $item->get_issuancedetails($idissuance,'approved')->fetchAll();
              foreach($rows as $row): ?>
                <tr>
                  <td><?= $row['itemcode'] ?></td>
                  <td><?= $row['itemname'] ?></td>
                  <td style="text-align: right;"><?= $row['quantity'] ?></td>                  
                </tr>
              <?php endforeach; ?>
            </tbody>

          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">          
        </div>
      </div>

      <div class="row">
        <div class="col-12 row" style="position: fixed; bottom: 0;">          
          <div class="col-sm-4 invoice-col">
            <?php $signs = $db->query("select * from tbl_signatory limit 1")->fetch_assoc(); ?>
            REQUISTED BY:
            <div style="margin-right: 10px;">
              <center>
                <address><br> 
                  <?= utf8_decode($query['receivedby']) ?>
                </address>
              </center>
            </div>
          </div>
          <div class="col-sm-4 invoice-col">
            ISSUED BY:
            <div style="margin-right: 10px;">
              <center>
                <address><br>
                  <?= utf8_decode($_SESSION['user']) ?>
                </address>
              </center>
            </div>
          </div>
          <div class="col-sm-4 invoice-col">
            RECEIVED BY:
            <div style="margin-right: 10px;">
              <center>
                <address><br>
                  <?= utf8_decode($query['receivedby']) ?>
                </address>
              </center>
            </div>
          </div>
          
        </div>
      </div>
    </section>

    <?php //include 'footer.php'?>
    <script>
     window.addEventListener("load", window.print());
   </script>

 </body>
 </html>


