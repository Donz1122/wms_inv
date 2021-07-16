<?php include '../session.php'; ?>
<?php include '../class/clsreturns.php'; ?>
<?php include '../includes/toastmsg.php'; ?>

<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body text-sm">
          <table id="table2" class="table table-sm text-nowrap table-hover table-striped">
            <thead>
              <tr>
                <th style="display: none;"></th>
                <th>Date</th>
                <th>MCrT No.</th>
                <th>Particulars</th>
                <th>Amount</th>
                <th>Returned By</th>
                <th>Return To</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </table>
      </div>
    </div>
  </div>
</div>
</section>

<script>

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";

  var dataTable = $('#table2').DataTable( {
    "paging"      : true,
    "ordering"    : true,
    "info"        : true,
    "autoWidth"   : false,
    "responsive"  : true,
    "columnDefs"  : [ {
      "targets"   : [ 0 ],
      "visible"   : false,
    },{
      "targets": [ 8 ],
      "render": function ( data, type, row ) {
        return '<center>'+
        '<div class="btn-group">'+
        '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="editRM('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
        '<?php if($_SESSION["restriction"] == 101) { ?>'+
        '<button class="btn btn-danger btn-xs text-sm" title="Delete" onclick="delRM('+row[0]+')"><i class="fa fa-trash-alt"></i></button></div>'+
        '<?php } ?>'+
        '</div>'+
        '</center>';
      }
    }],
    "processing"  : true,
    "serverSide"  : true,
    "ajax"        : {
      url         : "serverside/ss_return_to_supplier.php",
      type        : "post",
      error       : function() {}
    },
  });

  $(document).ready(function() {
    $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
    $('[data-mask]').inputmask();
  });

  $('.add').click(function(e){
    e.preventDefault();
    $('#modal-addreturn').modal('show');
  });

  function editRM(id) {
    $.ajax({
      url: 'datahelpers/returntosupplier_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(data.iduser == iduser || res == 101) {
          $('#eidreturned').val(id);
          $('#eReturnedDate').val(data.returneddate);
          $('#eParticulars').val(data.particulars);
          $('#eMCrTNo').val(data.returnedno);

          $("#eReturnedBy").select2().val(data.empno).trigger('change.select2');
          $("#esuppliercode").select2().val(data.suppliercode).trigger('change.select2');

          $('#eRefNo').val(data.refno);
          $('#modal-editreturn').modal('show');
        }
      }
    });
  };

  function delRM(id) {
    $.ajax({
      url: 'datahelpers/returnedmaterials_helper.php',
      type: 'POST',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(data.iduser == iduser || res == 101) {
          $('#didreturned').val(id);
          $('#dParticulars').html(data.particulars);
          $('#modal-deletereturn').modal('show');
        }
      }
    });
  };



</script>

