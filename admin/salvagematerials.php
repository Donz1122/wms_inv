<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php';
?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 ml-3">
              <h4>Salvage Materials</h4>
            </div>
            <div class="col-sm-4">
              <div class="btn-group">
                <button class="btn btn-info btn-sm add" data-toggle="modal"><i class="fa fa-plus-circle mr-1"></i> New</button>
              </div>
            </div>
          </div>
        </div>
        <div id="salvagematerials-list"></div>
      </div>
    </div>

    <?php include 'includes/datatable-footer.php'?>
    <?php include 'modals/salvage_modal.php'?>
  </div>

  <script>

    $('.select2').select2();

    var x = document.getElementById("sb_verification");
    var y = document.getElementById("sb_verification_b");
    var z = document.getElementById("sb_salvagematerials");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_salvagematerialslist(){
      $.ajax({
        url: 'datatables/salvagematerials_list.php',
        success: function (data) {
          $('#salvagematerials-list').html(data);
        },
        error: function(){
          alert('Error: salvagematerials');
        }
      });
    }load_salvagematerialslist();


  </script>
</body>
</html>
