<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include_once 'includes/navbar-header.php';
include_once 'includes/sidebar.php';

$x = "";
if(isset($_GET['id'])) {
 $x = $_GET['id'];
 // unset($_SESSION['expirethisyear']);
}
?>



<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-1">
            <div class="col-sm-1.5 mr-2">
              <h4> Warranty</h4>
            </div>
            <div class="col-4 row">

              <div class="btn-group ml-1">
                <button class="btn btn-sm btn-info dropdown-toggle mt-1 status_label" data-toggle="dropdown" style="height: 31px">Warranty</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item " value="0" onclick="select_warranty(0)">Expire this year</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item " value="1" onclick="select_warranty(1)">Not Expired</a>
                  <a class="dropdown-item " value="2" onclick="select_warranty(2)">Expired</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item " value="3" onclick="select_warranty()">All</a>
                </div>
              </div>
            </div>

            <div class="btn-group ml-auto">
            </div>

          </div>
        </div>

        <div id="warranty-list"></div>
      </div>
    </div>

    <?php include_once 'includes/datatable-footer.php'?>




  </div>

  <script type="text/javascript">

    $(document).ready(function() {
      var loadx = '<?= $x; ?>';
      if(loadx != '') {
        alert('Warranty expire this year...');
        select_warranty(0);
      }
    });

    var x = document.getElementById("sb_verification");
    var y = document.getElementById("sb_verification_b");
    var z = document.getElementById("sb_warranty");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';


    function load_warrantylist(){
      $.ajax({
        url: 'datatables/warranty_item_list.php',
        success: function (data) {
          $('#warranty-list').html(data);
        },
        error: function(){
          alert('Error: warranty List');
        }
      });
    }load_warrantylist();

    function select_warranty(e) {
      $('#table2').DataTable().destroy();
      load_warranty(e);
    };

    function printItem(e) {
      if((Number(res) == 0) || (Number(res) == 101) ) {
        var redirectWindow = window.open("printing/print_itemindex_list.php"+"?type="+e+"&zero=on", '_blank');
        redirectWindow.location;
      } else {
        alert('Viewing Only');
      }

    };

    $('.printlist').click(function(e){
      if((Number(res) == 0) || (Number(res) == 101) ) {
        e.preventDefault();
        $type = $('#type').val();
        $datefrom = $('#datecoveredfrom').val();
        $dateto = $('#datecoveredto').val();
        $zero = $('input[name="inczero"]:checked').val();

        var redirectWindow = window.open("printing/print_itemindex_list.php"+"?type="+$type+"&datefrom="+$datefrom+"&dateto="+$dateto+"&zero="+$zero, '_blank');
        redirectWindow.location;
      } else {
        alert('Viewing only...');
      }

    });



  </script>
</body>
</html>