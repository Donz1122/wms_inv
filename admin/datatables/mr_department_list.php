<?php include '../session.php'; ?>
<?php include '../includes/toastmsg.php'; ?>

<section class="content">
  <div class="row">
    <div class="col-12">
      <table id="table3" class="table table-bordered table-hover table-striped table-sm">
        <thead>
          <tr>
            <th style="display: none;"></th>
            <th width="400px;">Department</th>
            <th>MR Numbers</th>
            <th>Amount</th>
          </tr>
        </thead>
      </table>
    </div>
  </section>

  <script>

    var res       = "<?php echo $_SESSION['restriction']; ?>";
    var iduser    = "<?php echo $_SESSION['iduser']; ?>";

    var dataTable = $('#table3').DataTable( {
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
          return '<a data-toggle="tooltip" title="View Details" target="" href="mr_department_details.php?id='+row[0]+'">'+row[1]+'</a>';
        }
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
        url :"serverside/ss_mr_department.php",
        type: "post",
        error: function(){}
      }
    });

    // $('.add_mr_department').click(function(e){
    //   e.preventDefault();
    //   if(res == 1 || res == 101) $('#modal-addmr').modal('show');
    // });

  </script>