<?php
header('content-Type: text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/datatable-header.php'; ?>
<?php include 'session.php'; ?>
<?php include 'class/clsitems.php'; ?>

<?php
if(isset($_GET['id'])) {
  $idreceipts = $_GET['id'];
  $query = $item->get_rr($idreceipts);
}?>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php //include 'includes/navbar-header.php'; ?>
    <?php include 'includes/alertmsg.php'; ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">
            <div class="col-sm-3">
            </div>
          </div>
        </div>

        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Material Receipt Details</h3>
                </div>
                <div class="card-body">
                  <input type="hidden" id="idreceipts" name="idreceipts" value="<?= $query['idreceipts'] ?>">
                  <input type="hidden" id="rrno" name="rrno" value="<?= $query['rrnumber'] ?>">
                  <strong> Date</strong>
                  <p class="text-muted float-right"><?= date('M d, Y', strtotime($query['receivedate'])) ?></p>
                  <hr>
                  <strong> RR Number</strong>
                  <p class="text-muted float-right"><?= $query['rrnumber'] ?></p>
                  <hr>
                  <strong> Supplier</strong>
                  <p class="text-muted"><?= $query['suppliername'] ?></p>

                  <hr>
                  <strong> PO Number</strong>
                  <p class="text-muted float-right"><?= $query['ponumber'] ?></p>
                  <hr>
                  <strong> RV Number</strong>
                  <p class="text-muted float-right"><?= $query['rvnumber'] ?></p>

                  <hr>
                  <strong> Amount</strong>
                  <p class="text-muted float-right"><?= number_format($query['amount'],2) ?></p>
                  <hr>
                  <strong> Received By</strong>
                  <p class="text-muted float-right"><?= utf8_decode($query['fullname']) ?></p>
                </div>
              </div>
            </div>

            <div class="col-sm-9">
              <section class="content">
                <div class="col-12 col-sm-12 mb-1">
                  <button title="Add Details" class="btn btn-info btn-sm add"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> Add</button>
                  <button title="Print Details" class="btn btn-info btn-sm print"><i class="fa fa-print mr-1"></i>  Print</button>
                  <a href="receipts.php" class="btn btn-danger btn-sm mr-auto" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                </div>
                <div class="col-12 col-sm-12 mb-1">
                  <div class="card card-warning card-outline">
                    <div class="card-body">
                      <table id="table2" class="table table-sm text-nowrap">
                        <thead>
                          <tr>
                            <th></th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Brand Name</th>
                            <th>Model</th>
                            <th>Specs</th>
                            <th>Serials</th>
                            <th style="text-align: right;">Quantity</th>
                            <th>Unit</th>
                            <th style="text-align: right;">Unit Cost</th>
                            <th style="text-align: right;">Amount</th>
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
    <?php include 'modals/receiptdetails_modal.php'?>
  </div>


  <div class="modal fade" id="modal-receiptsissuedto">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><span id="">Receipt Issued To</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></span>
          </button>
        </div>

        <form class="form-horizontal" method="POST" action="">
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Issued To:</label>

              <div class="col">
                <select onchange="" class="form-control form-control-sm select2" id="preceiptto" name="preceiptto" >
                  <?php
                  $empname = $db->query("select empnumber, concat(lastname,', ',firstname,' ',middleinitial) empname, title from zanecopayroll.employee where concat(lastname,', ',firstname,' ',middleinitial) is not null order by empname;");
                  foreach($empname as $row): ?>
                    <option value="<?= $row['empnumber']; ?>"><?= utf8_decode(ucwords(strtolower($row['empname']))); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-info btn-sm" data-dismiss="static" onclick="printReceipt()"><i class="fa fa-print mr-1 faa-ring animated-hover"> </i>Print</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">

    var res       = "<?php echo $_SESSION['restriction']; ?>";
    var iduser    = "<?php echo $_SESSION['iduser']; ?>";
    var idreceipts = "<?php echo $idreceipts; ?>";

    function load_receipts_details() {
      var table = $('#table2').DataTable( {
        "scrollX"       : true,
        "autoWidth"     : false,
        "paging"        : true,
        "ordering"      : true,
        "info"          : true,
        "fixedColumns"  : {
          "leftColumns" : 2
        },
        "columnDefs"    : [ {
          "targets"  : [ 7,9,10 ],"className": "text-right",
        },{
          "targets"     : [ 0 ],
          "render"      : function ( data, type, row ) {
            return '<center>'+
            '<div class="btn-group">'+
            ' <button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_rrdetails('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
            '   <?php if($_SESSION["restriction"] == 101) { ?>'+
            '     <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_rrdetails('+row[0]+')"><i class="fa fa-trash-alt "></i></button>'+
            '   <?php } ?>'
            ' </div>'+
            '</center>';
          }
        } ],
        "processing": true,
        "serverSide": true,
        "ajax"      : {
          url       : "serverside/ss_receipts_details.php",
          type      : "post",
          data      : { idreceipts:idreceipts },
          error     : function() {
          }
        },
      } );
    }load_receipts_details();

    function reload_receipts_details(e) {
      $('#table2').DataTable().destroy();
      load_receipts_details();
    };

    $('#table2 tbody').on( 'click', 'tr', function () {
      $('#table2').DataTable().rows( '.selected' ).nodes().to$().removeClass( 'selected' );
      $(this).toggleClass('selected');
      $("input").change(function (e) {
        e.preventDefault();
        if ($(this).is(":checked")) {
          $("input").prop("checked", false);
          $(this).prop("checked", true);
        }
      });
    });

    $('.add').click(function(e){
      e.preventDefault();
      if(res == 1 || res == 101) {
        $('#add-details').modal('show');
        $('#rdrrno').val($('#rrno').val());
        $('#rdidreceipts').val($('#idreceipts').val());
      } else {
        alert("Viewing only!");
      }
    });
    function save_receipt_details() {
      var rrno            = $('#rdrrno').val();
      var idreceipts      = $('#rdidreceipts').val();
      var iditemindex     = $('#iditemindex').val();
      var itemcode        = $('#itemcode').val();
      var itemname        = $('#aitemname').val(); // for DMOG0001
      var quantity        = $('#qty').val();
      var unitcost        = $('#unitcost').val();
      var brandname       = $('#brandname').val();
      var specs           = $('#specs').val();
      var model           = $('#model').val();
      var serialnos       = $('#serials').val();

      var iswithwarranty  = $('#iswithwarrantyz').val();
      var warranty        = $('#warranty').val();
      $.ajax({
        url: 'datahelpers/receiptdetails_helper.php',
        type: 'post',
        //dataType: 'json',
        data: {
          add:'save',
          rrno:rrno,
          idreceipts:idreceipts,
          iditemindex:iditemindex,
          itemcode:itemcode,
          itemname:itemname,
          quantity:quantity,
          unitcost:unitcost,
          brandname:brandname,
          specs:specs,
          model:model,
          serialnos:serialnos,
          iswithwarranty:iswithwarranty,
          warranty:warranty,
        },
        success: function(data){
          $('#add-details').modal('hide');
          reload_receipts_details();
          msgAlert('success','Saved','Record Successfully saved!');
        },
      });

    }

    function edit_rrdetails(id) {
      if(res == 1 || res == 101) {
        $.ajax({
          url: 'datahelpers/receiptdetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            if(Number(data.iduser) == Number(iduser) || iduser == 0) {

              $('#rdeidreceipts').val(data.idreceipts);
              $('#rderrno').val(data.rrnumber);
              $('#rdeidreceiptsdetails').val(data.idreceiptsdetails);
              $('#errno').val(data.rrnumber);
              $('#eitemcode').val(data.itemcode);
              $('#eitemname').val(data.itemname);
              $('#eunit').val(data.unit);
              $('#eqty').val(data.quantity);
              $('#eunitcost').val(data.unitcost);

              $('#ebrandname').val(data.brandname);
              $('#emodel').val(data.model);
              $('#especs').val(data.specifications);
              $('#eserials').val(data.serialnos);

              if(typeof(data.warranty) != "undefined" && data.warranty !== null) {
                $("#eiswithwarrantyz").val('1');
                $('#ewarranty').val(data.warranty);
                document.getElementById("ewarranty").disabled = false;
                $("#ewithwarranty").prop("checked", true );
              } else {
                $("#eiswithwarrantyz").val('0');
                $("#ewarranty").val('');
                document.getElementById("ewarranty").disabled = true;
                $("#ewithwarranty").prop("checked", false );
              }

              $("#eiditemindex").select2().val(data.iditemindex).trigger('change.select2');

              if(data.itemcode == 'DMOG0001') {
                load_allpurpose(data.itemcode);
              }

              $('#modal-edit').modal('show');
            } else {

            }
          }
        });
      } else {
        alert("Viewing only!");
      }
    };
    function load_allpurpose(e){
      $.ajax({
        url: 'contents/content.php',
        type: 'post',
        data: { allpurpose:e },
        success: function (data) {
          $('#eallpurposeitem').html(data);
        },
        error: function(){
          alert('Error: allpurposeitem');
        }
      });
    };

    function delete_rrdetails(id) {
      var ok = confirm('Are you sure to delete this item?');
      if (ok == true) {
        $.ajax({
          url: 'datahelpers/receiptdetails_helper.php',
          type: 'post',
          // dataType: 'json',
          data: { delete:id },
          success: function(data){
            reload_receipts_details();
            msgAlert('danger','Deleted!','Record Successfully delete!');
          }
        });
      }
    };

    $('.print').click(function(e){
      if ( (table.data().count() > 0) ) {
        var id = "<?= $query['idreceipts']; ?>";
        var redirectWindow = window.open("printing/print_receipts_hardcopy.php?id="+id, '_blank');
        redirectWindow.location;
      } else {
        alert("Noting to print!");
      }
    });


    function msgAlert(bg, header, remarks){
      $(document).Toasts('create', {
        class    : 'bg-'+bg,
        title    : '<span style="font-size: 12px;"> '+header+' </span>',
        subtitle : '<a href="" class="nav-link" id=""></a>',
        position : 'bottomRight',
        icon     : 'fas fa-envelope fa-lg',
        body     : '<span style="font-size:13px;">'+remarks+'<span><strong><hr>' + '<span style="font-size:11px;"></span></strong>',
        allowToastClose: true,
      })
    };

  </script>

</body>
</html>