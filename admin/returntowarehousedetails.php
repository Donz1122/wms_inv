<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/datatable-header.php'; ?>
</head>

<?php
include 'session.php';
include 'class/clsreturns.php';
include 'includes/alertmsg.php';

$idreturns      = $_GET['id'];
$returnedno     = $_GET['no'];
$query          = $returns->all_items_return_to_warehouse($returnedno,1);

$empno          = "";
$empname        = "";
$returnedno     = "";
$returneddate   = "";
$particulars    = "";
$amount         = 0.00;
$suppliercode   = "";

if(!empty($query['empno'])) {

  $empno        = $query['empno'];
  $empname      = $query['empname'];
  $returnedno   = $query['returnedno'];
  $returneddate = $query['returneddate'];
  $particulars  = $query['particulars'];
  //$amount       = $query['amount'];
  // $refno        = $query['refno'];
} else {
  $suppliercode = $query['suppliercode'];
  $empname      = $query['empname'];
  $returnedno   = $query['returnedno'];
  $returneddate = $query['returneddate'];
  $particulars  = $query['particulars'];
}

$_SESSION['xempno']         = $empno;
$_SESSION['xsuppliercode']  = $suppliercode;
// $_SESSION['xrefno'] = $refno;

// $reference = substr($refno, 0,2);

?>
<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <input type="hidden"  id="returnedno"    name="returnedno"    value="<?= $returnedno ?>">
    <input type="hidden"  id="empno"         name="empno"         value="<?= $empno ?>">
    <input type="hidden"  id="suppliercode"  name="suppliercode"  value="<?= $suppliercode ?>">
    <input type="hidden"  id="idreturns"     name="idreturns"     value="<?= $idreturns ?>">

    <div class="content-wrapper">
      <div class="content-header">
        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Details</h3>
                </div>
                <div class="card-body">
                  <strong>Returned By</strong>
                  <p class="text-muted float-right"><?= utf8_decode($empname) ?></p>
                  <hr>
                  <strong>Control No.</strong>
                  <p class="text-muted float-right"><?= $returnedno ?></p>
                  <hr>
                  <strong>Date</strong>
                  <p class="text-muted float-right"><?= date('M d, Y', strtotime($returneddate)) ?></p>
                  <hr>
                  <strong>Particulars</strong>
                  <p class="col text-muted"><?= $particulars ?></p>
                </div>
              </div>
            </div>

            <div class="col">
              <section class="content">
                <div class="col-12 col-sm-12 mb-1">
                  <button class="btn btn-info btn-sm" onclick="add_returned_materials_details()"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> Add</button>
                  <a href="returntowarehouse.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                </div>
                <div class="col-12 col-sm-12">
                  <div class="card card-warning card-outline">
                    <div class="card-body">
                      <table id="table2" class="table table-sm text-nowrap">
                        <thead>
                          <tr>
                            <th style="display: none;"></th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th style="text-align: right;">Quantity</th>
                            <th>Unit</th>
                            <th></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                    <div class="card-footer">

                    </div>
                  </div>
                </div>
              </section>

            </div>
          </div>
        </section>

      </div>
    </div>

    <?php include 'includes/datatable-footer.php'?>
    <?php include 'modals/returntowarehousedetails_modal.php'?>


    <script type="text/javascript">

      var ref = $('#reference').val();
      var returnedno = '<?= $returnedno ?>';
      function load_warehouse_details() {
        var dataTable = $('#table2').DataTable( {
          "paging"    : true,
          "ordering"  : true,
          "info"      : true,
          "responsive": true,
          "autoWidth" : false,
          "processing": true,
          "columnDefs": [{
            "targets" : [ 0 ],
            "visible" : false,
          },{
            "targets" : [ 3 ],
            "className": "text-right",
          },{
            "targets": [ 5 ],
            "render": function ( data, type, row ) {
              return '<center>'+
              '<div class="btn-group">'+
              ' <button class="btn btn-warning btn-xs" title="Edit" onclick="edit_selected_materials_details('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
              '   <?php if($_SESSION["restriction"] == 101) { ?>'+
              '     <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_selected_warehouse_details('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
              '   <?php } ?>'
              ' </div>'+
              '</center>';
            }
          }],
          "processing": true,
          "serverSide": true,
          "ajax"      :
          {
            url       : "serverside/ss_return_to_warehouse_details.php",
            type      : "post",
            data      : { returnedno:returnedno },
            error     : function() { }
          },
        } );
      }load_warehouse_details();

      function reload_warehouse_details() {
        $('#table2').DataTable().destroy();
        load_warehouse_details();
      };

      function add_returned_materials_details() {
        var empno = $('#empno').val();

        $('#areturnedno').val($('#returnedno').val());
        $('#aidreturns').val($('#idreturns').val());
        $('#aempno').val(empno);
        $('#add').modal('show');
      };

      function edit_selected_materials_details(id) {
        $.ajax({
          url      : 'datahelpers/returnedmaterialsdetails_helper.php',
          type     : 'post',
          dataType : 'json',
          data: { id:id },
          success: function(data){
            $('#eidreturnsdetails').val(data.idreturnsdetails);
            $('#ereturnedno').val(data.returnedno);
            $('#eiditemindex').val(data.iditemindex);
            $('#eitemcode').val(data.itemcode);
            $('#eitemname').val(data.itemname);
            $('#eunit').val(data.unit);
            $('#eqty').val(data.quantity);
            $('#eretqty').val(data.quantity);
            $('#eunitcost').val(data.unitcost);
            $('#edit').modal('show');
          }
        });
      };

      function delete_selected_warehouse_details(id) {
        $.ajax({
          url      : 'datahelpers/returnedmaterialsdetails_helper.php',
          type     : 'post',
          dataType : 'json',
          data: { id:id },
          success: function(data){
            $('#didreturnsdetails').val(data.idreturnsdetails);
            $('#dreturnedno').val(data.returnedno);
            $('#ditemname').html(data.itemname);
            $('#delete').modal('show');
          }
        });
      };

      function msg(bg,header,remarks){
        $(document).Toasts('create', {
          class    : 'bg-'+bg,
          title    : '<span style="font-size: 12px;"> '+ header +' </span>',
          position : 'topRight',
          icon     : 'fas fa-envelope fa-lg',
          body     : '<span style="font-size:13px;">'+ remarks +'<span>',
        })
      };

    </script>

    <!-- <script type="text/javascript" src="helper.js"></script> -->
  </body>
  </html>
