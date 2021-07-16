<?php
$num  = 0;
$id   = $db->query("select max(idpoles)+1 as cnt from tbl_poles order by idpoles desc limit 1")->fetch_assoc();
if(empty($id['cnt']) || $id['cnt'] == '') {
  $num = 1;
}
else {
  $num  = $id['cnt'];
}

$num1   = 'P'.$mos.$year.$num;

$category  = $db->query("select distinct category from tbl_poles order by category asc");
$address     = $db->query("select distinct address from tbl_poles order by address asc");

?>
<!-- Add -->
<div class="modal fade" id="add">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title">Add Poles</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">       
        <div class="form-group row">
          <label for="poleno" class="col-sm-3 col-form-label">Pole No.</label>
          <div class="col-sm-9">            
            <input type="text" class="form-control" id="poleno" name="poleno" value="<?= $num1 ?>" readonly>            
          </div>
        </div>

        <div class="form-group row">
          <label for="category" class="col-sm-3 col-form-label">Category</label>
          <div class="col-sm-9">            
            <select class="form-control form-control-sm inputs" id="category" name="category" required>
              <?php foreach($category as $row): ?>
                <option value="<?= $row['category']; ?>"><?= utf8_decode($row['category']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label for="polenotype" class="col-sm-3 col-form-label">Pole Type</label>
          <div class="col-sm-9">            
            <select class="form-control form-control-sm select2" id="poletype" name="poletype" required>              
                <option value="Concrete" selected>Concrete</option>              
                <option value="Steel">Steel</option>              
                <option value="Wood">Wood</option>              
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label for="street" class="col-sm-3 col-form-label">Street</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="street" name="street">            
          </div>
        </div>
        <div class="form-group row">
          <label for="address" class="col-sm-3 col-form-label">Address</label>
          <div class="col-sm-9">            
            <select class="form-control form-control-sm inputs" id="address" name="address" required>
              <?php foreach($address as $row): ?>
                <option value="<?= $row['address']; ?>"><?= utf8_decode($row['address']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label for="lat" class="col-sm-3 col-form-label">Latitude</label>
          <div class="col-sm-9">            
            <input type="text" class="form-control" id="lat" name="lat">            
          </div>
        </div>
        <div class="form-group row">
          <label for="long" class="col-sm-3 col-form-label">Longitude</label>
          <div class="col-sm-9">            
            <input type="text" class="form-control" id="long" name="long">            
          </div>
        </div>
        <div class="form-group row">
          <label for="lenght" class="col-sm-3 col-form-label">Length</label>
          <div class="col-sm-9">            
            <input type="text" class="form-control" id="length" name="length">            
          </div>
        </div>        
        
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
       <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_poles('a')"> Save and Close</button>
       <button type="button" class="btn btn-info" data-dismiss="static" onclick="save_poles('b')"> Save and Open Details</button>     
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
        <input type="hidden" id="edit_idsupplier" name="edit_idsupplier">
        <div class="form-group row">
          <label for="edit_suppliercode" class="col-sm-3 col-form-label">Supplier Code</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_suppliercode" name="edit_suppliercode" value="<?= $num1 ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_suppliername" class="col-sm-3 col-form-label">Supplier Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_suppliername" name="edit_suppliername" required autocomplete="false">
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_address" class="col-sm-3 col-form-label">Address</label>
          <div class="col-sm-9">
            <textarea type="text" class="form-control" id="edit_address" name="edit_address" style="resize: none;"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_contactno" class="col-sm-3 col-form-label">Contact Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="edit_contactno" name="edit_contactno">
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_vatable" class="col-form-label col-sm-3">Vatable</label>
          <div class="col-12 col-md-9">
            <select class="form-control" id="edit_vatable" name="edit_vatable" >
              <option value="" selected>-Please Select-</option> 
              <option value="0">Non-Vatable</option>                                    
              <option value="1">Vatable</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="edit_warehouse" class="col-form-label col-sm-3">Warehouse</label>
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
       <button type="button" class="btn btn-warning" name="edit"> Update</button>     
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
      <form class="form-horizontal" method="POST" action="datahelpers/suppliers_helper.php">
        <div class="modal-body">
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

