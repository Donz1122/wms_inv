<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php' ?>
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h4> Transferred MR</h4>
            </div>
          </div>
        </div>
        <div id="transferredmr-list"></div>
      </div>
    </div>

    <?php include 'includes/datatable-footer.php'?>
    <?php //include 'modals/receipts_modal.php'?>

  </body>
  </html>

  <script>

    $(function () {

      var x = document.getElementById("sb_verification");
      var y = document.getElementById("sb_verification_b");
      var z = document.getElementById("sb_transferredmr");
      x.className = 'nav-item has-treeview menu-open';
      y.className = 'nav-link active';
      z.className = 'nav-link active';

      $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
      $('[data-mask]').inputmask()
    });

    function load_transferedmrlist(){
      $.ajax({
        url: 'datatables/transferredmr_list.php',
        success: function (data) {
          $('#transferredmr-list').html(data);
        },
        error: function(){
        }
      });
    }load_transferedmrlist();


  </script>
