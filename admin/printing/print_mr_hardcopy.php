<?php
include '../session.php';
include '../class/clsitems.php';

$idempreceipts    = $_GET['id'];
$mri              = $_GET['mri'];
//$dept             = $_GET['dept'];


$mrinfo           = $db->query("select * from tbl_empreceipts where idempreceipts = '$idempreceipts' limit 1")->fetch_assoc();

$sql              = "select idempreceiptdetails, c.itemcode, itemname, brandname, specifications, model, a.serialnos, a.quantity, unitcost, amount,
                    if(ifnull(unitstatus,0)=0,'Functional','Salvage') unitstatus,
                    if((if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now())) <=0, 0, (if(depyear is null,1,depyear)*12) - (TIMESTAMPDIFF(MONTH, datetransferred, now()))) *
                    (((amount * a.quantity)/if(depyear is null,1,depyear))/12) depreciation,
                    depyear, a.idreceipts, dateaquired, a.serialnos, b.rrnumber, a.idreceiptsdetails
                    from tbl_empreceiptdetails a
                    left join tbl_itemindex c on c.iditemindex = a.iditemindex
                    left join tbl_receiptsdetails b on b.idreceiptsdetails = a.idreceiptsdetails
                     where a.active=0 and a.idempreceipts = '".$idempreceipts."' ";
$rows             = $db->query($sql);

$signs            = $db->query("select * from tbl_signatory limit 1")->fetch_assoc();

if($mri == 'Employee') {
  $rcvdby         = $db->query("select distinct concat(lastname,', ',firstname,' ',middleinitial) empname, title
                    from zanecoinvphp.tbl_empreceipts a
                    inner join zanecopayroll.employee b on b.empnumber = a.empno
                    where a.empno = '".$mrinfo['empno']."' limit 1")->fetch_assoc();
}

?>

  <!DOCTYPE html>
  <html lang="en">
  <?php include 'header.php'?>
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
                    Memorandum Receipt
                    <br>
                    DMO
                  </address>
                </div>
                <div class="col-12 row">
                  <div class="col-sm-2">
                    Memo Receipt No.
                  </div>
                  <div class="col-sm-5">
                    <b><?= $mrinfo['mrnumber'] ?></b> <br>
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
                  <th width="120px">Item Code</th>
                  <th width="200px">Item Name</th>
                  <th width="200px">Brand Name</th>
                  <th width="300px">Specification</th>
                  <th width="150px">Model</th>
                  <th width="150px">Serial No.</th>
                  <th width="50px" style="text-align: right;">Qty</th>
                  <th width="100px" style="text-align: right;">Amount</th>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach($rows as $row): ?>
                <tr>
                  <td><?= $row['itemcode']; ?></td>
                  <td><?= $row['itemname']; ?></td>
                  <td><?= $row['brandname']; ?></td>
                  <td><?= $row['specifications']; ?></td>
                  <td><?= $row['model']; ?></td>
                  <td><?= $row['serialnos']; ?></td>
                  <td style="text-align: center;"><?= number_format($row['quantity'],0); ?></td>
                  <td style="text-align: right;"><?= number_format($row['amount'],2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <!-- <div class="invoice-info row" style="bottom: 0;">            
            <div class="col-sm-12">
              Note: Please present your copy of this MR to Internal Audit Department when you return this equipment/materials or else you will not be cleared.
              <address>
                <p></p>
              </address>
            </div>

            <div class="col-sm-12 row">
              <div class="col-sm-4">
                Issued by:
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
              <div class="col-sm-4"></div>
              <div class="col-sm-4">
                <?php if($mri == 'Employee') { ?>
                Received by:
                <address>
                  <br>
                  <div style="margin-right: 10px;">
                    <center>
                      <b><u><?= $rcvdby['empname'] ?></u></b><br>
                      <b><?= $rcvdby['title'] ?></b>
                    </center>
                  </div>
                </address>
              <?php } ?>
              </div>
            </div>

            <div class="col-sm-12 row">
              <div class="col-sm-4">
                Reviewed by:
                <address>
                  <br>
                  <div style="margin-right: 10px;">
                    <center>
                      <b><u><?= $signs['MRreviewedby'] ?></u></b><br>
                      <b><?= $signs['MRreviewpos'] ?></b>
                    </center>
                  </div>
                </address>
              </div>

              <div class="col-sm-4">
                <br>
                <br>
                Noted by:
                <address>
                  <br>
                  <div style="margin-right: 10px;">
                    <center>
                      <b><u><?= $signs['MRApprovedby'] ?></u></b><br>
                      <b><?= $signs['MRapprovedpos'] ?></b>
                    </center>
                  </div>
                </address>
              </div>

              <div class="col-sm-4">
                Verified by:
                <address>
                  <br>
                  <div style="margin-right: 10px;">
                    <center>
                      <b><u><?= $signs['MRverified'] ?></u></b><br>
                      <b><?= $signs['MRverifiedpos'] ?></b>
                    </center>
                  </div>
                </address>
              </div>
            </div>
          </div> -->
        </div>
      </section>

    </body>
    </html>


