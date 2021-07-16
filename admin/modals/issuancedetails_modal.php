<?php

include 'serverside/ss_itemindex_helper.php';

$rows       = $item->itemindex(1);
$itemname   = $item->itemindex();
$serials    = $item->serialnos();
// $serials2   = $item->serialnos();
?>

<!-- Add -->
<div class="modal fade" id="modal-adddetails">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Issuance Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/issuancedetails_helper.php">

          <input type="hidden" id="ainumber" name="ainumber">
          <input type="hidden" id="aidissuance" name="aidissuance">
          <input type="hidden" id="aiditemindex_i" name="aiditemindex_i" value="<?= $rows['iditemindex'] ?>">
          <input type="hidden" id="aitemname2" name="aitemname2" >

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="aitemcode" name="aitemcode" readonly value="<?= $rows['itemcode'] ?>">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-sm-10">
              <select onchange="select_itemname_for_issuance()" class="form-control select2" id="aitemname" name="aitemname" >
                <?php foreach($itemname as $row): ?>
                  <option value="<?= $row['iditemindex']; ?>"><?= $row['itemname'] .' - '. $row['itemcode'] .':'.$row['dummyqty']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Category</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="acategory" name="acategory" readonly="true" placeholder="Category" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Qty Left</label>
            <div class="col-sm-3">
              <input type="numeric" class="form-control form-control-sm" id="abalqty" name="abalqty" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly placeholder="Balance Quantity">
            </div>
            <label class="col-form-label col-sm-3" style="text-align: right;">Unit</label>
            <div class="col-sm-4">
              <input type="text" class="form-control form-control-sm" id="aunit" name="aunit" readonly placeholder="Unit">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-3">
              <input type="numeric" class="form-control form-control-sm" id="aqty" name="aqty" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask required placeholder="Quantity">
            </div>

            <label class="col-form-label col-sm-3" style="text-align: right;">Unit Cost</label>
            <div class="col-sm-4">
              <input type="decimal" class="form-control form-control-sm" id="aunitcost_i" name="aunitcost_i" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask readonly placeholder="0.00">
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-2 row"></div>
            <div class="col row">
              <div class="icheck-primary d-inline">
                <input type="checkbox" id="checkbox_isspareparts" name="checkbox_isspareparts" onchange="ispareparts()">
                <label for="checkbox_isspareparts">this item is a spare parts?</label>
              </div>
            </div>
          </div>

          <div class="form-group row" id="spareparts1">
            <label class="col-form-label col-sm-2" ><p style="margin-top: -10px; font-size: 12px;">Serial/Equipment / Pole No.</p></label>
            <div class="col-sm-10">
              <select class="form-control" id="tag_no" name="tag_no" >
                <?php foreach($serials as $row): ?>
                  <option value="<?= $row['eno']; ?>"><?= $row['eno']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group row" id="spareparts2">
            <label class="col-form-label col-sm-2" >Request Remarks</label>
            <div class="col-sm-10">
              <textarea class="form-control form-control-sm" row="2" id="tag_remarks" name="tag_remarks" placeholder="Replacement parts of etc..." >
              </textarea>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info save" id="save" data-dismiss="static" onclick="saveIssuanceDetails()"> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Issuance Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" method="POST" action="datahelpers/issuancedetails_helper.php">

            <input type="hidden" id="einumber" name="einumber">
            <input type="hidden" id="eidissuancedetails" name="eidissuancedetails">
            <input type="hidden" id="eiditemindex_i" name="eiditemindex_i">

            <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Code</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="eitemcode" name="eitemcode" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="eitemname" name="eitemname" readonly>
              </div>
            </div>
            <!-- <div class="form-group row">
              <label class="col-form-label col-sm-2">Serial Nos.</label>
              <div class="col-sm-10">
                <textarea type="text" class="form-control form-control-sm" rows="3" id="eserialnos" name="eserialnos" style="resize: none;" readonly></textarea>
              </div>
            </div> -->
            <div class="form-group row">
              <label class="col-form-label col-sm-2">Unit</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="eunit" name="eunit" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-sm-2">Quantity</label>
              <div class="col-sm-3">
                <input type="numeric" class="form-control form-control-sm" id="equantity" name="equantity" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask required>
              </div>

              <label class="col-form-label col-sm-2" style="text-align: right;">Unit Cost</label>
              <div class="col-sm-5">
                <input type="decimal" class="form-control form-control-sm" id="eunitcost" name="eunitcost" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask readonly>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-2 row"></div>
              <div class="col row">
                <div class="icheck-primary d-inline">
                  <input type="checkbox" id="echeckbox_isspareparts" name="echeckbox_isspareparts" onchange="ispareparts()">
                  <label for="checkbox_isspareparts">this item is a spare parts?</label>
                </div>
              </div>
            </div>

            <div class="form-group row" id="espareparts1">
              <label class="col-form-label col-sm-2" ><p style="margin-top: -14px;">Serial / Equipment No.</p></label>
              <div class="col-sm-10">
                <select class="form-control" id="etag_no" name="etag_no" >
                  <?php foreach($serials as $row): ?>
                    <option value="<?= $row['eno']; ?>"><?= $row['eno']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group row" id="espareparts2">
              <label class="col-form-label col-sm-2" >Request Remarks</label>
              <div class="col-sm-10">
                <textarea class="form-control form-control-sm" row="2" id="etag_remarks" name="etag_remarks" placeholder="Replacement parts of etc..." >
                </textarea>
              </div>
            </div>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
            <button type="button" class="btn btn-warning edit" id="btn-update" data-dismiss="static" onclick="updateIssuanceDetails()"><i class="fa fa-save"></i> Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- approved -->
