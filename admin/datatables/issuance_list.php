<?php
include '../session.php';
include '../includes/toastmsg.php';
?>

<style type="text/css">

  table.dataTable th.focus,
  table.dataTable td.focus { outline: none; }
  .gray   { color: gray;    }
  .red    { color: red;     }
  .orange { color: orange;  }
  .green  { color: green;   }
  .blue   { background: blue;  color: white; }
</style>

<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body text-sm">
          <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
            <thead>
              <tr>
                <th style="display: none;"></th>
                <th style="width: 80px;">Date</th>
                <th>MRV No.</th>
                <th style="width: 140px;">Issuance No.</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Requisitioner</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script>

  var res       = "<?php echo $_SESSION['restriction']; ?>";
  var iduser    = "<?php echo $_SESSION['iduser']; ?>";
  var area      = "<?php echo $_SESSION['area']; ?>";

  $(document).ready(function() {
    $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
    $('[data-mask]').inputmask();
  });

  fetch_data('no');
  function fetch_data(isdatesearch,startdate='',enddate='',status=''){
    var dataTable = $('#table2').DataTable( {
      //"paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [{
        "targets": [ 0 ],
        "visible": false,
      },{
        "targets": [ 1 ],
        "className": "text-center",
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="#" onclick="view_issuance_details('+row[0]+')">'+row[1]+'</a>';
        }
      },{
        "targets": [ 2 ],
        "render": function ( data, type, row ) {
          return '<a data-toggle="tooltip" title="View Details" href="#" onclick="view_issuance_details('+row[0]+')">'+row[2]+'</a>';
        },
      },{
        "targets": [ 6 ],
        "className": "text-right",
      },{
        "targets": [ 8 ],
        "render": function ( data, type, row ) {
          return '<center>'+
          '<div class="btn-group">'+
          '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_issuance('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
          '<?php if($_SESSION["restriction"] == 101) { ?>'+
          '<button class="btn btn-danger btn-xs text-sm" title="Delete" onclick="delete_issuance('+row[0]+')"><i class="fa fa-trash-alt"></i></button></div>'+
          '<?php } ?>'+
          '</div>'+
          '</center>';
        }
      }],
      "processing": true,
      "serverSide": true,
      "ajax":{
        url :"serverside/ss_issuance.php",
        type: "post",
        data: {
          isdatesearch:isdatesearch,
          startdate:startdate,
          enddate:enddate,
          status:status
        },
        error: function(data){ alert('error'); }
      },
      // "createdRow": function( row, data, dataIndex ) {
      //   if ( data[5] == 'Canceled' ) {
      //     $(row).addClass('red');
      //   } else if ( data[5] == 'Pending' ) {
      //     $(row).addClass('orange');
      //   } else {
      //     $(row).addClass('black');
      //   }
      // }
    });
  };

  $('#table2 tbody').on( 'click', 'tr', function (e, datatable, cell) {
    $('#table2').DataTable().rows( '.selected' ).nodes().to$().removeClass( );
    $(this).toggleClass('selected');
    $('#table2').DataTable().rows( '.selected' ).nodes().to$().addClass( 'blue' );
  });

  $('#table2 tbody').on( 'click', 'tr', function () {
    $('#table2').DataTable().rows( '.selected' ).nodes().to$().removeClass( 'selected' );
    $(this).toggleClass('selected');
    $("input").change(function (e) {
      e.preventDefault();
      if ($(this).is(":checked")) {
        $("input").prop("checked", false);
        $(this).prop("checked", true);
      }
    });
  });

  $('.ticket_summary').click(function(e){
    e.preventDefault();
    if(Number(res) <= 1 || Number(res) == 101)  {
      var startdate = $('#fromdate').val();
      var redirectWindow = window.open("printing/issuance_print_ticket_summary.php"+"?id="+startdate, '_blank');
      redirectWindow.location;
      // window.location="printing/issuance_print_ticket_summary.php"+"?id="+startdate;
    } else {
      alert("Viewing only!");
    }
  });

  function view_issuance_details(id) {
    // var status = $('#table2').DataTable().rows('.selected').data()[0][5];
    if(Number(res) == 1 && area == 'DMO')  {
      window.location="issuancedetails.php?id="+id;  /*1 = warehouse*/
    } else {
      window.location="issuancedetails_addons.php?id="+id;
    }
    if(res == 101) {
      var ok = confirm("View as admin?");
      if(ok == true) {
        window.location="issuancedetails.php?id="+id;
      } else {
        window.location="issuancedetails_addons.php?id="+id;
      }
    }
  }

  function edit_issuance(id) {
    if(Number(res) > 0 || Number(res) == 101)  {
      // var y = document.getElementById("earea");
      $.ajax({
        url: 'datahelpers/issuance_helper.php',
        type: 'post',
        dataType: 'json',
        data: { id:id },
        success: function(data){
          if(Number(data.iduser) == Number(iduser) || Number(res) == 101) {
            if(data.trans == 'MCT') {
              // y.style.display = "none";
              $('#modal-editissuance').find('.modal-title').text('Material Charge Ticket');
              // $('#modal-editissuance').find('.ilabel').text('MCT No.');
            } else {
              // y.style.display = "";
              $('#modal-editissuance').find('.modal-title').text('Material Transfer Ticket');
              // $('#modal-editissuance').find('.ilabel').text('MTT No.');
            }
            $('#eidissuance').val(data.idissuance);
            $('#einumber').val(data.inumber);
            $('#eidate').val(data.idate);
            $('#epurpose').val(data.purpose);
            $('#ervno').val(data.rvno);

            $('#eempno').val(data.empno);

            $("#erequister").select2().val(data.empno).trigger('change.select2');

            $('#eissuedby').val(data.issuedby);
            $('#etransferto').val(data.transferto);
            $('#modal-editissuance').modal('show');

            if(Number(data.status) == 0) {
              document.getElementById("btn-edit").disabled = false;
            } else {
              document.getElementById("btn-edit").disabled = true;
            }



          } else {
            alert("Requisitioner only can modify this entry!");
          }
        }
      });
    } else {
      alert("Requisitioner only can modify this entry!");
    }
  }

  function delete_issuance(id) {
    $.ajax({
      url: 'datahelpers/issuance_helper.php',
      type: 'post',
      dataType: 'json',
      data: { id:id },
      success: function(data){
        if(Number(data.iduser) == Number(iduser) || (Number(res) == 101)) {
          $('#dinumber').val(data.inumber);
          $('#dpurpose').html(data.purpose);
          $('#didissuance').val(id);
          $('#modal-deleteissuance').modal('show');
        } else {
          alert("Requisitioner only can remove this entry!");
        }
      }
    });
  }

</script>

