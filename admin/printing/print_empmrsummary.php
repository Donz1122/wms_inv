<?php
include '../session.php';
$empno  = $_GET['id'];
$mri    = $_GET['mri'];
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'?>

<style type="text/css" media="print">
  @print{
    @page :footer { color: transparent; }
    @page :header { color: transparent; }
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
                  MR Summary
                  <br>
                  DMO
                </address>
              </div>

              <?php if ($mri == "Employee") {
                $mrname = $db->query("select concat(lastname,', ',firstname,' ',middleinitial) empname, title
                  from zanecoinvphp.tbl_empreceipts a
                  inner join zanecopayroll.employee b on b.empnumber = a.empno
                  where a.empno = '".$empno."'")->fetch_assoc();

              } else {
                $mrname = $db->query("select deptcode, deptname from tbl_department
                  where deptcode = '".$empno."'")->fetch_assoc();
              }
              ?>
              <div class="col-12 row">
                <div class="col-sm-2">
                  <?php if ($mri == "Employee") { ?>
                    Employee: <br>
                    Designation
                  <?php } else { ?>
                    Department: <br>                    
                  <?php } ?>
                  </div>
                  <div class="col-sm-5">
                    <?php if ($mri == "Employee") { ?>
                      <b><?= $mrname['empname'] ?></b> <br>
                      <b><?= $mrname['title'] ?></b> <br>
                    <?php } else { ?>
                      <b><?= $mrname['deptname'] ?></b> <br>                      
                    <?php } ?>
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
                  <th>MR Date</th>
                  <th>MR Number</th>
                  <th>Description</th>
                  <th style="text-align: center;">Qty</th>
                  <th>Unit</th>
                  <th style="text-align: center;">Amount</th>
                </tr>
              </thead>

              <tbody>

                <?php
                $sqlquery = "select a.idempreceipts, a.empno, a.mrnumber, amount, datereceived, itemname, quantity, c.unit
                from zanecoinvphp.tbl_empreceipts a
                left outer join (select idempreceipts, quantity, (unitcost*quantity) amount, iditemindex from zanecoinvphp.tbl_empreceiptdetails where active = 0 group by idempreceipts) b on b.idempreceipts
                = a.idempreceipts
                left outer join (select iditemindex, itemname, unit from tbl_itemindex) c on c.iditemindex = b.iditemindex
                where a.active = 0 and a.empno = '".$empno."' and quantity is not null
                order by datereceived desc;";
                $rows = $db->query($sqlquery);
                while($row = $rows->fetch_assoc()){ ?>
                  <tr>
                    <td><?= date('M d, Y', strtotime($row['datereceived'])) ?></td>
                    <td><?= $row['mrnumber'] ?></td>
                    <td><?= $row['itemname'] ?></td>
                    <td style="text-align: center;"><?= $row['quantity'] ?></td>
                    <td><?= $row['unit'] ?></td>
                    <td style="text-align: right;"><?= number_format($row['amount'],2) ?></td>
                  </tr>
                <?php }; ?>
              </tbody>
              <?php
              $totalmr = $db->query("select sum(amount) amount
                from zanecoinvphp.tbl_empreceipts a
                left outer join (select idempreceipts, (quantity*unitcost) amount from zanecoinvphp.tbl_empreceiptdetails where active = 0 group by idempreceipts) b on b.idempreceipts = a.idempreceipts
                where a.active = 0 and a.empno = '".$empno."'")->fetch_assoc();?>
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th style="text-align: right;"><?= number_format($totalmr['amount'],2)?></th>
                  </tr>
                </thead>

              </table>
            </div>
          </div>


        </section>

        <script>
          window.addEventListener("load", window.print());
        </script>
      </body>
      </html>


