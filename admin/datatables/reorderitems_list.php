<?php include '../session.php'; ?>
<?php include '../includes/toastmsg.php'; ?>
<?php include '../serverside/ss_itemindex_helper.php'; ?>

<section class="content">
  <div class="row">
    <div class="col-12">
     <div class="card">
      <div class="card-body">
        <table id="table2" class="table table-sm text-nowrap table-hover table-striped">
          <thead>
            <tr>
              <th style="display: none;"></th>
              <th>Item Code</th>
              <th>Description</th>
              <th>Category</th>
              <th>Unit</th>
              <th>Type</th>
              <th align="center">Current Qty</th>
              <th align="center" title="Reorder Point">Reorder Point</th>
              <th width="30px;"></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>

<script>

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";
  var area      = "<?php echo $_SESSION['area']; ?>";

  $(document).ready(function() {
    $('#table2 tbody').on( 'click', 'tr', function () {
      $('#table2').DataTable().rows( '.selected' ).nodes().to$().removeClass( 'selected' );
    });
  });

  fetch_data('no');
  function fetch_data(issearch, itemname=''){
    var dataTable = $('#table2').DataTable( {
      "paging": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [2,"asc"],
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      },{
        "targets": [ 1 ],
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="itemindexdetails.php?id='+row[0]+'&no='+row[1]+'">'+row[1]+'</a>';
        }
      },{
        "targets": [ 6,7 ],
        "className": "text-center",
      },{
        "targets": [ 8 ],
        "render": function ( data, type, row ) {
          return '<center>'+
          '<div class="btn-group">'+
          '<button class="btn btn-success btn-xs" title="Hide this item on alert..." onclick="hideItemAlert('+row[0]+')"><span style="font-size: 12px;"> Hide</span></button>'+
          '</div>'+
          '</center>';
        }
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_reorderitems.php",
        type: "post",
        data: {
          issearch:issearch,
          itemname:itemname,
        },
        error: function() {}
      }
    });
  }

  function search_itemindex(){
    var empname = $('#search').val();
    $('#table2').DataTable().destroy();
    fetch_data('yes',empname);
  };

  function hideItemAlert(id) {
    if(Number(res) == 0 || Number(res) == 101)  {
      var ok =confirm("Are you sure to hide reorder alert for this item?");
      if (ok == true) {
        var empname = $('#search').val();
        $.ajax({
          url: 'datahelpers/record_finder_helper.php',
          type: 'post',
          data: { id:id },
          success: function(data){
            window.location.reload(true);
          },
          error: function() {},
        });
      }
    } else {
      alert('Viewing only...');
    }
  }

  $('.print').click(function(e){
    e.preventDefault();
    window.location="printing/print_reorderlist.php";
  });

</script>