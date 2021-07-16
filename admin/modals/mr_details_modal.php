<?php
require_once "class/clsitems.php";
require_once "class/clsreceipts.php";

// $rows       = $item->itemindex(1)->fetchArray();
// $itemname   = $item->itemindex()->fetchAll();

$empname    = $item->employee();
$rrlist     = $receiptitem->rr_list();

// $id         =
// $db->query("select if(count(idempreceipts) is null,1,count(idempreceipts))+1 as cnt
//   from tbl_empreceipts
//   where year(datereceived) = year(now())
//   limit 1;")->fetch_assoc();
// $num1       = $id['cnt'];

// back:
// $mrno       = 'MR'.$_SESSION["area"].$mos.$day.$year.'-'.$num1;
// // $mrno       = 'MR'.$_SESSION["area"].$year.$mos.'-'.$num1;
// $id         = $db->query("select mrnumber from tbl_empreceipts where mrnumber = '$mrno' limit 1");
// if(mysqli_num_rows($id)>0) {
//   $num1 += 1;
//   goto back;
// }
?>

<!-- Add details-->
<div class="modal fade" id="modal-addmrdetails">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add MR Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_details_helper.php">
        <input type="hidden" id="aidreceipts"         name="aidreceipts"/>
        <input type="hidden" id="aidreceiptsdetails"  name="aidreceiptsdetails"/>
        <input type="hidden" id="aiditemindex"        name="aiditemindex"/>
        <input type="hidden" id="aidempreceipts"      name="aidempreceipts"/>
        <input type="hidden" id="amrnumber"           name="amrnumber"/>
        <div class="modal-body">
          <div class="row">
            <div class="col-8">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Item Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="aitemname" name="aitemname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Brand Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="abrandname" name="abrandname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Specification</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="aspecs" name="aspecs" rows="3" style="resize: none;" readonly></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Model</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="amodel" name="amodel" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Serial No's</label>
                <div class="col">
                  <select class="form-control form-control-sm select2" id="aserialnos" name="aserialnos" >

                    <?php if (!empty($_SESSION['serialnos'])) {
                      $serials  = $_SESSION['serialnos'];
                      unset($_SESSION['serialnos']);
                      $pieces   = explode(",", $serials);
                      foreach ($pieces as $row) { ?>
                        <option value="<?= $row; ?>"><?= $row ?></option>
                      <?php }
                    }
                    ?>

                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">RR No.</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="arrnumber" name="arrnumber" readonly>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label class="col col-form-label">Date Received</label>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <input type="date" id="adateaquired" name="adateaquired" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask value="<?php $enddate ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Quantity</label>
                <input type="hidden" class="form-control form-control-sm" id="abalqty" name="abalqty" readonly>
                <div class="col">
                  <input type="numeric" class="form-control form-control-sm" id="aquantity" name="aquantity" placeholder="Quantity" value="<?= $qty?>" required="true" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Unit Cost</label>
                <div class="col">
                  <input type="decimal" class="form-control form-control-sm " id="aamount" name="aamount" value="<?= number_format($averagecost,2) ?>" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                </div>
              </div>

            </div>
          </div>


        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="add"> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add details-->
<div class="modal fade" id="modal-addmrdetails_select">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add MR Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_details_helper.php">
        <input type="hidden" id="sidempreceipts"      name="sidempreceipts"/>
        <input type="hidden" id="smrnumber"           name="smrnumber"/>

        <input type="hidden" id="sidreceipts"         name="sidreceipts"/>
        <input type="hidden" id="sidreceiptsdetails"  name="sidreceiptsdetails"/>
        <input type="hidden" id="siditemindex"        name="siditemindex"/>
        <div class="modal-body">
          <div class="row">
            <div class="col-8">

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">RR No.</label>
                <div class="col">
                  <select onchange="select_receipts()" class="form-control form-control-sm select2" id="rrnumber" name="rrnumber" >
                    <?php foreach($rrlist as $row): ?>
                      <option value="<?= $row['idreceiptsdetails']; ?>"><?= $row['rrnumber'].' - '.$row['itemname']; ?></option>
                    <?php endforeach; ?>
                  </select>

                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Item Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="sitemname" name="sitemname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Brand Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="sbrandname" name="sbrandname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Specification</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="sspecs" name="sspecs" rows="3" style="resize: none;" readonly></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Model</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="smodel" name="smodel" readonly>
                </div>
              </div>
              <div class="form-group row" id="mrserials">
                <label class="col-sm-3 col-form-label">Serial No's</label>
                <div class="col">
                  <select class="form-control form-control-sm select2" id="sserialnos" name="sserialnos" >

                    <?php if (!empty($_SESSION['serialnos'])) {
                      $serials  = $_SESSION['serialnos'];
                      unset($_SESSION['serialnos']);
                      $pieces   = explode(",", $serials);
                      foreach ($pieces as $row) { ?>
                        <option value="<?= $row; ?>"><?= $row ?></option>
                      <?php }
                    }
                    ?>

                  </select>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group">
                <label class="col col-form-label">Date Received</label>
                <div class="col">
                  <div class="input-group input-group-sm">
                    <input type="date" id="sdateaquired" name="sdateaquired" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask value="<?php $enddate ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Quantity</label>
                <input type="hidden" class="form-control form-control-sm" id="sbalqty" name="sbalqty" readonly>
                <div class="col">
                  <input type="numeric" class="form-control form-control-sm" id="squantity" name="squantity" placeholder="Quantity" value="<?= $qty?>" required="true" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Unit Cost</label>
                <div class="col">
                  <input type="decimal" class="form-control form-control-sm " id="sunitcost" name="sunitcost" value="<?= number_format($averagecost,2) ?>" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="add_select"> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit details-->
