<?php
include '../session.php';
// include '../class/clsitems.php';
$trans    = $_GET['id'];
if($trans == 'All') $trans = '';
$from     = $_GET['from'];
$to       = $_GET['to'];
$sql      = "select a.idissuance,idate,a.inumber,purpose,if(status=0,'Pending',if(status=1,'Approved','Canceled')) status, rvno,requister,
if(b.amount is null,0, b.amount) amount 
from tbl_issuance a
left outer join (select idissuance, inumber, sum(quantity * unitcost) amount from tbl_issuancedetails where active=0 group by inumber) b 
on b.idissuance = a.idissuance 
where idate between cast('$from' as date) and cast('$to' as date)
and a.inumber like '$trans%' 
";
$rows = $db->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<style type="text/css" media="print"> 

  @page { size: landscape; }

  body { height: 850px; width: 1400px; }

</style>


<input type="hidden" id="mrno"       name="mrno"      value="<?= $mrinfo['idempreceipts'] ?>">

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
                  Issuance
                  <br>
                  DMO
                </address>
              </div>
              <div class="col-12 row">
                <div class="col-sm-2">
                  <!-- Memo Receipt No. -->
                </div>
                <div class="col-sm-5">
                  <!-- <b><?= $mrinfo['mrnumber'] ?></b> <br> -->
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
        <div class="col-sm-12 table-responsive table-stripe">
          <table class="table">
            <thead>
              <tr>
                <th width="120px">Date</th>
                <th width="150px">MRV No.</th>
                <th width="150px">Issuance No.</th>
                <th>Purpose</th>                                
                <th>Requisitioner</th>

              </tr>
            </thead>
            <tbody>
              <?php
              foreach($rows as $row): ?>
                <tr>
                  <td><?= date('M d, Y', strtotime( $row["idate"] )); ?></td>
                  <td><?= $row['rvno']; ?></td>
                  <td><?= $row['inumber']; ?></td>
                  <td><?= utf8_decode($row['purpose']); ?></td>                                    
                  <td><?= utf8_decode($row["requister"]); ?></td>                  
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">          
      </div>
    </section>
    <script>
     window.addEventListener("load", window.print());
   </script>
 </body>
 </html>


