<!DOCTYPE html>
<html lang="en">
<?php
include 'session.php';
include 'includes/datatable-header.php';
include 'class/clsitems.php';
?>

<style type="text/css">

  .gray { color: gray; }
  .red { color: red; }
  .orange { color: orange; }
  .green { color: green; }

</style>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php
    include 'includes/navbar-header.php';
    include 'includes/alertmsg.php';
    ?>

    <?php
    $inumber = $_GET['id'];
    $query   = $item->get_issuance($inumber);
    $a       = substr(strtoupper($query['inumber']),0,3) ?>

    <input type="hidden" id="doctype" name="doctype" value="<?= $a ?>">
    <input type="hidden" id="inumber" name="inumber" value="<?= $query['inumber'] ?>">
    <input type="hidden" id="idissuance" name="idissuance" value="<?= $_GET['id'] ?>">

    <div class="content-wrapper">
      <div class="content-header">
        <section class="content">
          <div class="row">
           <div class="col-md-3">
            <div class="card card-primary">
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
                <hr>
                <strong> By</strong>
                <p class="text-muted float-right"><?= $query['receivedby'] ?></p>
              </div>
            </div>
          </div>

          <div class="col">
            <section class="content">
              <div class="col-12 col-sm-12 mb-1">

                  <?php if (intval($query['issubmitted']) == 0) { ?>
                    <button class="btn btn-info btn-sm add">Add Item</button>
                  <?php } else { ?>
                    <?php if(intval($query['iscancelled']) == 1) { ?>
                      <button class="btn btn-info btn-sm add">Add Item</button>
                    <?php } ?>
                  <?php } ?>
                  <!-- <button class="btn btn-info btn-sm hardcopy"> Hard Copy</button> -->
                  <button class="btn btn-info btn-sm requisition_voucher"> Requisition Voucher</button>

                  <?php if(intval($query['issubmitted']) == 1) { ?>
                    <?php if(intval($query['iscancelled']) == 1) { ?>
                      <button class="btn btn-success btn-sm submit_request"> Re-submit</button>
                    <?php } else { ?>
                      <button class="btn btn-success btn-sm"> Submitted</button>
                    <?php } ?>
                  <?php } else { ?>
                    <button class="btn btn-warning btn-sm submit_request"> Submit Requisition</button>
                  <?php } ?>

                  <?php if($_SESSION['restriction'] == 2) { ?>
                    <a href="index2.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                  <?php } else { ?>
                    <a href="issuance.php" class="btn btn-danger btn-sm" ><i class="fa fa-arrow-alt-circle-left mr-1"></i> Back</a>
                  <?php }  ?>

              </div>
              <div class="col-12 col-sm-12 mb-1">
                <div class="card card-warning card-outline">
                  <div class="card-body">
                    <table id="table2" class="table table-nowrap table-hover table-striped table-sm">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Item Code</th>
                          <th>Item Name</th>
                          <th>Serials</th>
                          <th>Unit</th>
                          <th style="text-align: right;">Qty</th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $rows = $item->get_issuancedetails($inumber)->fetchAll();
                        foreach($rows as $row): ?>
                          <tr>
                            <td><?= $row['idissuancedetails'] ?></td>
                            <td><?= $row['itemcode']; ?></td>
                            <td><?= $row['itemname']; ?></td>
                            <td><?= $row['tag_no']; ?></td>
                            <td><?= $row['unit']; ?></td>
                            <td align="right"><?= $row['requestqty']; ?></td>
                            <td style="width: 120px;">
                              <center>
                                <div class="btn-group">
                                  <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" onclick="edit_issuance_details('<?= $row['idissuancedetails'] ?>')"><i class="fa fa-pencil-alt mr-1"></i><span style="font-size: 12px;">Edit</span>
                                  </button>
                                  <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" onclick="delete_issuance_details('<?= $row['idissuancedetails'] ?>')"><i class="fa fa-trash-alt faa-ring animated-hover"></i><span style="font-size: 12px;"></span>
                                  </button>
                                </div>
                              </center>
                            </td>
                            <td style="display: none;"><?= $row['approved'] ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
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

  <?php include 'includes/datatable-footer.php'?>
  <?php include 'modals/issuancedetails_modal.php'?>

  <script type="text/javascript">

    var res       = "<?php echo $_SESSION['restriction']; ?>";
    var iduser    = "<?php echo $_SESSION['iduser']; ?>";

    var table = $('#table2').DataTable( {
      "paging": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "columnDefs": [
      {
        "targets": [ 0,7 ],
        "visible": false
      }],
      "processing": true,
      // "createdRow": function( row, data, dataIndex ) {
      //   if ( data[6] == 1 ) {
      //     $(row).addClass('green');
      //   } else if ( data[6] == 2 ) {
      //     $(row).addClass('red');
      //   } else {
      //     $(row).addClass('gray');
      //   }
      // }
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

    $('.add').click(function(e){
      e.preventDefault();
      $('#modal-adddetails').modal('show');
      $('#ainumber').val($('#inumber').val());
      $('#aidissuance').val($('#idissuance').val());
    });

    function edit_issuance_details(id) {
      // if(Number(res) == 2)  {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            if(Number(data.issubmitted) == Number(0)) {
              if(Number(data.iduser) == Number(iduser)) {
                $('#eidissuancedetails').val(data.idissuancedetails);
                $('#einumber').val(data.inumber);
                $('#eiditemindex_i').val(data.iditemindex);
                $('#eitemcode').val(data.itemcode);
                $('#eitemname').val(data.itemname);

                $('#eunit').val(data.unit);
                $('#equantity').val(data.requestqty);
                $('#eunitcost').val(data.unitcost);
                $('#etag_no').val(data.tag_no);
                $('#etag_remarks').val(data.tag_remarks);

                $('#edit').modal('show');

                if(Number(data.approved) == 1)
                  document.getElementById("btn-update").disabled = true;
                else
                  document.getElementById("btn-update").disabled = false;
              }  else {
                alert("Viewing only!");
              }
            } else {
              alert("Submitted item can't be modified!");
            }
          }
        });
      // } else {
      //   alert("Viewing only!");
      // }
    };

    function delete_issuance_details(id) {
      if(Number(res) == 2 && id != '')  {
        $.ajax({
          url: 'datahelpers/issuancedetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function(data){
            if(Number(data.issubmitted) == Number(0)) {
              if((Number(data.iduser) == Number(iduser)) && (Number(data.approved) == 0)){
                $('#d_idissuancedetails').val(data.idissuancedetails);
                $('#d_inumber').val(data.inumber);
                $('#ditemname').val(data.itemname);
                $('#d_itemname').html(data.itemname);
                $('#d_itemindexcode').html(data.itemcode);
                $('#delete').modal('show');
              } else {
                alert("Approved item can't be remove!");
              }
            } else {
              alert("Submitted item can't be modified!");
            }
          }
        });
      } else {
        alert("Viewing only!");
      }
    };

    $('.requisition_voucher').click(function(e){
      e.preventDefault();
      var mrvid = $('#idissuance').val();
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        dataType: 'json',
        data: { mrvid:mrvid },
        success: function(data){
          if(Number(data.rows) >= 1 ) {
            var redirectWindow = window.open("printing/issuance_print_requisition_voucher.php"+"?id="+mrvid, '_blank');
            redirectWindow.location;
            // window.location="printing/issuance_print_requisition_voucher.php"+"?id="+mrvid;
          } else {
            alert("Nothing to print...");
          }
        }
      });
    });

    $('.submit_request').click(function(e){
      if ( (table.data().count() > 0) ) {
        var submitid  = $('#idissuance').val();
        var remarks   = "<?= $query['purpose']; ?>";
        var by        = "<?= $query['receivedby']; ?>";
        var ok        = confirm('Are you sure to submit this request?');
        if(ok == true) {
          $.ajax({
            url: 'datahelpers/issuancedetails_helper.php',
            type: 'post',
            dataType: 'json',
            data: { submitid:submitid,remarks:remarks,by:by },
            success: function(data){
              // if(data.ok == 1) {
                history.back();
              // }
            }
          });
        }
      } else {
        alert('...');
      }
    });

  </script>

</body>
</html>
