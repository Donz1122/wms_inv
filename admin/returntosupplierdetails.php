
<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/alertmsg.php';
include 'class/clsreturns.php';
$idreturns = $_GET['id'];
$query = $returns->all_items_return_to_suppliers($idreturns);
?>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <input type="hidden" id="idreturns"   name="idreturns"  value="<?= $idreturns ?>">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
          </div>
        </div>
        <section class="content">
          <div class="row">
           <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Return To Supplier Material Details</h3>
              </div>
              <div class="card-body">
                <strong>Returned By</strong>
                <p class="text-muted float-right"><?= utf8_decode($query['empname']) ?></p>
                <hr>
                <strong>Control No.</strong>
                <p class="text-muted float-right"><?= $query['returnedno'] ?></p>
                <hr>
                <strong>Date</strong>
                <p class="text-muted float-right"> <?= date('M d, Y', strtotime($query['returneddate'])) ?></p>
                <hr>
                <strong>Particulars</strong>
                <p class="col text-muted"><?= $query['particulars'] ?></p>
                <hr>
                <strong>Amount</strong>
                <p class="text-muted float-right"><?= number_format($query['amount'], 2) ?></p>
              </div>
            </div>
          </div>

          <div class="col">
            <section class="content">
              <div class="col-12 col-sm-12 mb-1">
                <button class="btn btn-info btn-sm add"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> Add</button>
                <a title="Print Details" class="btn btn-info btn-sm" href="printing/print_return_to_supplier_hardcopy.php?id=<?= $query['idreturns'] ?>&no=<?= $query['returnedno']?>"><i class="fa fa-print mr-1"></i> Print</a>
                <a href="returntosupplier.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
              </div>
              <div class="col-12 col-sm-12">
                <div class="card card-warning card-outline">
                  <div class="card-header">
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand" title="Maximize"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <table id="table2" class="table table-sm text-nowrap">
                      <thead>
                        <tr>
                          <th style="display: none;"></th>
                          <th>Item Code</th>
                          <th>Item Name</th>
                          <th>Brand Name</th>
                          <th>Model</th>
                          <th>Specs</th>
                          <th>Serials</th>
                          <th>Remarks</th>
                          <th style="text-align: right;">Quantity</th>
                          <th>Unit</th>
                          <th style="text-align: right;">Unit Cost</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
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
  <?php include 'modals/returntosupplierdetails_modal.php'?>


  <script type="text/javascript">

    var res         = "<?php echo $_SESSION['restriction']; ?>";
    var iduser      = "<?php echo $_SESSION['iduser']; ?>";
    var returnedno  = "<?= $query['returnedno'] ?>";

    function load_supplier_details() {
      var dataTable = $('#table2').DataTable( {
        "scrollX"       : true,
        "paging"        : true,
        "lengthChange"  : true,
        "ordering"      : true,
        "info"          : true,
        // "autoWidth" : false,
        "responsive"    : true,
        "columnDefs"    : [{
          "targets"     : [ 0 ],
          "visible"     : false,
        },{
          "targets"     : [ 8,10 ],
          "className"   : "text-right",
        },{
          "targets"     : [ 11 ],
          "render": function ( data, type, row ) {
            return '<center>'+
            '<div class="btn-group">'+
            ' <button class="btn btn-warning btn-xs" title="Edit" onclick="edit_selected_return_details('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
            '   <?php if($_SESSION["restriction"] == 101) { ?>'+
            '     <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_selected_return_details('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
            '   <?php } ?>'
            ' </div>'+
            '</center>';
          }
        }],
        "processing"    : true,
        "serverSide"    : true,
        "ajax"          : {
          url           : "serverside/ss_return_to_supplier_details.php",
          type          : "post",
          data          : { returnedno:returnedno },
          error         : function() { }
        },
      });
    }load_supplier_details();

    function reload_supplier_details() {
      $('#table2').DataTable().destroy();
      load_supplier_details();
    };

    $('.add').click(function(e){
      e.preventDefault();
      if(Number(res) == 1 || res == 101)  {
        $('#areturnedno').val(returnedno);
        $('#aidreturns').val($('#idreturns').val());
        $('#add').modal('show');
      }
    });

    function edit_selected_return_details(id) {
      if((Number(res) == 1 || Number(res) == 101))  {
        $.ajax({
          url: 'datahelpers/returntosupplierdetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            $('#eidreturnsdetails').val(data.idreturnsdetails);
            $('#ereturnedno').val(data.returnedno);
            $('#errnumber').val(data.linkrefno);
            $('#eitemname').val(data.itemname);
            $('#ebrandname').val(data.brandname);
            $('#especs').val(data.specifications);
            $('#emodel').val(data.model);
            $('#eserialnos').val(data.serialnos);
            $('#eremarks').val(data.remarks);
            $('#edateaquired').val(data.returneddate);
            $('#eqty').val(data.quantity);
            $('#eretqty').val(data.quantity);
            $('#eunit').val(data.unit);
            $('#ecostperunit').val(data.unitcost);

            $('#edit').modal('show');
          }
        });
      }
    };

    function delete_selected_return_details(id) {
      if(Number(res) == 1 || Number(res) == 101)  {
        $.ajax({
          url: 'datahelpers/returntosupplierdetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
           $('#didreturnsdetails').val(data.idreturnsdetails);
           $('#dreturnedno').val(data.returnedno);
           $('#ditemname').val(data.itemname);
           $('#dditemname').html(data.itemname);
           $('#delete').modal('show');
         }
       });
      }
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


</body>
</html>
