<?php
$empno          = $_SESSION['xempno'];
$suppliercode   = $_SESSION['xsuppliercode'];

$rows  = "";
$query = "";
$from  = "";
if(!empty($empno)) {
  $rows       = $returns->get_mr_and_issuance_of_employee($empno, 1);
  $query      = $returns->get_mr_and_issuance_of_employee($empno);
  $from       = "employee";
} else {
  $rows       = $returns->get_items_from_supplier($suppliercode, 1);
  $query      = $returns->get_items_from_supplier($suppliercode);
  $from       = "supplier";
}

?>

<div class="modal fade" id="add">
  <div class="modal-dialog modal-lg" style="width: 650px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Return Items</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <!-- variables holder! -->
          <input type="hidden" id="aempno"        name="aempno"       value="<? $empno ?>" />
          <input type="hidden" id="aidreturns"    name="aidreturns" />
          <input type="hidden" id="areturnedno"   name="areturnedno" />
          <input type="hidden" id="alinkrefno"    name="alinkrefno"   value="<?= $rows['docno'] ?>"/>
          <input type="hidden" id="alinkid"       name="alinkid"      value="<?= $rows['id'] ?>"/>
          <input type="hidden" id="from"          name="from"         value="<?= $from ?>" />
          <?php if($from == 'supplier') { ?>
            <input type="hidden" id="idreturnsdetails"    name="idreturnsdetails" value="<?= $rows['id'] ?>"/>
          <?php } ?>

          <!-- end variables -->

          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Code</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-sm" id="aitemcode" name="aitemcode"  value="<?= $rows['itemcode'] ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-sm-2">Item Name</label>
            <div class="col-12 col-md-10">
              <select onchange="selectItemIndex('a')" class="form-control select2" id="aitemname" name="aitemname" >
                <?php foreach($query as $row): ?>
                  <option value="<?= $row['id']; ?>"><?= $row['docno'] .' - '. $row['itemname']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-sm-2">Unit</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="aunit" name="aunit" value="<?= $rows['unit'] ?>" readonly >
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-sm-2">Quantity</label>
            <div class="col-sm-3">
              <input type="numeric" class="form-control form-control-sm" id="aqty" name="aqty" value="<?= $rows['quantity'] ?>" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask readonly>
            </div>
            <label class="col-form-label col-sm-4" style="text-align: right;">Return Qty</label>
            <div class="col-sm-3">
              <input type="numeric" class="form-control form-control-sm" id="aretqty" name="aretqty" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask required value="<?= $rows['quantity'] ?>" >
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
          <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_warehouse_details()"><i class="fas fa-save mr-1"></i> Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit -->
  <div class="modal fade" id="edit">
    <div class="modal-dialog modal-lg" style="width: 650px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Item</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" method="POST" action="datahelpers/returnedmaterialsdetails_helper.php">

            <input type="hidden" id="eidreturnsdetails" name="eidreturnsdetails">
            <input type="hidden" id="ereturnedno" name="ereturnedno">
            <input type="hidden" id="eiditemindex" name="eiditemindex">

            <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Code</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="eitemcode" name="eitemcode" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-sm-2">Item Name</label>
              <div class="col">
                <input type="text" class="form-control form-control-sm" id="eitemname" name="eitemname" readonly>
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
                <input type="text" class="form-control form-control-sm" id="eqty" name="eqty" readonly>
              </div>
              <label class="col-form-label col-sm-4" style="text-align: right;">Return Qty</label>
              <div class="col-sm-3">
                <input type="numeric" class="form-control form-control-sm" id="eretqty" name="eretqty" data-inputmask-alias="numeric" data-inputmask-inputformat="999,999,999" data-mask required placeholder="Quantity">
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-warning" name="edit"><i class="fas fa-save mr-1"></i> Update</button>
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
          <h4 class="modal-title">Delete!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- <form class="form-horizontal" method="POST" action="datahelpers/returnedmaterialsdetails_helper.php"> -->
            <input type="hidden" id="didreturnsdetails" name="didreturnsdetails">
            <input type="hidden" id="dreturnedno"       name="dreturnedno">
            <p>Are you sure to delete this item?</p>
          </div>
          <div class="modal-footer justify-content-left">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" data-dismiss="static" onclick="delete_warehouse_details()"> Delete</button>
          </div>
          <!-- </form> -->
        </div>
      </div>
    </div>

    <script type="text/javascript">

      $('.select2').select2();
      $('[data-mask]').inputmask();

      var from = '<?= $from; ?>';

      function selectItemIndex(e) {
        var empno         = '<?= $empno; ?>';
        var suppliercode  = '<?= $suppliercode; ?>';
        var indexid       = $('#aitemname').val();
        var from          = 'employee';
        if(empno == '' || empno == null) { from = 'supplier' }
          $.ajax({
            url: 'datahelpers/returnedmaterialsdetails_helper.php',
            type: 'post',
            dataType: 'json',
            data: {
              from:from,
              empno:empno,
              indexid:indexid,
              suppliercode:suppliercode
            },
            success: function (data) {
              if(data.from == 'employee') {
                $('#alinkid').val(data.id);               // idissuancedetails or idempreceiptdetails
                $('#alinkrefno').val(data.docno);         // inumber or mrnumber
                $('#aiditemindex').val(data.iditemindex);
                $('#aitemcode').val(data.itemcode);
                $('#aqty').val(data.quantity);
                $('#aunit').val(data.unit);
                $('#aretqty').val(data.quantity);
              } else {
                $('#idreturnsdetails').val(data.id);      // id
                $('#alinkrefno').val(data.docno);         // returnno
                $('#aitemcode').val(data.itemcode);
                $('#aqty').val(data.quantity);
                $('#aretqty').val(data.quantity);
              }
            },
            error: function() {}
          });
      }

      function save_warehouse_details() {
        var returnedno    = $('#areturnedno').val();
        var itemcode      = $('#aitemcode').val();
        var cur_qty       = $('#aqty').val();
        var ret_qty       = $('#aretqty').val();

        var linkrefno     = $('#alinkrefno').val();
        var linkid        = $('#alinkid').val();
        var trans_from    = $('#from').val();

        if ( (Number(cur_qty) >= Number(ret_qty)) || (Number(ret_qty) != 0) ) {
          $.ajax({
            url     : 'datahelpers/returnedmaterialsdetails_helper.php',
            type    : 'post',
            dataType: 'json',
            data    : {
              add:'add',
              returnedno:returnedno,
              itemcode:itemcode,
              quantity:ret_qty,
              linkid:linkid,
              linkrefno:linkrefno,
              trans_from:trans_from,
            },
            success: function (data) {
              $('#add').modal('hide');
              msg('success','Warehouse Details','Record Successfully saved!');
              reload_warehouse_details();
            },
            error: function(){
              alert('add warehouse details|helper.js|769');
            },
          });
        } else {
          alert('err: dako ang cur_qty');
        }
      }

      function delete_warehouse_details() {
        var idreturnsdetails = $('#didreturnsdetails').val();
        $.ajax({
          url     : 'datahelpers/returnedmaterialsdetails_helper.php',
          type    : 'post',
          dataType: 'json',
          data    : { delete:idreturnsdetails },
          success : function (data) {
            $('#delete').modal('hide');
            msg('danger','Warehouse Details','Record Successfully deleted!');
            reload_warehouse_details();
          },
          error: function() {}
        });
      }



    </script>


    <!-- <script type="text/javascript" src="helper.js"></script> -->