<div class="modal fade" id="modal-editmrdetails">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit MR Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_details_helper.php">
        <input type="hidden" id="eidempreceiptdetails" name="eidempreceiptdetails" placeholder="empreceipts"/>
        <input type="hidden" id="eidempreceipts"       name="eidempreceipts"/>
        <input type="hidden" id="emrnumber"            name="emrnumber"/>
        <div class="modal-body">
          <div class="row">
            <div class="col-7">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Item Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="eitemname" name="eitemname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Brand Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="ebrandname" name="ebrandname" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Specification</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="especs" name="especs" rows="3" style="resize: none;" placeholder="Specification" readonly></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Model</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="emodel" name="emodel" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Serial No's</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="eserialnos" name="eserialnos" ></input>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">RR No.</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="errnumber" name="errnumber" readonly>
                </div>
              </div>
            </div>

            <div class="col-5">
              <div class="form-group row">
                <label class="col col-form-label" style="text-align: right;">Date Received</label>
                <div class="col-sm-8">
                  <div class="input-group input-group-sm">
                    <input type="date" id="edateaquired" name="edateaquired" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col col-form-label" style="text-align: right;">Quantity</label>
                <input type="hidden" class="form-control form-control-sm" id="ebalqty" name="ebalqty" readonly>
                <div class="col-sm-8">
                  <input type="numeric" class="form-control form-control-sm" id="equantity" name="equantity" placeholder="Quantity" required="true" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>
              <div class="form-group row">
                <label class="col col-form-label" style="text-align: right;">Unit Cost</label>
                <div class="col-sm-8">
                  <input type="decimal" class="form-control form-control-sm " id="eamount" name="eamount" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                </div>
              </div>
              <div class="form-group row">
                <label class="col col-form-label" style="text-align: right;">Unit Status</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control form-control-sm" id="eunitstatus" name="eunitstatus" readonly>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-4 row"></div>
                <div class="col row">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="checkboxTransfer" name="checkboxTransfer" onchange="istransferred()">
                    <label for="checkboxTransfer">Transfer this item?</label>
                  </div>
                </div>
              </div>

              <div class="form-group" id="istransferred" style="display: none">
                <div class="form-group row">
                  <label class="col col-form-label" style="text-align: right">Transfer Date</label>
                  <div class="col-sm-8">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                      <input type="date" id="edatetransferred" name="edatetransferred" value="<?= $today ?>" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask >
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col col-form-label" style="text-align: right">Transfer To</label>
                  <div class="col-sm-8">
                    <input type="hidden" id="eempnoto" name="eempnoto">
                    <input type="hidden" id="eempnameto" name="eempnameto">

                    <input type="hidden" id="eempnofrom" name="eempnofrom">
                    <input type="hidden" id="eempnamefrom" name="eempnamefrom">
                    <input type="hidden" id="prevmrnumber" name="prevmrnumber">
                    <input type="hidden" id="newmrnumber" name="newmrnumber" value="<?= $mrno ?>">

                    <select onchange="selectEmployee()" class="form-control form-control-sm select2" id="eunitto" name="eunitto" >
                      <?php foreach($empname as $row): ?>
                        <option value="<?= $row['empnumber']; ?>"><?= ucwords(strtolower($row['empname'])); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" id="edit" name="edit"> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete mr -->
<div class="modal fade" id="modal-deletemrdetails">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="">Delete!</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></span>
        </button>
      </div>

      <form class="form-horizontal" method="POST" action="datahelpers/mr_details_helper.php">
        <div class="modal-body">
          <input type="hidden" id="didempreceiptdetails" name="id">
          <input type="hidden" id="ditemname2" name="ditemname2">
          <p>Are you sure to delete this item?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger" name="delete"> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>

  $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
  $('[data-mask]').inputmask();

  $('.select2').select2();
  $('#aunitstatus').editableSelect({ effects: 'fade' });
  $('#eunitstatus').editableSelect({ effects: 'fade' });

  function istransferred(e) {
    var check = $('#checkboxTransfer').is(':checked');
    if(check) {
      $('#istransferred').show();
      $('#edit_details').html('Transfer!')
    } else {
      $('#istransferred').hide();
      $('#edit_details').html('Update')
    }
  }

  function selectEmployee() {
    var empcode = document.getElementById("eunitto").value;
    var empname = $("#eunitto option:selected").text();
    $('#eempnoto').val(empcode);
    $('#eempnameto').val(empname);
  }

  function select_receipts() {
    var idreceiptdetails = $('#rrnumber').val();
    $.ajax({
      url: 'datahelpers/mr_details_helper.php',
      type: 'post',
      dataType: 'json',
      data: { idreceiptdetails:idreceiptdetails },
      success: function(data){

        $('#sidreceiptsdetails').val(data.idreceiptsdetails);
        $('#sidreceipts').val(data.idreceipts);
        $('#siditemindex').val(data.iditemindex);

        $('#srrnumber').val(data.rrnumber);
        $('#sdateaquired').val(data.receivedate);
        $('#sitemname').val(data.itemname);
        $('#sbrandname').val(data.brandname);
        $('#smodel').val(data.model);
        $('#sserialnos').val(data.serialnos);
        $('#sspecs').val(data.specifications);

        $('#sbalqty').val(data.quantity);
        $('#squantity').val(data.quantity);
        $('#sunitcost').val(data.unitcost);
        load_serials(data.serialnos);
      }

    });
  }

  function load_serials(serialnos){
    $.ajax({
      url: 'contents/content.php',
      type: 'post',
      data: { serialnos:serialnos },
      success: function (data) {
        $('#mrserials').html(data);
      },
      error: function(){
        alert('Error: load_serials');
      }
    });
  }load_serials();

</script>