<?php
include '../session.php'; 

$area         = substr($_SESSION['area'], 0,2);

$closingdate  = $_GET['date'];

$sql = " 
select a.iditemindex, a.itemcode, a.itemname, a.category, if(a.type=0,'Non Consumable','Consumable') type, ifnull(a.reorderpoint,0) rop, a.unit, 
ifnull(dummyqty,0) qty, closingqty, actualqty, actualqty-closingqty as def,inactive
from tbl_itemindex a
where a.itemcode like '$area%'
and actualqty > 0 ";

if (isset($_GET['date'])) { $sql.=" and closingdate like '$closingdate' "; }

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
                <!-- <div class="col-sm-2">
                  Item Type: <br>                   
                </div>
                <div class="col-sm-5">
                  <b><?= $itemtype ?></b> <br>
                  <b></b> <br>
                </div>
                <address>
                  <b></b>
                </address> -->
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
                <th style="text-align: center;">Closing Qty</th>
                <th style="text-align: center;">Actual Qty</th>
                <th style="text-align: center;">Difference</th>
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
                  <td style="text-align: center;"><?= $row['closingqty'] ?></td>
                  <td style="text-align: center;"><?= $row['actualqty'] ?></td>
                  <td style="text-align: center;"><?= $row['def'] ?></td>
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


