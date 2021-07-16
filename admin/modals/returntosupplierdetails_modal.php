<?php
$rrnumbers  = $db->query("select idreceipts, rrnumber from tbl_receipts where active <> 1 group by rrnumber order by length(rrnumber), rrnumber asc");

?>

<div class="modal fade" id="add">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Return Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
         <!-- <form class="form-horizontal" method="POST" action="datahelpers/returntosupplierdetails_helper.php"> -->

          <!-- variables holder! -->
          <input type="hidden" id="aidreturns"      name="aidreturns"       />
          <input type="hidden" id="areturnedno"     name="areturnedno"      />

          <input type="hidden" id="aiditemindex"    name="aiditemindex"     />
          <input type="hidden" id="aitemcode"       name="aitemcode"        />
          <input type="hidden" id="aitemname"       name="aitemname"        />
          <input type="hidden" id="alinkid"         name="alinkid"          />
          <input type="hidden" id="alinkrefno"      name="alinkrefno"       />
          <!-- end variables -->

          <div class="row">
            <div class="col-8">
              <div class="form-group row">
                <label class="col-form-label col-sm-3">RR Number</label>
                <div class="col">
                  <select onchange="load_itemindex_from_rr()" class="form-control select2" id="rrnumber" name="rrnumber" >
                    <?php foreach($rrnumbers as $row): ?>
                      <option value="<?= $row['idreceipts']; ?>"><?= $row['rrnumber']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group row" id="rr-itemindex">
                <label class="col-form-label col-sm-3">Item Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm" id="rsitemname" name="rsitemname" placeholder="Item Name" readonly>
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
                  <textarea type="text" class="form-control form-control-sm" id="aspecs" name="aspecs" rows="2" style="resize: none;" readonly></textarea>
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
                  <textarea type="text" class="form-control form-control-sm" id="aserialnos" name="aserialnos" rows="1" style="resize: none;" placeholder="Serial No's"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Remarks</label>
                <div class="col">
                  <textarea type="text" class="form-control form-control-sm" id="aremarks" name="aremarks" rows="2" style="resize: none;" placeholder="Return remarks"></textarea>
                </div>
              </div>
            </div>

            <div class="col-4">
              <div class="form-group">
                <label class="col col-form-label">Date Received</label>
                <div class="col">
                  <input type="date" id="adateaquired" name="adateaquired" class="form-control form-control-sm" value="<?= $enddate ?>" data-inputmask-alias="datetime" data-inputmask-inputformat="mm-d-Y" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Quantity</label>
                <div class="col">
                  <input type="numeric" class="form-control form-control-sm" id="aqty" name="aqty" placeholder="Quantity" value="<?= $qty?>" readonly data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>
              <div class="form-group">
                <label class="col col-form-label">Return Quantity</label>
                <div class="col">
                  <input type="numeric" class="form-control form-control-sm" id="aretqty" name="aretqty" placeholder="Quantity" value="<?= $qty?>" required="true" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                </div>
              </div>

              <div class="form-group">
                <label class="col col-form-label">Unit</label>
                <div class="col">
                  <input type="text" class="form-control form-control-sm" id="aunit" name="aunit" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col col-form-label">Unit Cost</label>
                <div class="col">
                  <input type="decimal" class="form-control form-control-sm " id="acostperunit" name="acostperunit" value="<?= number_format($unitcost,2) ?>" data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_return_to_supplier_details()"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> Save</button>
          <!-- </form> -->
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
          <!-- <form class="form-horizontal" method="POST" action="datahelpers/returntosupplierdetails_helper.php"> -->

            <input type="hidden" id="ereturnedno"       name="ereturnedno"      />
            <input type="hidden" id="eidreturnsdetails" name="eidreturnsdetails"/>

            <div class="row">
              <div class="col-8">
                <div class="form-group row">
                  <label class="col-form-label col-sm-3">RR Number</label>
                  <div class="col">
                    <input type="text" class="form-control form-control-sm" id="errnumber" name="errnumber" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label col-sm-3">Item Name</label>
                  <div class="col-sm-9">
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
                    <textarea type="text" class="form-control form-control-sm" id="especs" name="especs" readonly rows="2" style="resize: none;"></textarea>
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
                    <textarea type="text" class="form-control form-control-sm" id="eserialnos" name="eserialnos" rows="1" style="resize: none;"></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Remarks</label>
                  <div class="col">
                    <textarea type="text" class="form-control form-control-sm" id="eremarks" name="eremarks" rows="2" style="resize: none;"></textarea>
                  </div>
                </div>
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label class="col col-form-label">Date Received</label>
                  <div class="col">
                    <input type="date" class="form-control form-control-sm" id="edateaquired" name="edateaquired" readonly data-inputmask-alias="datetime" data-inputmask-inputformat="mm-d-Y" data-mask>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col col-form-label">Quantity</label>
                  <div class="col">
                    <input type="numeric" class="form-control form-control-sm" id="eqty" name="eqty" readonly data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col col-form-label">Return Quantity</label>
                  <div class="col">
                    <input type="numeric" class="form-control form-control-sm" id="eretqty" name="eretqty" required="true" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col col-form-label">Unit</label>
                  <div class="col">
                    <input type="text" class="form-control form-control-sm" id="eunit" name="eunit" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col col-form-label">Unit Cost</label>
                  <div class="col">
                    <input type="decimal" class="form-control form-control-sm " id="ecostperunit" name="ecostperunit" readonly data-inputmask-alias="decimal" data-inputmask-inputformat="999,999,999.99" data-mask>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning" data-dismiss="static" onclick="update_return_to_supplier_details()"><i class="fa fa-plus-circle mr-1" aria-hidden="true"></i> Update</button>
          <!-- </form> -->
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
          <!-- <form class="form-horizontal" method="POST" action="datahelpers/returntosupplierdetails_helper.php"> -->

            <input type="hidden" id="ditemname"         name="ditemname"        />
            <input type="hidden" id="didreturnsdetails" name="didreturnsdetails"/>
            <input type="hidden" id="dreturnedno"       name="dreturnedno"      />
            <p>Are you sure to delete this record?</p>
          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" data-dismiess="static" onclick="delete_return_to_supplier_details()"><i class="fa fa-trash-alt mr-1"></i> Delete</button>
          </div>
          <!-- </form> -->
        </div>
      </div>
    </div>

    <script type="text/javascript">

      $('.select2').select2();
      $('#datemask').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
      $('[data-mask]').inputmask();

      function load_itemindex_from_rr(){
        var idreceipts = $('#rrnumber').val();
        $.ajax({
          url: 'contents/content.php',
          type: 'post',
          data: { idreceipts:idreceipts },
          success: function (data) {
            $('#rr-itemindex').html(data);
            select_item();
          },
          error: function(e){
            alert(e);
          }
        });
      }load_itemindex_from_rr();

      function select_item(e) {
        var id = $('#iditemindex').val();
        $.ajax({
          url: 'datahelpers/receiptdetails_helper.php',
          type: 'post',
          dataType: 'json',
          data: { id:id },
          success: function (data) {
            $('#aiditemindex').val(data.iditemindex);
            $('#aitemcode').val(data.itemcode);
            $('#aitemname').val(data.itemname);
            $('#alinkid').val(data.idreceiptsdetails);
            $('#alinkrefno').val(data.rrnumber);

            $('#abrandname').val(data.brandname);
            $('#amodel').val(data.model);
            $('#aspecs').val(data.specifications);
            $('#aserialnos').val(data.serialnos);

            $('#aunit').val(data.unit);
            $('#aqty').val(data.quantity);
            $('#aretqty').val(data.quantity);
            $('#acostperunit').val(data.unitcost);

          },
          error: function(){
          }
        });
      }

      function save_return_to_supplier_details() {

        var returnedno    = $('#areturnedno').val();
        var itemcode      = $('#aitemcode').val();
        var cur_qty       = $('#aqty').val();
        var ret_qty       = $('#aretqty').val();
        var linkid        = $('#alinkid').val();
        var linkrefno     = $('#alinkrefno').val();
        var serialnos     = $('#aserialnos').val();
        var remarks       = $('#aremarks').val();
        var unitcost      = $('#acostperunit').val();

        if (itemcode == "DMOG0001") return;
        if ( (Number(cur_qty) >= Number(ret_qty)) && (Number(ret_qty) != 0)) {
          $.ajax({
            url     : 'datahelpers/returntosupplierdetails_helper.php',
            type    : 'post',
            dataType: 'json',
            data    : {
              add:'add',
              returnedno:returnedno,
              itemcode:itemcode,
              quantity:ret_qty,
              linkrefno:linkrefno,
              serialnos:serialnos,
              remarks:remarks,
              unitcost:unitcost,
              linkid:linkid,
            },
            success: function (data) {
              $('#add').modal('hide');
              msg('success','Return Details','Record Successfully saved!');
              reload_supplier_details();
            },
            error: function(){
              alert('add return supplier details|helper.js|769');
            },
          });
      } else {
        alert('err: dako ang cur_qty');
      }
    }

    function update_return_to_supplier_details() {

        var edit       = $('#eidreturnsdetails').val();
        var cur_qty    = $('#eqty').val();
        var ret_qty    = $('#eretqty').val();
        var serialnos  = $('#eserialnos').val();
        var remarks    = $('#eremarks').val();

        if ( (Number(cur_qty) >= Number(ret_qty))
          || (Number(ret_qty) != 0)) {
          $.ajax({
            url     : 'datahelpers/returntosupplierdetails_helper.php',
            type    : 'post',
            dataType: 'json',
            data    : {
              edit:edit,
              quantity:ret_qty,
              serialnos:serialnos,
              remarks:remarks,
            },
            success: function (data) {
              $('#edit').modal('hide');
              msg('warning','Return Details','Record Successfully updated!');
              reload_supplier_details();
            },
            error: function(){
              alert('edit return supplier details|helper.js|769');
            },
          });
      } else {
        alert('err: dako ang cur_qty');
      }
    }

    function delete_return_to_supplier_details() {
      var idreturnsdetails = $('#didreturnsdetails').val();
      $.ajax({
        url     : 'datahelpers/returntosupplierdetails_helper.php',
        type    : 'post',
        dataType: 'json',
        data    : { delete:idreturnsdetails },
        success : function (data) {
          $('#delete').modal('hide');
          msg('danger','Return Details','Record Successfully deleted!');
          reload_supplier_details();
        },
        error: function() {}
      });
    }


  </script>


