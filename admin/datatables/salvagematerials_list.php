<?php include '../session.php'; ?>

<section class="content">
  <?php include '../includes/toastmsg.php'; ?>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body text-sm">
          <table id="table2" class="table table-sm text-nowrap table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Control No.</th>
                <th>Purpose</th>
                <th>Returned By</th>
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

  $(document).ready(function() {
    $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
    $('[data-mask]').inputmask();
  });

  var dataTable = $('#table2').DataTable( {
    "lengthChange": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "columnDefs": [{
      "targets": [ 1 ],
      "render": function ( data, type, row ) {
        return '<a data-toggle="tooltip" title="View Details" href="salvagematerialsdetails.php?id='+row[4]+'">'+row[1]+'</a>';
        }
      },{
      "targets": [ 4 ],
      "render": function ( data, type, row ) {
        return '<center>'+
        '<div class="btn-group">'+
        '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_salvage('+row[4]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
        '<?php if($_SESSION["restriction"] == 101) { ?>'+
        '<button class="btn btn-danger btn-xs text-sm" title="Delete" onclick="delelete_salvage('+row[4]+')"><i class="fa fa-trash-alt"></i></button></div>'+
        '<?php } ?>'+
        '</div>'+
        '</center>';
      }
    }],
    "processing": true,
    "serverSide": true,
    "ajax":{
      url :"serverside/ss_salvage.php",
      type: "post",
      error: function(){}
    }
  });


  $('.add').click(function(e){
    e.preventDefault();
    if(Number(res) == 1 || Number(res) == 101){
      $('#add').modal('show');
    }
  });

  function edit_salvage(id) {

    $.ajax({
      url: 'datahelpers/salvage_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(iduser == data.iduser) {
          $('#eidsalvage').val(id);
          $('#etransactiondate').val(data.returneddate);
          $('#edescription').val(data.description);
          $('#esalvageno').val(data.salvageno);
          $("#ereturnedby").select2().val(data.empno).trigger('change.select2');
          $('#edit').modal('show');
        }
      }
    });
  };

  function delelete_salvage(id) {
    $.ajax({
      url: 'datahelpers/salvage_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(iduser == data.iduser || iduser == 101) {
          $('#didsalvage').val(id);
          $('#ddescription').html(data.description);
          $('#delete').modal('show');
        }
      }
    });
  };

</script>

