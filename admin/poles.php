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
            <div class="col-sm-1.5 mr-2"><h4> Poles</h4></div>

            <div class="col row">
              <button href="#add" class="btn btn-info btn-sm mt-1 ml-1" data-toggle="modal" title="New Item Index" >
                <i class="fa fa-plus-circle mr-2"></i>New
              </button>
            </div>

            <div class="col-sm-4">
              <ol class="breadcrumb">
                <div class="input-group input-group-sm">
                </div>
              </ol>
            </div>

          </div>
        </div>
        <div id="load_poles-list"></div>
      </div>
    </div>
  </div>
  <?php include 'includes/datatable-footer.php'?>
  <?php include 'modals/poles_modal.php'?>
  <script type="text/javascript" src="helper.js"></script>


</body>
</html>
