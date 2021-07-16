<!DOCTYPE html>
<html lang="en">
<?php include 'session.php';  ?>
<?php include 'includes/datatable-header.php'; ?>
<?php include 'class/clsitems.php'; ?>

<?php
$iditemindex  = $_GET['id'];
$itemcode     = $_GET['no'];
$yearnow = date("Y",strtotime("now"));
$yearend = date("Y",strtotime("now")) - 1;
?>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php //include 'includes/navbar-header.php'; ?>
    <?php include 'includes/alertmsg.php' ?>
    <div class="content-wrapper">
      <div class="content-header">
       <div class="container-fluid">
        <div class="row mb-2">
          <!-- <div class="col-sm-3"><h4><i style="font-size: 18px;">Item Index Details</i></h4></div> -->
          <!-- <div class="col"><h4>Transaction History</h4></div> -->
          <!-- <div class="ml-auto">
            <button class="btn btn-default btn-sm" id="printtransactions" href="#"><i class="fa fa-print mr-1"></i> Print</button>
            <a href="itemindex.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
          </div> -->
        </div>
      </div>
      <section class="content">
        <div class="row">
          <div class="col-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Item Index Details</h3>
              </div>

              <?php $query = $item->search_itemindex_summary($iditemindex); ?>
              <input type="hidden" id="qty" name="qty" value="<?= $query['qty'] ?>">

              <div class="card-body">

                <strong>Item Code</strong>
                <p class="text-muted float-right"><?= $query['itemcode']; ?></p>
                <hr>
                <strong>Item Name</strong>
                <p class="text-muted"><?= $query['itemname']; ?></p>
                <hr>
                <strong><a href="itemindexcategory.php">Category</a></strong>
                <p class="text-muted float-right"><?= $query['category']; ?></p>
                <hr>
                <strong>Unit</strong>
                <p class="text-muted float-right"><?= $query['unit']; ?></p>
                <hr>
                <strong>Type</strong>
                <p class="text-muted float-right"><?= $query['type']; ?></p>
                <!-- <hr>
                <strong>Reorder Point</strong>
                <p class="text-muted float-right"><?= $query['rop']; ?></p> -->
                <hr>
                <strong>Balance Quantity</strong>
                <p class="text-muted float-right"><?= $query['qty']; ?></p>
                <hr>
                <a data-toggle="modal" title="Add Year End Balance"  href="#modal-quantityforwarder" onclick="">
                  <strong>Year End Date for <?= $query['closingdate'] ?></strong>
                </a>
                <p class="text-muted float-right"><?= $query['closingqty']; ?></p>
                <hr>
                <strong>Average Cost</strong>
                <p class="text-muted float-right"><?= number_format($query['dummyave'], 2); ?></p>
              </div>
            </div>
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title ml-2">Closing History <span class="badge badge-info"></span></h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body p-0">
                <table id="table1" class="table table-nowrap table-sm">
                  <thead>
                    <tr>
                      <th width="110px;">Date</th>
                      <th style="text-align: right;">Closing</th>
                      <th style="text-align: right;">Actual</th>
                      <th style="text-align: right;">Average</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $str_ye = $db->query("select * from tbl_closing where iditemindex = '".$iditemindex."'");
                    foreach($str_ye as $row) { ?>
                      <tr align="center">
                        <td align="left"><?= $row['closingdate']; ?></td>
                        <td align="right"><?= $row['closingqty']; ?></td>
                        <td align="right"><?= $row['actualqty']; ?></td>
                        <td align="right"><?= number_format($row['average'],2); ?></td>
                        <td>
                          <center>
                           <?php if($_SESSION['restriction'] == '101') { ?>
                            <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_ye('<?= $row['idclosing']?>')"><i class="fa fa-trash-alt"></i></button>
                          <?php } ?>
                        </center>
                      </td>
                    </tr>
                  <?php }; ?>
                </tbody>
              </table>
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div>
        <div class="col-sm-9">
          <section class="content">
            <div class="col-12 col-sm-12 mb-1">
              <div class="col row">
                <h5>Transaction History</h5>
                <div class="ml-auto">
                  <button class="btn btn-default btn-sm" id="printtransactions" href="#"><i class="fa fa-print mr-1"></i> Print</button>
                  <a href="itemindex.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-12 mb-1">
              <div class="card card-warning card-outline">
                <div class="card-body">
                  <table id="table2" class="table table-nowrap table-striped table-sm">
                    <thead>
                      <tr>
                        <th style="display: none;"></th>
                        <th width="60px">Date</th>
                        <th width="120px">Document No.</th>
                        <th>Particulars</th>
                        <th style="text-align: right;">In</th>
                        <th style="text-align: right;">Out</th>
                        <th>Unit</th>
                        <th style="text-align: right;">Unit Cost</th>
                        <th style="display: none;"></th>
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

