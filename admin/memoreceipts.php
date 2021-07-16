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
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
              <li class="pt-2 px-3"><h4 class="card-title">Memorandum Receipts</h4></li>
              <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true"><i class="fas fa-user mr-1"></i>Employees</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false"><i class="fas fa-network-wired mr-1"></i>Department</a>
              </li>
              <button title="Add MR for Employee's" href="#modal-add_mr_employees" class="btn btn-default btn-sm mb-1 ml-2" data-toggle="modal" ><i class="fa fa-plus-circle mr-1"></i> Employee's MR</button>
              <button title="Add MR for Department" href="#modal_add_mr_department" class="btn btn-default btn-sm mb-1 ml-2" data-toggle="modal" ><i class="fa fa-plus-circle mr-1"></i> Department MR</button>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-two-tabContent">
              <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
               <div id="mr_employee_list"></div>
             </div>
             <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
               <div id="mr_department_list"></div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>

   <?php include 'includes/datatable-footer.php'?>
   <?php include 'modals/mr_department_modal.php'?>
   <?php include 'modals/mr_employees_modal.php'?>

 </body>
 </html>


 <script>

   var x = document.getElementById("sb_materials");
   var y = document.getElementById("sb_materials_b");
   var z = document.getElementById("sb_memoreceipts");
   x.className = 'nav-item has-treeview menu-open';
   y.className = 'nav-link active';
   z.className = 'nav-link active';

   function load_mr_employee(){
    $.ajax({
      url: 'datatables/mr_employee_list.php',
      success: function (data) {
        $('#mr_employee_list').html(data);
      },
      error: function(){}
    });
  }load_mr_employee();

  function load_mr_department(){
    $.ajax({
      url: 'datatables/mr_department_list.php',
      success: function (data) {
        $('#mr_department_list').html(data);
      },
      error: function(){}
    });
  }load_mr_department();

</script>
