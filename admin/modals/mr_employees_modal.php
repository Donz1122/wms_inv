<?php
if (!file_exists('../class/clsitems.php')) {
  require_once 'class/clsitems.php';
}
else {
  require_once '../class/clsitems.php';
}

$id         = $db->query("select if(count(idempreceipts) is null,1,count(idempreceipts))+1 as cnt
  from tbl_empreceipts
  where year(datereceived) = year(now())
  limit 1;")->fetch_assoc();
$num1       = $id['cnt'];

back:
$mrno       = 'MR'.$_SESSION["area"].$mos.$year.'E'.$num1;
$id         = $db->query("select mrnumber from tbl_empreceipts where mrnumber = '$mrno' limit 1");
if(mysqli_num_rows($id)>0) {
  $num1 += 1;
  goto back;
}

$employee   = $item->employee();

?>

<!-- Add -->
<div class="modal fade" id="modal-add_mr_employees">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Employee Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_employees_helper.php">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date Received</label>
            <div class="col">
              <div class="input-group input-group-sm">
                <input type="date" class="form-control form-control-sm" id="a_datereceived" name="a_datereceived" data-mask value="<?php echo date('Y-m-d'); ?>">
              </div>
            </div>

          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">MR Number</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="a_mrnumber" name="a_mrnumber" value="<?= $mrno ?>" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Emp. Code</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="a_empno" name="a_empno" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Emp. Name</label>
            <div class="col">
              <input type="hidden" id="empname" name="empname" />
              <select onchange="selectEmployee('a')" class="form-control form-control-sm select2" id="a_empname" name="a_empname" >
                <?php foreach($employee as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= ucwords(strtolower($row['empname'])); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Remarks</label>
            <div class="col">
              <textarea type="text" class="form-control" id="a_remarks" name="a_remarks" style="resize: none;"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="add"> Save and Close</button>
          <button type="submit" class="btn btn-info" name="add_open"> Save and Open Details</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit -->
<div class="modal fade" id="modal-edit_mr_employees">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modify MR</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal" method="POST" action="datahelpers/mr_employees_helper.php">
        <input type="hidden" id="e_idempreceipts" name="e_idempreceipts" />
        <!--previous holder-->
        <input type="hidden" id="e_empno_a" name="e_empno_a" />
        <input type="hidden" id="e_empname_a" name="e_empname_a" />
        <!--present holder-->
        <input type="hidden" id="e_empno_b" name="e_empno_b" />
        <input type="hidden" id="e_empname_b" name="e_empname_b" />

        <input type="hidden" id="e_transfers" name="e_transfers" />
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date Received</label>
            <div class="input-group col-sm-9">
              <div class="input-group-prepend">
                <span class="form-control form-control-sm input-group-text "><i class="far fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control form-control-sm" id="e_receivedate" name="e_receivedate" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask value="<?php echo date('Y-m-d'); ?>">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">MR Number</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="e_mrnumber" name="e_mrnumber" required>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Emp. Code</label>
            <div class="col">
              <input type="text" class="form-control form-control-sm" id="e_empno" name="e_empno" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Emp. Name</label>
            <div class="col">
              <select onchange="selectEmployee('e')" class="form-control form-control-sm select2" id="e_empname" name="e_empname" >
                <?php foreach($employee as $row): ?>
                  <option value="<?= $row['empnumber']; ?>"><?= ucwords(strtolower($row['empname'])); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Remarks</label>
            <div class="col">
              <textarea type="text" class="form-control" id="e_remarks" name="e_remarks" style="resize: none;"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info" name="edit"><i class="fa fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete -->
<div class="modal fade" id="modal-delete_mr_employees">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="">Delete!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="datahelpers/mr_employees_helper.php">
          <input type="hidden" id="dempreceipts"  name="dempreceipts">
          <input type="hidden" id="dmrnumber"     name="dmrnumber">
          <p>Are you sure to delete this item?</p>
        </div>
        <div class="modal-footer justify-content-left">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-sm" name="delete"><i class="fa fa-trash-alt faa-ring animated-hover"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.select2').select2();

  function selectEmployee(e) {
    if(e == 'a') {
      $selectedid = $('#a_empname').val();
      $('#a_empno').val($selectedid);
      $('#empname').val($("#a_empname option:selected").html());
    } else {
     $selectedid = $('#e_empname').val();
     $selectedname = $("#e_empname option:selected").html();
     $('#e_empno').val($selectedid);
     $('#e_empname_b').val($selectedname);
   }
 }

 function selectItem(e) {
  if(e == 'a') {
    $selectedid = $('#a_itemname').val();
    $('#a_itemcode').val($selectedid);
  } else { }
}


</script>