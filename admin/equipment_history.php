<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php';
//include 'serverside/ss_equipment_history_helper.php';
?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 mr-2"><h5> Equipment History</h5></div>
            <div class="col-sm-4">
              <ol class="breadcrumb">
                <div class="input-group input-group-sm">
                </div>
              </ol>
            </div>
          </div>
        </div>
        <div id="equipment_history-list"></div>
      </div>
    </div>
    <?php include 'includes/datatable-footer.php'?>
  </div>

  <script>

    $('.select2').select2();

    var x = document.getElementById("sb_history");
    var y = document.getElementById("sb_history_b");
    var z = document.getElementById("sb_history1");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_userlogs(){
      $.ajax({
        url: 'datatables/equipment_history_list.php',
        type: 'post',
        success: function (data) {
          $('#equipment_history-list').html(data);
        },
        error: function(){
          alert('Error: equipment_history List');
        }
      });
    }load_userlogs();


  </script>

</body>
</html>
