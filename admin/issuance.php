<?php
header('content-Type: text/html; charset=ISO-8859-1');
?>
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
              <h4> Material Issuance</h4>
            </div>
            <div class="col-sm-4 row">
              <?php
              $res     = $_SESSION['restriction'];
              $area    = $_SESSION['area'];
              if($res == 2 )  { ?>
                <div class="btn-group btn-sm row">
                  <button class="btn btn-info btn-sm" style="margin-top: -3px; height: 30px;" onclick="prepareIssuanceNumber('mct')" > New MRV</button>
                </div>
              <?php } elseif ($res == 1 && $area <> 'DMO') { ?>
                <div class="btn-group btn-sm row">
                  <button class="btn btn-info btn-sm" style="margin-top: -3px; height: 30px;" onclick="prepareIssuanceNumber('mtt')" > New MRV</button>
                </div>
              <?php } elseif ($res == 101) { ?>
                <div class="input-group-prepend ml-2" style="height: 39px;">
                  <button class="btn btn-sm btn-info dropdown-toggle status_label" data-toggle="dropdown" style="height: 30px">New MRV</button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="prepareIssuanceNumber('mct')">MCT</a>
                    <a class="dropdown-item" href="#" onclick="prepareIssuanceNumber('mtt')">MTT</a>
                  </div>
                </div>
              <?php }?>
              <!-- <button class="btn btn-info btn-sm ticket_summary"> Ticket Monthly Summary</button> -->

              <div class="input-group-prepend ml-2" style="height: 40px;">
                <button class="btn btn-sm btn-info dropdown-toggle status_label" data-toggle="dropdown" style="height: 31px">Status</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" onclick="selected_status(0)">Pending</a>
                  <a class="dropdown-item" href="#" onclick="selected_status(1)">Approved</a>
                  <a class="dropdown-item" href="#" onclick="selected_status(2)">Cancelled</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" onclick="selected_status(3)">All</a>
                </div>
              </div>
            </div>

            <div class="row ml-auto">

              <div class="btn-group ">
                <p class="col-form-label" style="text-align: right; padding-right: 10px;">Period Covered From  </p>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <input type="date" class="form-control form-control-sm" id="fromdate" name="fromdate" data-mask value="<?= $startdate; ?>">
                  </div>
                </div>
              </div>
              <div class="btn-group ">
                <p class="col-form-label" style="text-align: right; padding: 5px 10px 0 10px;">To</p>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <input type="date" class="form-control form-control-sm" id="todate" name="todate" data-mask value="<?= $enddate; ?>">
                    <div class="input-group-append ">
                      <div class="btn btn-info" onclick="search_daterange_issuancelist()">
                        <i class="fas fa-search"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="input-group-prepend mr-2" style="height: 39px;">
                <button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" style="height: 31px">Printing</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item " onclick="print_issuance('MCT')">Material Charge Ticket</a>
                  <a class="dropdown-item " onclick="print_issuance('MTT')">Material Transfer Ticket</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" onclick="print_issuance('All')">All Material Issuance Ticket</a>
                </div>
              </div>

            </div>

          </div>
        </div>
        <div id="issuance-list"></div>
      </div>
    </div>

    <?php include 'includes/datatable-footer.php'?>
    <?php include 'modals/issuance_modal.php'?>

  </div>

  <script>

    var x = document.getElementById("sb_materials");
    var y = document.getElementById("sb_materials_b");
    var z = document.getElementById("sb_issuance");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    function load_issuancelist(){
      $.ajax({
        url: 'datatables/issuance_list.php',
        success: function (data) {
          $('#issuance-list').html(data);
        },
        error: function(){
          alert('Error: Issuance List');
        }
      });
    }load_issuancelist();

    function search_daterange_issuancelist(){
      var startdate = $('#fromdate').val();
      var enddate = $('#todate').val();
      $('#table2').DataTable().destroy();
      fetch_data('yes',startdate,enddate);
    };

    var empno = "<?= $_SESSION['empno'] ?>";
    var area  = "<?= $_SESSION['area'] ?>";
    function prepareIssuanceNumber(x)  {
      $('#idissuance').val('');
      $('#inumber').val('');
      $('#purpose').val('');
      $('#amount').val('0.00');
      $('#jono').val('');
      var y = document.getElementById("area");

      $.ajax({
        url: 'datahelpers/issuance_helper.php',
        type: 'post',
        dataType: 'json',
        data: { x:x },
        success: function (data) {
          if(x == 'mct') {
            y.style.display = 'none';
            $('#modal-addissuance').find('.modal-title').text('Material Charge Ticket');
            $('#modal-addissuance').find('.ilabel').text('MCT No.');
            $('#inumber').val(data.num);
            $('#rvno').val(data.rvno);
          } else {
            y.style.display = '';
            $('#modal-addissuance').find('.modal-title').text('Material Transfer Ticket');
            $('#modal-addissuance').find('.ilabel').text('MTT No.');
            $('#inumber').val(data.num);
            $('#rvno').val(data.rvno);
          }

          $("#requister").select2().val(empno).trigger('change.select2');
          $('#empno').val(empno);
          $('#modal-addissuance').modal('show');

          $("#transferto").select2().val(area).trigger('change.select2');

        },
        error: function(ex){
          alert('issuance line: 122');
        }
      });
    };

    function selected_status(num) {
      if(num == 0) $('.status_label').html('Pending');
      if(num == 1) $('.status_label').html('Approved');
      if(num == 2) $('.status_label').html('Cancelled');
      if(num == 3) $('.status_label').html('All');

      $('#table2').DataTable().destroy();
      fetch_data('no','','',num);
    };

    function print_issuance(trans) {
      var from  = $('#fromdate').val();
      var to    = $('#todate').val();

      var redirectWindow = window.open('printing/issuance_print_ticket_summary.php?id='+trans+'&from='+from+'&to='+to, '_blank');
      redirectWindow.location;
    };

  </script>
</body>
</html>
