<?php
$id = $db->query("select max(idsupplier)+1 as cnt from tbl_supplier order by idsupplier desc limit 1")->fetch_assoc();
$num1 = 'S'.$mos.$year.$id['cnt'];
?>
<!-- Add -->
<div class="modal fade" id="addnew">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title">Add Supplier</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/suppliers_helper.php">                
        <div class="form-group row">
          <label for="suppliercode" class="col-sm-3 control-label">Supplier Code</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="suppliercode" name="suppliercode" value="<?= $num1 ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="suppliername" class="col-sm-3 control-label">Supplier Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="suppliername" name="suppliername" required autocomplete="false">
          </div>
        </div>
        <div class="form-group row">
          <label for="address" class="col-sm-3 control-label">Address</label>
          <div class="col-sm-9">
            <textarea type="text" class="form-control" id="address" name="address" style="resize: none;"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="contactno" class="col-sm-3 control-label">Contact Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="contactno" name="contactno">
          </div>
        </div>
        <div class="form-group row">
          <label for="vatable" class="control-label col-sm-3">Vatable</label>
          <div class="col-12 col-md-9">
            <select class="form-control" id="vatable" name="vatable" >
              <option value="" selected="">-select-</option>
              <option value="0">Non-Vatable</option>                                    
              <option value="1">Vatable</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="warehouse" class="control-label col-sm-3">Warehouse</label>
          <div class="col-12 col-md-9">
            <select class="form-control" id="warehouse" name="warehouse" >
              <option value="" selected="">-select-</option>
              <option value="0">Non-Warehouse</option>                                    
              <option value="1">Warehouse</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-info" name="add"> Save</button>
     </form>
   </div>
 </div>
</div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title"><span id="">Modify Supplier info</span></h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/suppliers_helper.php">                
        <input type="hidden" id="edit_idsupplier" name="edit_idsupplier">
        <div class="form-group row">
          <label for="edit_suppliercode" class="col-sm-3 control-label">Supplier Code</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_suppliercode" name="edit_suppliercode" value="<?= $num1 ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_suppliername" class="col-sm-3 control-label">Supplier Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_suppliername" name="edit_suppliername" required autocomplete="false">
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_address" class="col-sm-3 control-label">Address</label>
          <div class="col-sm-9">
            <textarea type="text" class="form-control" id="edit_address" name="edit_address" style="resize: none;"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_contactno" class="col-sm-3 control-label">Contact Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_contactno" name="edit_contactno">
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_vatable" class="control-label col-sm-3">Vatable</label>
          <div class="col-12 col-md-9">
            <select class="form-control" id="edit_vatable" name="edit_vatable" >
              <option value="" selected>-Please Select-</option> 
              <option value="0">Non-Vatable</option>                                    
              <option value="1">Vatable</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_warehouse" class="control-label col-sm-3">Warehouse</label>
          <div class="col-12 col-md-9">
            <select class="form-control" id="edit_arehouse" name="edit_warehouse" >
              <option value="" selected>-Please Select-</option>  
              <option value="0">Non-Warehouse</option>                                    
              <option value="1">Warehouse</option>
            </select>
          </div>
        </div>
      </div>

      <div class="modal-footer">
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
       <h4 class="modal-title">Delete!</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/suppliers_helper.php">
        <input type="hidden" id="del_idsuppplier" name="id">
        <div class="text-center">
          <p>Are you sure to delete this record?</p>          
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" name="delete"> Delete</button>
      </div>
    </form>
  </div>
</div>
</div>



