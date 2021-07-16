
<div class="modal fade" id="modal-additemcategory">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title">New Category</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/itemcategory_helper.php">                
        <div class="form-group row">
          <label for="suppliercode" class="col-sm-3 control-label">Category</label>
          <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm" id="category" name="category">
          </div>
        </div>        
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-info" name="add"><i class="fa fa-save mr-2"></i> Save</button>
     </form>
   </div>
 </div>
</div>
</div>
 
<div class="modal fade" id="modal-edititemcategory">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title"><span id="">Modify Category</span></h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/itemcategory_helper.php">                
        <input type="hidden" id="eiditemcategory" name="eiditemcategory">
        <div class="form-group row">
          <label for="edit_suppliercode" class="col-sm-3 control-label">Category</label>
          <div class="col-sm-9">
            <input type="text" class="form-control form-control-sm" id="ecategory" name="ecategory">
          </div>
        </div>        
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-warning" name="edit"><i class="fa fa-save mr-2"></i> Update</button>
     </form>
   </div>
 </div>
</div>
</div>

<div class="modal fade" id="modal-deleteitemcategory">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h5 class="modal-title"><span id="">Delete this category?</span></h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/itemcategory_helper.php">
        <input type="hidden" id="diditemcategory" name="id">
        <div class="text-center">
          <p>Delete Supplier Information</p>
          <h4 id="dcategory"></h4>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt mr-2"></i> Delete</button>
      </div>
    </form>
  </div>
</div>
</div>



