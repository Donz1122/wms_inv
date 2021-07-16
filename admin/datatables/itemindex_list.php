<?php include '../session.php'; ?>
<?php include '../includes/toastmsg.php'; ?>
<?php include '../serverside/ss_itemindex_helper.php'; ?>

<input type="hidden" class="form-control form-control-sm" id="inactive" name="inactive"  readonly>
<input type="hidden" class="form-control form-control-sm" id="ischeck" name="ischeck"  readonly>

<section class="content">
  <div class="row">
    <div class="col-12">
     <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-nowrap table-sm">
          <thead>
            <tr>
              <th style="display: none;"></th>
              <th>Item Code</th>
              <th>Description</th>
              <th>Category</th>
              <th>Type</th>
              <th>Stock</th>
              <th>Unit</th>
              <th>Status</th>
              <th style="width: 60px"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-quantityforwarder">
  <?php //include 'serverside/ss_itemindex_helper.php' ?>
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
                <input class="form-control form-control-sm" type="date" id="cyear" name="cyear" value="<?= $enddate ?>" onchange="get_itemindex_closing_quantity_on_date()" data-mask>
              </div>
            </div>
          </div>

          <input type="hidden" id="ciditemindex" name="ciditemindex" >
          <input type="hidden" id="citemcode" name="citemcode" >

          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Closing Quantity</label>
            <div class="col">
              <input class="form-control form-control-sm" type="numeric" id="cqtya" name="cqtya" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Actual Quantity</label>
            <div class="col">
              <input class="form-control form-control-sm inputs" type="numeric" id="cqtyb" name="cqtyb" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-4 col-form-label">Average Cost</label>
            <div class="col">
              <input class="form-control form-control-sm inputs" type="numeric" id="caverage" name="caverage" placeholder="0"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-info save inputs mr-1" data-dismiss="static" onclick="save_closing()"> Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-closing-print">
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
                <input class="form-control form-control-sm" type="date" id="closingdate" name="closingdate" value="<?= $enddate ?>" data-mask>
              </div>
            </div>
          </div>

          <input type="hidden" id="cliditemindex" name="cliditemindex" >
          <input type="hidden" id="clitemcode" name="clitemcode" >


        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-info inputs mr-1" data-dismiss="static" onclick="print_closing()"> Print</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var area      = "<?php echo $_SESSION['area']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";

  $('[data-mask]').inputmask();
  $('.inputs').keydown(function (e) {
    if (e.which === 13) {
      var index = $('.inputs').index(this) + 1;
      $('.inputs').eq(index).focus();
    }
  });

  // change focus to modal input
  $(document).ready(function(){
    $("#modal-quantityforwarder").on('shown.bs.modal', function(){
      $(this).find('#cqtyb').focus();
      $('#cqtyb').select();
      // enable datatable focus
      $("#modal-quantityforwarder").on('hidden.bs.modal', function(){
        $('#table2').DataTable().keys.enable();
      });
    });
  });

  fetchData();
  function fetchData(qty='',inactive='',category='',type='') {
    var table         = $('#table2').DataTable( {
      "scrollX"       : true,
      "lengthChange"  : true,
      "searching"     : true,
      "paging"        : true,
      "ordering"      : true,
      "info"          : true,
      "autoWidth"     : false,
      // "responsive"    : true,
      "order"         : [ 2,"asc" ],
      "columnDefs"    : [{
        "targets"     : [ 0 ],
        "visible"     : false,
      },{
        "targets"     : [ 1 ],
        "render"      : function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="itemindexdetails.php?id='+row[0]+'&no='+row[1]+' ">'+row[1]+'</a>';
        }
      },{
        "targets"     : [ 5 ],
        "className"   : "text-right",
      },{
        "targets"     : [ 8 ],
        "render"      : function ( data, type, row ) {
          return '<center>'+
          '<div class="btn-group">'+
          ' <button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_itemindex('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
          ' <?php if($_SESSION["restriction"] == 101) { ?>'+
          '   <button class="btn btn-danger btn-xs text-sm" title="Delete" onclick="delete_itemindex('+row[0]+')"><i class="fa fa-trash-alt"></i></button></div>'+
          ' <?php } ?>'+
          ' </div>'+
          '<center>';
        }
      }],
      "processing"    : true,
      "serverSide"    : true,
      "ajax"          : {
        url           : "serverside/ss_itemindex.php",
        type          : "post",
        data          : { qty:qty,inactive:inactive,category:category,type:type },
        error         : function(){}
      },
      keys            : true,
      "bJQueryUI"     : true,
      "sDom"          : 'l<"H"Rf>t<"F"ip>',
    });

    $('#table2').DataTable().rows('.selected').nodes().to$().removeClass( 'selected' );
    $('#table2').on('key-focus.dt', function(e, datatable, cell){
      $(table.row(cell.index().row).node()).addClass('selected');
    });
    $('#table2').on('key-blur.dt', function(e, datatable, cell){
      $(table.row(cell.index().row).node()).removeClass('selected');
    });
  };

  $('#table2').on('key.dt', function(e, datatable, key, cell, originalEvent){
    if(key == 13){
      if((Number(res) == 0) || (Number(res) == 101) ) {
        $('#table2').DataTable().keys.disable();  // disable datatable focus

        var id        = $('#table2').DataTable().rows('.selected').data()[0][0];
        var itemcode  = $('#table2').DataTable().rows('.selected').data()[0][1];

        $('#modal-quantityforwarder').modal('show');
        $('#ciditemindex').val(id);
        $('#caverage').val('0');
        $('#citemcode').val(itemcode);
        get_itemindex_closing_quantity(itemcode);
      }
    }
  });
  function get_itemindex_closing_quantity(id) {
    var getquantity  = id;
    var givendate   = $('#cyear').val();
    $.ajax({
      url: 'datahelpers/itemindex_helper.php',
      type: 'post',
      dataType: 'json',
      data: { getquantity:id, givendate:givendate },
      success: function(data){
        $('#cqtya').val(data.qty);
        $('#cqtyb').val(data.qty);
      },
      error: function() {
        alert('err');
      },
    });
  };
  function get_itemindex_closing_quantity_on_date() {
    var id = $('#table2').DataTable().rows('.selected').data()[0][0];
    get_itemindex_closing_quantity(id);
  };

  function save_closing() {
    var addyearendqtyb;
    var ciditemindex    = $('#ciditemindex').val();
    var citemcode       = $('#citemcode').val();
    var cqtya = $('#cqtya').val();
    var cqtyb = $('#cqtyb').val();
    var cyear = $('#cyear').val();
    var average = $('#caverage').val();
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
      success: function(data) {
        $('#modal-quantityforwarder').modal('hide');
        // $('#table2').DataTable().ajax.reload();
      },
      error: function(argument) { alert('error'); }
    });
  };
  function reload_itemindex() {
    $('#table2').DataTable().ajax.reload();
  };

  function isshowwithquantity(e) {
    var inactive = $('#inactive').val();
    var check = $('#showwithquantity').is(':checked');
    $('#table2').DataTable().destroy();
    if (check) {
      $('#ischeck').val(1);
      fetchData('yes',inactive);
    } else {
      $('#ischeck').val(0);
      fetchData('no',inactive);
    }
  };
  function show_inactive(e) {
    $('#inactive').val(e);
    $('#table2').DataTable().destroy();
    if ($('#ischeck').val() == 0) {
      fetchData('yes',e);
    } else {
      fetchData('no',e);
    }
    if (e == 0)
      $('.status_label').html('Active');
    else
      if (e == 1) $('.status_label').html('In-Active');
    else
      $('.status_label').html('All Item Index');
  };


  $('.add').click(function(e){
    if((Number(res) == 0) || (Number(res) == 101) ) {
      $('#modal-addnew').modal('show');
    } else  {
      alert("Viewing only!");
    }
  });

  $('.addmultiple').click(function(e){
    if((Number(res) == 0) || (Number(res) == 101) ) {
      window.location ="itemindex_multi_entry.php";
      // $('#modal-addnew').modal('show');
    } else  {
      alert("Viewing only!");
    }
  });

  function edit_itemindex(id) {
    if((Number(res) == 0) || (Number(res) == 101) ) {
      $('#modal-edit').modal('show');
      getRow(id);
    } else  {
      alert("Viewing only!");
    }
  };

  function delete_itemindex(id) {
    if((Number(res) == 0) || (Number(res) == 101) ) {
      $('#modal-delete').modal('show');
      getRow(id);
    } else  {
      alert("Viewing only!");
    }
  };

  function getRow(id){
    $.ajax({
      url: 'datahelpers/itemindex_helper.php',
      type: 'post',
      dataType: 'json',
      data: {id:id},
      success: function(data){
        $('#del_itemcode').html(data.itemcode);
        $('#del_itemname').html(data.itemname);

        $('#ditemindex').val(id);
        $('#ditemname').val(data.itemname);

        $('#e_area').val(data.Areas);
        $('#xe_itemindex').val(data.iditemindex);
        $('#e_itemcode').val(data.itemcode);
        $('#e_itemname').val(data.itemname);
        $("#e_consumable").select2().val(data.type).trigger('change.select2');
        $("#e_category").select2().val(data.category).trigger('change.select2');
        $("#e_unit").select2().val(data.unit).trigger('change.select2');
        $('#e_acct_in').val(data.acct_in);
        $('#e_acct_out').val(data.acct_out);
        $('#ereorderpoint').val(data.reorderpoint);
        $('#edepyear').val(data.depyear);
      }
    });
  };

  // ------------
  $(document).ready( function () {


  });

  function change_active(iditemindex) {
    var inactive = iditemindex;
    if((Number(res) == 0) || (Number(res) == 101) ) {
      var ok = confirm('Are you sure to change status?');
      if (ok == true) {
        $.ajax({
          url: 'datahelpers/itemindex_helper.php',
          type: 'post',
          dataType: 'json',
          data: { inactive:inactive },
          success: function(data){
            if (data.ok == 1) reload_itemindex();
          }
        });
      }
    }
  }

  function print_closing(e) {
    if((Number(res) == 0) || (Number(res) == 101) ) {
      var date = $('#closingdate').val();
      var redirectWindow = window.open("printing/print_itemindex_list_closing.php"+"?date="+date+"", '_blank');
      redirectWindow.location;
    } else {
      alert("Viewing only!");
    }
  };

</script>