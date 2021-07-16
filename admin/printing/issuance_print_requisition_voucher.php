<?php 
include '../session.php';
include '../class/clsitems.php';

$idissuance = $_GET['id'];

$query = $item->get_issuance($idissuance);

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
                <br><br>
              </div>
              <div class="col-sm-4 invoice-col">
                <b>Zamboanga del Norte Electric Coop., Inc.</b>
                <address>
                  <strong>ZANECO</strong><br>
                  General Luna St., Dipolog City, <br>
                  Zamboanga del Norte<br>
                </address>
              </div>
              <div class="col-sm-2 invoice-col">
                <address>
                </address>
              </div>  
              <div class="col-sm-4 invoice-col">
                <address>
                  <?= date('M d, Y') ?><br>
                  Material/Supplies Request Voucher <br>
                  <?= $_SESSION['area'] ?> 
                </address>
                <b><?= $query['rvno'] ?></b>
              </div>                
              <div class="col-sm-12 row">
                <div class="col-sm-1 ">To: </div>
                <div class="col">
                  <b>The General Manager</b><br>              
                  Please furnish the following materials / supplies for:
                </div>
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
                <th>Item Code</th>
                <th>Description</th>
                <th>Qty</th>                    
              </tr>
            </thead>

            <tbody>

              <?php 
              $rows = $item->get_issuancedetails($idissuance,'approved','')->fetchAll();
              foreach($rows as $row): ?>
                <tr>
                  <td><?= $row['itemcode'] ?></td>
                  <td><?= $row['itemname'] ?></td>
                  <td><?= $row['quantity'] ?></td>                      
                </tr>
              <?php endforeach; ?>
            </tbody>             

          </table>
        </div>
      </div>

      <div class="row invoice-info" style="position: fixed; bottom: 0;">              
        <div class="col-sm-12">                
          <address>
            <p></p>I HEREBY CERTIFY the the materials / supplies requisition above are necessary and will be used solely for the purpose stated above.
          </address>
        </div>        
        <div class="col-sm-1 invoice-col"></div>
        <div class="col-sm-3 invoice-col">
          <?php
          $signs = $db->query("select * from tbl_signatory limit 1")->fetch_assoc();  
          ?>
          REQUESTED BY:
          <div style="margin-right: 10px;">
            <center>
              <address>
                <br>
                <u><?= utf8_decode($query['receivedby']) ?></u><br>
                Requester
              </address>

            </center>
          </div>
        </div>  
        <div class="col-sm-3 invoice-col">
          RECOMMENDED BY:
          <div style="margin-right: 10px;">
            <center>
              <address>
                <br>
                <u><?= $signs['MCTNotedby'] ?></u><br>
              <?= $signs['MCTNotedPos'] ?>
              </address>
            </center>
          </div>
        </div>  
        <div class="col-sm-3 invoice-col">
          APPROVED BY:
          <div style="margin-right: 10px;">
            <center>
              <address>
                <br>
                <u><?= $signs['MCTApproved'] ?></u><br>
              <?= $signs['MCTApprovedPos'] ?>
              </address>
            </center>
          </div>
        </div>        
      </div>
    </section>
    <script>
     window.addEventListener("load", window.print());
   </script>
 </body>
 </html>


