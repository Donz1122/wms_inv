<?php
header('content-Type: text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html>
<html lang="en">

<?php
// include 'includes/header.php';
include 'includes/datatable-header.php';
include 'session.php';
include 'class/clsitems.php';
include 'includes/navbar-header.php';
include 'includes/sidebar.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-1.5 mr-2">
              <h4> Material Receipts</h4>
            </div>
            <div class="col">
              <button title="Add Receipts" class="btn btn-info btn-sm addreceipts"> New Receipt</button>
            </div>
            <div class=" ml-auto">
              <div class="input-group">
                <p class="breadcrumb-item col-form-label" style="text-align: right; padding-right: 10px;">Period Covered From  </p>
                <div class="col-sm-2.1">
                  <div class="input-group input-group-sm">
                    <input type="date" class="form-control form-control-sm" id="fromdate" name="fromdate" data-mask value="<?= $startdate; ?>">
                  </div>
                </div>
                <p class="breadcrumb-item col-form-label" style="text-align: right; padding: 5px 10px 0 10px;">To</p>
                <div class="col-sm-2.1">
                  <div class="input-group input-group-sm">
                    <input type="date" class="form-control form-control-sm" id="todate" name="todate" data-mask value="<?= $enddate; ?>">
                    <div class="input-group-append">
                      <div class="btn btn-info" onclick="search_daterange_receipt_list()">
                        <i class="fas fa-search"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="receipts-list"></div>
      </div>
    </div>
    <?php //include 'includes/footer.php'?>
    <?php include 'includes/datatable-footer.php'?>
    <?php include 'modals/receipts_modal.php'?>
  </body>
  </html>

  <script>

			   var x = document.getElementById("sb_materials");
			   var y = document.getElementById("sb_materials_b");
			   var z = document.getElementById("sb_receipts");
			   x.className = 'nav-item has-treeview menu-open';
			   y.className = 'nav-link active';
			   z.className = 'nav-link active';


			   $(function () {
				$('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
				$('[data-mask]').inputmask()
			  });

			  function load_receiptslist(){
				$.ajax({
				  url: 'datatables/receipts_list.php',
				  success: function (data) {
					$('#receipts-list').html(data);
				  },
				  error: function(){
					alert('Error: Receipts List');
				  }
				});
			  }load_receiptslist();

			  function search_daterange_receipt_list(){
				var startdate = $('#fromdate').val();
				var enddate = $('#todate').val();
				$('#table2').DataTable().destroy();
				fetch_data('yes',startdate,enddate);
			  };

			  $('#table2').on('key-focus.dt', function(e, datatable, cell) {
				$(table.row(cell.index().row).node()).addClass('selected');
			  });

			  $('#table2').on('key-blur.dt', function(e, datatable, cell) {
				$(table.row(cell.index().row).node()).removeClass('selected');
			  });


</script>
