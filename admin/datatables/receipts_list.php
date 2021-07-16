<?php include '../session.php'; ?>
<style type="text/css">
  #events {
    margin-bottom: 1em;
    padding: 1em;
    background-color: #f6f6f6;
    border: 1px solid #999;
    border-radius: 3px;
    height: 100px;
    overflow: auto;
  }
</style>
<section class="content">
  <?php include '../includes/toastmsg.php'; ?>
  <input type="hidden" id="iduser" name="iduser" value="<?= $_SESSION['iduser'] ?>">
  <input type="hidden" id="restriction" name="restriction" value="<?= $_SESSION['restriction'] ?>">
  <div class="row">
    <div class="col-12">
      <div class="card card-primary card-outline">
        <div class="card-body">
          <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
            <thead>
              <tr>
                <th style="display: none;"></th>
                <th>Date</th>
                <th>RR #</th>
                <th>Supplier</th>
                <th>PO #</th>
                <th>RV #</th>
                <th>DR #</th>
                <th>SI #</th>
                <th>Amount</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script>

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";

  fetch_data('no');
  function fetch_data(isdatesearch, fromdate='',todate=''){
    var table = $('#table2').DataTable( {
      "scrollX"       : true,
      //"paging"      : true,
      "lengthChange"  : true,
      "searching"     : true,
      "ordering"      : true,
      "info"          : false,
      //"bInfo"       : false,
      "autoWidth"     : false,
      // "responsive"    : true,
      "order"         : [[ 1,"desc" ],[ 0,"desc" ]],
      "columnDefs"    : [{
        "targets"     : [ 0 ],
        "visible"     : false,
      },{
        "targets"     : [ 1 ],
        "className"   : "text-center",
      },{
        "targets"     : [ 2 ],
        "render"      : function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="receiptdetails.php?id='+row[0]+'">'+row[2]+'</a>';
        }
      },{
        "targets"     : [ 8 ],
        "className"   : "text-right",
      },{
        "targets"     : [ 9 ],
        "render"      : function ( data, type, row ) {
          return '<center>'+
          '<div class="btn-group">'+
          '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_receipt('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
          '<?php if ($_SESSION["restriction"] == 101) { ?>'+
          '<button class="btn btn-danger btn-xs text-sm" title="Delete" onclick="delete_receipt('+row[0]+')"><i class="fa fa-trash-alt"></i></button></div>'+
          '<?php } ?>'+
          '</div>'+
          '</center>';
        }
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_receipts.php",
        type: "post",
        data: {
          isdatesearch:isdatesearch,
          fromdate:fromdate,
          todate:todate },
          error: function(e){ },
        },
        "processing": true,
        "initComplete": function() {
          // $('#table2 tr:last').addClass('selected');
          // $('#table2 tbody tr:last').click();
        },
      });
  }

  // this.addEventListener('keydown', function(event) {
    $('#table2').on('key-focus.dt', function(e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass('selected');
    });

    $('#table2').on('key-blur.dt', function(e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass('selected');
    });

    $('#table2').on('key.dt', function(e, datatable, key, cell, originalEvent) {
      if(key == 13) {
        var rowData = table.row(cell.index().row).data();
         //window.location="returntowarehousedetails.php?"+"id="+rowData[0];
       }
     });
  // }, false);



  $('.addreceipts').click(function(e){
    // if(Number(res) == 1 || Number(res) == 101) {
      $('#modal-add').modal('show');
    // } else  {
    //   alert("Viewing only!");
    // }
  });

  function edit_receipt(id) {
    $.ajax({
      url: 'datahelpers/receipts_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(Number(data.iduser) == Number(iduser) || Number(res) == 101) {
          // $('#errno').html(data.rrnumber);
          $('#esuppliername').html(data.suppliername);

          $("#eidpo").select2().val(data.idpo).trigger('change.select2');

          $('#eidreceipts').val(id);
          $('#errno').val(data.rrnumber);
          $('#epono').val(data.ponumber);
          $('#ervno').val(data.rvnumber);

          $('#edrno').val(data.drnumber);
          $('#esino').val(data.sinumber);
          $('#ereceivedate').val(data.receivedate);
          $('#ereceiveby').val(data.fullname);

          $('#esuppliername').val(data.suppliername);

          $('#modal-edit').modal('show');
        } else {
          alert("Viewing only!");
        }
      },
    });
  }

  function delete_receipt(id) {
    if(id != '') {
      $.ajax({
        url: 'datahelpers/receipts_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          if(Number(data.iduser) == Number(iduser) || Number(res) == 101) {
           $('#del_rrno').html(data.rrnumber);
           $('#del_rrno').val(data.rrnumber);
           $('#del_suppliername').html(data.suppliername);
           $('#del_idreceipts').val(id);

           $('#modal-delete').modal('show');
         } else {
          alert("Viewing only!");
        }
      },
    });
    }
  }

</script>
