<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/header.php';
include_once 'includes/navbar-header.php';
include_once 'includes/sidebar.php';
// require_once 'class/clsitems.php';
// $categories = $item->categories();
$sql="select distinct category from tbl_itemindex order by category asc";
$query = $db->query($sql);
?>

<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm">
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-1">
            <div class="col-sm-1.5 mr-2">
              <h4> Item Index</h4>
            </div>
            <div class="col row">

              <button class="btn btn-info btn-sm add mt-1 ml-1" data-toggle="modal" title="New Item Index" style="height: 31px;" >
                <i class="fa fa-plus-circle"></i>
                New
              </button>

              <div class="btn-group ml-1">
                <button class="btn btn-sm btn-info dropdown-toggle mt-1 status_label" data-toggle="dropdown" style="height: 31px">Status</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item " value="0" onclick="show_inactive(0)">Active</a>
                  <a class="dropdown-item " value="1" onclick="show_inactive(1)">In-Active</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item " value="2" onclick="show_inactive()">All Item Index</a>
                </div>
              </div>

            </div>

            <div class="btn-group ml-auto">
              <div class="input-group-prepend">
                <button class="btn btn-sm btn-info dropdown-toggle mt-1" data-toggle="dropdown" style="height: 31px">Printing</button>
                <div class="dropdown-menu">
                  <a class="dropdown-item " onclick="printItem(1)">List of All Consumable Items</a>
                  <a class="dropdown-item " onclick="printItem(0)">List of All Non-Consumable Items</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#modal-print_itemindexlist" aria-hidden="true" data-toggle="modal">Custom printing...</a>
                  <a class="dropdown-item" href="#modal-closing-print" aria-hidden="true" data-toggle="modal">Closing Transaction</a>
                </div>
              </div>
              <div class="icheck-success d-inline ml-1">
                <input type="checkbox" id="showwithquantity" name="showwithquantity" onchange="isshowwithquantity()">
                <label for="showwithquantity" style="font-size: 13px; font-weight: normal !important;">Show with quantity</label>
              </div>
            </div>

          </div>
        </div>

        <div id="itemindex-list"></div>
      </div>
    </div>

    <?php include_once 'includes/footer.php' ?>
    <script type="text/javascript" src="../dist/css/selectdatatables/datatables.min.js"></script>

    <?php include_once 'modals/itemindex_modal.php' ?>

    <div class="modal fade" id="modal-print_itemindexlist">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"> Print List</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="form-horizontal" method="POST" action="#" >
            <div class="modal-body">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Date From</label>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="datecoveredfrom" name="datecoveredfrom" data-mask value="<?= $startdate; ?>">
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label"> To </label>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="datecoveredto" name="datecoveredto" data-mask value="<?= $enddate; ?>">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Type</label>
                <div class="col">
                  <select class="form-control form-control-sm select2" id="type" name="type" >
                    <option value="0">Non Consumable</option>
                    <option value="1">Consumable</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label ml-2"></label>
                <div class="icheck-success d-inline">
                  <input type="checkbox" id="inczero" name="inczero">
                  <label for="inczero" style="font-size: 13px;">Include Zero Balance</label>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-left">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-info printlist" name=""><i class="fa fa-print"></i> Print</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>

  <script type="text/javascript">

    var x = document.getElementById("sb_otherinfo");
    var y = document.getElementById("sb_otherinfo_b");
    var z = document.getElementById("sb_itemindex");
    x.className = 'nav-item has-treeview menu-open';
    y.className = 'nav-link active';
    z.className = 'nav-link active';

    $(function () {
    });

    function load_itemindexlist(){
      $.ajax({
        url: 'datatables/itemindex_list.php',
        success: function (data) {
          $('#itemindex-list').html(data);
        },
        error: function(){
          alert('Error: itemindex List');
        }
      });
    }load_itemindexlist();

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