<div class="modal fade" id="modal-print_transactions">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Print List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="#">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date From</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="date" class="form-control form-control-sm" id="datefrom" name="datefrom" data-mask value="<?= $startdate; ?>">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date To</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                  </span>
                </div>
                <input type="date" class="form-control form-control-sm" id="dateto" name="dateto" data-mask value="<?= $enddate; ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info printtransactions" name=""><i class="fa fa-print"></i> Print</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-quantityforwarder">
  <?php include 'serverside/ss_itemindex_helper.php' ?>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Closing Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form class="form-horizontal" method="POST" action="">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Closing Date</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-sm" type="date" id="ayear" name="ayear" value="<?= $query['closingdate'] ?>" data-mask onchange="get_itemindex_closing_quantity_on_date()">
              </div>
            </div>
          </div>


          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Closing Quantity</label>
            <div class="col">
              <input class="form-control form-control-sm" type="numeric" id="dqtya" name="dqtya" value="<?= $query['qty'] ?>" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Actual Quantity</label>
            <div class="col">
              <input class="form-control form-control-sm" type="numeric" id="dqtyb" name="dqtyb" value="<?= $query['qty'] ?>" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Average Cost</label>
            <div class="col">
              <input class="form-control form-control-sm inputs" type="numeric" id="daverage" name="daverage" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-info save inputs mr-1" data-dismiss="static" onclick="save_closing()"> Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <!-- <button type="submit" class="btn btn-info" name="addyearendqty"> Save</button> -->
        </div>
      </form>
    </div>
  </div>
</div>
<?php include 'includes/datatable-footer.php'?>


<script type="text/javascript">

  $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
  $('[data-mask]').inputmask();

  $(document).ready(function(){
    $("#modal-quantityforwarder").on('shown.bs.modal', function(){
      $(this).find('#dqtyb').focus();
      $('#dqtyb').select();

      $("#modal-quantityforwarder").on('hidden.bs.modal', function(){
        $('#table2').DataTable().keys.enable();
      });
    });
  });

  var id          = '<?= $iditemindex ?>';
  var itemcode    = '<?= $itemcode ?>';

  function loaddetails() {
    var table         = $('#table2').DataTable( {
      "lengthChange"  : true,
      "searching"     : true,
      "paging"        : true,
      "ordering"      : true,
      "info"          : true,
      "autoWidth"     : false,
      "responsive"    : true,
      "order"         : [ 8,"desc" ],
      "columnDefs"    : [{
        "targets"     : [ 0,8 ],
        "visible"     : false,
      },{
        "targets"     : [ 4,5,7 ],
        "className"   : "text-right",
      }],
      "processing"    : true,
      "serverSide"    : true,
      "ajax"          : {
        url           : "serverside/ss_itemindex_details.php",
        type          : "post",
        data          : { itemcode:itemcode },
        error         : function(){},
      },
      keys: true,
    });
  }loaddetails();

  function quantityForwarded(){
    $('#modal-quantityforwarder').modal('show');
    get_itemindex_closing_quantity(id);
  };
  function get_itemindex_closing_quantity(id) {
    var getquanity  = id;
    var givendate   = $('#ayear').val();
    $.ajax({
      url         : 'datahelpers/itemindex_helper.php',
      type        : 'post',
      dataType    : 'json',
      data        : { getquanity:id, givendate:givendate },
      success: function(data){
        $('#dqtya').val(data.qty);
        $('#dqtyb').val(data.qty);
      },
      error: function() {
        alert('itemindedetails.php line 311');
      },
    });
  };
  function get_itemindex_closing_quantity_on_date() {
    get_itemindex_closing_quantity(id);
  };
  function save_closing() {
    var addyearendqtyb;
    var ciditemindex  = id;
    var citemcode     = itemcode;
    var cqtya         = $('#dqtya').val();
    var cqtyb         = $('#dqtyb').val();
    var cyear         = $('#ayear').val();
    var average       = $('#daverage').val();
    $.ajax({
      url: 'datahelpers/itemindex_helper.php',
      type: 'post',
      dataType: 'json',
      data: {
        addyearendqtyb:'ok',
        ciditemindex:ciditemindex,
        citemcode:citemcode,
        cqtya:cqtya,
        cqtyb:cqtyb,
        cyear:cyear,
        average:average,
      },
      success: function(data){
        $('#modal-quantityforwarder').modal('hide');
        window.location.reload();
      },
      error: function(argument) {
        alert('error');
      }
    });
  };

  $('#printtransactions').click(function(e){
    e.preventDefault();
    var redirectWindow = window.open("printing/print_itemtransactions.php"+"?id="+id, '_blank');
    redirectWindow.location;
  });

  $('#table1 tbody').on( 'click', 'tr', function () {
    var table = $('#example').DataTable();
    if ( $(this).hasClass('selected') ) {
      $(this).removeClass('selected');
    }
    else {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
  function delete_ye(idclosing) {
    var ok = confirm('Are you sure to delete this closing?');
    if (ok == true) {
      $.ajax({
        url: 'datahelpers/itemindex_helper.php',
        type: 'post',
        dataType: 'json',
        data: { idclosing:idclosing },
        success: function(data){
          if (Number(data.ok) == 1) {
            $("#table1").DataTable({
              "lengthChange": false,
              "searching": false }).row('.selected').remove().draw( true );
          }
        },
        error: function(argument) {
          alert('error');
        }
      });
    }
  };

</script>

</body>
</html>
