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
        <div class="row mb-1">
          <div class="col-sm-1.5 mr-2 ml-3">
            <h4> Supplier</h4>
          </div>
          <div class="col">
            <button href="#addnew" class="btn btn-info btn-sm" data-toggle="modal" ><i class="fa fa-plus-circle mr-1"></i> New Supplier
            </button>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <div id="suppliers-list"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'includes/datatable-footer.php'?>
  <?php include 'modals/suppliers_modal.php'?>

  <script type="text/javascript">

   var x = document.getElementById("sb_otherinfo");
   var y = document.getElementById("sb_otherinfo_b");
   var z = document.getElementById("sb_suppliers");
   x.className = 'nav-item has-treeview menu-open';
   y.className = 'nav-link active';
   z.className = 'nav-link active';

   function load_supplierslist(){
    $.ajax({
      url: 'datatables/suppliers_list.php',
      type: 'post',
      success: function (data) {
        $('#suppliers-list').html(data);
      },
      error: function(){
        alert('Error: suppliers_list');
      }
    });
  }load_supplierslist();

  /*function load_itemcategory(){
    $.ajax({
      url: 'datatables/itemcategory_list.php',
      type: 'post',
      success: function (data) {
            //$('#category-list').html(data);
          },
          error: function(){
            alert('Error: itemcategory_list');
          }
        });
  }load_itemcategory();*/

</script>

</body>
</html>
