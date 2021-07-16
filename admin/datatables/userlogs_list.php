<?php include '../session.php'; ?>
<section class="content">
  <div class="row">
    <div class="col-12">
     <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-sm text-nowrap table-hover table-striped">
          <thead>
            <tr>
              <th width="130px;">Date</th>
              <th>Transaction</th>
              <th width="200px;">User</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>


<script>

 var dataTable = $('#table2').DataTable( {
    "scrollX": true,
    "lengthChange": true,
    "searching": true,
    "paging": true,
    "ordering": true,
    "info": true,
    // "autoWidth": false,
    // "responsive": true,
    "processing": true,
    "serverSide": true,
    "ajax":{
      url :"serverside/ss_userlogs.php",
      type: "post",
      error: function(){}
    }
  });



</script>