<?php include '../session.php'; ?>
<section class="content">
 <?php include '../includes/toastmsg.php'; ?>
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-sm text-nowrap table-hover table-striped">
          <thead>
            <tr>
              <th style="display: none;"></th>
              <th>Date Transfer</th>
              <th>Item Code</th>
              <th>Item Name</th>
              <th>Brand Name</th>
              <th>Specification</th>
              <th>Model</th>
              <th>Serial No.</th>
              <th>From</th>
              <th>To</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
</section>

<script>

  $(document).ready(function() {
    var dataTable = $('#table2').DataTable( {
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,

      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      }],

      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_transferredmr.php",
        type: "post",
        error: function(){}
      }
    });
  });



</script>
