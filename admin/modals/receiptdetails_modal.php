<?php
$rows     = $item->itemindex(1);
$itemname = $item->itemindex();
?>

<div class="modal fade" id="add-details">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">New Material Receipts Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <!-- <form class="form-horizontal" method="POST" action="datahelpers/receiptdetails_helper.php"> -->

          <input type="hidden" id="rdrrno" name="rdrrno">
          <input type="hidden" id="rdidreceipts" name="rdidreceipts">
          <input type="hidden" id="itemname" name="itemname" >

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="itemcode" name="itemcode" readonly placeholder="Item Code" value="<?= $rows['itemcode'] ?>">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-12 col-md-10">
              <select onchange="selectedItemName('a')" class="form-control select2" id="iditemindex" name="iditemindex" >
                <?php foreach($itemname as $row): ?>
                  <option value="<?= $row['iditemindex']; ?>"><?= $row['itemname'] .' - '.$row['itemcode']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div id="allpurposeitem"></div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Brand</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="brandname" name="brandname" placeholder="Brand Name">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Model</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="model" name="model" placeholder="Model">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Specification</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="specs" name="specs" rows="2" style="resize: none;" placeholder="Specification" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Serials</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="serials" name="serials" rows="2" style="resize: none;" placeholder="Serials" ></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="unit" name="unit" readonly >
            </div>

            <input type="hidden" id="iswithwarrantyz" name="iswithwarrantyz">
            <label for="withwarranty" class="col-sm-2 col-form-label" style="text-align: right;">Warranty End</label>
            <input type="checkbox" name="checkbox" id="withwarranty" name="withwarranty" onchange="iswithwarranty()" style="margin-top: 10px;">
            <div class="col">
              <div class="input-group input-group-sm">
                <input type="date" class="form-control form-control-sm" id="warranty" name="warranty" data-mask value="<?= $enddate; ?>">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-4">
              <input type="numeric" class="form-control form-control-sm" id="qty" name="qty" required placeholder="0" data-inputmask-alias="numeric" data-inputmask-inputformat="999999999.99" data-mask>
            </div>

            <label class="col-form-label col-sm-2" style="text-align: right;">Unit Cost</label>
            <div class="col">
              <input type="decimal" class="form-control form-control-sm" id="unitcost" name="unitcost" required placeholder="0.00" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_receipt_details()"> Save</button>
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modify Material Receipts Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/receiptdetails_helper.php">
          <input type="hidden" id="rderrno" name="rderrno">
          <input type="hidden" id="rdeidreceipts" name="rdeidreceipts">
          <input type="hidden" id="rdeidreceiptsdetails" name="rdidreceiptsdetails">

          <!-- <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="eitemcode" name="eitemcode" readonly placeholder="Item Code">
            </div>
          </div> -->

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-12 col-md-10">
              <input type="text" class="form-control form-control-sm" id="eitemname" name="eitemname" readonly>
            </div>
          </div>

          <div id="eallpurposeitem"></div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Brand</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="ebrandname" name="ebrandname">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Model</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="emodel" name="emodel">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Specification</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="especs" name="especs" rows="3" style="resize: none;"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Serials</label>
            <div class="col">
              <textarea type="text" class="form-control form-control-sm" id="eserials" name="eserials" rows="3" style="resize: none;"
              placeholder="Serials" data-toggle="tooltip" title="ex: 100-199;201;203-250"></textarea>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="eunit" name="eunit" readonly >
            </div>

            <input type="hidden" id="eiswithwarrantyz" name="eiswithwarrantyz">
            <label for="ewithwarranty" class="col-sm-2 col-form-label" style="text-align: right;">Warranty End</label>
            <input type="checkbox" name="checkbox" id="ewithwarranty" name="ewithwarranty" onchange="eiswithwarranty()" style="margin-top: 10px;">
            <div class="col">
              <div class="input-group input-group-sm">
                <input type="date" class="form-control form-control-sm" id="ewarranty" name="ewarranty" data-mask>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-4">
              <input type="numeric" class="form-control form-control-sm" id="eqty" name="eqty" required placeholder="0" data-inputmask-alias="numeric" data-inputmask-inputformat="999999999" data-mask>
            </div>

            <label class="col-form-label col-sm-2" style="text-align: right;">Unit Cost</label>
            <div class="col">
              <input type="decimal" class="form-control form-control-sm" id="eunitcost" name="eunitcost" required placeholder="0.00" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
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

<div class="modal fade" id="modal-delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="">Delete!</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></span>
        </button>
      </div>

      <form class="form-horizontal" method="POST" action="datahelpers/receiptdetails_helper.php">
        <div class="modal-body">
          <input type="hidden" id="didreceiptsdetails" name="id">
          <p>Are you sure to delete this item?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger"  name="delete"> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();
  $('[data-mask]').inputmask();

  function selectedItemName(e) {
    var selectitemid = e;
    if(e =='a') {
      selectitemid = document.getElementById("iditemindex").value;
    } else {
      //var selectitemid = document.getElementById("e_itemname").value;
      //document.getElementById("e_itemcode").value = selectitemid;
    }
    $.ajax({
      url: 'datahelpers/receiptdetails_helper.php',
      type: 'post',
      dataType: 'json',
      data: { selectitemid:selectitemid },
      success: function (data) {
        document.getElementById("itemname").value = data.itemname;
        document.getElementById("itemcode").value = data.itemcode;
        document.getElementById("unit").value = data.unit;
        if(data.itemcode === 'DMOG0001') {
          load_allpurposeitem('asdf');
        }
      },
      error: function(){}
    });
  }
  function load_allpurposeitem(ex){
    var allpurposeitem = ex;
    $.ajax({
      url: 'contents/content.php',
      type: 'post',
      data: { allpurposeitem:ex },
      success: function (data) {
        $('#allpurposeitem').html(data);
      },
      error: function(){
        alert('Error: allpurposeitem');
      }
    });
  }

  $("#warranty").attr( "disabled", true );

  function iswithwarranty() {
    var check = $('#withwarranty').is(':checked');
    if(check) {
      $("#iswithwarrantyz").val('1');
      $("#warranty").attr( "disabled", false );
    } else {
      $("#iswithwarrantyz").val('0');
      $("#warranty").attr( "disabled", true );
    }
  };

  function eiswithwarranty() {
    var check = $('#ewithwarranty').is(':checked');
    if(check) {
      $("#eiswithwarrantyz").val('1');
      $("#ewarranty").attr( "disabled", false );
    } else {
      $("#eiswithwarrantyz").val('0');
      $("#ewarranty").attr( "disabled", true );
    }
  };

</script>


