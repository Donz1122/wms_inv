
<?php
include '../session.php';
include '../includes/toastmsg.php';
$sql = "select * from tbl_department order by deptname asc";
$query = $db->query($sql);
?>

<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card-body">
        <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
          <thead>
            <tr>
              <th style="display: none;"></th>
              <th width="90px;">Dept. Code</th>
              <th>Department</th>
              <th width="70px;"></th>
            </tr>
          </thead>

          <tbody>
            <?php
            while($row = $query->fetch_assoc()){ ?>
              <tr>
                <td style="display: none;"><?= $row['iddepartment'] ?></td>
                <td style="text-align: center;"><?= $row['deptcode'] ?></td>
                <td><?= $row['deptname'] ?></td>
                <td>
                  <center>
                    <div class="btn-group">
                      <button class="btn btn-warning btn-xs" title="Edit" onclick="edit_department('<?= $row['iddepartment'] ?>')">
                        <i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
                      </button>
                      <?php if ($_SESSION['restriction'] == 101) { ?>
                        <button class="btn btn-danger btn-xs" title="Delete" onclick="delete_department('<?= $row['iddepartment'] ?>')">
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

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";

  $(document).ready(function() {

    var dataTable = $('#table2').DataTable( {
      "paging": true,
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
  });


  function edit_department(id) {
    //if(res == 0 || res == 101) {
      $.ajax({
        url: 'datahelpers/department_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          $('#eiddepartment').val(id);
          $('#edeptcode').val(data.deptcode);
          $('#odeptname').val(data.deptname);
          $('#edeptname').val(data.deptname);
          $('#edit').modal('show');
        }
      });
    //}
  };

  function delete_department(id) {
    // if(res == 0 || res == 101) {
      $.ajax({
        url: 'datahelpers/department_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          $('#diddepartment').val(id);
          $('#ddeptname').val(data.deptname);
          $('#ddeptname2').html(data.deptname);
          $('#delete').modal('show');
        }
      });
    // }
  };

</script>



