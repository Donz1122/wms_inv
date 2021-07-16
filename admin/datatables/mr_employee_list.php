<?php include '../session.php'; ?>
<?php include '../includes/toastmsg.php'; ?>

<section class="content">
  <div class="row">
    <div class="col-12">
      <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
        <thead>
          <tr>
            <th style="display: none;"></th>
            <th>Employee Name</th>
            <th>MR Numbers</th>
            <th>Amount</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</section>

<script type="text/javascript">
  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";

  function load_mr_employees_list() {
    var dataTable = $('#table2').DataTable( {
      "keys": true,
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "order":[[ 1, "asc" ]],
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      },{
        "targets": [ 1 ],
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" target="" href="mr_employees_details.php?id='+row[0]+'">'+row[1]+'</a>';
        }
      },{
        "targets": [ 2 ],
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" target="" href="mr_employees_details.php?id='+row[0]+'" style="color: black">'+row[2]+'</a>';
        }
      },{
        "targets": [ 1 ],
        "width": "200px",
      },{
        "targets": [ 2 ],
        "font-size": "8px",
      },{
        "targets": [ 3 ],
        "className": "text-right",
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_mr_employees.php",
        type: "post",
        error: function(){}
      }
    });
  }load_mr_employees_list();


</script>