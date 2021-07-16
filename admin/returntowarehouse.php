<!DOCTYPE html>
<html lang="en">
<?php include 'session.php'?>
<?php include 'includes/datatable-header.php'?>
<?php include 'includes/navbar-header.php'?>
<?php include 'includes/sidebar.php' ?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
           <div class="col-sm-1.5 mr-2">
            <h4>Return To Warehouse</h4>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb">
              <div class="btn-group">
                <button class="btn btn-info btn-sm add" data-toggle="modal"><i class="fa fa-plus-circle mr-1"></i> New</button>
              </div>
            </ol>
          </div>
        </div>
      </div>
      <div id="returntowarehouse-list"></div>
    </div>
  </div>
  <?php include 'includes/datatable-footer.php'?>
  <?php include 'modals/returntowarehouse_modal.php'?>
</div>

<script>

  $('.select2').select2();

  var x = document.getElementById("sb_verification");
  var y = document.getElementById("sb_verification_b");
  var z = document.getElementById("sb_returntowarehouse");
  x.className = 'nav-item has-treeview menu-open';
  y.className = 'nav-link active';
  z.className = 'nav-link active';

  function load_returntowarehouselist(){
    $.ajax({
      url: 'datatables/returntowarehouse_list.php',
      success: function (data) {
        $('#returntowarehouse-list').html(data);
      },
      error: function(){
        alert('Error: returntowarehouse');
      }
    });
  }load_returntowarehouselist();

</script>
</body>
</html>
