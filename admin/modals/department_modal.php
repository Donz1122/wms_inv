<?php
$id = $db->query("select max(iddepartment)+1 as cnt from tbl_department order by deptname desc limit 1")->fetch_assoc();
$num1 = $id['cnt'];

back:
$no = $num1;
$id = $db->query("select deptcode from tbl_department where deptcode = '$no' limit 1");
if(mysqli_num_rows($id)>0) {
  $num1 += 1;
  goto back;
}
if (empty($num1)) $num1 = 1;

?>
<!-- Add -->
<div class="modal fade" id="add">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
       <h4 class="modal-title">Add Department</h4>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/department_helper.php">
        <div class="form-group row">
          <label for="suppliercode" class="col-sm-3 col-form-label">Dept. Code</label>
          <div class="col">
            <input type="text" class="form-control form-control-sm" id="deptcode" name="deptcode" value="<?= $num1 ?>" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label for="suppliername" class="col-sm-3 col-form-label">Dept. Name</label>
          <div class="col">
            <input type="text" class="form-control form-control-sm" id="deptname" name="deptname" required autocomplete="false">
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-left">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Save</button>
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
      <h4 class="modal-title"><span id="">Modify Department</span></h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/department_helper.php">
        <input type="hidden" class="form-control form-control-sm" id="eiddepartment" name="eiddepartment" readonly>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Dept. Code</label>
          <div class="col">
            <input type="text" class="form-control form-control-sm" id="edeptcode" name="edeptcode" readonly>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Dept. Name</label>
          <input type="hidden" class="form-control form-control-sm" id="odeptname" name="odeptname">
          <div class="col">
            <input type="text" class="form-control form-control-sm" id="edeptname" name="edeptname" required autocomplete="false">
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-left">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
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
       <h5 class="modal-title"><span id="">Are you sure to delete this record?</span></h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" method="POST" action="datahelpers/department_helper.php">
        <input type="hidden" id="diddepartment" name="id">
        <input type="hidden" id="ddeptname" name="ddeptname">
        <div class="text-center"><h4 id="ddeptname2"></h4>
        </div>
      </div>
      <div class="modal-footer justify-content-left">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger" name="delete"><i class="fa fa-trash-alt faa-ring animated-hover"></i> Delete</button>
      </div>
    </form>
  </div>
</div>
</div>



