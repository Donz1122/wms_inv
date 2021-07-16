<?php include '../session.php'; ?>

<style type="text/css">

  .gray { color: gray; }
  .red { color: red; font-size: 12px; }
  .orange { color: orange; }
  .green { color: green; }

</style>


<section class="content">
 <?php include '../includes/toastmsg.php'; ?>
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-sm text-nowrap table-hover table-striped">
          <thead>
            <tr> <!-- 'idreceiptsdetails', 'warranty', 'itemcode', 'itemname', 'brandname', 'specifications', 'model', 'serialnos', 'quantity', 'unit', 'unitcost' -->
              <th style="display: none;"></th>
              <th>Warranty End</th>
              <th>Item Code</th>
              <th>Description</th>
              <th>Brand Name</th>
              <th>Specs.</th>
              <th>Model</th>
              <th>Serial Nos.</th>
              <th>Qty.</th>
              <th>Unit</th>
              <th>Unit Cost</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
</section>

<script>

  $(document).ready( function() {} );

  function load_warranty(switchs) {
    var dataTable = $('#table2').DataTable( {
      // "scrollY"       : "600px",
      "scrollX"       : true,
      // "scrollCollapse": true,
      // paging:         false,

      "responsive"    : true,
      "lengthChange"  : true,
      "searching"     : false,
      "autoWidth"     : false,
      "processing"    : true,
      "select"        : true,
      "keys"          : true,

      "columnDefs"    : [
      { "targets" : [ 0 ],    "visible"     : false, },
      { "targets" : [ 8,10 ], "className"   : "text-right", }],

      "processing"    : true,
      "serverSide"    : true,
      "ajax"          : {
        url           : "serverside/ss_warranty_item.php",
        type          : "post",
        data          : { switchs:switchs },
        error: function(){},
      },
      "createdRow": function( row, data, dataIndex ) {
        var dtDue = new Date(data[1]);
        var dtNow = new Date();
        if ( dtDue < dtNow ) {
          $(row).addClass('red');
        }
      },
    });
  }load_warranty();

  // var table = $('#table2').DataTable();
  // table.columns.adjust().draw();



</script>
