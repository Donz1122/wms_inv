<?php
include '../session.php';
$search = '';
if(isset($_POST['search'])){
  $search = $_POST['search'];
  $sql = "select idsupplier, suppliercode, suppliername, address, contactno, if(vatable=0,'','Vatable') vatable, if(Warehouse=0,'','Warehouse') warehouse
  from tbl_supplier
  where suppliername like '$search%' order by suppliername";
  $query = $db->query($sql);
} else {
  $sql = "select idsupplier, suppliercode, suppliername, address, contactno, if(vatable=0,'','Vatable') vatable, if(Warehouse=0,'','Warehouse') warehouse from tbl_supplier order by suppliername";
  $query = $db->query($sql);
}
?>

<section class="content">
  <?php include '../includes/toastmsg.php'; ?>
  <div class="row">
    <div class="col-12">
      <div class="card-body">
        <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
          <thead>
            <tr>
              <th style="display: none;"></th>
              <th>Name</th>
              <th>Address</th>
              <th>Contact #</th>
              <th>Warehouse</th>
              <th></th>
            </tr>
          </thead>

          <tbody>
            <?php
            while($row = $query->fetch_assoc()){ ?>
              <tr>
                <td style="display: none;"><?= $row['idsupplier'] ?></td>
                <td><?= $row['suppliername'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['contactno'] ?></td>
                <td><?= $row['warehouse'] ?></td>
                <td>
                  <center>
                    <div class="btn-group">
                      <button class="btn btn-warning btn-xs" title="Edit" onclick="editSupplier('<?= $row['idsupplier'] ?>')">
                        <i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
                      </button>
                      <?php if ($_SESSION['restriction'] == 101 ) { ?>
                        <button class="btn btn-danger btn-xs" title="Delete" onclick="delSupplier('<?= $row['idsupplier'] ?>')">
                          <i class="fa fa-trash-alt"></i>
                        </button>
                      <?php } ?>
                    </div>
                  </center>
                </td>
              </tr>
            <?php }; ?>
          </tbody>
        </table>
      </div>
      <!--/div-->
    </div>
  </div>
</section>


<script>

 $(document).ready(function() {

   var dataTable = $('#table2').DataTable( {
    "paging": true,
      //"lengthChange": false,
      //"searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      }],
      "processing": true,
    });

   $('#table2 tbody').on( 'click', 'tr', function () {
    $('#table2').DataTable().rows( '.selected' ).nodes().to$().removeClass( 'selected' );
    $(this).toggleClass('selected');
    /*$("input").change(function (e) {
      e.preventDefault();
      if ($(this).is(":checked")) {
        $("input").prop("checked", false);
        $(this).prop("checked", true);
      }
    });*/
  });

   $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
   $('[data-mask]').inputmask();

 });


 function editSupplier(id) {
  $('#edit').modal('show');
  getRow(id);
};

function delSupplier(id) {
  $('#delete').modal('show');
  getRow(id);
};

function getRow(id){
  $.ajax({
    url: 'datahelpers/suppliers_helper.php',
    type: 'POST',
    dataType: 'json',
    data: { id:id },
    success: function(data){

      $('#edit_idsupplier').val(id);
      $('#edit_suppliercode').html(data.suppliername);
      $('#edit_suppliername').val(data.suppliername);
      $('#edit_address').val(data.address);
      $('#edit_contactno').val(data.contactno);
      $('#edit_vatable').val(data.vatable);
      $('#edit_warehouse').val(data.warehouse);

      $('#del_suppliername').html(data.suppliername);
      $('#del_suppliercode').html(data.suppliername);
      $('#del_idsuppplier').val(id);

    }
  });

}

</script>