<div class="modal fade" id="approved">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modify Request Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" method="POST" action="datahelpers/issuancedetails_helper.php">

            <input type="hidden" id="apinumber"           name="apinumber">
            <input type="hidden" id="apidissuance"        name="apidissuance">
            <input type="hidden" id="apidissuancedetails" name="apidissuancedetails">

            <!-- <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Code</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="apitemcode" name="apitemcode" readonly>
              </div>
            </div> -->
            <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm inputs" id="apitemname" name="apitemname" readonly autofocus>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-2">Serial Nos.</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm inputs" id="apserialnos" name="apserialnos" autofocus></input>
                <!-- <textarea type="text" class="form-control form-control-sm inputs" rows="3" id="apserialnos" name="apserialnos" style="resize: none;" autofocus></textarea> -->
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-2">Remaining Qty</label>
              <div class="col-sm-4">
                <input type="numeric" class="form-control form-control-sm inputs" id="apremqty" name="apremqty" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly autofocus>
              </div>

              <label class="col-form-label col-sm-2" style="text-align: right;">Unit</label>
              <div class="col">
                <input type="text" class="form-control form-control-sm inputs" id="apunit" name="apunit" readonly autofocus>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-sm-2">Request Qty</label>
              <div class="col-sm-4">
                <input type="numeric" class="form-control form-control-sm inputs" id="apquantity" name="apquantity" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly autofocus>
              </div>

              <label class="col-form-label col-sm-2" style="text-align: right;">Approve Qty</label>
              <div class="col">
                <input type="numeric" class="form-control form-control-sm inputs" id="apquantity2" name="apquantity2" placeholder="0" data-inputmask-alias="numeric" data-inputmask-inputformat="999999999" data-mask required autofocus>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-success inputs" id="btn-approved" data-dismiss="static" onclick="update_issuance_details()" autofocus> Update</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
          </div>
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
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <form class="form-horizontal" method="POST" action="datahelpers/issuancedetails_helper.php">
          <input type="hidden" id="d_idissuancedetails" name="d_idissuancedetails"/>
          <input type="hidden" id="d_inumber"           name="d_inumber"          />
          <input type="hidden" id="ditemname"           name="ditemname"          />
          <p>Are you sure to remove this item?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger mr-2" name="delete"><i class="fa fa-trash-alt"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();
  $('[data-mask]').inputmask();
  $('#tag_no').editableSelect();
  $('#etag_no').editableSelect();

  $('.inputs').keydown(function (e) {
    if (e.which === 13) {
      var index = $('.inputs').index(this) + 1;
      $('.inputs').eq(index).focus();
    }
  });

  $(document).ready(function(){
    $("#approved").on('shown.bs.modal', function(){
      $(this).find('#apserialnos').focus();
      $('#apserialnos').select();
      $("#approved").on('hidden.bs.modal', function(){
        $('#table2').DataTable().keys.enable();
      });
    });

    $("#edit").on('shown.bs.modal', function(){
      $(this).find('#eserialnos').focus();
      $('#eserialnos').select();
      $("#edit").on('hidden.bs.modal', function(){
        $('#table2').DataTable().keys.enable();
      });
    });
  });

  // $(document).ready(function() {
  //   $('#approved').on('shown.bs.modal', function() {
  //     $('#apitemname').focus();
  //   })
  // });

  function select_itemname_for_issuance() {
    $('#aqty').val(0);
    var selectitemid = document.getElementById("aitemname").value;
    $('#aitemname2').val($("#aitemname option:selected").html());
    $.ajax({
      url: 'datahelpers/issuancedetails_helper.php',
      type: 'post',
      dataType: 'json',
      data: { selectitemid:selectitemid },
      success: function (data) {
        $('#aitemcode').val(data.itemcode);
        $('#aunit').val(data.unit);
        $('#abalqty').val(data.qty);
        $('#aiditemindex_i').val(data.iditemindex);
        $('#aunitcost_i').val(data.dummyave);
        $('#acategory').val(data.category);
        var curqty =  data.qty;
        if ((curqty <= 0) || (data.qty === null)) {
          document.getElementById("aqty").disabled = true;
          document.getElementById("save").disabled = true;
        } else {
          document.getElementById("aqty").disabled = false;
          document.getElementById("save").disabled = false;
        }
      },
      error: function() {}
    });
  }

  $('document').ready(function() {
    $('#aqty').on('change', function() {
      var num1            = document.getElementById("abalqty").value;
      var num2            = document.getElementById("aqty").value;
      if (num1 <= 0) {
        $('#aqty').focus();
      } else {
        if (num1 > num2) {
          $('#aqty').focus();
        }
      }
    });
  });

  function update_issuance_details() {
    var approved          = "approved";
    var itemname          = $('#apitemname').val();
    var idissuance        = $('#apidissuance').val();
    var quantity          = $('#apquantity').val();
    var quantity2         = $('#apquantity2').val();
    var idissuancedetails = $('#apidissuancedetails').val();
    var serialnos         = $('#apserialnos').val();

    if(Number(quantity) >= Number(quantity2)) {
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        data: {
          itemname:itemname,
          approved:approved,
          idissuancedetails:idissuancedetails,
          quantity:quantity,
          quantity2:quantity2,
          serialnos:serialnos
        },
        success: function(data){
          $('#approved').modal('hide');
          reload_issuance_details_table();
          //window.location.reload();
        },
        error: function(e){}
      });
    } else {
      alert('Please check quantity...');
    }
  };

  function saveIssuanceDetails() {
    var add             = "save";
    var balqty          = $('#abalqty').val();
    var iditemindex     = $('#aiditemindex_i').val();
    var idissuance      = $('#aidissuance').val();
    var inumber         = $('#ainumber').val();
    var itemcode        = $('#aitemcode').val();
    var quantity        = $('#aqty').val();
    var unitcost        = $('#aunitcost_i').val();
    var tag_no          = $('#tag_no').val();
    var tag_remarks     = $('#tag_remarks').val();

    if(Number(quantity) <= Number(balqty)) {
      $.ajax({
        url: 'datahelpers/issuancedetails_helper.php',
        type: 'post',
        data: {
          add:add,
          iditemindex:iditemindex,
          idissuance:idissuance,
          inumber:inumber,
          itemcode:itemcode,
          quantity:quantity,
          unitcost:unitcost,
          tag_no:tag_no,
          tag_remarks:tag_remarks
        },
        success: function(data){
          window.location.reload();
        },
        error: function(){ }
      });
    } else {
      alert('[Quantity] must be less than [Qty Left]!');
    }
  };

  function updateIssuanceDetails() {
    var edit                = "update";
    var idissuancedetails   = $('#eidissuancedetails').val();
    var iditemindex         = $('#eiditemindex_i').val();
    var quantity            = $('#equantity').val();
    var unitcost            = $('#eunitcost').val();
    var inumber             = $('#einumber').val();
    var serialnos           = $('#eserialnos').val();
    var tag_no              = $('#etag_no').val();
    var tag_remarks         = $('#etag_remarks').val();
    $.ajax({
      url: 'datahelpers/issuancedetails_helper.php',
      type: 'post',
      data: {
        edit:edit,
        idissuancedetails:idissuancedetails,
        iditemindex:iditemindex,
        quantity:quantity,
        unitcost:unitcost,
        serialnos:serialnos,
        tag_no:tag_no,
        tag_remarks:tag_remarks
      },
      success: function(data){
        $('#edit').modal('hide');
        window.location.reload();
      },
      error: function(){ }
    });
  };

  function cleanIDM() {
    $('#abalqty').val('');
    $('#aiditemindex_i').val('');
    $('#aidissuance').val('');
    $('#ainumber').val('');
    $('#aitemcode').val('');
    $('#aqty').val('');
    $('#aunitcost_i').val('');

    $('#eidissuancedetails').val('');
    $('#eiditemindex').val('');
    $('#equantity').val('');
    $('#eunitcost').val('');
    $('#einumber').val('');
  }

  function ispareparts() {
    var check = $('#checkbox_isspareparts').is(':checked');
    if(check) {
      $('#spareparts1').show();
      $('#spareparts2').show();

      $('#espareparts1').show();
      $('#espareparts2').show();
    } else {
      $('#spareparts1').hide();
      $('#spareparts2').hide();

      $('#espareparts1').hide();
      $('#espareparts2').hide();

      $('#tag_no').val('');
      $('#tag_remarks').val('');
    }
  }ispareparts();

</script>


