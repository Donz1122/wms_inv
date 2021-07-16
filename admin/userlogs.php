<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php'; ?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 mr-2"><h5> User Account</h5></div>
            <div class="col-sm-4">
              <ol class="breadcrumb">
                <div class="input-group input-group-sm">
                  <button title="Add New User" class="btn btn-info btn-sm add" data-toggle="modal"> Add User</button>
                </div>
              </ol>
            </div>
          </div>
        </div>
        <div id="useraccount-list"></div>
      </div>
    </div>
    <?php include 'includes/datatable-footer.php'?>
  </div>

  <script>

    $('.select2').select2();

    var x = document.getElementById("sb_maintenance");
    var y = document.getElementById("sb_maintenance_b");
    var z = document.getElementById("sb_userlogs");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_userlogs(){
      $.ajax({
        url: 'datatables/userlogs_list.php',
        type: 'post',
        success: function (data) {
          $('#useraccount-list').html(data);
        },
        error: function(){
          alert('Error: useraccount List');
        }
      });
    }load_userlogs();


  </script>

</body>
</html>
