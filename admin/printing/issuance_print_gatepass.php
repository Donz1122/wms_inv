<?php
include '../session.php';

if (isset($_GET['ino'])) {
  $ino = $_GET['ino'];
  $doc = $_GET['doc'];
  $sql = "select a.*, if(b.amount is null,0, b.amount) amount, fullname receivedby from tbl_issuance a
  left outer join (select idissuance, inumber, sum(quantity * unitcost) amount from tbl_issuancedetails group by inumber) b 
  on b.idissuance = a.idissuance
  left outer join tbl_user c on c.iduser = a.iduser
  where a.idissuance = '$ino'
  order by inumber asc;";
  $query = $db->query($sql)->fetch_assoc();

  /*$sql = "select idissuancedetails, a.inumber, a.itemindexcode, itemname, quantity, a.unit, 
  unitcost, (quantity * unitcost) amount
  from tbl_issuancedetails a
  inner join tbl_itemindex b on b.iditemindex = a.iditemindex
  inner join tbl_items c on c.iditems = b.iditems
  where a.idissuance = '$idissuance'
  order by itemname";
  $rows = $db->query($sql);*/
  $sql = "select if(max(counter) is null,0,max(counter))+1 cnt from tbl_gatepass order by counter asc limit 1";
  $counter = $db->query($sql)->fetch_assoc();

}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>

<style type="text/css" media="print"> 

  @media print {    
    @page {
      size: 8.5in 5.5in; /*297 - a4 height*/
      size: portrait;
    }
  }

</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

  <!--section class="page-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Requisition Voucher</h1>
        </div>
      </div>
    </div>
  </section-->

  <section class="invoice">

    <div class="row invoice-info">
      <div class="col-12">
        <div class="invoice p-3 mb-3">
          <div class="row">

            <div class="col-2 row">
              <img src="../../dist/img/logo2-1.2x1.2.png" alt="ZANECO">
            </div>
            <div class="col-sm-4 invoice-col" style="border-left: -10px;">
              <b>Zamboanga del Norte Electric Coop., Inc.</b>
              <address>
                <strong>ZANECO</strong><br> 
                General Luna St., Dipolog City, <br>
                Zamboanga del Norte<br>
              </address>
            </div>

            <div class="col-sm-3 invoice-col" style="text-align: right;">
              <!-- <b>Gate Pass No.: DMO<?= str_pad($counter['cnt'], 6, "0", STR_PAD_LEFT); ?></b> -->
              <address>
                <?=
                date('M d, Y') ?><br>
              </address>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <h2 style="text-align: center;"><b>GATE PASS</b></h2>
            </div>
          </div>
          <div class="row">

            <div class="col-12">
              To: <b>SECURITY GUARD ON DUTY</b>
              <address>
                <br>
                <b><?= utf8_decode('      ') ?></b>Please check and allow <b><u><?= strtoupper($query['requister']) ?></u></b> to bring outside the company premise
                the item(s) listed under attached document as follows:
              </address>
            </div>
          </div>
          <br><br>
          <div class="row">
            <div class="col-12" style="text-align: center;">
              <span>DOCUMENT NUMBER:</span>
              <h2><b><u><?= $doc ?></u></b></h2>
            </div>
          </div>

        </div>
      </div>
    </div>


    <br>

    <div class="row">
      <?php $signs = $db->query("select * from tbl_signatory limit 1")->fetch_assoc(); ?>
      <div class="col-sm-4 invoice-col">
        PREPARED BY:
        <center>
          <address>
            <br>
            <u><?=  strtoupper(utf8_decode($_SESSION['user'])) ?></u><br>
            Warehouseman
          </address> 
        </center>       
      </div>
      <div class="col-sm-4 invoice-col">
        VERIFIED BY:
        <center>
          <address>
            <br>
            <u><?= $signs['GPApproved']?></u><br>
            <?= $signs['GPAppPos']?>
          </address>
        </center>
      </div>
      <div class="col-sm-3 invoice-col">
        APPROVED BY:
        <center>
          <address>
            <br>
            <u><?= $signs['GPVerified']?></u><br>
            <?= $signs['GPVerPos']?>
          </address>
        </center>
      </div>
    </div>

    

    <script>
      window.addEventListener("load", window.print());
    //var popupWin = window.open('', '_blank', 'height=850, width=1100');
    //popupWin.document.open();
    //popupWin.document.write('<html> <body onload="window.print()">'+ newstr + '</html>' + footer);
    //popupWin.document.close(); 
  </script>
</body>
</html>


