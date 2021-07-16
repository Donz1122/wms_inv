<?php include '../session.php'; ?>

<section class="content">
  <div class="row">
    <div class="col-12">
     <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th width="130px;">Item Code</th>
              <th>Item Name</th>
              <th>Serial</th>
              <th>Parts Replacement</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>


<script>

 var dataTable = $('#table2').DataTable( {
    //"scrollX": true,
    "lengthChange"  : true,
    "searching"     : true,
    "paging"        : true,
    "ordering"      : true,
    "info"          : true,
    "autoWidth"     : false,
    "responsive"    : true,
    "columnDefs"    : [ {
        "targets"   : [ 0 ],
        "visible": false
      },{
        "targets"     : [ 1 ],
        "render"      : function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="equipment_history_details.php?id='+row[0]+'">'+row[1]+'</a>';
        }
      }],
    "processing"    : true,
    "serverSide"    : true,
    "ajax"          : {
      url           : "serverside/ss_equipment_history.php",
      type          : "post",
      error: function(){}
    }
  });



</script>