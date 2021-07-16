<?php 
include '../session.php';
header('content-Type: text/html; charset=ISO-8859-1');
$sql = "select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, ifnull(dummyqty,0) qty
from tbl_itemindex a 
where a.itemcode like '".substr($_SESSION['area'], 0,2)."%' and ( reorderpopup = 0 or reorderpopup is null )
group by a.iditemindex
having qty <= rop 
order by itemname asc";
$query = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<style type="text/css" media="print">
  @print{
    @page :footer {color: #fff}
    @page :header {color: #fff}
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

              <div class="col-sm-3 invoice-col">
                <address>
                </address>
              </div>  

              <div class="col-sm-3 invoice-col">
                <address>
                  <?= date('M d, Y')?>
                  <br>
                  <br>
                  DMO 
                </address>
              </div>  

              
              <div class="col-sm-12 invoice-col">
                <b></b>
                <address>
                  <b>List of critical Items.</b>
                </address>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th>Category</th>
                <th style="text-align: center;">Qty. Left</th>                  
                <!-- <th style="text-align: center;">Suggested Qty</th>   -->
              </tr>
            </thead>

            <tbody>
              <?php foreach ($query as $row) { ?>     
                <tr>
                  <td><?= $row['itemcode'] ?></td>
                  <td><?= $row['itemname'] ?></td>
                  <td><?= $row['category'] ?></td>
                  <td style="text-align: center;"><?= $row['qty'] ?></td>
                  <!-- <td style="text-align: center;"><?= $row['SuggestedQty'] ?></td>                   -->
                </tr>
              <?php }; ?>
            </tbody>

            <thead>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </thead>

          </table>
        </div>
      </div>

      <div class="row invoice-info" style="bottom: 0;">              
        <div class="col-sm-12">                
          <address>
            <p></p>
          </address>
        </div>
        <div class="col-sm-1 invoice-col"></div>

        <div class="col-sm-12">
          PREPARED BY:
          <address>
            <br>
            <br>
            <b><?= utf8_decode($_SESSION['user']) ?></b>              
          </address>
        </div>          
      </div>
    </section>

    <script>
     window.addEventListener("load", window.print('_blank'));
   </script>
 </body>
 </html>


