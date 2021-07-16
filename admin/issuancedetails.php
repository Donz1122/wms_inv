<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'class/clsitems.php';
include 'includes/alertmsg.php';
?>

<!-- <link rel="stylesheet" href="../dist/css/selectdatatables/datatables.min.css"> -->

<style type="text/css">

  .gray   { color: gray;    }
  .red    { color: red;     }
  .orange { color: orange;  }
  .green  { color: green;   }

</style>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php

    $idissuance   = $_GET['id'];
    $query        = $item->get_issuance($idissuance);
    $a            = substr(strtoupper($query['inumber']),0,3);

    ?>

    <input type="hidden" id="idissuance"  name="idissuance" value="<?= $idissuance ?>">
    <input type="hidden" id="doctype"     name="doctype"    value="<?= $a ?>">
    <input type="hidden" id="inumber"     name="inumber"    value="<?= $query['inumber'] ?>">

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="ml-auto">
              <!-- <?php if($row['restriction'] == 2) { ?>
                <a href="index2.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                <?php } else { ?> -->
                <!-- <a href="issuance.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a> -->
                <!-- <?php }  ?> -->

              </div>
            </div>
          </div>

          <section class="content">
            <div class="row">
              <div class="col-sm-3">
                <div class="card card-primary ">
                  <div class="card-header">
                    <h3 class="card-title"><?= $a ?> Issuance Details</h3>
                  </div>
                  <div class="card-body">
                    <strong> Date</strong>
                    <p class="text-muted float-right"><?= date('M d, Y', strtotime($query['idate'])) ?></p>
                    <hr>
                    <strong> MRV Number</strong>
                    <p class="text-muted float-right"><?= $query['rvno'] ?></p>
                    <hr>
                    <strong> Purpose</strong>
                    <p class="text-muted"><?= utf8_decode($query['purpose']) ?></p>
                    <hr>
                    <strong> Doc. Number</strong>
                    <p class="text-muted float-right"><?= $query['inumber'] ?></p>
                  </div>
                </div>
              </div>

              <div class="col">
                <section class="content">
                  <div class="col-12 col-sm-12 mb-1">

                    <?php if($query['status'] == 0) { ?>
                      <button class="btn btn-sm btn-success approved">Approve</button>
                      <button class="btn btn-sm btn-warning cancel">Cancel | Return</button>
                    <?php } elseif($query['status'] == 1) { ?>
                      <button class="btn btn-warning btn-sm pending" title="Pending this transaction!">Pending</button>
                    <?php } ?>

                    <button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" style="height: 31px">Printing</button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item hardcopy"             href="#" > <?= $a ?> Hard Copy</a>
                      <a class="dropdown-item requisition_voucher"  href="#" > Requisition Voucher</a>
                      <a class="dropdown-item gate_pass"            href="#" > Gate Pass</a>
                    </div>

                    <a href="issuance.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>

                  </div>
                  <div class="col-12 col-sm-12 mb-1">
                    <div class="card card-warning card-outline">
                      <div class="card-body">
                        <table id="table2" class="table table-sm text-nowrap">
                          <thead>
                            <tr>
                              <th style="display: none;"></th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Serial Nos.</th>
                              <th>Unit</th>
                              <th style="text-align: right;">Qty</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </section>

        </div>
      </div>


    <!-- <center>
    <div class="btn-group">
    <button id="btn_edit" class="btn btn-xs btn-warning" onclick="edit_serial('<?= $row['idissuancedetails'] ?>')" data-toggle="tooltip" title="Modify" >
    <i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
    </button>
    </div>
  </center> -->

  <?php include 'includes/datatable-footer.php'?>
  <!-- <script type="text/javascript" src="../dist/css/selectdatatables/datatables.min.js"></script> -->
  <?php include 'modals/issuancedetails_modal.php'?>

  <script type="text/javascript">

    var res         = "<?php echo $_SESSION['restriction']; ?>";
    var iduser      = "<?php echo $_SESSION['iduser']; ?>";
    var idissuance  = "<?php echo $idissuance; ?>";
    var doc         = $('#inumber').val();

    function reload_issuance_details_table() {
      $('#table2').DataTable().ajax.reload();
    };

    show_issuance_details(idissuance,'','',1);
    function show_issuance_details(idissuance, approved='', filter='', submitted='') {
      var table = $('#table2').DataTable( {
        "paging": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
          "targets": [ 0 ],
          "visible": false,
        },{
          "targets": [ 5 ],
          "className": "text-right",
        },{
          "targets": [ 6 ],
          "render": function ( data, type, row ) {
            return '<center>'+
            '<div class="btn-group">'+
            '<button class="btn btn-warning btn-xs text-sm" title="Edit" onclick="edit_serial('+row[0]+')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span></button>'+
            '</div>'+
            '</center>';
          }
        }],
        "processing": true,
        "serverSide": true,
        "ajax":{
          url :"serverside/ss_issuance_details.php",
          type: "post",
          data: {
            idissuance:idissuance,
            approved:approved,
            filter:filter,
            submitted:submitted,
          },
          error: function() { }
        },
        keys: true,
      });

      // $('#table2').DataTable().rows('.selected').nodes().to$().removeClass( 'selected' );
      // $('#table2').on('key-focus.dt', function(e, datatable, cell){
      //   $(table.row(cell.index().row).node()).addClass('selected');
      // });
      // $('#table2').on('key-blur.dt', function(e, datatable, cell){
      //   $(table.row(cell.index().row).node()).removeClass('selected');
      // });
    };

    $('#table2').on('key.dt', function(e, datatable, key, cell, originalEvent){
      if(key == 13){
        var id = $('#table2').DataTable().rows('.selected').data()[0][0];
        edit_serial(id);
      }
    });

    $('.add').click(function(e){
      e.preventDefault();
      $('#modal-adddetails').modal('show');
      $('#ainumber').val(doc);
      $('#aidissuance').val($('#idissuance').val());
    });

    var idissuance  = "<?= $idissuance; ?>";
    var mrvno       = "<?= $query['rvno']; ?>";
    var status      = "<?= $query['status']; ?>";
    var requesterid = "<?= $query['iduser']; ?>";
    $('.approved').click(function(e){
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { isapproved:idissuance },
        success: function(data){
          if(Number(data.approved) == 0) {
            if(Number(data.rows) >= 1) {
              approved_details(idissuance,mrvno);
            } else {
              alert('Nothing to approve!')
            }
          } else {
            alert("Can't approve! or already approved!");
          }
        }
      });
    });
    function approved_details(approvedid,mrvno) {
      var ok = confirm("Are you sure to approve all this request?");
      if (ok == true) {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          //dataType: 'json',
          data: {
            approvedid:approvedid,
            mrvno:mrvno,
            inumber:doc,
            requesterid:requesterid,
          },
          success: function(data){
            history.back();
          }
        });
      }
    };
    $('.pending').click(function(e){
      var ok = confirm("Are you sure to pending this request?");
      if (ok == true) {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: {
            pendingid:idissuance,
            mrvno:mrvno,
            requesterid:requesterid,
          },
          success: function(data){
           if(data.ok == 1) { history.back(); }
         }
       });
      }
    });

    function edit_serial(id) {
      if((Number(res) == 1  && id != '') || Number(res) == 101)  {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            $('#apidissuancedetails').val(data.idissuancedetails);
            $('#apidissuance').val(data.idissuance);
            $('#apinumber').val(data.inumber);
            $('#apitemcode').val(data.itemcode);
            $('#apitemname').val(data.itemname);
            $('#apserialnos').val(data.serialnos);
            $('#apunit').val(data.unit);
            $('#apunitcost').val(data.unitcost);
            $('#apquantity').val(data.requestqty);
            $('#apquantity2').val(data.requestqty);

            $('#apremqty').val(data.remainingqty);

            $('#approved').modal('show');

            if(Number(status) == 0) {
              document.getElementById("btn-approved").disabled = false;
            } else {
              document.getElementById("btn-approved").disabled = true;
            }
          }
        });
      } else {
        alert("Viewing only!");
      }
    };


    function EditID(id) {
      if((Number(res) == 1  && id != '') || Number(res) == 101)  {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            if((Number(data.iduser) == Number(iduser)) && (Number(data.approved) == 0)){
              $('#eidissuancedetails').val(data.idissuancedetails);
              $('#einumber').val(data.inumber);
              $('#eiditemindex_i').val(data.iditemindex);
              $('#eitemcode').val(data.itemcode);
              $('#eitemname').val(data.itemname);

              $('#eunit').val(data.unit);
              $('#equantity').val(data.quantity);
              $('#eunitcost').val(data.unitcost);

              $('#edit').modal('show');

              if(Number(status) == 0) {
                document.getElementById("btn-approved").disabled = false;
              } else {
                document.getElementById("btn-approved").disabled = true;
              }
            }
          }
        });
      } else {
        alert("Viewing only!");
      }
    };

    $('.cancel').click(function(e){
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { isapproved:idissuance },
        success: function(data){
          if(Number(data.approved) == 0) {
            cancel_details(idissuance);
          } else {
            alert("Can't cancel!, request approved already...")
          }
        }
      });
    });
    function cancel_details(id) {
      var ok = confirm("Are you sure to decline this request?");
      if (ok == true) {
        if((Number(res) == 1  && id != '') || Number(res) == 101)  {
          $.ajax({
            url: 'datahelpers/issuancedetails_helper.php',
            type: 'post',
            // dataType: 'json',
            data: {
              declinedid:id,
              isdeclined:'yes',
              mrvno:mrvno,
            },
            success: function(data){
              window.history.back();
            }
          });
        } else {
          alert("Viewing only!");
        }
      }
    }

    $('.hardcopy').click(function(e){
      e.preventDefault();
      var mrvid = $('#idissuance').val();
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { mrvid:mrvid },
        success: function(data){
          if(data.rows > 0) {
            var redirectWindow = window.open("printing/issuance_print_hardcopy.php"+"?id="+mrvid, '_blank');
            redirectWindow.location;
            // window.location="printing/issuance_print_hardcopy.php"+"?id="+mrvid;
          } else {
            alert('Nothing to print...');
          }
        }
      });
    });

    $('.requisition_voucher').click(function(e){
      e.preventDefault();
      var mrvid = $('#idissuance').val();
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { mrvid:mrvid },
        success: function(data){
          if(data.rows > 0) {
            var redirectWindow = window.open("printing/issuance_print_requisition_voucher.php"+"?id="+mrvid, '_blank');
            redirectWindow.location;
            // window.location="printing/issuance_print_requisition_voucher.php"+"?id="+mrvid;
          } else {
            alert('Nothing to print...');
          }
        }
      });
    });

    $('.gate_pass').click(function(e){
      e.preventDefault();
      var mrvid = $('#idissuance').val();
      var inumber = $('#inumber').val();
      var doctype = $('#doctype').val();
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { mrvid:mrvid },
        success: function(data){
          if(data.rows > 0) {
            var redirectWindow = window.open("printing/issuance_print_gatepass.php"+"?ino="+mrvid+"&doc="+inumber, '_blank');
            redirectWindow.location;
            // window.location="printing/issuance_print_gatepass.php"+"?ino="+mrvid+"&doc="+inumber;
          } else {
            alert('Nothing to print...');
          }
        }
      });
    });

  </script>

</body>
</html>
