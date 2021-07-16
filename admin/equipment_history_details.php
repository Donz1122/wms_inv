<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/header.php';
include 'class/clsitems.php';
include 'includes/alertmsg.php';
?>

<!-- <link rel="stylesheet" href="../dist/css/selectdatatables/datatables.min.css"> -->

<style type="text/css">

  .gray   { color: gray;    }
  .red    { color: red;     }
  .orange { color: orange;  }
  .green  { color: green;   }

</style>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php

    $id     = $_GET['id'];
    $query  = $item->equipment_history($id);

    //'iditemindex', 'itemcode', 'itemname', 'tag_no', 'parts'
    ?>

    <!-- <input type="hidden" id="idissuance"  name="idissuance" value="<?= $idissuance ?>">
    <input type="hidden" id="doctype"     name="doctype"    value="<?= $a ?>">
    <input type="hidden" id="inumber"     name="inumber"    value="<?= $query['inumber'] ?>">
  -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-3">
            <h4><i style="font-size: 18px;"> Equipment Replacement Details</i></h4>
          </div>

          <div class="ml-auto">

            <a href="equipment_history.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>

          </div>
        </div>
      </div>

      <section class="content">
        <div class="row">
          <div class="col-sm-3">
            <div class="card card-primary card-outline">
                <!-- <div class="card-header">
                  <h5>Status - Approved</h5>
                </div> -->
                <div class="card-body">
                  <strong><i class="fas fa-calendar-alt mr-1"></i>Equipment</strong>
                  <p class="text-muted float-right"><?= $query['itemname'] ?></p>
                  <hr>
                  <strong><i class="fas fa-server mr-1"></i>Serial No.</strong>
                  <p class="text-muted float-right"><?= $query['tag_no'] ?></p>
                </div>
              </div>
            </div>

            <div class="col">
              <section class="content">
                <div class="card card-warning card-outline">
                  <div class="card-body">
                    <table id="table2" class="table table-nowrap table-sm">
                      <thead>
                        <tr>
                          <th style="width: 100px;">Date</th>
                          <th>Item Name</th>
                          <th style="text-align: right;">Qty.</th>
                          <th>Unit</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $strquery = $db->query("
                          select id.requestdate, ii2.iditemindex, ii2.itemcode, ii2.itemname, id.quantity, ii2.unit from tbl_itemindex ii
                          left join tbl_empreceiptdetails erd on erd.iditemindex = ii.iditemindex
                          left join tbl_issuancedetails id on id.tag_no = erd.serialnos and approved = 1
                          left join tbl_itemindex ii2 on ii2.iditemindex = id.iditemindex
                          where trim(tag_no) != '' and ii.iditemindex = '$id';");
                          foreach($strquery as $row) { ?>
                            <tr align="center">
                              <td align="left"><?= date('M d, Y', strtotime($row['requestdate'])); ?></td>
                              <td align="left"><?= $row['itemname']; ?></td>
                              <td align="right"><?= $row['quantity']; ?></td>
                              <td align="left"><?= $row['unit']; ?></td>
                          </tr>
                        <?php }; ?>
                      </tbody>

                    </table>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </section>

      </div>
    </div>


    <?php include 'includes/footer.php'?>
    <script type="text/javascript" src="../dist/css/selectdatatables/datatables.min.js"></script>

    <script type="text/javascript">

      var dataTable = $('#table2').DataTable( {
        "paging"      : true,
        "ordering"    : true,
        "info"        : true,
        "autoWidth"   : false,
        "responsive"  : true,
        "columnDefs"  : [
        {
          "targets"   : [ 0 ],
          "visible"   : true,
          "searchable": false
        }],
        "processing"  : true,
      });




    </script>

  </body>
  </html>
