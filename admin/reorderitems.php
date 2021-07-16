<!DOCTYPE html>

<html lang="en">
<?php include 'includes/datatable-header.php'?>
<?php include 'session.php'?>
<?php include 'includes/navbar-header.php'?>
<?php include 'includes/sidebar.php' ?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 mr-2">
              <h4>Reorder Items</h4>
            </div>
            <div class="col-sm-4">
              <a  href="#" title="Print" class="btn btn-info btn-sm print" data-toggle="modal"><i class=" mr-1"></i> Print List</a>
            </div>
          </div>
        </div>
        <div id="reorder-list"></div>
      </div>
    </div>
    <?php include 'includes/datatable-footer.php'?>
  </div>
  <script>

    var x = document.getElementById("sb_verification");
    var y = document.getElementById("sb_verification_b");
    var z = document.getElementById("sb_reorderlist");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_reorderlist(){
      $.ajax({
        url: 'datatables/reorderitems_list.php',
        success: function (data) {
          $('#reorder-list').html(data);
        },
        error: function(){
          alert('Error: reorder List');
        }
      });
    }load_reorderlist();


  </script>

</body>
</html>