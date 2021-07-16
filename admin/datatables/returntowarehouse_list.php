<?php
include '../session.php';
include '../class/clsreturns.php';
include '../includes/toastmsg.php';
?>

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
                <!-- <th>Amount</th> -->
                <th>Returned By</th>
                <!-- <th>Reference No.</th> -->
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

  $(document).ready(function() {

    var events = $('#events');
    var table = $('#table2').DataTable( {
      "paging": true,
      "ordering": true,
      "info": true,
      "keys": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      },{
        "targets": [ 5 ],
        "render": function ( data, type, row ) {
          return '<center>'+
          '<div class="btn-group">'+
          '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_selected_mr('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
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
        url         : "serverside/ss_return_to_warehouse.php",
        type        : "post",
        error       : function() {}
      },
      "initComplete": function() {
        // $('#table2 tr:last').addClass('selected');
        // $('#table2 tbody tr:last').click();
        //table.row(cell.index().row).click();

      },
    });

    /*$('#table2').on('key-focus.dt', function(e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass('selected');
    });

    $('#table2').on('key-blur.dt', function(e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass('selected');
    });

    $('#table2').on('key.dt', function(e, datatable, key, cell, originalEvent) {
      if(key == 13) {
        var rowData = datatable.row(cell.index().row).data();
        window.location="returntowarehousedetails.php?"+"id="+rowData[0];
      }
    });*/



    // $('#table2 tr:last').addClass('selected');
    //$('#table2 tr:last').click();


  });



  $(document).ready(function() {
    $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
    $('[data-mask]').inputmask();
  });

  $('.add').click(function(e){
    e.preventDefault();
    $('#modal-addreturn').modal('show');
  });

  function edit_selected_mr(id) {
    $.ajax({
      url: 'datahelpers/returnedmaterials_helper.php',
      type: 'POST',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(data.iduser == iduser || res == 101) {
          $('#eidreturned').val(id);
          $('#eReturnedDate').val(data.returneddate);
          $('#eParticulars').val(data.particulars);
          $('#eMCrTNo').val(data.returnedno);

          $("#eReturnedBy").select2().val(data.empno).trigger('change.select2');
          $('#modal-editreturn').modal('show');

          if(data.empno === '' || data.empno === null) {
            $("#ereturnfrom").select2().val(1).trigger('change.select2');
            echange_from(1, data.suppliercode);
            // $("#aReturnedBy").select2().val(data.suppliercode).trigger('change.select2');
          }  else {
            $("#ereturnfrom").select2().val(0).trigger('change.select2');
            echange_from(0, data.empno);
            // $("#aReturnedBy").select2().val(data.empno).trigger('change.select2');
          }
        } else {
          alert('this is not your entry!');
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

  /*function getRow(id){
    $.ajax({
      url: 'datahelpers/returnedmaterials_helper.php',
      type: 'POST',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        $('#eidreturned').val(id);
        $('#eReturnedDate').val(data.returneddate);
        $('#eParticulars').val(data.particulars);
        $('#eMCrTNo').val(data.returnedno);
        //$('#eReturnedBy').val(data.returnedby);

        $("#eReturnedBy").select2().val(data.returnedby).trigger('change.select2');

        $('#eRefNo').val(data.refno);

        $('#didreturned').val(id);
        $('#dParticulars').html(data.particulars);
      }
    });
  }*/

</script>

