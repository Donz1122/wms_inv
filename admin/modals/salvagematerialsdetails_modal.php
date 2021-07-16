<?php
include "class/clssalvage.php";
if($_SESSION['xempno']) {
  $empno                = $_SESSION['xempno'];
  
  $rows                 = $salvage->get_employee_mr_items($empno,1);

  $iditemindex          = "";
  $itemcode             = "";
  $itemname             = "";

  $brandname            = "";
  $model                = "";
  $specs                = "";
  $serialnos            = "";

  $unit                 = "";
  $quantity             = 0;
  $unitcost          = 0;

  $idempreceiptdetails  = "";

  if($rows > 0) {

    $idempreceipts        = $rows['idempreceipts'];

    $iditemindex          = $rows['iditemindex'];
    $itemcode             = $rows['itemcode'];
    $itemname             = $rows['itemname'];

    $brandname            = $rows['brandname'];
    $model                = $rows['model'];
    $specs                = $rows['specifications'];
    $serialnos            = $rows['serialnos'];

    $unit                 = $rows['unit'];
    $quantity             = $rows['quantity'];
    $unitcost          = $rows['unitcost'];

    $mrnumber             = $rows['mrnumber'];
    $idempreceiptdetails  = $rows['idempreceiptdetails'];
  }

  $itemname               = $salvage->get_employee_mr_items($empno);
}
//unset($_SESSION['xempno']);

?>

<!-- Add -->
<div class="modal fade" id="add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/salvagematerialsdetails_helper.php">

          <input type="hidden" id="empno"               name="empno"                  value="<?= $empno ?>"/>
          <input type="hidden" id="aidsalvage"          name="aidsalvage"/>
          <input type="hidden" id="salvageno"           name="salvageno"/>
          <input type="hidden" id="mrno"                name="mrno"                   value="<?= $mrnumber ?>"/>
          <input type="hidden" id="idempreceiptdetails" name="idempreceiptdetails"    value="<?= $idempreceiptdetails ?>"/>

          <div class="row">
            <div class="col-8">
              <div class="form-group row" id="rr-itemindex">
                <label class="col-form-label col-sm-3">Item Name</label>
                <div class="col-sm-9">
                  <select onchange="select_salvage_item()" class="form-control select2" id="itemname" name="itemname" >
                    <?php foreach($itemname as $row): ?>
                      <option value="<?= $row['idempreceiptdetails']; ?>"><?= $row['itemname'].' - '.$row['itemcode']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Brand Name</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="brandname" name="brandname" value="<?= $brandname ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Specification</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="specs" name="specs" rows="2" style="resize: none;" readonly><?= $specs ?></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Model</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="model" name="model" value="<?= $model ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Serial No's</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="serialnos" name="serialnos" rows="1" style="resize: none;" value="<?= $serialnos ?>"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Remarks</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="remarks" name="remarks" rows="2" style="resize: none;" placeholder="Salvage remarks"></textarea>
                </div>
              </div>
            </div>

            <div class="col-4">
              <div class="form-group">
                <label class="col col-form-label">Date Received</label>
                <div class="col">
                  <input type="date" id="transactiondate" name="transactiondate" class="form-control form-control-sm" value="<?= $enddate ?>" data-inputmask-alias="datetime" data-inputmask-inputformat="mm-d-Y" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Quantity</label>
                <div class="col">
                  <input type="numeric" class="form-control form-control-sm" id="quantity" name="quantity" value="<?= $quantity?>" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Unit</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="unit" name="unit" value="<?= $unit ?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col col-form-label">Unit Cost</label>
                <div class="col">
                  <input type="decimal" class="form-control form-control-sm " id="unitcost" name="unitcost" value="<?= number_format($unitcost,2) ?>" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="add"> Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/salvagematerialsdetails_helper.php">

          <input type="hidden" id="eidsalvagedetails" name="eidsalvagedetails">
          <input type="hidden" id="eiditemindex" name="eiditemindex">

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="eitemcode" name="eitemcode" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-sm-10">
              <select onchange="selectItemIndex_returmaterialsdetails('e')" class="form-control select2" id="eitemname" name="eitemname" >
                <?php foreach($itemname as $row): ?>
                  <option value="<?= $row['idempreceiptdetails']; ?>"><?= $row['itemcode'].' - '.$row['itemname']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="eunit" name="eunit" readonly>
            </div>

          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-3">
              <input type="numeric" class="form-control form-control-sm" id="eqty" name="eqty"
              data-inputmask-alias="numeric" data-inputmask-inputformat="999" data-mask>
            </div>

            <label class="col-form-label col-sm-2" style="text-align: right">Unit Cost</label>
            <div class="col-sm-5">
              <input type="decimal" class="form-control form-control-sm" id="eunitcost" name="eunitcost"
              data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
            </div>

          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" name="edit"> Update</button>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="">Delete!</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/salvagematerialsdetails_helper.php">
          <input type="hidden" id="didsalvagedetails" name="didsalvagedetails">
          <p>Are you sure to delete this item?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger " name="delete"> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();
  $('[data-mask]').inputmask();

  $(document).ready(function() {
     // var itemcode = $('#itemcode').val();
     // $.ajax({
     //   url: 'modals/salvagematerialsdetails_modal.php',
     //   type: 'post',
     //   data: { empno:empno },
     // });
  });

  function select_salvage_item(e) {
    var selectitemid = $('#itemname').val();
    $('#idempreceiptdetails').val(selectitemid);
    $.ajax({
      url: 'datahelpers/salvagematerialsdetails_helper.php',
      type: 'post',
      dataType: 'json',
      data: {selectitemid:selectitemid},
      success: function (data) {
        $('#iditemindex').val(data.iditemindex);
        $('#itemcode').val(data.itemcode);
        $('#itemname').val(data.itemname);

        $('#brandname').val(data.brandname);
        $('#model').val(data.model);
        $('#specs').val(data.specifications);
        $('#serialnos').val(data.serialnos);

        $('#unit').val(data.unit);
        $('#quantity').val(data.quantity);
        $('#unitcost').val(data.unitcost);


      },
      error: function(){
      }
    });
  }

</script>


