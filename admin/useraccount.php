<!DOCTYPE html>
<html lang="en">
<?php include 'session.php';
include 'includes/header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php'; ?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 mr-2"><h4> User Account</h4></div>
            <div class="col-sm-4">
              <div class="input-group input-group-sm">
                <button title="Add New User" class="btn btn-info btn-sm add" data-toggle="modal"><i class="fa fa-plus-circle mr-1"></i> New User</button>
              </div>
            </div>
          </div>
        </div>
        <div id="useraccount-list"></div>
      </div>
    </div>

    <?php include 'includes/footer.php'?>
    <?php include 'modals/useraccount_modal.php'?>
  </div>

  <script>

    $('.select2').select2();
    
    var x = document.getElementById("sb_otherinfo");
    var y = document.getElementById("sb_otherinfo_b");
    var z = document.getElementById("sb_users");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_userlist(){
      $.ajax({
        url: 'datatables/useraccount_list.php',
        type: 'post',
        success: function (data) {
          $('#useraccount-list').html(data);
        },
        error: function(){
          alert('Error: useraccount List');
        }
      });
    }load_userlist();

  </script>

</body>
</html>
